<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

namespace Module\Oauth\Controller\Front;

use Zend\Validator\Explode;

use Pi;
use Pi\Oauth\Provider\Service as Oauth;
use Module\Oauth\Form\ClientRegisterForm;
use Module\Oauth\Form\ClientRegisterFilter;
use Module\Oauth\Form\ClientEditForm;
use Module\Oauth\Form\ClientEditFilter;
use Module\Oauth\Controller\AbstractProviderController;

/**
 * Client management controller
 *
 * Client management of oauth server in front end
 *
 * @author Xingyu Ji <xingyu@eefocus.com>
 */
class ClientController extends AbstractProviderController
{
    /**
     * Index action
     *
     * @return void
     */
    public function indexAction()
    {
        $this->registerAction();
    }

    /**
     * Register client
     *
     * @return void
     */
    public function registerAction()
    {
        if (!Pi::user()->hasIdentity()) {
            $loginUrl = $this->url('', array(
                'module'     => 'system',
                'controller' => 'login',
                'action'     => 'index'
            ));
            $this->view()->assign('login', $loginUrl);
            $this->view()->setTemplate('authorize-redirect');

            return;
        }

        $this->view()->assign('title', __('Client register'));

        $form = new ClientRegisterForm();
        $form->setAttribute('method','POST');
        $form->setAttribute('action', $this->url('', array('action' => 'register')));
        if ($this->request->ispost()) {
            $post = $this->request->getPost();
            $form->setData($post);
            $form->setInputFilter(new ClientRegisterFilter);
            if (!$form->isValid()) {
                $this->view()->assign('form', $form);

                return;
            }
            $uid = Pi::user()->getUser()->id;
            $data = $form->getData();
            if (!$data['logo']) {//TODO
                $data['logo'] = "/asset/oauth/logo.png";
            }
            $data = array(
                'name'          => $data['name'],
                'redirect_uri'  => urldecode(urldecode($data['redirect_uri'])),
                'uid'           => $uid,
                'type'          => 'public',
                'description'   => $data['description'],
                'address'       => $data['address'],
                'logo'          => $data['logo'],
                'scope'         => $this->config('unverified_scope') ?: ''
            );
            Oauth::boot($this->config());
            $exists = Oauth::storage('client')->get($data['name'], 'name');
            if (!empty($exists)) {
                $message = __('The client already exists.');
            } else {
                $result = Oauth::storage('client')->addClient($data);
                $this->redirect()->toUrl('/oauth/client/list');

                return;
            }
        }
        $this->view()->assign(array(
            'form'      => $form,
            'message'   => $message,
        ));
        $this->view()->setTemplate('client-register');
    }

    /**
     * List client
     *
     * @return void
     */
    public function listAction()
    {
        $id = $this->params('id', '');
        $userid = Pi::user()->getUser()->id;
        Oauth::boot($this->config());
        if (!$id) {
            $result = Oauth::storage('client')->getList(array('uid' => $userid));
            $this->view()->assign('client', $result);
            $this->view()->setTemplate('client-list');
        } else {
            $result = Oauth::storage('client')->get($id);
            $this->view()->assign('client', $result);
            $this->view()->setTemplate('client-info');
        }
    }

    /**
     * Display client details
     *
     * @return void
     */
    public function detailAction()
    {
        $id = _get('id', 'int');
        Oauth::boot($this->config());
        $detail = Oauth::storage('client')->get($id);

        if (!empty($detail['scope'])) {
            // get scope brief
            $scope  = explode(' ', $detail['scope']);
            $model  = $this->getModel('scope');
            $scopeBrief = $model->select(array('name' => $scope))->toArray();
            $detail['scope'] = $scopeBrief;
        }

        $this->view()->assign('client', $detail);
    }

