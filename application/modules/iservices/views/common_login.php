<style>
    .login_tabs li {
        width: 33.3%
    }

    .navTab {
        text-align: center;
        border-radius: 0px !important;
        background-color: #e0e2e5;
    }

    .nav-pills .nav-link.active {
        background-color: #1e1510 !important;
    }

    .csc {
        display: flex;
        flex-direction: row;
        margin-top: 10px;
    }

    .csc a {
        width: 100% !important;
        border-radius: 0;
        border: 0;
    }

    #citizen_login {
        padding-top: 20px;
        border: 1px solid #ddd;
    }

    #pfc_login {
        padding-top: 20px;
        border: 1px solid #ddd;
    }

    #csc_login {
        min-height: 150px;
        padding-top: 20px;
    }

    .card {
        border-radius: 0;
        min-height: 300px;
        text-align: justify
    }

    .card .card-body a {
        border-radius: 0;
        border: 0;
    }


    @media only screen and (max-width: 768px) {
        .login_tabs {
            display: block;
            width: 100%;
            text-align: center;
        }

        .login_tabs li {
            width: 100%;
            border: 1px solid #ddd;
        }

        .csc a {
            font-size: 13px;
        }
    }

    .blink {
        animation: blinker 1.5s linear infinite;
        font-family: sans-serif;
    }

    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }
</style>

<!-- <div class="container mb-5" style="min-height:55vh">
    <div class="col-sm-5 mx-auto mt-5 ">
        <div class="shadow">
            <ul class="nav nav-pills login_tabs" role="tablist">
                <li class="nav-item logTab">
                    <a class="nav-link navTab active" data-toggle="tab" href="#citizen_login" style="font-size: large;">CITIZEN</a>
                </li>
                <li class="nav-item logTab">
                    <a class="nav-link navTab" data-toggle="tab" href="#pfc_login" style="font-size: large;">PFC</a>
                </li>
                <li class="nav-item logTab">
                    <a class="nav-link navTab" data-toggle="tab" href="#csc_login" style="font-size: large;">CSC</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="citizen_login" class="container tab-pane active">
                    <p class="blink"><a target="_blank" href="<?= base_url('assets/frontend/epramaan-guidlines-citizen.pdf') ?>" style="color:red;text-decoration:none">Click here for login/registration guidelines.</a></p>
                    <p>Now we are upgrading to e-Pramaan SSO login. If you are an existing user of e-Pramaan, you can login using e-Pramaan. If you don't have an e-Pramaan account yet, then please register with e-Pramaan using RTPS linked mobile number.</p>
                    <p class="text-danger"><strong>Please note that if the RTPS linked mobile number is not used for e-Pramaan registration, then linking to your existing RTPS account will fail.</strong></p>
                    <p class="text-center">
                        <a class="btn btn-md btn-primary" href="<?= base_url('iservices/ssologin/citizenlogin') ?>" style="border-radius:0">Login/Registration</a>
                    </p>
                </div>
                <div id="pfc_login" class="container tab-pane fade">
                    <p class="blink"><a target="_blank" href="<?= base_url('assets/frontend/epramaan-guidlines-PFC.pdf') ?>" style="color:red;text-decoration:none">Click here for login/registration guidelines.</a></p>
                    <p>Now we are upgrading to e-Pramaan SSO login. If you are an existing user of e-Pramaan, you can login using e-Pramaan. If you don't have an e-Pramaan account yet, then please register with e-Pramaan using RTPS linked email ID and mobile number. Please use PFC login ID(email) as username in e-Pramaan while registering</p>
                    <p class="text-danger"><strong>Please note that if the RTPS PFC login ID(email) is not used as username in e-Pramaan registration, then linking to your existing RTPS account will fail.</strong></p>
                    <p class="text-center">
                        <a class="btn btn-md btn-warning" href="<?= base_url('iservices/ssologin/pfclogin') ?>" style="border-radius:0">Login/Registration</a>
                    </p>
                </div>
                <div id="csc_login" class="container tab-pane fade">
                    <p>For CSC Login</p>
                    <div class="csc">
                        <?php
                        if (!empty($this->session->userdata('applyIn'))) {
                            if ($this->session->userdata('applyIn') == 'servicePlus') {
                                $cscUrl = 'https://sewasetu.assam.gov.in/deptusr/loginWindow.do?servApply=N&%3Ccsrf:token%20uri=%27loginWindow.do%27/%3E';
                            } else {
                                $cscUrl = base_url('iservices/admin/CSC_Auth/initiate');
                            }
                        } else {
                            $cscUrl = base_url('iservices/admin/CSC_Auth/initiate');
                        } ?>
                        <a class="btn btn-primary mr-2" style="background-color: darkcyan;" href="<?= $cscUrl ?>">CSC Connect Login</a>&nbsp;
                    </div>
                </div>
            </div>
        </div>
    </div>

</div> -->


