<?php
namespace Module\Oauth\Controller\Front;

use Pi;
use Pi\Mvc\Controller\ActionController;

class IndexController extends ActionController
{
    public function indexAction()
    {
        $config = $this->config('oauth_role');
        if ($config == 0) {
            // consumer
            $this->jump(
                '',
                __('No page for consumer.')
            );
            return;
        } else {
            // provider
            $this->redirect()->toRoute('', array(
                'controller' => 'client',
                'action'     => 'register',
            ));
        }
    }
}