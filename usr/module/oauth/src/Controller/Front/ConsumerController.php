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

class ConsumerController extends AbstractConsumerController
{
    public function indexAction()
    {
    }

    /**
    * 授权服务回调函数，使用授权码换取token
    *
    * @param code ：必须  授权码服务返回的授权码
    * @param state：可选  请求和回调的状态字符串
    * @param next： 可选  token获取后，浏览器的导向地址，默认跳转到当前域名地址
    * @return 如果请求token过程出现错误，则显示错误信息页面；否则跳转到后续页面
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
