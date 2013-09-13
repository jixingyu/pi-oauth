<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

namespace Module\Oauth\Controller;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Zend\Mvc\MvcEvent;

/**
 * Basic provider action controller
 *
 * @author Xingyu Ji <xingyu@eefocus.com>
 */
abstract class AbstractProviderController extends ActionController
{
    /**
     * Execute the request
     *
     * @param  MvcEvent         $e
     * @return mixed
     * @throws \DomainException
     */
    public function onDispatch(MvcEvent $e)
    {
        $config = $this->config('oauth_role');
        // not provider
        if ($config != 1) {
            $this->jumpToDenied();

            return;
        }

        $actionResponse = parent::onDispatch($e);

        return $actionResponse;
    }

}
