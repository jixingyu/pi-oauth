<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

namespace Module\Oauth;

use Pi;

/**
 * Navigation handler
 *
 * @author Xingyu Ji <xingyu@eefocus.com>
 */
class Navigation
{
    /**
     * Admin navigation
     *
     * @param  string $module
     * @return array
     */
    public static function admin($module)
    {
        $pages = array();
        $nav = array(
            'parent' => &$pages,
        );

        // get oauth role
        $oauthRole = Pi::service('module')->config(
            'oauth_role',
            $module
        );

        if ($oauthRole == 0) {
            // consumer
            $pages = array(
                'add'  => array(
                    'label'      => 'Consumer management',
                    'route'      => 'admin',
                    'module'     => $module,
                    'controller' => 'consumer',
                    'action'     => 'index',
                ),
            );
        } else {
            // provider
            $pages = array(
                'list'  => array(
                    'label'      => 'Client management',
                    'route'      => 'admin',
                    'module'     => 'oauth',
                    'controller' => 'client',
                    'action'     => 'index',
                ),
                'scope'     => array(
                    'label'      => 'Scope management',
                    'route'      => 'admin',
                    'module'     => 'oauth',
                    'controller' => 'scope',
                    'action'     => 'index',
                ),
            );
        }

        return $nav;
    }
    /**
     * Front navigation
     *
     * @param  string $module
     * @return array
     */
    public static function front($module)
    {
        $pages = array();
        $nav = array(
            'parent' => &$pages,
        );

        // get oauth role
        $oauthRole = Pi::service('module')->config(
            'oauth_role',
            $module
        );

        if ($oauthRole == 0) {
            // consumer
            $pages = array(
            );
        } else {
            // provider
            $pages = array(
                'list'     => array(
                    'label'         => 'Client list',
                    'route'         => 'default',
                    'module'        => 'oauth',
                    'controller'    => 'client',
                    'action'        => 'list',
                ),
                'register' => array(
                    'label'         => 'Register client',
                    'route'         => 'default',
                    'module'        => 'oauth',
                    'controller'    => 'client',
                    'action'        => 'register',
                ),
                'scope'    => array(
                    'label'         => 'Apply scope',
                    'route'         => 'default',
                    'module'        => 'oauth',
                    'controller'    => 'client',
                    'action'        => 'scope',
                ),
            );
        }

        return $nav;
    }
}
