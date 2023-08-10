<style>
    .bg {
        background: #1e7bb6;
        border-radius: 4px;
    }

    /* For search bar  */
    button.service-serach-btn:hover,
    button.service-serach-btn:focus {
        background-color: #362f2d;
        color: whitesmoke;
    }

    @media only screen and (max-width: 600px) {
        input#search {
            width: 100% !important;
        }

        button.service-serach-btn {
            transform: translate(0);
        }
    }
</style>

<nav class="navbar navbar-expand-md container-fluid py-0">
    <div class="container">

        <a class="navbar-brand p-2" href="<?= base_url('site') ?>">
            <img src="<?= base_url('assets/site/theme1/images/home.png') ?>" alt="Home" width="16">
        </a>

        <?php
        if ($this->session->userdata('opt_status')) {
        ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars" style="color: #ffd303;"></i>
            </button>

        <?php
        }
        ?>

        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav text-uppercase">
                <?php
                if ($this->session->userdata('opt_status')) {
                ?>
                    <a class="nav-link nav-item text-white me-4 px-md-3" href="<?= base_url('iservices/transactions') ?>">
                        My Transactions
                    </a>
                    <a class="nav-link nav-item text-white me-4 px-md-3" href="<?= base_url('iservices/archived-transactions') ?>">
                        Archived Transactions
                    </a>
                    <a class="nav-link nav-item text-white me-4 px-md-3" href="<?= base_url('iservices/delivered-applications') ?>">
                        Delivered Applications
                    </a>

                    <a class="nav-link nav-item text-white " href="<?= base_url('iservices/login/elogout') ?>">
                        LOGOUT
                    </a>
                <?php
                }
                ?>
            </div>
        </div>

    </div>
</nav>

<!-- <section>
    <nav class="navbar navbar-expand-md navbar">
        <div class="container-fluid header-sec" style="width:1300px !important">

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                   <?php
                    if ($this->session->userdata('opt_status')) {
                    ?>
                    <li class="nav-item hvr-bounce-in hvr-float-shadow mr-md-4 bg">
                        <a class="nav-link text-light text-center" href="<?= base_url('iservices/transactions') ?>"><span>My Transactions</span></a>
                    </li>
                    <li class="nav-item hvr-bounce-in hvr-float-shadow mr-md-4 bg">
                        <a class="nav-link text-light text-center" href="<?= base_url('iservices/status') ?>"><span>Track Application</span></a>
                    </li> 
                    <li class="nav-item hvr-bounce-in hvr-float-shadow mr-md-4 bg">
                        <a class="nav-link text-light text-center" href="<?= base_url('iservices/login/logout') ?>"><span>LOGOUT</span></a>
                    </li>
                    <?php
                    }
                    ?>

                </ul>
            </div>
        </div>
    </nav>
</section> -->