<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">


<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
<style>
.parsley-errors-list {
    color: red;
}

.mbtn {
    width: 100% !important;
    margin-bottom: 3px;
}

.blk {
    display: block;
}
</style>
<div class="content-wrapper">
    
    <!-- Content Header (Page header) -->
    <?php if(!empty($this->session->userdata('role'))){ 
        
        // pre($service_list);
        ?>
    <div class="content-header">
      <div class="container-fluid">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= base_url("iservices/admin/dashboard"); ?>">Home</a></li>
          <li class="breadcrumb-item active"><a href="<?= base_url("iservices/admin/my-transactions"); ?>">Pending Applications</a></li>
          <li class="breadcrumb-item active"><a href="<?= base_url("iservices/myapplications/delivered"); ?>">Delivered Applications</a></li>
          <!-- <li class="breadcrumb-item active">My Applications</li> -->
        </ol>
      </div><!-- /.container-fluid -->
    </div>
    <?php } ?>

    <div class="container">
    <?php if(!empty($this->session->userdata('role'))){ ?>
        <div class="row">
        <a href="<?=base_url("iservices/admin/my-transactions")?>" style="background: #ed3b3b;color: white;margin: auto;padding-left: 14px;padding-right: 14px;">Click here for pending Applications</a>
 
        </div>
        <?php } ?>
        <br/>
      <div class="card my-12">
        <div class="card-header">
          <h3>Your Applications</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
                            <div class="form-group">
                                <label for="location_id">Choose a service</label>
                                <select class="select2" name="service" id="service_id"
                                        data-placeholder="Select a service" style="width: 100%;">
                                      <option value="">Choose a service</option>
                                      <?php if($service_list){
                                          foreach($service_list as $item){ ?>
                                                  <option value="<?=$item->service_id?>" data-item='<?=json_encode($item)?>'><?=$item->service_name?></option>
                                          <?php }
                                      }
                                       ?>
                                      
                                     
                                
                                </select>
                            </div>
                        </div>
          </div>


          <!-- table -->
          <table id="applications" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>RTPS Trans No</th>
                            <th>App Ref No</th>
                            <th>Service Name</th>
                            <th>Mobile</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
        </div>
      </div>
    </div>
</div>

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/select2/js/select2.min.js') ?>"></script>
<script>
  $(document).ready(function() {
    var filter_data=null;

  
    var table = $('#applications').DataTable({
            "processing": true,
            language: {
                processing: '<div class="lds-ripple"><div></div><div></div></div>',
            },
            "pagingType": "full_numbers",
            "paging": true,
            "pageLength": 25,
            "serverSide": true,
            "orderMulti": false,
            "order": [[0, "desc"]],
            "columnDefs": [{
                "targets": 0,
                "orderable": true
                },
                {
                    "targets": 1,
                    "orderable": false
                }
            ],
            "columns": [
              {
                "data": "sl_no"
            },
                {
                    "data": "rtps_trans_id"
                },
                {
                    "data": "app_ref_no"
                },
                {
                    "data": "service_name"
                },
               
                {
                    "data": "mobile"
                },
                {
                    "data": "status"
                },
                {
                    "data": "action"
                },
                
            ],
            "ajax": {
                url: "<?php echo site_url("iservices/Myapplications/get_records") ?>",
                type: 'POST',
                // data:{in: filter_data},
                data: function(d) {
                                        d.filter_date = $("#service_id").find(':selected').data("item");
                                      
                                    },
                // dataType: "json",
                beforeSend: function () {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
                },
                complete: function () {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
                }
            },
        });




    $('.select2').select2({
        placeholder: "Choose one",
        allowClear: true
    });

    $("#service_id").change(function(){
      let sevice_id = $(this).find(':selected').val();
      var data =$(this).find(':selected').data("item");
      filter_data=data;
    //   console.log(filter_data);
      // table.draw();
      table.ajax.reload()
      // console.log("filer data",filter_data)
    })
    // console.log("filer data",filter_data)
  });
</script>