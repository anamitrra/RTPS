<div class="container my-2">
    <form method="POST" action="<?=base_url('appeal/test_app/set_mobile')?>">
        <div class="row form-group">
            <div class="col-12">
                <label for="mobile_number">Mobile Number</label>
                <input type="text" name="mobile_number" id="mobile_number" placeholder="Enter mobile number here" required>
            </div>
        </div>
        <button type="submit" class="btn btn-outline-success">Submit</button>
    </form>
</div>