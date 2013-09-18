<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

namespace Module\Oauth\Controller\Admin;

use Pi;
use Module\Oauth\Form\ClientInfoForm;
use Module\Oauth\Form\ClientInfoFilter;
use Module\Oauth\Form\ConsumerEditForm;
use Module\Oauth\Form\ConsumerEditFilter;
use Module\Oauth\Controller\AbstractConsumerController;

/**
 * Consumer client management controller
 *
 * Client management for consumer admin
 * Features: client list, addition, edition, deletion and verification
 *
 * @author Xingyu Ji <xingyu@eefocus.com>
 */
class ConsumerController extends AbstractConsumerController
{
    /**
     * Index Action
     *
     * @return void
     */
    public function indexAction()
    {
        $form = new ClientInfoForm('ClientInfo');
        $form->setAttribute('action', $this->url('', array('action' => 'client')));
        $this->view()->assign('form', $form);
        $this->view()->setTemplate('consumer-index');
    }

    /**
     * Add client
     *
     * @return void
     */
    public function clientAction()
    {
        $form = new ClientInfoForm('ClientInfo');
        $form->setAttribute('action', $this->url('', array('action' => 'client')));

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $form->setData($post);
            $form->setInputFilter(new ClientInfoFilter);
            if (!$form->isValid()) {
                $this->view()->assign('form', $form);
                $this->view()->setTemplate('consumer-index');
            } else {
                $params = $form->getData();
                $data = array(
                    'module'        => $params['module'],
                    'server'        => $params['server'],
                    'client_id'     => $params['key'],
                    'client_secret' => $params['secret'],
                    'server_host'   => $params['host'],
                    'time_create'   => time(),
                );

                $row = Pi::model('consumer_client', 'oauth')->createRow($data);
                $row->save();
                // $this->view()->assign('form', $form);
                $this->redirect()->toUrl('/admin/oauth/consumer/list');
            }
        }
    }


    /**
     * List client
     *
     * @return void
     */
    public function listAction()
    {
        $row = Pi::model('consumer_client', 'oauth')->select(array());
        $client = array();
        if ($row) {
            $client = $row->toArray();
        }
        $this->view()->assign('client', $client);
        $this->view()->setTemplate('consumer-list');
    }

    /**
     * Edit client infomation
     *
     * @return void
     */
    public function editAction()
    {
        $form = new ConsumerEditForm('ConsumerEdit');
        $form->setAttribute('method', 'POST');
        $form->setAttribute('action', $this->url('', array(
            'action' => 'edit'
        )));

        $message = '';
        $model = $this->getModel('consumer_client');

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $form->setData($post);
            $form->setInputFilter(new ConsumerEditFilter);
            if (!$form->isValid()) {
                $this->view()->assign('form', $form);
                $message = $form->getMessage()
                    ?: __('Invalid data, please check and re-submit.');
            } else {
                $data = $form->getData();
                $id = $data['id'];
                unset(
                    $data['id'],
                    $data['submit']
                );
                $result = $model->update($data, array('id' => $id));
                if ($result) {
                    $this->jump(array('action' => 'list'));

                    return;
                } else {
                    $message = __('Client data not saved.');
                }
            }
        } else {
            $id     = _get('id', 'int');
            $userid = Pi::user()->getUser()->id;
            $select = $model->select()
                            ->columns(array('id', 'module', 'server',
                                'client_id', 'client_secret', 'server_host'
                            ))
                            ->where(array('id' => $id));
            $rowset = $model->selectWith($select);
            if (!$rowset) {
                return;
            }
            $detail = $rowset->toArray();d($detail);
            $form->setData($detail[0]);
        }
        $title = __('Edit client');
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
        $id     = _get('id', 'int');
        $model  = $this->getModel('consumer_client');
        $result = $model->delete(array('id' => $id));

        $this->jump(
            array('action' => 'list'),
            __('The client is deleted successfully.')
        );
        $this->setTemplate('false');
    }
}
