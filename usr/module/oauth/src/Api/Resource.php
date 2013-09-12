<?php
namespace Module\Oauth\Api;

use Pi;
use Pi\Application\AbstractApi;
use Pi\Oauth\Provider\Service as Oauth;

class Resource extends AbstractApi
{
	/**
	* this method is not support client credentials type to vertify token
	*/
	public function check($token)
	{
		$row = Pi::model('access_token', 'oauth')->find($token, 'token');
		if ($row) {
			$data = $row->toArray();
			if ($data['expires'] > time()) {
				return array(
				'uid' => $data['resource_owner'],
				'scope'=> $data['scope'],
				);
			}
		}
	}

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
        if (!$result) {
            $result = $resource->getResult();
        }
        return $result;
	}
}