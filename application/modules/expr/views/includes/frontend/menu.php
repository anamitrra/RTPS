<style>
.bg {
  background: #1e7bb6;
  border-radius: 4px;
}
</style>
<section>
    <nav class="navbar navbar-expand-md navbar">
        <div class="container-fluid header-sec" style="width:1300px !important">

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                   <?php
                    if($this->session->userdata('opt_status')){
                   ?>
                    <li class="nav-item hvr-bounce-in hvr-float-shadow mr-md-4 bg">
                        <a class="nav-link text-light text-center" href="<?=base_url('expr/transactions')?>"><span>My Transactions</span></a>
                    </li>
                    <!-- <li class="nav-item hvr-bounce-in hvr-float-shadow mr-md-4 bg">
                        <a class="nav-link text-light text-center" href="<?=base_url('expr/status')?>"><span>Track Application</span></a>
                    </li> -->
                    <li class="nav-item hvr-bounce-in hvr-float-shadow mr-md-4 bg">
                        <a class="nav-link text-light text-center" href="<?=base_url('expr/login/logout')?>"><span>LOGOUT</span></a>
                    </li>
                    <?php
                    }
                   ?>

                </ul>
            </div>
        </div>
    </nav>
</section>
