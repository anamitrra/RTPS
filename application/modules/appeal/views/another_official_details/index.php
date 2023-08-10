<?php
    $this->lang->load('appeal');
    // print_r ($official_details);




    
?>
<style>
    .select2-drop-active{
    margin-top: -25px;
}

</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<div class="content-wrapper" style="min-height: 1376.4px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Mapping service with authority</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Official Details</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List</h3>
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm float-right" id="add_official_button"><i
                                class="fas fa-plus" aria-hidden="true"></i>&nbsp;
                        Add Mapping Draft</a>
                </div>


                <!-- /.card-header -->
                <div class="card-body">
                <?php if ($this->session->flashdata('draft_submit') != null) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> <?= $this->session->flashdata('draft_submit') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('dist_adimin_creation_error') != null) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Failed!</strong> <?= $this->session->flashdata('dist_adimin_creation_error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('dist_adimin_creation_success') != null) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> <?= $this->session->flashdata('dist_adimin_creation_success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                   
                    <?php }//End of if ?>
                    <span id="process_body_s"></span>
           
                    <!-- <form  method="POST" action="<?php echo base_url('appeal/another_official_details/final_submit') ?>" enctype="multipart/form-data">
<button class="btn btn-success" id="" type="submit">
                       
                       SUBMIT
                   </button>

</form> -->



<div class="row">
    <div class="col-1 border"><dt>Service Name</dt></div>
    <div class="col-1 border"><dt>Location</dt></div>
    <div class="col-2 border"><dt>DA (Appellate Authority)</dt></div>
    <div class="col-2 border"><dt>Appellate Authority</dt></div>
    <div class="col-1 border"><dt>DPS Name</dt></div>
    <div class="col-1 border"><dt>DA (Tribunal)</dt></div>
    <div class="col-1 border"><dt>Registrar</dt></div>
    <div class="col-1 border"><dt>Chairman</dt></div>
    <div class="col-1 border"><dt>Action</dt></div>
