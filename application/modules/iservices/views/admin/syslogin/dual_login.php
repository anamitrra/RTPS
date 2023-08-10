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
        <div class=" row col-md-12 mt-3 text-center">
            <?php if ($this->session->userdata('error') <> '') { ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error</strong>
                    <?php echo $this->session->userdata('error') <> '' ? $this->session->userdata('error') : ''; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
        </div>

    <div class="col-sm-5 mx-auto mt-5">
        <!-- Nav tabs -->
        <ul class="nav nav-pills login_tabs" role="tablist">
            <li class="nav-item logTab">
                <a class="nav-link navTab active" data-toggle="tab" href="#citizen_login" style="font-size: large;">CITIZEN LOGIN</a>
            </li>
            <li class="nav-item logTab">
                <a class="nav-link navTab" data-toggle="tab" href="#pfc_login" style="font-size: large;">PFC LOGIN</a>
            </li>
            <li class="nav-item logTab">
                <a class="nav-link navTab" data-toggle="tab" href="#csclogin" style="font-size: large;">CSC LOGIN</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div id="citizen_login" class="container tab-pane active">
                <?php
                $data = array("pageTitle" => "Login");
                $this->load->view('admin/syslogin/clogin', $data);
                ?>
            </div>
            <div id="pfc_login" class="container tab-pane fade">
                <?php
                $data = array("pageTitle" => "Login");
                $this->load->view('admin/syslogin/alogin', $data);
                ?>
            </div>
              <div id="csclogin" class="container tab-pane fade">
                <?php
                $data = array("pageTitle" => "Login");
                $this->load->view('admin/syslogin/csclogin', $data);
                ?>
            </div>
        </div>

        <!-- </div> -->

        
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