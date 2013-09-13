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

class ClientEditForm extends BaseForm
{
    public function init()
    {
        $this->add(array(
            'name'          => 'name',
            'options'       => array(
                'label' => __('Client Name'),
            ),
            'attributes'    => array(
                'type'          => 'text',
                'description'   => __('Client name used to display'),
            )
        ));

        $this->add(array(
            'name'          => 'client_id',
            'options'       => array(
                'label' => __('Client ID'),
            ),
            'attributes'    => array(
                'type'          => 'text',
                'class'         => 'input-xlarge',
                'readonly'      => true,
                'description'   => __('Client id generated after registering'),
            )
        ));

        $this->add(array(
            'name'          => 'client_secret',
            'attributes'    => array(
                'value'         => '',
                'class'         => 'input-xlarge',
                'readonly'      => true,
                'description'   => __('Client secret generated after registering'),
            ),
            'options'       => array(
                'label'         => __('Client Secret'),
            ),
            'type'          => 'text',
        ));

        $this->add(array(
            'name'          => 'address',
            'options'       => array(
                'label' => __('Client Address'),
            ),
            'attributes'    => array(
                'type'  => 'text',
                'description'   => __('Url of client website'),
            )
        ));

        $this->add(array(
            'name'          => 'redirect_uri',
            'options'       => array(
                'label' => __('Redirect Uri'),
            ),
            'attributes'    => array(
                'type'  => 'text',
                'description'   => __('Redirect uri after authorization'),
            )
        ));

        $this->add(array(
            'name'          => 'description',
            'options'       => array(
                'label' => __('Client Description'),
            ),
            'attributes'    => array(
                'type'  => 'textarea',
                'description'   => __('Client description'),
            )
        ));

        $this->add(array(
            'name'          => 'logo',
            'options'       => array(
                'label' => __('Client Logo Uri'),
            ),
            'attributes'    => array(
                'type'  => 'text',
                'description'   => __('Logo uri of client website'),
            )
        ));

        $this->add(array(
            'name'          => 'id',
            'attributes'    => array(
                'type'     => 'hidden',
            )
        ));

        $this->add(array(
            'name'          => 'uid',
            'attributes'    => array(
                'type'     => 'hidden',
            )
        ));

        $this->add(array(
            'name'          => 'submit',
            'attributes'    => array(
                'value'     => __('Submit'),
            ),
            'type'          => 'submit',
        ));
    }
}