</div>
<hr>
<?php foreach ($official_details as $official_detail) { ?>
   <!-- <?php print_r ($official_detail['is_draft']); ?> -->

   <?php if($official_detail['is_draft']==1){ ?>
    <form class=""  method="POST" action="<?php echo base_url('appeal/another_official_details/final_submit') ?>" enctype="multipart/form-data">

<div class="row pt-1">
<input type="hidden"  id="draft_id" name="draft_id" value="<?php echo $official_detail['ids'] ?>">

   <div class="col-1 border-right border-bottom"> <?= ( $official_detail['department_names']['service_details']['service_name']) ?  $official_detail['department_names']['service_details']['service_name'] : '' ?>
   <input type="hidden"  id="service_idd" name="service_idd" value="<?php echo $official_detail['department_names']['service_details']['_id']['$oid'] ?>"></div>

   <div class="col-1  border-right border-bottom">    <?= ( $official_detail['location'][0]['location_name']) ?  $official_detail['location'][0]['location_name'] : '' ?>
   <input type="hidden"  id="location_idd" name="location_idd" value="<?php echo $official_detail['location'][0]['_id']['$id'] ?>"></div>


   <div class="col-2  border-right border-bottom"><select required class="form-control select2" name="da_app_auth" id="" data-placeholder="Select" style="width: 100%;" >
                                   <?php
                                   if (!empty($roleWiseUserList)) {
                                       ?>
                                       <option value=""> Choose One </option>
                                       <?php
                                       foreach ($roleWiseUserList as $userRole) {
                                           if ($userRole->slug === 'DA') {
                                               foreach ($userRole->users as $dps) {
                                                   if(!property_exists($dps,'da_type') || $dps->da_type !== 'Tribunal'){
                                                       ?>
                                                       <option value="<?= $dps->{'_id'} ?>"> <?= $dps->name ?> 
                                                   </option>                                                      
                                                       <?php
                                                   }
                                                   
                                                               
                                               }
                                           }
                                       }
                                       
                                   } ?>

                               </select></div>
   <div class="col-2 border-right border-bottom"><select required class="form-control select2" name="app_auth" id=""
                                       data-placeholder="Select" style="" >
                                   <?php
                                   if (!empty($roleWiseUserList)) {
                                       ?>
                                       <option value=""> Choose One </option>
                                       <?php
                                       foreach ($roleWiseUserList as $userRole) {
                                           if ($userRole->slug === 'AA') {
                                               foreach ($userRole->users as $aa) {
                                                   ?>
                                                <option value="<?= $aa->{'_id'} ?>"> <?= $aa->name ?> </option>
                                                   <?php
                                               }
                                           }
                                       }
                                   } ?>
</select></div>
   <div class="col-1 border-right border-bottom"><select required class="form-control select2" name="dps_id" id="" data-placeholder="Select"
                                       style="" >
                                   <?php
                                   if (!empty($roleWiseUserList)) {
                                       ?>
                                       <option value=""> Choose One </option>
                                       <?php
                                       foreach ($roleWiseUserList as $userRole) {
                                           if ($userRole->slug === 'DPS') {
                                               foreach ($userRole->users as $dps) {
                                                   ?>
                                                   <option value="<?= $dps->{'_id'} ?>"> <?= $dps->name ?> </option>
                                                   <?php
                                               }
                                           }
                                       }
                                   } ?>
                               </select></div>
   <div class="col-1 border-right border-bottom"><select required class="form-control select2" name="da_tribunal" id="" data-placeholder="Select" style="" >
                                   <?php
                                   if (!empty($roleWiseUserList)) {
                                       ?>
                                       <option value=""> Choose One </option>
                                       <?php
                                       foreach ($roleWiseUserList as $userRole) {
                                           if ($userRole->slug === 'DA') {
                                               foreach ($userRole->users as $dps) {
                                                   if(isset($dps->da_type) && $dps->da_type === 'Tribunal'){
                                                       ?>
                                                       <option value="<?= $dps->{'_id'} ?>"> <?= $dps->name ?> </option>
                                                       <?php
                                                   }
                                                   
                                               }
                                           }
                                       }
                                   } ?>
</select></div>
   <div class="col-1 border-right border-bottom"><?= "Registrar One"?>
   <input type="hidden" id="register_id" name="register_id" value="Registrar One">
</div>
   <div class="col-1 border-right border-bottom"><?= "Chairman 1"?><input type="hidden" id="chairman_id" name="chairman_id" value="Chairman 1">
</div>
   <div class="col-1  border-bottom"><button class="btn btn-success final-submit" id="" type="submit">SUBMIT</button></div>
</div>



</form>
   <?php } ?>
   

<!-- <hr> -->
    
<?php } ?>


   
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var st = $('#traveling_as').val();
        var table = $('#apeals').DataTable({
            "processing": true,
            language: {
                processing: '<div class="lds-ripple"><div></div><div></div></div>',
            },
            "pagingType": "full_numbers",
            "pageLength": 25,
            "serverSide": true,
            "orderMulti": false,
            "order": [[0, "desc"]],
            "columnDefs": [{
                "width": "15%",
                "targets": 0,
                "orderable": true
                },
                {
                    "targets": 1,
                    "orderable": false
                },
                {
                    "targets": 2,
                    "orderable": false
                },
                {
                    "targets": 3,
                    "orderable": false
                },
                {
                    "targets": 4,
                    "orderable": false
                },
                {
                    "targets": 5,
                    "orderable": false
                },
                {
                    "targets": 6,
                    "orderable": false
                },
                {
                    "targets": 7,
                    "orderable": false
                },
                {
                    "targets": 8,
                    "orderable": false
                }
            ],
            "columns": [{
                "data": "sl_no"
            },
                {
                    "data": "service_name"
                },
                {
                    "data": "location_id"
                },
                {
                    "data": "da_array"
                },
                {
                    "data": "appellate_auth"
                },
                {
                    "data": "dps_name"
                },
                {
                    "data": "da_tribunal_array"
                },
                {
                    "data": "registrar"
                },
                {
                    "data": "reviewing_auth"
                },
                
            ],
            "ajax": {
                url: "<?php echo site_url("appeal/another_official_details/get_records") ?>",
                type: 'POST',
                beforeSend: function () {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
                },
                complete: function () {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
                }
            },
        });

        // Get Application's details
        $(document).on("click", "#add_official_button", function () {
            $("#add_official").modal("show");
        });
        $(document).on("click", "#add_official_submit", function () {
            console.log("modal");
            $('#processing').fadeIn("slow");
            //$("#modal-title").empty().append("Please Wait....<div class='lds-ripple'><div></div><div></div></div>");
            $('#process_body').empty().append('<div class="alert alert-warning alert-dismissible fade show" role="alert">\
            <strong>Info!</strong> Processing....\
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">\
                <span aria-hidden="true">&times;</span>\
                </button>\
            </div>');
            var data = $('#add_official_form').serializeArray();
            $.ajax({
                url: "<?php echo site_url("appeal/another_official_details/create") ?>",
                type: 'POST',
                data: data,
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        $('#process_body').empty().append('<div class="alert alert-success alert-dismissible fade show" role="alert">\
            <strong>Success!</strong> Data Successfully submited.\
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">\
                <span aria-hidden="true">&times;</span>\
                </button>\
            </div>');
                        table.draw();
                        $("#add_official_form").trigger("reset");
                       location.reload();
                    } else {
                        $('#process_body').empty().append('<div class="alert alert-danger alert-dismissible fade show" role="alert">\
            <strong>Info!</strong>' + data.error_msg + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">\
                <span aria-hidden="true">&times;</span>\
                </button>\
            </div>');
                    }

                }
            });

        });

    });
