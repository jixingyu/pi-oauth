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
use Module\Oauth\Controller\AbstractConsumerController;

/**
 * Consumer controller
 *
 * @author Xingyu Ji <xingyu@eefocus.com>
 */
class ConsumerController extends AbstractConsumerController
{
    /**
    * Redirect uri, get access token by authorization code
    *
    * @return void
    */
    public function callbackAction()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $cancel = _get('cancel', 'int');
        if ($cancel) {
            $this->jump('', __('Cancel authorization'));

            return;
        }
        $code = $this->params('code','');
        $state = $this->params('state','');
        // $next = $this->params('next', '');

        if (isset($_SESSION['state'])) {
            if ($state != $_SESSION['state']) {
                return false;
            }
            list($module, $server) = explode('*@*', base64_decode($_SESSION['state']));
//            unset($_SESSION['state']);TODO unuse for testing
        }

        if (!$code) {
            $token['error'] = 'missing code';
        } else {
            $redirect_uri = Pi::url('/oauth/consumer/callback');
            $token = Pi::service('api')->oauth(array('client', 'getAccessToken'), $module, $server,'code', array('code' => $code,'redirect_uri' => $redirect_uri));
        }var_dump($token);exit;
        if (!isset($token['error'])) {
            // $this->view()->assign('url', $next);
            $this->view()->setTemplate('callback-jump');
        } else {
            $this->view()->assign('error',$token);
            $this->view()->setTemplate('callback-error');
        }
    }

}
