<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

namespace Module\Oauth\Controller\Front;

use Pi;
use Pi\Oauth\Provider\Service as Oauth;
use Module\Oauth\Controller\AbstractProviderController;

/**
 * Authorization controller
 *
 * @author Xingyu Ji <xingyu@eefocus.com>
 */
class AuthorizeController extends AbstractProviderController
{
    /**
     * Authorizaiton process
     *
     * @return void
     */
    public function indexAction()
    {
        Oauth::boot($this->config());
        $authorize = Oauth::server('authorization');
        $request = Oauth::request();
        $params = $this->getParams();
        $params['resource_owner'] = Pi::user()->getUser()->id;
        $request->setParameters($params);

        if ($authorize->validateRequest($request)) {
            // check login
            $login_status = Pi::user()->hasIdentity();
            if (!$login_status) {
                $login_page = Pi::url('/system/login/index');//TODO
                $this->view()->assign('login', $login_page);
                $this->view()->setTemplate('authorize-redirect');

                return;
            }
            // Unverified client can only access the client creater's data
            $clientData = Oauth::storage('client')->getClient($params['client_id']);
            if ($clientData['verify'] != 2 && $clientData['uid'] != Pi::user()->getUser()->id) {
                $authorize->setError('access_denied');
            } else {
                if (!$request->ispost()) {
                    //check if user has authorized this client already in scope
                    $accessToken = Oauth::storage('access_token')->getUserToken(
                        $params['resource_owner'],
                        $params['client_id']
                    );
                    if ($accessToken && $accessToken['expires'] > time()) {
                        $scopeNew = Oauth::scope($params['scope']);
                        $scopeOld = Oauth::scope($accessToken['scope']);
                        if (!$scopeNew->isSubsetOf($scopeOld)) {
                            $isAuth = false;
                        }
                        $isAuth = true;
                    } else {
                        $isAuth = false;
                    }
                    if ($isAuth) {
                        $authorize->process($request);
                    } else {
                        // client infomation
                        $client = Oauth::storage('client')->getClient($params['client_id']);

                        // scope infomation
                        $scopeRequested = explode(' ', $params['scope']);
                        $model = $this->getModel('scope');
                        $select = $model->select()->where(array(
                            'name' => $scopeRequested
                        ));
                        $scopes = $model->selectWith($select)->toArray();
                        $scopeBase = array_merge(
                            explode(' ', $this->config('base_scope')),
                            explode(' ', $this->config('unverified_scope'))
                        );
                        foreach ($scopes as &$scope) {
                            if (in_array($scope['name'], $scopeBase)) {
                                $scope['disabled'] = true;
                            }
                        }

                        if (strpos($params['redirect_uri'], '?')) {
                            $backUri = $params['redirect_uri'] . '&cancel=1';
                        } else {
                            $backUri = $params['redirect_uri'] . '?cancel=1';
                        }

                        $this->view()->assign('scopes', $scopes);
                        $this->view()->assign('client', $client);
                        $this->view()->assign('backuri', $backUri);
                        $this->view()->setTemplate('authorize-auth');

                        return;
                    }
                } else {
                    $authorize->process($request);
                }
            }
        }
        $result = $authorize->getResult();
        $content = $result->setContent()->getContent();
        if ($result instanceof \Pi\Oauth\Provider\Result\Error) {
            $content = json_decode($content, true);
            $this->view()->assign('authorize_error', $content);
            $this->view()->setTemplate('authorize-error');
            return;
        }
        $this->response->setStatusCode($result->getStatusCode());
        $this->response->setHeaders($result->getHeaders());
        $this->response->setContent($content);

        return $this->response;
    }

    /**
    * Get paramesters of request
    *
    * @return array
    */
    protected function getParams()
    {
        $clientid = $this->params('client_id');
        $response_type = $this->params('response_type');
        $redirect_uri = $this->params('redirect_uri');
        $state = $this->params('state');
        $scope = $this->params('scope');
        if (empty($scope)) {
            $scope = $this->config('base_scope');
        }

        return array(
            'client_id'     => $clientid,
            'response_type' => $response_type,
            'redirect_uri'  => urldecode(urldecode($redirect_uri)),
            'state'         => $state,
            'scope'         => $scope,
        );
    }
}
