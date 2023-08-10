<?php
setcookie("verifier_c", "", time() - 3600, "/");
setcookie("nonce_c", "", time() - 3600, "/");
$scope = 'openid';
$serviceId = '100001115';
$aeskey = '424ff8d5-d248-496b-82bf-685fdf81644a';
$redirect_uri = 'http://localhost/rtps/iservices/elogin/response';
$response_type = 'code';
$code_challenge_method = 'S256';
$request_uri = 'https://epramaan.meripehchaan.gov.in/openid/jwt/processJwtAuthGrantRequest.do';

//$request_uri= 'https://up.epramaan.in/openid/jwt/processJwtAuthGrantRequest.do';
$state = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));

//nonce
$nonce = bin2hex(random_bytes(16));

setcookie("nonce_c", "$nonce", time() + 3600, "/");

//verifier
$verifier_bytes = random_bytes(64);
//$code_verifier=base64url_encode($verifier_bytes);
$code_verifier = rtrim(strtr(base64_encode($verifier_bytes), "+/", "-_"), "=");
//echo 'code verifier'.$code_verifier;

setcookie("verifier_c", "$code_verifier", time() + 3600, "/");


//code challenge
$challenge_bytes = hash("sha256", $code_verifier, true);
//$code_challenge=base64url_encode($challenge_bytes);
$code_challenge = rtrim(strtr(base64_encode($challenge_bytes), "+/", "-_"), "=");
//echo 'code challenge'.$code_challenge;

$input = $serviceId . $aeskey . $state . $nonce . $redirect_uri . $scope . $code_challenge;

//apiHmac
$apiHmac = hash_hmac('sha256', $input, $aeskey, true);
$apiHmac = base64_encode($apiHmac);
//echo 'hashmac'.$apiHmac;

echo '<br>';

$url = 'https://epramaan.meripehchaan.gov.in/openid/jwt/processJwtAuthGrantRequest.do';

//$url = 'https://up.epramaan.in/openid/jwt/processJwtAuthGrantRequest.do';

$finalUrl = $url . "?&scope=" . $scope . "&response_type=" . $response_type . "&redirect_uri=" . $redirect_uri . "&state=" . $state . "&code_challenge_method=" . $code_challenge_method . "&nonce=" . $nonce . "&client_id=" . $serviceId . "&code_challenge=" . $code_challenge . "&request_uri=" . $request_uri . "&apiHmac=" . $apiHmac;


?>
<style>
    .logTab {
        /* padding-left: 15px; */
        margin-top: 5rem;
        /* margin-left: 10px; */
        /* padding-right: 50px; */
    }

    .navTab {
        /* padding-left: 61px;
        padding-right: 61px; */
        padding-left: 52px;
        padding-right: 44px;
        border-radius: 0px !important;
        background-color: #e0e2e5;
    }

    .nav-pills .nav-link.active {
        background-color: #1e1510 !important;
    }

    .csc {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 15px;
    }
</style>
<div class="container">
    <div class="row">
        <!-- Pills navs -->
        <div class="col-sm-5 mx-auto">
            <ul class="nav nav-pills login_tabs" id="ex1" role="tablist" style="margin-left: 14px;">
                <li class="nav-item logTab" role="presentation">
                    <a class="nav-link navTab active" id="ex1-tab-1" data-mdb-toggle="pill" href="#ex1-pills-1" role="tab" aria-controls="ex1-pills-1" aria-selected="true" style="font-size: large;">CITIZEN LOGIN</a>
                </li>
                <li class="nav-item logTab" role="presentation">
                    <a class="nav-link navTab " id="ex1-tab-2" data-mdb-toggle="pill" href="#ex1-pills-2" role="tab" aria-controls="ex1-pills-2" aria-selected="false" style="font-size: large;">PFC LOGIN</a>
                </li>

            </ul>
        </div>
        <!-- Pills navs -->

        <!-- Pills content -->

        <!-- Pills content -->

        <!-- <div class="col-sm-5 mx-auto">
            <div class="card my-2">
                <div class="card-body">
                   
                </div>
            </div>
        </div> -->
    </div>
    <div class="tab-content" id="ex1-content" style="margin-bottom: 5rem;">
        <div class="tab-pane fade show active" id="ex1-pills-1" role="tabpanel" aria-labelledby="ex1-tab-1">
            <?php
            $data = array("pageTitle" => "Login");
            $this->load->view('login', $data);
            ?>
        </div>
        <div class="tab-pane fade" id="ex1-pills-2" role="tabpanel" aria-labelledby="ex1-tab-2">
            <?php
            $data = array("pageTitle" => "Login");
            $this->load->view('admin/login', $data);
            ?>
        </div>
        <div class="csc">
            <a class="btn btn-primary mr-2" style="width:210px;background-color: darkcyan;" href="<?= base_url('iservices/admin/CSC_Auth/initiate') ?>">CSC Login</a>
            <?= $this->epramaan->epramaan_login_btn() ?>

        </div>
        <!-- <a class="btn btn-primary" style="width: 404px;background-color: darkcyan;" href="<?= $finalUrl ?>">E Login</a> -->

    </div>
</div>

<script>
    $(document).ready(function() {

        $(".login_tabs li a").on('click', function(event) {

            event.preventDefault();

            var id = $(this).attr('href');
            var tabid = $(this).attr('id');
            // console.log(tabid);
            // console.log($('.login_tabs li a'+ tabid));
            //    alert(tabid);
            //    console.log($(".tab-content div"+ id));
            $(".tab-content div").removeClass('show');
            $(".tab-content div").removeClass('active');

            $(".login_tabs li a").removeClass('active');

            $(".tab-content div" + id).addClass('show');
            $(".tab-content div" + id).addClass('active');

            $(".login_tabs li a" + "#" + tabid).addClass('active');
        })
    })
</script>