<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * Pi Engine admin application specifications
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 * @author          Taiwen Jiang <taiwenjiang@tsinghua.org.cn>
 */

// Inherite from front
$config = include __DIR__ . '/application.front.php';

// Translations
$config['resource']['i18n'] = array(
    'translator'    => array(
        'global'    => array('user:main', 'usr:admin'),
        'module'    => array('main', 'admin'),
    ),
);

// Permission ACL
$config['resource']['acl'] = array(
    // Default access perm in case not defined
    'default'       => false,
    // If check page access
    'check_page'    => true,
    // Managed components
    'component'     => array('block', 'config', 'page', 'resource', 'event'),
    // Admin entries
    'entry'         => array('index', 'dashboard'),
);

// Render caching
$config['resource']['render'] = false;

// Audit
/*
 * Options for recording:
 * skipError - skip error action
 * users - specific users to be logged
 * ips - specific IPs to be logged
 * roles - specific roles to be logged
 * pages - specific pages to be logged
 * methods - specific request methods to be logged
 */
$config['resource']['audit'] = array(
    'skipError' => true,
    'methods'   => array('POST'),
);

// Admin mode detection
$config['resource']['adminmode'] = array();

// Session settings
$config['resource']['session'] = array(
    //'service'   => 'service.session-admin.php',
);

// Application service configuration
$config['application']['view_manager']['layout'] = 'layout-admin';

return $config;
