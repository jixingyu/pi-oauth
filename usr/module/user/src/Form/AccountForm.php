<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

namespace Module\User\Form;

use Pi;
use Pi\Form\Form as BaseForm;

/**
 * Account edit form
 *
 * @author Liu Chuang <liuchuang@eefocus.com>
 */
class AccountForm extends BaseForm
{
    public function init()
    {
        $this->add(array(
            'name'       => 'name',
            'options'    => array(
                'label' => __('Display name'),
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));

        $this->add(array(
            'name'       => 'submit',
            'attributes' => array(
                'value' => __('Submit'),
            ),
            'type'       => 'submit',
        ));
    }
}