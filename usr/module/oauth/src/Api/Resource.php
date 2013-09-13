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
use Pi\Oauth\Provider\Service as Oauth;

/**
 * Oauth resource API
 *
 * @author Xingyu Ji <xingyu@eefocus.com>
 */
class Resource extends AbstractApi
{
    /**
     * Validate the access token and scope for resource server
     *
     * @param string $module
     * @param string $server   Server name
     * @return array
     */
    public function validateToken($clientid, $token, $scope)
    {//TODO
        $config = Pi::service('module')->config(
            '',
            $this->getModule()
        );

        $params = array(
            'client_id'    => $clientid,
            'access_token' => $token,
            'scope'        => $scope,
        );

        Oauth::boot($config);
        $resource = Oauth::server('resource');
        $request = Oauth::request();
        $request->setParameters($params);
        $result = $resource->process($request);

        return $resource->getResult();
    }
}
