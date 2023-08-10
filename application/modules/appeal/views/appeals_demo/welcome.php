<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<style>
    .parsley-errors-list{
        color: red;
    }
    .shadow{
        box-shadow: 5px 5px 10px #b4b4b4;
    }
    .icon-box i{
        font-size: 4.5em;
        color: #bbbbbb;
    }
</style>
<div class="container py-5">
    <div class="d-md-flex justify-content-md-between">
        <div class="card p-2 rounded h5 shadow bg-light mx-3" style="height: 100%;">
            <div class="card-body text-center">
                <div class="text-center pb-3 icon-box">
                    <i class="far fa-file appeal-icon-color-blue"></i>
                </div>
                <?php
                    if($this->session->has_userdata('role') && $this->session->userdata('role')->role_name !== 'PFC'){
                ?>
                        <p class="text-center mb-4 h5">For new appeal you will have to provide your <b>Application Reference Number and Registered Contact Number.</b></p>
                <?php
                    }else{
                ?>
                        <p class="mb-4 h5 text-justify">For new appeal you will have to provide <b>Application Reference Number.</b></p>
                <?php
                    }
                ?>
                <a href="<?=base_url('appeal/login')?>" class="text-decoration-none">
                    <button class="btn btn-block btn-outline-primary h4 mb-0">Apply for New Appeal</button>
                </a>

            </div>
        </div>
        <div class="card p-2 rounded h5 shadow bg-light mx-3" style="height: 100%;">
            <div class="card-body text-center">
                <div class="text-center pb-3  icon-box">
                    <i class="far fa-copy appeal-icon-color-blue"></i>
                </div>

                <?php
                if($this->session->has_userdata('role') && $this->session->userdata('role')->role_name !== 'PFC'){
                ?>
                <p class="text-center mb-4 h5">For second appeal you will have to provide your previous <b>Appeal Reference Number and Registered Contact Number.</b></p>
                <?php
                }else{
                ?>
                    <p class="text-justify mb-4 h5">For second appeal you will have to provide previous <b>Appeal Reference Number.</b></p>
                <?php
                }
                ?>
                <a href="<?=base_url('appeal/second')?>" class="text-decoration-none">
                    <button class="btn btn-block btn btn-outline-primary h4 mb-0">Apply for Second Appeal</button>
                </a>
            </div>
        </div>
        <div class="card p-2 rounded h5 shadow bg-light mx-3" style="height: 100%;">
            <div class="card-body text-center">
                <div class="text-center pb-3 icon-box">
                    <i class="fas fa-file-contract appeal-icon-color-blue"></i>
                </div>
                <?php
                if($this->session->has_userdata('role') && $this->session->userdata('role')->role_name !== 'PFC'){
                ?>
                <p class="text-center mb-4 h5">For tracking appeal you will have to provide your <b>Appeal Reference Number and Registered Contact Number.</b></p>
                <?php
                }else{
                ?>
                    <p class="text-justify mb-4 h5">For tracking appeal you will have to provide <b>Appeal Reference Number.</b></p>
                <?php
                }
                ?>
                <a href="<?=base_url('appeal/login')?>" class="text-decoration-none">
                    <button class="btn btn-block btn btn-outline-primary h4 mb-0">Track Appeal</button>
                </a>
            </div>
        </div>
    </div>
</div>