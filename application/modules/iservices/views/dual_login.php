<style>
    .login_tabs li {
        width: 50%
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
        width: 50% !important;
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
        }

        .csc a {
            font-size: 13px;
        }
    }
</style>

<div class="container mb-5">
    <div class="col-sm-5 mx-auto mt-5">
        <!-- Nav tabs -->
        <ul class="nav nav-pills login_tabs" role="tablist">
            <li class="nav-item logTab">
                <a class="nav-link navTab active" data-toggle="tab" href="#citizen_login" style="font-size: large;">CITIZEN LOGIN</a>
            </li>
            <li class="nav-item logTab">
                <a class="nav-link navTab" data-toggle="tab" href="#pfc_login" style="font-size: large;">PFC LOGIN</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div id="citizen_login" class="container tab-pane active">
                <?php
                $data = array("pageTitle" => "Login");
                $this->load->view('login', $data);
                ?>
            </div>
            <div id="pfc_login" class="container tab-pane fade">
                <?php
                $data = array("pageTitle" => "Login");
                $this->load->view('admin/login', $data);
                ?>
            </div>
        </div>

        <!-- </div> -->

        <div class="csc">
            <!-- <a class="btn btn-primary mr-2" style="width: 445px;background-color: darkcyan;" href="<?= base_url('iservices/admin/CSC_Auth/initiate') ?>">CSC Login</a> -->
            <a class="btn btn-primary mr-2" style="width: 220px;background-color: darkcyan;" href="<?= base_url('iservices/admin/CSC_Auth/initiate') ?>">CSC Login</a>&nbsp;
            <?= $this->epramaan->epramaan_login_btn() ?>

            <!-- <a class="btn btn-primary" style="width: 404px;background-color: darkcyan;" href="<?= base_url('iservices/admin/CSC_Auth/initiate') ?>">CSC Login</a> -->
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
    })
</script>