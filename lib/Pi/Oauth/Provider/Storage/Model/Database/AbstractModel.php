<?php
namespace Pi\Oauth\Provider\Storage\Model\Database;

use Pi\Oauth\Provider\Storage\ModelInterface;
use Pi\Application\Model\Model as DbModel;

abstract class AbstractModel implements ModelInterface
{
    protected $model;

    public function __construct(DbModel $model)
    {
        $this->model = $model;
    }

    public function add($params)
    {
        $row = $this->model->createRow($params);
        $result = $row->save();

        return $result;
    }

    public function get($value,$name = 'id')
    {
        $params = '';
        $row = $this->model->find($value,$name);
        if ($row) {
            $params = $row->toArray();
        }

        return $params;
    }

    public function update($id, $params)
    {
        $row = $this->model->find($id);
        $result = $row->assign($params)->save();

        return $result;
    }

    public function delete($id)
    {
        $result = $this->model->delete(array('id' => $id));

        return $result;
    }

    public function expire($expires)
    {
        $result = $this->model->delete(array('expires < ?' => $expires));

        return $result;
    }
}
