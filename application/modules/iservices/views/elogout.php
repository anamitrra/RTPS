<html>

<head></head>

<body>
    <?php
    $epramaan_data = $this->session->userdata('epramaan_data');
    $epramaanRequestTokenUrl = 'https://epramaan.meripehchaan.gov.in/openid/jwt/processOIDCSLORequest.do';
    $redirect_uri = 'http://localhost/rtps/iservices/elogin/logout_res';
    $service_id = '100001115';
    $iss = 'ePramaan';
    $logoutRequestId = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
    $input = $service_id . $epramaan_data->session_id . $iss . $logoutRequestId . $epramaan_data->sub . $redirect_uri;

    //apiHmac
    $apiHmac = hash_hmac('sha256', $input, $logoutRequestId, true);
    $apiHmac = base64_encode($apiHmac);

    $json = array(
        "clientId" => $service_id,
        "sessionId" => $epramaan_data->session_id,
        "sessionId" => $epramaan_data->session_id,
        "hmac" => $apiHmac,
        "iss" => $iss,
        "logoutRequestId" => $logoutRequestId,
        "sub" => $epramaan_data->sub,
        "redirectUrl" => $redirect_uri,
        "customParameter" => "custom"
    );
    $json_data = json_encode($json);

    ?>
    <br>
    <br>
    <form action="https://epramaan.meripehchaan.gov.in/openid/jwt/processOIDCSLORequest.do" method="post">
        <input type='text' name='data' value='<?= $json_data ?>'>
        <input type="submit" value="logout">
    </form>
</body>

</html>