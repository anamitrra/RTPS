<div class="d-flex justify-content-center">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body login-card-body">
                    <h4>Change Mobile number by Application Number</h4>
                    <form method="POST" action="<?=base_url('appeal/test_app/process_change_mobile')?>">
                        <div class="form-group">
                            <label for="">Application Number</label>
                            <input type="text" class="form-control" name="application_number" placeholder="Enter Application Number" required>
                        </div>
                        <div class="form-group">
                            <label for="">Mobile Number</label>
                            <input type="text" name="mobile_number" class="form-control" placeholder="Enter Mobile Number" required>
                        </div>
                        <button type="submit" class="btn btn-outline-success">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>