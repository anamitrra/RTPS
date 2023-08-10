<style>
    span {
        font-size: 18px;
        font-weight: bold
    }

    .card-footer a {
        font-size: 14px
    }
</style>
<div class="content-wrapper">
    <div class="container-fluid dashboard">
        <div class="row pt-3">
            <div class="col-sm-3">
                <a href="<?= base_url('spservices/office/applications') ?>">
                    <div class="card shadow">
                        <div style="background: linear-gradient(to top, #0ba360 0%, #3cba92 100%);" class="card-body text-center text-white">
                            <span>
                                <h3><?= (isset($counts[0])) ? $counts[0]->total : '0' ?></h3>
                            </span>
                            <p class="font-weight-bold">Received Applications</p>
                        </div>
                        <!-- <div class="card-footer py-1 text-center"><a href="<?= base_url('spservices/office/applications') ?>">More Info <i class="fas fa-arrow-alt-circle-right"></i></a></div> -->
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="<?= base_url('spservices/office/pending-applications') ?>">
                    <div class="card shadow">
                        <div style="background: linear-gradient(to top, #c79081 0%, #dfa579 100%);" class="card-body text-center text-white">
                            <span>
                                <h3><?= (isset($pending[0])) ? $pending[0]->total : '0' ?></h3>
                            </span>
                            <p class="font-weight-bold">Pending Applications</p>
                        </div>
                        <!-- <div class="card-footer py-1 text-center"><a href="<?= base_url('spservices/office/pending-applications') ?>">More Info <i class="fas fa-arrow-alt-circle-right"></i></a></div> -->
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <div class="card shadow">
                    <div style="background: radial-gradient(circle at 12.3% 19.3%, rgb(25, 88, 310) 0%, rgb(15, 209, 349) 100.2%);" class="card-body text-center text-white">
                        <span>
                            <h3><?= (isset($counts[0])) ? $counts[0]->applicant : '0' ?></h3>
                        </span>
                        <p class="font-weight-bold">Pending with Applicant</p>
                    </div>
                    <!-- <div class="card-footer py-1 text-center">
                        <a href="#">More Info <i class="fas fa-arrow-alt-circle-right"></i></a>
                    </div> -->
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card shadow">
                    <div style="background: radial-gradient(circle at 12.3% 19.3%, rgb(85, 88, 218) 0%, rgb(95, 209, 249) 100.2%);" class="card-body text-center text-white">
                        <span>
                            <h3><?= (isset($counts[0])) ? $counts[0]->delivered : '0' ?></h3>
                        </span>
                        <p class="font-weight-bold">Delivered Applications</p>
                    </div>
                    <!-- <div class="card-footer py-1 text-center">
                        <a href="#">More Info <i class="fas fa-arrow-alt-circle-right"></i></a>
                    </div> -->
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card shadow">
                    <div style="background: linear-gradient(to right, rgb(242, 112, 156), rgb(255, 148, 114));" class="card-body text-center text-white">
                        <span>
                            <h3><?= (isset($counts[0])) ? $counts[0]->rejected : '0' ?></h3>
                        </span>
                        <p class="font-weight-bold">Rejected Applications</p>
                    </div>
                    <!-- <div class="card-footer py-1 text-center">
                        <a href="#">More Info <i class="fas fa-arrow-alt-circle-right"></i></a>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>