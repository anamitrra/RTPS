<div class="d-flex justify-content-center">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body login-card-body">
                    <h4>Update Appeal by Appeal ID</h4>
                    <form method="POST" action="<?=base_url('appeal/test_app/process_update_appeal')?>">
                        <div class="form-group">
                            <label for="appeal_id">Appeal ID</label>
                            <input type="text" class="form-control" name="appeal_id" placeholder="Enter Appeal ID eg. EF98FD" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email ID</label>
                            <input type="text" name="email" class="form-control" placeholder="Enter email ID" required>
                        </div>
                        <div class="form-group">
                            <label for="mobile_number">Mobile Number</label>
                            <input type="text" name="mobile_number" class="form-control" placeholder="Enter Mobile Number" required>
                        </div>
                        <div class="form-group">
                            <label for="date_of_appeal">Submission Date</label>
                            <input type="date" name="date_of_appeal" class="form-control" placeholder="Date of appeal">
                        </div>
                        <button type="submit" class="btn btn-outline-success">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
