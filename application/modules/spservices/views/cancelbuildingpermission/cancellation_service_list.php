<main class="rtps-container">
    <div class="container my-2">
        <div class="card shadow-sm" style="background:#E6F1FF">
            <div class="card-header" style="background:#589DBF; text-align: center; font-size: 20px; color: #fff; font-family: Roboto,sans-serif; font-weight: bold">
                Building Permission upto Ground+2 storied under Mukhya Mantrir Sohoj Griha Nirman Achoni
            </div>
            <div class="card-body">
                <?php if ($this->session->flashdata('fail') != null) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?= $this->session->flashdata('fail') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php }
                if ($this->session->flashdata('error') != null) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php }
                if ($this->session->flashdata('success') != null) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>

                <div class="row">
                    <div class="col-sm-12 mx-auto">
                        <div class="card my-4">
                            <div class="card-body">
                                <h4>Cancellation cum reapply </h4>
                                <?php if (!empty($applications_db)): ?>

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th width="5%">Sl No.</th>
                                            <th>Application Ref No</th>
                                            <th>Name</th>
                                            <th>Mobile No</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($applications_db as $key => $value): ?>
                                        <tr>
                                            <td>#</td>
                                            <td><?= $value['old_appl_ref_no'] ?></td>
                                            <td><?= $value['applicant_name'] ?></td>
                                            <td><?= $value['applicant_mobile'] ?></td>
                                            <td><a href="<?= base_url('spservices/buildingpermission/registration/cancel_form?appl_ref_no='.$value['old_appl_ref_no']) ?>">Cancel/Re-Apply</a></td>  
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php else: ?>
                                <p>No Application Found<p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                </div><!--End of .card-body -->
                <!--End of .card-footer-->
            </div><!--End of .card-->
        </div><!--End of .container-->
</main>
