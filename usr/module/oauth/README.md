Oauth Module
============

This is an Oauth 2.0 module for Oauth provider and consumer based according to RFC6749.

The OAuth 2.0 authorization framework enables a third-party application to obtain limited access to an HTTP service.


Features
---------
As provider, oauth module supports the following:
- Client registration, web application can apply for the client id and client secret.
- To obtain an access token, four grant types are provided -- authorization code, implicit, resource owner password credentials, and client credentials.
- Apply for extra scopes to obtain more infomation.
- Back end -- client and scope management, configurations of oauth provider server.

As consumer, oauth module provides the following api:
- Get existent token
- Generate authorize url used by authorization code and implicit authorization
- Get access token from oauth provider server
- Generate resource url to get resource data.

Check out [manual for consumer development](https://github.com/jixingyu/pi-oauth/tree/master/usr/module/oauth/doc)

TODO List
=========
- Client logo image resize
- Uri validation and multi-support
- UI optimization
- Consumer api optimization
- Client with different type get different grant types support
