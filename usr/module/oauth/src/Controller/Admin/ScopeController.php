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
use Module\Oauth\Form\ScopeAddForm;
use Module\Oauth\Controller\AbstractProviderController;

class ScopeController extends AbstractProviderController
{
    public function indexAction()
    {
        $this->view()->assign('title', __('Scope Page'));
        $model = $this->getModel('scope');
        $rowset = $model->select(array());
        if (!$rowset) {
            return;
        }
        $data = $rowset->toArray();
        $form = new ScopeAddForm();
        $form->setAttribute('action', $this->url('', array(
            'action' => 'add'
        )));
        $this->view()->assign('form', $form);
        $this->view()->assign('list', $data);
        $this->view()->setTemplate('scope-index');
    }

    public function addAction()
    {
        if (!$this->request->ispost()) {
            return ;
        }
        $data = $this->request->getPost()->toArray();
        unset($data['submit']);
        if (in_array('', $data)) {
            return ;
        }
        $model = $this->getModel('scope');
        $row = $model->createRow($data);
        $row->save();
        if (!$row->id) {
            return false;
        }
        $this->redirect()->toUrl($this->url('', array(
            'action' => 'index'
        )));
    }

    public function deleteAction()
    {
        $id = _get('id', 'int');
        if (!$id) {
            $message = __('Scope is not deleted.');
        } else {
            $model = $this->getModel('scope');
            $model->delete(array('id' => $id));
            $message = __('Scope is deleted successfully.');
        }

        $this->jump(array(
            'controller' => 'scope',
            'action'     => 'index',
        ), $message);
    }

    public function verifyAction()
    {
        $model = $this->getModel('client');
        $verify = _get('verify', 'int');
        if ($verify == 1) {
            // approve
            $id = _get('id', 'int');
            $row = $model->find($id);
            $row->scope = implode(' ', array_merge(
                explode(' ', $row->scope),
                explode(' ', $row->scope_detail)
            ));
            $row->scope_apply = 0;
            $row->scope_detail = '';
            $row->save();
            $this->jump(
                array('action' => 'verify'),
                __('Scope verify successfully.')
            );

            return;
        } elseif ($verify == 2) {
            // ignore
            $id = _get('id', 'int');
            $model->update(
                array('scope_apply' => 0, 'scope_detail' => ''),
                array('id' => $id)
            );
            $this->jump(
                array('action' => 'verify'),
                __('Ignore the application.')
            );

            return;
        } else {
            $rowset = $model->select(array('scope_apply' => 1));
            if (!$rowset) {
                return;
            }
            $client = $rowset->toArray();
            $temp = Pi::model('scope', 'oauth')->select(array())->toArray();
            foreach ($temp as $value) {
                $scope[$value['name']] = $value['brief'];
            }
            foreach ($client as $value) {
                $data['id'] = $value['id'];
                $data['name'] = $value['name'];
                $temp_scope_name = explode(' ', $value['scope_detail']);
                $data['scope'] = array();
                foreach ($temp_scope_name as $name) {
                    $data['scope'][$name] = $scope[$name];
                }
                $data_list[] = $data;
            }
            $this->view()->assign('list', $data_list);
            $this->view()->setTemplate('scope-verify');
        }
    }
}
