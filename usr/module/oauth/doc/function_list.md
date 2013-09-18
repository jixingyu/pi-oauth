front-end
========
#### 1. Client management
* **Register:** Web application register a client, then a client_id and client_secret will be generated. Registration needs client name, address, redirect uri, description, logo.
* **Client list:** List the clients that registered before.
* **Show details and edit client:** Client id and client secret can not be changed.
* **Submit for verification:**  Need to submit for verification after register a client. If the client is not verified, functions are limited.
* **Apply for scope:** The new registered client get the scope with base functions. The application for more scopes is provided after the client is virefied.

#### 2. Note
* Unverified client is used to develop and only client creater's data on resource server are accessible.

back-end
========
#### 1. Client management for provider
* **Manage:** List all clients information, show client detail and delete client.
* **Verify:** Process client verification, approve or disapprove the client with reasons.

#### 2. Scope management for provider
* **Manage:** List all exist scopes, add scope with scope name and brief and delete a scope.
* **Verify:** Process scope verification, approve or ignore the scope application.

#### 3. Configuration(Only for provider except oauth role)
* **Oauth role:** Set the module as consumer or provider. It is provider by default.
* **Code & token:** Set the length and expire of authorization code, access token and refresh token. It is also provided to set whether to support refresh token.
* **Grant type:** Set whether to support the four grant types -- authorization code, implicit, resource owner password credentials, and client credentials.
                      Only authorization code is supported by default.
* **Storage model:** Set the storage, database by default.
* **Scope:** Set the base scope for verified client and test scope for unverified client.
