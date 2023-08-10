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
        padding-right: 61px;
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

    .logTab {
        margin: 0;
    }

    @media only screen and (max-width: 768px) {
        .login_tabs {
            padding-left: 6px;
            display: block;
            width: 95.4%;
            text-align: center
        }
    }
</style>
<div class="container">
    <div class="row">
        <!-- Pills navs -->
        <div class="col-sm-5 mx-auto mt-4">
            <ul class="nav nav-pills login_tabs" id="ex1" role="tablist" style="margin-left:5px;">
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