<div class="container mb-5" style="min-height:55vh">
    <div class="col-sm-6 mx-auto mt-5 ">
        <div class="border border-1 rounded shadow-sm">
            <ul class="nav nav-pills login_tabs flex-nowrap" role="tablist">
                <li class="nav-item logTab">
                    <a class="nav-link navTab active" data-toggle="tab" href="#citizen_login" style="font-size: large;">CITIZEN</a>
                </li>
                <li class="nav-item logTab">
                    <a class="nav-link navTab" data-toggle="tab" href="#pfc_login" style="font-size: large;">PFC</a>
                </li>
                <li class="nav-item logTab">
                    <a class="nav-link navTab" data-toggle="tab" href="#csc_login" style="font-size: large;">CSC</a>
                </li>
                <li class="nav-item logTab">
                    <a class="nav-link navTab" data-toggle="tab" href="#official_login" style="font-size: large;">OFFICIAL</a>
                </li>

            </ul>
            <div class="tab-content">

                <div id="citizen_login" class="container tab-pane active">
                    <p class="blink"><a target="_blank" href="<?= base_url('assets/frontend/epramaan-guidlines-citizen.pdf') ?>" style="color:red;text-decoration:none">Click here for login/registration guidelines.</a></p>
                    <p>Now we are upgrading to e-Pramaan SSO login. If you are an existing user of e-Pramaan, you can login using e-Pramaan. If you don't have an e-Pramaan account yet, then please register with e-Pramaan using RTPS linked mobile number.</p>
                    <p class="text-danger"><strong>Please note that if the RTPS linked mobile number is not used for e-Pramaan registration, then linking to your existing RTPS account will fail.</strong></p>
                    <p class="text-center">
                        <a class="btn btn-md btn-primary" href="<?= base_url('iservices/ssologin/citizenlogin') ?>" style="border-radius:0">Login/Registration</a>
                    </p>
                </div>
                <div id="pfc_login" class="container tab-pane fade">
                    <p class="blink"><a target="_blank" href="<?= base_url('assets/frontend/epramaan-guidlines-PFC.pdf') ?>" style="color:red;text-decoration:none">Click here for login/registration guidelines.</a></p>
                    <p>Now we are upgrading to e-Pramaan SSO login. If you are an existing user of e-Pramaan, you can login using e-Pramaan. If you don't have an e-Pramaan account yet, then please register with e-Pramaan using RTPS linked email ID and mobile number. Please use PFC login ID(email) as username in e-Pramaan while registering</p>
                    <p class="text-danger"><strong>Please note that if the RTPS PFC login ID(email) is not used as username in e-Pramaan registration, then linking to your existing RTPS account will fail.</strong></p>
                    <p class="text-center">
                        <a class="btn btn-md btn-warning" href="<?= base_url('iservices/ssologin/pfclogin') ?>" style="border-radius:0">Login/Registration</a>
                    </p>
                </div>
                <div id="csc_login" class="container tab-pane fade">
                    <p>For CSC Login</p>
                    <div class="csc">
                        <?php
                        if (!empty($this->session->userdata('applyIn'))) {
                            if ($this->session->userdata('applyIn') == 'servicePlus') {
                                $cscUrl = 'https://sewasetu.assam.gov.in/deptusr/loginWindow.do?servApply=N&%3Ccsrf:token%20uri=%27loginWindow.do%27/%3E';
                            } else {
                                $cscUrl = base_url('iservices/admin/CSC_Auth/initiate');
                            }
                        } else {
                            $cscUrl = base_url('iservices/admin/CSC_Auth/initiate');
                        } ?>
                        <a class="btn btn-primary mr-2" style="background-color: darkcyan;" href="<?= $cscUrl ?>">CSC Connect Login</a>&nbsp;
                    </div>
                </div>



                <div id="official_login" class="container tab-pane fade p-2">
                    <details id="nav-official" open="">
                        <summary class="fw-bold p-2 rounded text-center" style="background-color: #cee7f7;color: #030395;">
                            Officials Login
                        </summary>

                        <ul class="px-1">
                            <li class="list-unstyled my-2">
                                <input type="radio" class="form-check-input me-2" name="officeLogin" id="offL1">
                                <label class="form-check-label" for="offL1">Deputy Commissioners Office</label>
                            </li>
                            <li class="list-unstyled my-2">
                                <input type="radio" class="form-check-input me-2" name="officeLogin" id="offL2">
                                <label class="form-check-label" for="offL2">Circle Office</label>
                            </li>
                            <li class="list-unstyled my-2">
                                <input type="radio" class="form-check-input me-2" name="officeLogin" id="offL4">
                                <label class="form-check-label" for="offL4">For Contractors</label>
                            </li>
                            <li class="list-unstyled my-2">
                                <input type="radio" class="form-check-input me-2" name="officeLogin" id="offL3">
                                <label class="form-check-label" for="offL3">Others</label>
                            </li>
                        </ul>

                        <button style="font-size: smaller;" type="button" id="officialLogin" class="btn d-block mt-2 mx-auto rounded-0 rtps-btn-alt text-uppercase">Login</button>

                    </details>


                </div>

            </div>
        </div>
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



         /* Official loggin button */
        document.querySelector('#officialLogin').addEventListener('click', function(event) {
            if (document.querySelector('#nav-official input[type="radio"]:checked') == null) {
                alert('Please select an option');
                return true;
            }

            // Option selected

            switch (document.querySelector('#nav-official input[type="radio"]:checked').id) {
                case 'offL3':
                    // dept User
                    window.open(`${window.location.origin}/deptusr/loginWindow.do?servApply=N&%3Ccsrf:token%20uri=%27loginWindow.do%27/%3E`, '_blank');

                    break;

                case 'offL4':
                    // For Contractors
                    window.open(`${window.location.origin}/spservices/upms/login`, '_blank');

                    break;

                default:
                    // MCC officials
                    window.open(`${window.location.origin}/spservices/mcc/user-login`, '_blank');

                    break;
            }

        });
        

    })
</script>