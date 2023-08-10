<div class="d-flex justify-content-center">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body login-card-body">
                    <h4>Update application by Application Reference Number</h4>
                    <form method="POST" action="<?=base_url('appeal/test_app/process_change_mobile_email')?>">
                        <div class="form-group">
                            <label for="">Application Number</label>
                            <input type="text" class="form-control" name="application_number" placeholder="Enter Application Number eg. RTPS-CRCPY/2020/00001" required>
                        </div>
                        <div class="form-group">
                            <label for="">Email ID</label>
                            <input type="text" name="email" class="form-control" placeholder="Enter email ID" required>
                        </div>
                        <div class="form-group">
                            <label for="">Mobile Number</label>
                            <input type="text" name="mobile_number" class="form-control" placeholder="Enter Mobile Number" required>
                        </div>
                        <div class="form-group">
                            <label for="">Submission Date</label>
                            <input type="date" name="submission_date" class="form-control" placeholder="Application submission date">
                        </div>
                        <button type="submit" class="btn btn-outline-success">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