    /**
     * Edit client
     *
     * @return void
     */
    public function updateAction()
    {
        Oauth::boot($this->config());
        $form = new ClientEditForm();
        $form->setAttribute('method', 'POST');
        $form->setAttribute('action', $this->url('', array(
            'action' => 'update'
        )));

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $form->setData($post);
            $form->setInputFilter(new ClientEditFilter);
            if (!$form->isValid()) {
                $this->view()->assign('form', $form);

                return;
            }
            $data = $form->getData();
            $userid = Pi::user()->getUser()->id;
            // not self
            if ($data['uid'] != $userid) {
                $this->jumpToDenied();

                return;
            }
            unset(
                $data['client_id'],
                $data['client_secret'],
                $data['submit']
            );
            // set client unverified
            $data['verify'] = 0;
            $data['verify_result'] = '';
            // reset scope
            $data['scope'] = $this->config('unverified_scope');
            $result = Oauth::storage('client')->update($data['id'], $data);
            if ($result) {
                $message = __('Client data saved successfully, please re-verify the client');
                $this->jump(array('action' => 'list'), $message);

                return;
            } else {
                $message = __('Client data not saved.');
            }
        } else {
            $id     = _get('id', 'int');
            $userid = Pi::user()->getUser()->id;
            $result = Oauth::storage('client')->get($id);
            // not self
            if ($result['uid'] != $userid) {
                $this->jumpToDenied();

                return;
            }
            $form->setData($result);
        }
        $this->view()->setTemplate('client-info');
        $title = __('Client Infomation');
        $this->view()->assign(array(
            'title'     => $title,
            'form'      => $form,
            'message'   => $message,
        ));
    }

    /**
     * Delete client
     *
     * @return void
     */
    public function deleteAction()
    {
        Oauth::boot($this->config());
        $id     = _get('id', 'int');
        $userid = Pi::user()->getUser()->id;
        $result = Oauth::storage('client')->get($id);
        // not self
        if ($result['uid'] != $userid) {
            $this->jumpToDenied();

            return;
        }
        Oauth::storage('client')->delete($id);

        $this->redirect()->toRoute('', array(
            'controller' => 'client',
            'action'     => 'list',
        ));
    }

    /**
     * Verify client
     *
     * @return void
     */
    public function verifyAction()
    {
        $id = _get('id', 'int');
        if (!$id) {
            $message = __('Fail to submit for verification.');
        } else {
            Oauth::boot($this->config());
            $result = Oauth::storage('client')->update($id, array('verify' => 1));
            $message = __('Submit for verification successfully.');
        }
        $this->jump(
            array('action' => 'list'),
            $message
        );
    }

    /**
     * Apply scope
     *
     * @return void
     */
    public function scopeAction()
    {
        $clientid = _get('id', 'int');
        if ($this->request->ispost()) {
            $param = $this->request->getPost()->toArray();
            if (empty($param['scope'])) {
                $message = __('Please select scope.');
                if (empty($clientid)) {
                    $this->jump(
                        array('action' => 'scope'),
                        $message
                    );
                } else {
                    $this->jump(
                        array('action' => 'scope', 'id' => $clientid),
                        $message
                    );
                }

                return;
            }
            Oauth::boot($this->config());
            $result = Oauth::storage('client')->update(
                $param['clientid'],
                array(
                    'scope_apply' => 1,
                    'scope_detail' => implode(' ', $param['scope'])
                )
            );
            if ($result) {
                $message = __('Scope applied successfully');
            } else {
                $message = __('Failed to apply scope');
            }
            $this->jump(array('action' => 'list'), $message);

            return;
        }
        if (empty($clientid)) {
            $scopeUrl = $this->url('', array('action' => 'scope'));
        } else {
            $scopeUrl = $this->url('', array(
                'action' => 'scope',
                'id'     => $clientid
            ));
        }

        $userid = Pi::user()->getUser()->id;
        $clientModel = $this->getModel('client');
        $select = $clientModel->select()
                              ->columns(array('id', 'name', 'scope'))
                              ->where(array('uid' => $userid, 'verify' => 2));
        $client = $clientModel->selectWith($select)->toArray();
        if ($client) {
            foreach ($client as $value) {
                $client_data[$value['id']] = array(
                    'name' => $value['name'],
                    'scope' => explode(' ', $value['scope']),
                );
            }
            if (!$clientid) {
                $temp_client_id =  array_keys($client_data);
                $clientid = $temp_client_id[0];
            }
            $model = $this->getModel('scope');
            $select = $model->select();
            // remove test scope
            $testScope = trim($this->config('unverified_scope'));
            if ($testScope) {
                $select->where("`name` <> '{$testScope}'");
            }
            $scope = $model->selectWith($select)->toArray();
            $this->view()->assign('scope', $scope);
            $this->view()->assign('scope_url', $scopeUrl);
            $this->view()->assign('clientid', $clientid);
            $this->view()->assign('client', $client_data);
        }
        $this->view()->setTemplate('client-scope');
    }
}
