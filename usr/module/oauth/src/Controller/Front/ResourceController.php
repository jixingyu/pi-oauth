<?php
namespace Module\Oauth\Controller\Front;

use Pi;
use Pi\Oauth\Provider\Service as Oauth;
use Module\Oauth\Controller\AbstractProviderController;

class ResourceController extends AbstractProviderController
{
    public function indexAction()
    {
        Oauth::boot($this->config());
        $grant = Oauth::server('resource');
        $request = Oauth::request();
//        $request->setParameters($this->getParams());
        $grant->process($request);
        $result = $grant->getResult();
        $this->response->setStatusCode($result->getStatusCode());
        $this->response->setHeaders($result->getHeaders());
        $this->response->setContent($result->setContent()->getContent());
        // $this->view()->setTemplate(false);
        return $this->response;
    }

    /**
    * get paramesters of request  
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