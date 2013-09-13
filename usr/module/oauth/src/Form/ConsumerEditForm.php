<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

namespace Module\Oauth\Form;

use Pi;
use Pi\Form\Form as BaseForm;
use Zend\Form\Zend\Form\Form;

class ConsumerEditForm extends BaseForm
{
    public function init()
    {
        $this->add(array(
            'name'          => 'module',
            'attributes'    => array(
                'value'         => '',
                'description'   => __('Module name which use oauth service'),
            ),
            'options'       => array(
                'label' => __('Module name'),
            ),
            'type'          => 'text',
        ));

        $this->add(array(
            'name'          => 'server',
            'attributes'    => array(
                'value'         => '',
                'description'   => __('Oauth server name used to '
                                    . 'identify a oauth server'),
            ),
            'options'       => array(
                'label' => __('Server name'),
            ),
            'type'          => 'text',
        ));

        $this->add(array(
            'name'          => 'client_id',
            'attributes'    => array(
                'value'         => '',
                'description'   => __('Client id generated after registering '
                                    . 'your client on oauth site'),
            ),
            'options'       => array(
                'label'         => __('Client ID'),
            ),
            'type'          => 'text',
        ));

        $this->add(array(
            'name'          => 'client_secret',
            'attributes'    => array(
                'value'         => '',
                'description'   => __('Client secret generated after '
                                    . 'registering your client on oauth site'),
            ),
            'options'       => array(
                'label'         => __('Client Secret'),
            ),
            'type'          => 'text',
        ));

        $this->add(array(
            'name'          => 'server_host',
            'attributes'    => array(
                'value'         => '',
                'description'   => __('Url of oauth server'),
            ),
            'options'       => array(
                'label'         => __('Server address'),
            ),
            'type'          => 'text',
        ));

        $this->add(array(
            'name'          => 'id',
            'attributes'    => array(
                'type'     => 'hidden',
            )
        ));

        $this->add(array(
            'name'          => 'submit',
            'attributes'    => array(
                'value'     => 'Submit',
            ),
            'options'       => array(
                'label' => '',
            ),
            'type'          => 'submit',
        ));
    }
}
