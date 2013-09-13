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

class AuthorizeLoginForm extends BaseForm
{
    public function init()
    {
        $this->add(array(
            'name'          => 'submit',
            'attributes'    => array(
                'value'     => __('yes'),
            ),
            'type'          => 'submit',
        ));
        $this->add(array(
            'name'          => 'authorized',
            'attributes'    => array(
                'value'     => 1,
            ),
            'type'          => 'hidden',
        ));
    }
}
