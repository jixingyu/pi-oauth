For consumer(Client Credentials)
================================
##### 1. Client register
- Url: \<host\>/oauth/client/register

##### 2. Get access token
- Url: \<host\>/oauth/grant/index
- method: POST
- parameters:
<table>
  <tr>
    <td>grant_type</td>
    <td>Grant type, the value is "client_credentials" fixed</td>
  </tr>
  <tr>
    <td>client_id</td>
    <td>Client ID assigned after registering the client</td>
  </tr>
  <tr>
    <td>client_secret</td>
    <td>Client secret assigned after registering the client</td>
  </tr>
</table>
- return: json string, example {"token_type":"bearer","expires_in":"3600","access_token":"f6d***7e"}

##### Error
- If error occurs, a json string contained "error" and "error_description" will be returned.
