<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

namespace Module\Oauth\Api;

use Pi;
use Pi\Application\AbstractApi;

/**
 * Oauth consumer API
 *
 * Usage:
 *
 * ```
 *  // Get exist access token
 *  Pi::api('oauth', 'client')->getToken('module', 'servername');
 *  // Get authorize url
 *  getAuthorizeUrl('module', 'servername', 'test_scope', 'callback');
 *  // Get access token(authorization code)
 *  getAccessToken('module', 'servername', 'code', array('code'=>'','redirect_uri'=>''));
 *  // Get access token(referesh token)
 *  getAccessToken('module', 'servername', 'refresh_token', array('refresh_token'=>''));
 *  // Get access token(resource owner password credentials)
 *  getAccessToken('module', 'servername', 'password', array('username'=>'', 'password'=>''));
 *  // Get access token(client credentials)
 *  getAccessToken('module', 'servername', 'client');
 * ```
 *
 * @author Xingyu Ji <xingyu@eefocus.com>
 */
class Client extends AbstractApi
{
    /**
     * Get exist access token
     *
     * @param string $module
     * @param string $server   Server name
     * @return array
     */
    public function getToken($module, $server)
    {
        //考虑为token的存储加上模块的信息，方便管理
        if (isset($_SESSION['token'])) {//TODO multi token
            $arr = $_SESSION['token'];
        }
        if (isset($arr['access_token']) && $arr['access_token']) {
            $token = array();
            if (time() < $arr['expired']) {
                $token['access_token'] = $arr['access_token'];
            }
            if (isset($arr['refresh_token']) && $arr['refresh_token']) {
                $token['refresh_token'] = $arr['refresh_token'];
            }
        }

        return $token;
    }

    /**
     * Get authorize url
     *
     * @param  string $module
     * @param  string $server
     * @param  array  $scope
     * @param  string $callback
     * @return string
     */
    public function getAuthorizeUrl($module, $server, $scope, $callback = '')
    {
        $client = $this->getClient($module, $server);
        if (!$client) {
            return false;
        }

        $params = array();
        $params['client_id'] = $client['client_id'];
        $params['redirect_uri'] = $callback ? $callback : $this->getUrl('', 'callback');
        $params['response_type'] = 'code';
        $params['scope'] = $scope;
        $params['state'] = $this->setState($module, $server);

        return $this->getUrl($client['server_host'], 'authorize') . "?" . http_build_query($params);
    }

    /**
     * Get access token
     *
     * @param string $module
     * @param string $server
     * @param string $type   Grant type: code, password, token, client
     * @param array $options
     * @return array
     */
    public function getAccessToken( $module, $server, $type , $options)
    {
        if (!in_array( $type, array('token', 'code', 'password', 'client'))) {
            return false;
        }
        $client = $this->getClient($module, $server);
        if (!$client) {
            return false;
        }
        $params = array();
        $params['client_id'] = $client['client_id'];
        $params['client_secret'] = $client['client_secret'];
        if ($type === 'token') {
            $params['grant_type'] = 'refresh_token';
            $params['refresh_token'] = $options['refresh_token'];
        } elseif ($type === 'code') {
            $params['grant_type'] = 'authorization_code';
            $params['code'] = $options['code'];
            $params['redirect_uri'] = $options['redirect_uri'];
        } elseif ($type === 'password') {
            $params['grant_type'] = 'password';
            $params['username'] = $options['username'];
            $params['password'] = $options['password'];
        } elseif ($type === 'client') {
            $params['grant_type'] = 'client_credentials';
        }
        $tokenUrl = $this->getUrl($client['server_host'], 'grant');
        $response = $this->oAuthRequest($tokenUrl, 'POST', $params);
        $token = json_decode($response, true);
        if (!$token['error']) {
            $this->setToken($token);
        }

        return $token;
    }


    /**
     * Set access token
     *
     * @param string $token
     * @return bool
     */
    private function setToken($token)
    {
        $token['expired'] = $token['expires_in'] + time();
        unset($_SESSION['token'], $token['expires_in']);
        $_SESSION['token'] = $token;
        return true;
    }

