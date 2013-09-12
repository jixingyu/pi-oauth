front-end
========

#### 1. Client management
* 1.1 **Register:** Web application register a client, then a client_id and client_secret will be generated. Registration needs client name, address, redirect uri, description, logo.
* 1.2 **Client list:** List the clients that registered before.
* 1.3 **Show details and edit client:** Client id and client secret can not be changed.
* 1.4 **Submit for verification:**  Need to submit for verification after register a client. If the client is not verified, functions are limited.
* 1.5 **Apply for scope:** The new registered client has the scope with base functions. The application for more scopes is provided after the client is virefied.

Check out [manual for consumer development](https://github.com/jixingyu/oauth/tree/master/oauth/doc)

back-end
========
#### 1. Client management for provider
* 1.1 **Manage:** List all clients information, show client detail and delete client.
* 1.2 **Verify:** Process client verification, approve or disapprove the client with reasons.

#### 2. Scope management for provider
* 1.1 **Manage:** List all exist scopes, add scope with scope name and brief and delete a scope.
* 1.2 **Verify:** Process scope verification, approve or ignore the scope application.

#### 3. Configuration(Only for provider except oauth role)
* 1.1 **Oauth role:** Set the module as consumer or provider. It is provider by default.
* 1.2 **Code & token:** Set the length and expire of authorization code, access token and refresh token. It is also provided to set whether to support refresh token.
* 1.3 **Grant type:** Set whether to support the four grant types -- authorization code, implicit, resource owner password credentials, and client credentials.
                      Only authorization code is supported by default.
* 1.4 **Storage model:** Set the storage, database by default.
* 1.5 **Scope:** Set the base scope for verified client and test scope for unverified client.
