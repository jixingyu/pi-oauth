<?php
namespace Pi\Oauth\Provider\Result;

/**
 * @see http://tools.ietf.org/html/rfc6749#section-7.2
 */
class ResourceError extends GrantError
{
    protected $errorType = 'token';
    protected $errors = array(
        'invalid_request'           =>  'The request is missing a required parameter, includes an unsupported parameter or parameter value, repeats the same parameter, uses more than one method for including an access token, or is otherwise malformed.',
        'invalid_token'             =>  'The access token provided is expired, revoked, malformed, or invalid for other reasons.',
        'insufficient_scope'        =>  'The request requires higher privileges than provided by the access token.',
   );
}