    /**
     * Get server grant url
     *
     * @param  string $host
     * @param  string $type
     * @return string
     */
    protected function getUrl($host, $type)
    {
        $host = rtrim($host, '/');
        switch ($type) {
            case 'authorize':
                $host .= '/oauth/authorize/index';
                break;
            case 'grant':
                $host .= '/oauth/grant/index';
                break;
            case 'callback':
                $host = Pi::url('/oauth/consumer/callback');
                break;
            default:
                break;
        }

        return $host;
    }

    /**
     * Set state of authorize url
     *
     * @param  string $module
     * @param  string $server
     * @return string
     * @ignore
     */
    private function setState($module, $server)
    {
        $state = base64_encode($module . '*@*' . $server);
        if (isset($_SESSION['state'])) {
            unset($_SESSION['state']);
        }
        $_SESSION['state'] = $state;

        return $state;
    }

    /**
     * Get client information
     *
     * @param  string $module
     * @param  string $server
     * @return array
     * @ignore
     */
    private function getClient($module, $server = '')
    {
        $row = Pi::model('consumer_client', 'oauth')->select(array(
            'module'   => $module,
            'server' => $server,
        ));
        if ($data = $row->toArray()) {
            return array(
                'client_id'     => $data[0]['client_id'],
                'client_secret' => $data[0]['client_secret'],
                'server_host'   => $data[0]['server_host'],
            );
        } else {
            return false;
        }
    }

    /**
     * Get resource by openapi
     *
     * @param  string $module
     * @param  string $server
     * @param  string $url   Open api url
     * @return array
     */
    public function getResource($module, $server, $url)
    {//TODO
        $token = $this->getToken();
        $client = $this->getClient($module, $server);
        if (!$client || empty($token)) {
            return false;
        }
        $response = $this->oAuthRequest(
            $url,
            'GET',
            array(
                'client_id'    => $client['client_id'],
                'access_token' => $token['access_token'],
            )
        );
        $result = json_decode($response, true);

        return $result;
    }

    /**
     * Send oauth request
     *
     * @param  string $url
     * @param  string $method
     * @param  string $parameters
     * @param  string $useBasicHeader
     * @return array
     */
    protected function oAuthRequest($url, $method, $parameters, $useBasicHeader = FALSE)
    {
        switch ($method) {
            case 'GET':
                $url = $url . '?' . http_build_query($parameters);
                $headers = array(
                    'X-Requested-With:XMLHttpRequest',
                    'Accept: application/json',
                    'Content-Type: application/x-www-form-urlencoded'
                );

                return $this->http($url, 'GET', '', $headers);
            default:
                $headers = array();
                if ( is_array($parameters) ) {
                    if ($useBasicHeader) {
                        $headers[] = "Authorization: Basic " . base64_encode($params['client_id'] . ":" . $params['client_secret']);
                        unset($params['client_id']);
                        unset($params['client_secret']);
                    }
                    $body = http_build_query($parameters);
                }
                $headers[] = "X-Requested-With:XMLHttpRequest";
                $headers[] = "Accept: application/json";
                $headers[] = "Content-Type: application/x-www-form-urlencoded";

                return $this->http($url, $method, $body, $headers);
        }
    }

    /**
     * Send an http request
     *
     * @param  string $url
     * @param  string $method
     * @param  string $parameters
     * @param  string $useBasicHeader
     * @return HttpResponse
     * @ignore
     */
    protected function http($url, $method, $postfields = NULL, $headers = array())
    {
        $ci = curl_init();
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, 'test');//todo$this->useragent);//TODO
        // curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
        // curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_ENCODING, "");
        // curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
//        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($ci, CURLOPT_HEADER, FALSE);

        if ($method == 'POST') {
            curl_setopt($ci, CURLOPT_POST, TRUE);
            if (!empty($postfields)) {
                curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
            }
        }
        curl_setopt($ci, CURLOPT_URL, $url );
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );

        $response = curl_exec($ci);

//        if (0) {
//            echo "=====post data======\r\n";
//            print_r($postfields);
//
//            echo "=====headers======\r\n";
//            print_r($headers);
//
//            echo '=====request info====='."\r\n";
//            print_r( curl_getinfo($ci) );
//
//            echo '=====response====='."\r\n";
//            print_r( $response );
//        }
        curl_close ($ci);

        return $response;
    }
}
