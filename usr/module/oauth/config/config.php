<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

$config = array();

// config category
$config['category'] = array(
    array(
        'name'      => 'general',
        'title'     => __('General'),
    ),
    array(
        'name'      => 'authorization_code',
        'title'     => __('Authorization code(Provider)'),
    ),
    array(
        'name'      => 'access_token',
        'title'     => __('Access token(Provider)'),
    ),
    array(
        'name'      => 'refresh_token',
        'title'     => __('Refresh token(Provider)'),
    ),
    array(
        'name'      => 'grant_type',
        'title'     => __('Grant type(Provider)'),
    ),
    array(
        'name'      => 'storage',
        'title'     => __('Storage model(Provider)'),
    ),
    array(
        'name'      => 'scope',
        'title'     => __('Scope(Provider)'),
    ),
);

// Config items
$config['item'] = array(

    // General section
    'oauth_role'      => array(
        'title'         => __('OAuth role'),
        'description'   => __('The oauth module is work as provider or consumer.'),
        'edit'          => array(
            'type'      => 'radio',
            'attributes'    => array(
                'options'   => array(
                    '0'       => __('Consumer'),
                    '1'       => __('Provider'),
                ),
            ),
        ),
        'value'         => 0,
        'filter'        => 'number_int',
        'category'      => 'general',
    ),

    //authorization code section
    'code_length'            => array(
        'title'         => __('length'),
        'description'   => __('The length of authorization code.'),
        'edit'          => 'text',
        'value'         => '40',
        'category'      => 'authorization_code',
    ),
    'code_expires'            => array(
        'title'         => __('expire'),
        'description'   => __('The life time of authorization code'),
        'edit'          => 'text',
        'value'         => '600',
        'category'      => 'authorization_code',
    ),

    // Access Token section
    'access_length'            => array(
        'title'         => __('length'),
        'description'   => __('The length of access token.'),
        'edit'          => 'text',
        'value'         => '40',
        'category'      => 'access_token',
    ),
    'access_expires'            => array(
        'title'         => __('expire'),
        'description'   => __('The life time of access token'),
        'edit'          => 'text',
        'value'         => '3600',
        'category'      => 'access_token',
    ),

    // Refresh Token section
    'refresh_flag'              => array(
        'title'         => __('Support refresh'),
        'description'   => __('Whether to support refresh token.'),
        'edit'          => 'checkbox',
        'value'         => '0',
        'filter'        => 'number_int',
        'category'      => 'refresh_token',
    ),
    'refresh_length'            => array(
        'title'         => __('length'),
        'description'   => __('The length of refresh token.'),
        'edit'          => 'text',
        'value'         => '40',
        'category'      => 'refresh_token',
    ),
    'refresh_expires'           => array(
        'title'         => __('expire'),
        'description'   => __('The life time of refresh token'),
        'edit'          => 'text',
        'value'         => '129600',
        'category'      => 'refresh_token',
    ),

    // Grant type section
    'code'              => array(
        'title'         => __('Enable authorization code'),
        'description'   => __('Authorization Code'),
        'edit'          => 'checkbox',
        'value'         => 1,
        'filter'        => 'number_int',
        'category'      => 'grant_type',
    ),
    'implicit'          => array(
        'title'         => __('Enable implicit'),
        'description'   => __('Implicit'),
        'edit'          => 'checkbox',
        'value'         => 0,
        'filter'        => 'number_int',
        'category'      => 'grant_type',
    ),
    'password'          => array(
        'title'         => __('Enable password credentials'),
        'description'   => __('resource owner password credentials'),
        'edit'          => 'checkbox',
        'value'         => 0,
        'filter'        => 'number_int',
        'category'      => 'grant_type',
    ),
    'client'            => array(
        'title'         => __('Enable client credentials'),
        'description'   => __('Client credentials'),
        'edit'          => 'checkbox',
        'value'         => 0,
        'filter'        => 'number_int',
        'category'      => 'grant_type',
    ),

    //storage model section
    'storage'           => array(
        'title'         => __('Storage model'),
        'description'   => __('Select the storage model'),
        'edit'          => array(
            'type'      => 'select',
            'attributes'    => array(
                'options'   => array(
                    'database'       => __('Database'),
                ),
            ),
        ),
        'value'         => 'Database',
        'category'      => 'storage',
    ),

    // scope section
    'base_scope'             => array(
        'title'         => __('Base scope'),
        'description'   => __('Base scope for verified client, multi-scopes are separated by space'),
        'edit'          => 'textarea',
        'value'         => '',
        'category'      => 'scope',
    ),

    'unverified_scope'             => array(
        'title'         => __('Test scope'),
        'description'   => __('Scope for unverified client, multi-scopes are separated by space'),
        'edit'          => 'textarea',
        'value'         => '',
        'category'      => 'scope',
    ),
);

return $config;

// $config = array(
//             'server'    => array(
//                 'authorization' => array(
//                     'response_types'    => array(
//                         'code', 'token',
//                     ),
//                  ),
//                 'grant' => array(
//                     'grant_types'   => array(
//                         'authorization_code'    => 'AuthorizationCode',
//                         'password'              => 'Password',
//                         'client_credentials'    => 'ClientCredentials',
//                         'refresh_token'         => 'RefreshToken',
//                         'urn:ietf:params:oauth:grant-type:jwt-bearer'
//                                         => 'JwtBearer',
//                     ),
//                 ),
//                 'resource'  => array(
//                     'token_type'    => 'bearer',
//                     'www_realm'     => 'service',
//                 ),
//             ),
//             'storage'   => array(
//                 'model_set'             => array(
//                     'name'      => 'database',
//                     'config'    => array(
//                         'table_prefix'  => 'oauth',
//                     ),
//                 ),
//                 'client' => array(
//                     'model_set'             => array(
//                         'name'      => 'database',
//                         'config'    => array(
//                             'table_prefix'  => 'oauth',
//                         ),
//                     ),
//                 ),
//                 'authorization_code'    => array(
//                     'expires_in'    => 300,
//                     'length'        => 40,
//                 ),
//                 'access_token'  => array(
//                     'token_type'    => 'bearer',
//                     'expires_in'    => 3600,
//                     'length'        => 40,
//                 ),
//                 'refresh_token' => array(
//                     'expires_in'    => 1209600,
//                     'length'        => 40,
//                 ),
//             )
//         );
