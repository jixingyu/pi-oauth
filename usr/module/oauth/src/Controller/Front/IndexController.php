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
use Pi\Mvc\Controller\ActionController;

/**
 * Index controller
 *
 * @author Xingyu Ji <xingyu@eefocus.com>
 */
class IndexController extends ActionController
{
    /**
     * Index action
     *
     * @return void
     */
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
