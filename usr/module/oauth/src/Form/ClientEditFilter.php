<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

namespace Module\Oauth\Form;

use Zend\InputFilter\InputFilter;

class ClientEditFilter extends InputFilter
{
    public function __construct()
    {
       $this->add(array(
            'name'          => 'name',
            'required'      => true,
            'filters'    => array(
                array(
                    'name'  => 'StringTrim',
                ),
            ),
            'validators'  => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'max'     => 32,
                    ),
                ),
            ),
        ));

       $this->add(array(
            'name'          => 'address',
            'required'      => true,
            'filters'    => array(
                array(
                    'name'  => 'StringTrim',
                ),
            ),
            'validators'  => array(
                new \Zend\Validator\Uri(),
            ),
        ));

       $this->add(array(
            'name'          => 'redirect_uri',
            'required'      => true,
            'filters'    => array(
                array(
                    'name'  => 'StringTrim',
                ),
            ),
            'validators'  => array(
                new \Zend\Validator\Uri(),
            ),
        ));

       $this->add(array(
            'name'          => 'description',
            'required'      => true,
            'filters'    => array(
                array(
                    'name'  => 'StringTrim',
                ),
            ),
        ));

       $this->add(array(
            'name'          => 'logo',
            'required'      => true,
            'filters'    => array(
                array(
                    'name'  => 'StringTrim',
                ),
            ),
            'validators'  => array(
                new \Zend\Validator\Uri(),
            ),
        ));
    }
}