</script>
</div>
</div>
</div>
</div>
</section>
</div>
<div class="modal fade" id="add_official">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">Mapping service with location</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="view-modal-body">
                <span id="process_body"></span>
                <form id="add_official_form" method="POST" action="<?= base_url('appeal/process-login') ?>">
                    <div class="row">
                        <div class="col-md-6">
                                <div class="form-group">
                                        <label for="department_name">Parent Organization</label>
                                        <select name="parent" id="parent" class="form-control" required>
                                            <option value="">-Select-</option>
                                            <option value="GOA">GOA(Govt Of Assam)</option>
                                            <option value="KAAC">KAAC</option>
                                            <option value="BTC">BTC</option>
                                            <option value="NCHAC">NCHAC</option>
                                        </select>
                                    
                                        <div class="text-danger error"></div>
                                    </div>

                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dept_id">Department</label>
                                <select class="select2" name="dept_id" id="dept_id"
                                        data-placeholder="Select a Department" style="width: 100%;">
                                  
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="service_id">Service</label>
                                <select class="select2" multiple name="service_id[]" id="service_id"
                                        data-placeholder="Select a Service" style="width: 100%;" disabled>
                                    <option value="">No data available</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location_id">Location ID</label>
                                <select class="select2" name="location_id" id="location_id"
                                        data-placeholder="Select a Location" style="width: 100%;">
                                
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- <legend class="h5">First Appeal</legend> -->
                    
