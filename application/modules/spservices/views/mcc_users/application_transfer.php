<style>
  body {
    overflow-x: hidden;
  }
</style>
<?php if (!empty($office_users)) {
  $office_users = $office_users;
}

if ($role == 'DA') {
  $dist_name = $this->session->userdata('admin')['district'];
  $districts = $this->districts_model->get_rows(array("state_id" => 1, "district_name" => $dist_name));
} else {
  $districts = $this->districts_model->get_rows(array("state_id" => 1));
}
$pa_district_name = set_value("pa_district_name");
$pa_circle = set_value("pa_circle");
?>

<script type="text/javascript">
  $(document).ready(function() {
    $(document).on("change", "#pa_district_id", function() {
      let district_id = $(this).val();
      $("#pa_district_name").val($(this).find("option:selected").text());
      $.ajax({
        type: "POST",
        url: "<?= base_url('spservices/mcc_users/admindashboard/get_circles') ?>",
        data: {
          "input_name": "pa_circle",
          "fld_name": "district_id",
          "fld_value": district_id
        },
        beforeSend: function() {
          $("#pa_circles_div").html("Loading");
        },
        success: function(res) {
          $("#pa_circles_div").html(res);
        }
      });
    });

    // Getting office user list based on dist id and role name

    $('#pa_district_id,#user_role').change(function() {
      var district_id = $('#pa_district_id').val();
      var user_role = $('#user_role').val();
      $.ajax({
        type: "POST",
        url: "<?= base_url('spservices/mcc_users/admindashboard/office_user_list') ?>",
        data: {
          "user_role": user_role,
          "dist_id": district_id
        },
        success: function(data) {
          $('#office_user').html(data);
        }
      })
    })

    $("#office_user").each(function(index) {
      // console.log( index + ": " + $( this ).text() );
    });

    // Getting office user list based on dist id, role name and circle name
    $(document).on("change", "#pa_circle", function() {
      let pa_circle = $(this).val();
      var district_id = $('#pa_district_id').val();
      var user_role = $('#user_role').val();
      $.ajax({
        type: "POST",
        url: "<?= base_url('spservices/mcc_users/admindashboard/office_user_list_by_circle') ?>",
        data: {
          "user_role": user_role,
          "dist_id": district_id,
          "circle_name": pa_circle
        },
        success: function(data) {
          $('#office_user').html(data);
        }
      })
    });

    // Active office user
    // Getting office user list based on dist id and role name
    $('#pa_district_id,#user_role').change(function() {
      var district_id = $('#pa_district_id').val();
      var user_role = $('#user_role').val();
      $.ajax({
        type: "POST",
        url: "<?= base_url('spservices/mcc_users/admindashboard/active_office_user_list') ?>",
        data: {
          "user_role": user_role,
          "dist_id": district_id
        },
        success: function(data) {
          $('#active_office_user').html(data);
        }
      })
    })

    // Getting office user list based on dist id, role name and circle name
    $(document).on("change", "#pa_circle", function() {
      let pa_circle = $(this).val();
      var district_id = $('#pa_district_id').val();
      var user_role = $('#user_role').val();
      $.ajax({
        type: "POST",
        url: "<?= base_url('spservices/mcc_users/admindashboard/active_office_user_list_by_circle') ?>",
        data: {
          "user_role": user_role,
          "dist_id": district_id,
          "circle_name": pa_circle
        },
        success: function(data) {
          $('#active_office_user').html(data);
        }
      })
    });
  });
</script>

<!-- Transfer From -->
<div class="content-wrapper">
  <main class="rtps-container">
    <div class="row">
      <div class="col">
        <div class="container my-3">
          <div class="card">
            <div class="card-body">
              <form id="myfrm" method="POST" action="<?= base_url('spservices/mcc_users/admindashboard/transfer') ?>" enctype="multipart/form-data">
                <input id="pa_district_name" name="pa_district_name" value="" type="hidden" />
                <?php if ($this->session->flashdata('warning') != null) { ?>
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> <?= $this->session->flashdata('warning') ?>
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
                <?php } //End of if 
                ?>
                <fieldset class="" style="">
                  <h3 class="text-center">Application Transfer</h3>
                  <hr>
                  <div class="row">
                    <div class="col-12 form-group">
                      <label>District<span class="text-danger">*</span> </label>
                      <select name="pa_district_id" id="pa_district_id" class="form-control">
                        <option>Please Select</option>
                        <?php if ($districts) {
                          foreach ($districts as $dist) {
                            $selectedDist = ($pa_district_name === $dist->district_name) ? 'selected' : '';
                            echo '<option value="' . $dist->district_id . '">' . $dist->district_name . '</option>';
                          } //End of foreach()
                        } //End of if 
                        ?>
                      </select>
                      <!-- <?= form_error("pa_district_name") ?> -->
                    </div>
                    <div class="col-12 form-group" id="" style="">
                      <label id="">Circle</label>
                      <div id="pa_circles_div">
                        <select name="pa_circle" id="pa_circle" class="form-control">
                          <option value="">Select</option>
                        </select>
                      </div>
                      <!-- <?= form_error("pa_circle") ?> -->
                    </div>

                    <div class="col-12 form-group">
                      <label>User Role<span class="text-danger">*</span> </label>
                      <select name="user_role" id="user_role" class="form-control">
                        <option>Please Select</option>
                        <?php if (!empty($user_roles)) { ?>
                          <?php foreach ($user_roles as $role) { ?>
                            <option value="<?php echo (isset($role->role_name)) ? $role->role_name : '' ?>"><?php echo (isset($role->role_name)) ?  $role->role_name : "" ?></option>
                          <?php }  ?>
                        <?php } else { ?>
                          <p>Records not found!</p>
                        <?php } ?>
                      </select>
                      <!-- <?= form_error("user_role") ?> -->
                    </div>
                    <!-- Select office user Trasnfer from -->
                    <div class="col-12 form-group" id="myDiv" style="">
                      <label>Transfer From<span class="text-danger">*</span><span class="text-danger"></span> </label>
                      <div id="">
                        <select name="office_user" id="office_user" class="form-control" required>
                          <option value="">All Office User</option>
                        </select>
                      </div>
                      <?= form_error("office_user") ?>
                    </div>
                    <!-- Select office user Transfer to -->
                    <div class="col-12 form-group" id="myDiv" style="">
                      <label>Transfer To<span class="text-danger">*</span><span class="text-danger"></span> </label>
                      <div id="">
                        <select name="active_office_user" id="active_office_user" class="form-control" required>
                          <option value="">Active Office User</option>
                        </select>
                      </div>
                      <?= form_error("active_office_user") ?>
                    </div>
                  </div> <!-- Row end -->
                  <div class="col-12 pl-0"><button id="displaybtn" type="submit" class="btn btn-primary btn-sm">Transfer</button></div>
            </div>
            </fieldset>
            </form>
          </div>
        </div>
      </div><!--End of .container-->
    </div>
</div>
</main>
</div>