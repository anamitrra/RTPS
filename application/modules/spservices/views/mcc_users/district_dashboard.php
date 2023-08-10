<style>
    body {
        overflow-x: hidden;
    }

    .card-body {
        padding: 30px 0;
        font-weight: bold
    }

    .card-body .count_data {
        font-size: 30px;
    }
</style>
<div class="content-wrapper">
    <main class="rtps-container">
        <div class="container pt-4">
            <div class="row ">
                <div class="col-md-4">
                    <div class="card text-center bg-primary">
                        <div class="card-body">
                            <h5>Application Received</h5>
                            <span class="count_data"><?= $counts[0]->total ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center bg-info">
                        <div class="card-body">
                            <h5>Under Process</h5>
                            <span class="count_data"><?= $counts[0]->under_process ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center bg-warning">
                        <div class="card-body">
                            <h5>Pending (Applicant's side)</h5>
                            <span class="count_data"><?= $counts[0]->applicant ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center bg-success">
                        <div class="card-body">
                            <h5>Delivered</h5>
                            <span class="count_data"><?= $counts[0]->delivered ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center bg-danger">
                        <div class="card-body">
                            <h5>Rejected</h5>
                            <span class="count_data"><?= $counts[0]->rejected ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var verify = '<?= $mobile_verify ?>';
        if (verify==0) {
            $("#myModal").modal({
                backdrop: 'static',
                keyboard: false
            });
        }
    });
</script>
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info py-2">
                <h4 class="modal-title">Mobile Number Verification</h4>
            </div>
            <div class="modal-body">
                Please Verify your Mobile Number. <a href="<?= base_url('spservices/mcc/verify-mobile') ?>">Click to verify</a>
            </div>
        </div>
    </div>
</div>