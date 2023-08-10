<style>
    html {
        font-size: 14px;
    }

    .bg {
        background: #1e7bb6;
        border-radius: 4px;

    }

    fieldset.border {
        padding: 0.7em;
        margin-block: 0.5em !important;
    }

    main.rtps-container {
        min-height: 65vh;
    }

    .form-control:disabled,
    .form-control[readonly] {
        background-color: #e9ecef;
        opacity: 1;
    }

    .form-label {
        margin-block: .5rem;
        font-weight: bold;
    }
</style>

<nav class="navbar navbar-expand-md container-fluid py-0">
    <div class="container">

        <a class="navbar-brand p-2" href="<?= base_url('iservices/transactions') ?>">
            <img src="<?= base_url('assets/site/theme1/images/home.png') ?>" alt="Home" width="16">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-bars" style="color: #ffd303;"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav text-uppercase">
                <?php if ($this->session->role) { ?>
                    <a class="nav-link text-white me-4 px-md-3" href="<?= base_url('iservices/admin/my-transactions') ?>">
                        My applications
                    </a>
                <?php } elseif ($this->session->opt_status) { ?>
                    <a class="nav-link text-white me-4 px-md-3" href="<?= base_url('iservices/transactions') ?>">
                        My applications
                    </a>
                <?php } //End of if else 
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