<!--                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dealingAssistant">Dealing Assistant (Appellate)</label>
                                <select class="select2" name="dealing_assistant[]" id="dealingAssistant" data-placeholder="Select a Dealing Assistant" style="width: 100%;" multiple>
                                    <?php
                                    if (!empty($roleWiseUserList)) {
                                        ?>
                                        <option value=""> Choose One </option>
                                        <?php
                                        foreach ($roleWiseUserList as $userRole) {
                                            if ($userRole->slug === 'DA') {
                                                foreach ($userRole->users as $dps) {
                                                    if(!property_exists($dps,'da_type') || $dps->da_type !== 'Tribunal'){
                                                        ?>
                                                        <option value="<?= $dps->{'_id'} ?>"> <?= $dps->name ?> </option>
                                                        <?php
                                                    }
                                                   
                                                }
                                            }
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="appellate_id">Appellate Authority</label>
                                <select class="select2" name="appellate_id" id="appellate_id"
                                        data-placeholder="Select an Appellate Authority" style="width: 100%;">
                                    <?php
                                    if (!empty($roleWiseUserList)) {
                                        ?>
                                        <option value=""> Choose One </option>
                                        <?php
                                        foreach ($roleWiseUserList as $userRole) {
                                            if ($userRole->slug === 'AA') {
                                                foreach ($userRole->users as $aa) {
                                                    ?>
                                                    <option value="<?= $aa->{'_id'} ?>"> <?= $aa->name ?> </option>
                                                    <?php
                                                }
                                            }
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        

                    </div> -->
                    <!-- <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dps_id">DPS</label>
                                <select class="select2" name="dps_id" id="dps_id" data-placeholder="Select a DPS"
                                        style="width: 100%;">
                                    <?php
                                    if (!empty($roleWiseUserList)) {
                                        ?>
                                        <option value=""> Choose One </option>
                                        <?php
                                        foreach ($roleWiseUserList as $userRole) {
                                            if ($userRole->slug === 'DPS') {
                                                foreach ($userRole->users as $dps) {
                                                    ?>
                                                    <option value="<?= $dps->{'_id'} ?>"> <?= $dps->name ?> </option>
                                                    <?php
                                                }
                                            }
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        
                    </div> -->
                   
                    <!-- <legend class="h5">Second Appeal</legend> -->
                    
                    
                </form>
            </div>
            <div class="modal-footer ">
                <a href="javascript:void(0)" class="btn btn-default float-right" data-dismiss="modal">Close</a>
                <a href="javascript:void(0)" id="add_official_submit" class="btn btn-primary float-right">Submit</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Multi-select plugin -->

<!-- Select2 -->
<script src="<?= base_url('assets/plugins/select2/js/select2.min.js') ?>"></script>
<script>
    $(function () {
        var processBodyRef = $('#process_body');
        var serviceSelectRef = $('#service_id');
        var locationSelectRef = $('#location_id');
        $('.select2').select2({
            placeholder: "Choose one",
            //allowClear: true
        });
        $('#dept_id').change(function () {
            let dept_id = $(this).find(':selected').val();
            let fetchServiceListUrl = '<?=base_url('appeal/official-details/get-service-list/:dept_id')?>';
            $('#process_body').empty().append('' +
                '<div class="alert alert-info alert-dismissible fade show" role="alert">\n' +
                '      <strong>Info!</strong> Processing....\n' +
                '       <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                '     <span aria-hidden="true">&times;</span>\n' +
                '     </button>\n' +
                '</div>');
            $.ajax({
                url: fetchServiceListUrl.replace(':dept_id', dept_id),
                type: 'POST',
                dataType: 'json',
                data: {dept_id},
                success: function (response) {
                    if (response.success) {
                        let serviceListToConvert = response.serviceList;
                        let serviceList = Object.keys(serviceListToConvert).map(key => {
                            return serviceListToConvert[key];
                        });
                        processBodyRef.empty();
                        serviceSelectRef.select2('destroy').empty().select2({
                            data: serviceList
                        });
                        // serviceSelectRef.trigger('refresh');
                        serviceSelectRef.prop('disabled', false);

                        //for location 
                        let locationList = response.locationList;
                        if(locationList){
                            locationSelectRef.html('');
                            locationSelectRef.append($("<option></option>")
                                                .attr("value", "")
                                                .text("Select a Location")); 
                            $.each(locationList, function(key, value) {
                                locationSelectRef.append($("<option></option>")
                                                .attr("value", value._id.$id)
                                                .text(value.location_name)); 
                            });

                        }else{
                            locationSelectRef.html('');
                        }



                    } else {
                        serviceSelectRef.prop('disabled', true);
                        processBodyRef.empty().append('' +
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert">\n' +
                            '      <strong>Failed!</strong> Unable to load service list \n' +
                            '       <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                            '     <span aria-hidden="true">&times;</span>\n' +
                            '     </button>\n' +
                            '</div>');
                    }
                },
                error: function () {
                    serviceSelectRef.prop('disabled', true);
                    processBodyRef.empty().append('' +
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert">\n' +
                        '      <strong>Failed!</strong> Unable to load service list \n' +
                        '       <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                        '     <span aria-hidden="true">&times;</span>\n' +
                        '     </button>\n' +
                        '</div>');
                }
            });
        });

        $(document).on('change',"#parent",function(){
            $('#service_id').empty();
            let p=$(this).val();
            swal.fire({
                title: 'Please wait.',
                allowEscapeKey: false,
                showCloseButton: true,
                allowOutsideClick: () => !Swal.isLoading(),
                onOpen: () => {
                    Swal.showLoading();
                    $.post('<?= base_url('appeal/departments/get_department_by_parent') ?>', {
                        'parent': p
                    }, function(jsn) {
                        if (jsn.status == true) {
                          //  console.log(jsn.data)
                          $("#dept_id").html('');
                          $('#dept_id')
                                    .append($("<option></option>")
                                                .attr("value", "")
                                                .text("Select a Department")); 
                            $.each(jsn.data, function(key, value) {
                                $('#dept_id')
                                    .append($("<option></option>")
                                                .attr("value", value._id.$id)
                                                .text(value.department_name)); 
                            });

                            Swal.close();
                            
                        } else {
                           // console.log(data);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: jsn.data ? jsn.data : 'Something went wrong! Please try again',
                            })
                        }
                    })
                }
            });
        });

    });
</script>

<!-- My Sc -->
<script>
    function myFunction() {
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("myInput");
      filter = input.value.toUpperCase();
      table = document.getElementById("myTable");
      tr = table.getElementsByTagName("tr");
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
          txtValue = td.textContent || td.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        }
      }
    }
  </script>



<script>
    $(document).ready(function () {
    $('#example').DataTable();
});
</script>

<script>
    $(document).ready(function () {
                    //     $('.final-submit').click(e){
                    //         e.preventDefault();

                    //         swal({
                    // title: "Are you sure?",
                    // text: "Once deleted, you will not be able to recover this imaginary file!",
                    // icon: "warning",
                    // buttons: true,
                    // dangerMode: true,
                    // })
                    // .then((willDelete) => {
                    // if (willDelete) {
                    //     swal("Poof! Your imaginary file has been deleted!", {
                    //     icon: "success",
                    //     });
                    // } else {
                    //     swal("Your imaginary file is safe!");
                    // }
                    // });
                    //     };

                    // $(document).on('click','.final-submit',function(){
                    //     Swal.fire({
                    //     title: 'Are you sure?',
                    //     text: "cjhdghgsd",
                    //     icon: 'warning',
                    //     showCancelButton: true,
                    //     confirmButtonColor: '#3085d6',
                    //     cancelButtonColor: '#d33',
                    //     confirmButtonText: 'Yes'
                    // }).then((result) => {
                    //    if(result.value){
                    //     $(".mapping_form").submit();
                    //    }
                    // });
                    // })

                  
});

</script>


