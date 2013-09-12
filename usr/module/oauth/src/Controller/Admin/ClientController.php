<?php
namespace Module\Oauth\Controller\Admin;

use Pi;
use Pi\Oauth\Provider\Service as Oauth;
use Pi\Mvc\Controller\ActionController;
use Module\Oauth\Controller\AbstractProviderController;

class ClientController extends AbstractProviderController
{
    public function indexAction()
    {
        $this->listAction();
    }

    public function listAction()
    {
        $model = $this->getModel('client');
        $clientTable = $model->getTable();
        $userTable = Pi::model('account', 'user')->getTable();

        $select = $model->select()->join(
            array('user' => $userTable), 
            'user.id = ' . $clientTable . '.uid',
            array('username' => 'name')
        );
        $rowset = $model->selectWith($select);
        if (!$rowset) {
            return;
        }
        $data = $rowset->toArray();
        $this->view()->assign('list', $data);
        $this->view()->setTemplate('client-list');
    }

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
    * unable service for client 
    * ajax action
    */
    public function deleteAction()
    {
        $id = _get('id', int);
        if ($id) {
            $model = $this->getModel('client');
            $model->delete(array('id' => $id));
            $message = __('The client is deleted successfully.');
        } else {
            $message = __('An error occurred, client is not deleted.');
        }

        $this->jump(
            array('action' => 'list'),
            $message
        );
        return;
    }

    public function verifyAction()
    {
        $id     = _get('id', 'int');
        $flag   = _get('flag', 'int');
        $model = $this->getModel('client');
        if ($flag == 1) {
            // approve
            $model->update(array(
                'verify' => 2,
                'scope'  => $this->config('base_scope')
            ), array('id' => $id));
            $this->jump(
                array('action' => 'verify'),
                __('The client application is approved successfully.')
            );
            return;
        } elseif ($flag == 2) {
            // disapprove
            $reason = _get('reason', 'string');
            $model->update(
                array(
                    'verify_result' => $reason,
                    'verify'        => 3
                ),
                array('id' => $id)
            );
            $this->jump(
                array('action' => 'verify'),
                __('The client application is disapproved.')
            );
            return;
        } else {
            $clientTable = $model->getTable();
            $userTable = Pi::model('user_account')->getTable();
            $select = $model->select()->join(
                array('user' => $userTable), 
                'user.id = ' . $clientTable . '.uid',
                array('username' => 'name')
            );
            $select->where(array('verify' => 1));
            $rowset = $model->selectWith($select);
            if (!$rowset) {
                return;
            }
            $data = $rowset->toArray();
            $this->view()->assign('list', $data);
            $this->view()->setTemplate('client-verify');
        }
    }
}