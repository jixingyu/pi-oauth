<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 * @package         Form
 */

namespace Pi\Form\View\Helper;

use Zend\Form\View\Helper\FormRadio as ZendFormElement;

/**
 * Radio element helper
 *
 * {@inheritDoc}
 * @author Taiwen Jiang <taiwenjiang@tsinghua.org.cn>
 */
class FormRadio extends ZendFormElement
{
    /** @var array Label attributes */
    protected $labelAttributes = array(
        'class' => 'radio',
    );
}
