For consumer(Implicit)
================================
##### 1. Client register
- Url: \<host\>/oauth/client/register

##### 2. Get access token
- Url: \<host\>/oauth/authorize/index
- method: GET
- parameters
<table>
  <tr>
    <td>client_id</td>
    <td>Client ID assigned after registering the client</td>
  </tr>
  <tr>
    <td>response_type</td>
    <td>Response type, the value is "token" fixed</td>
  </tr>
  <tr>
    <td>redirect_uri</td>
    <td>Callback uri after authorization</td>
  </tr>
  <tr>
    <td>state(optional)</td>
    <td>State of client, it will be sent back after authorization</td>
  </tr>
  <tr>
    <td>scope(optional)</td>
    <td>Uncompleted(please use "base" for testing)</td>
  </tr>
</table>

- return: redirect uri, example <redirect uri>#access_token=f6d***7e&expires_in=3600&token_type=bearer&scope=base

##### Error
- If error occurs, a json string contained "error" and "error_description" will be returned.
