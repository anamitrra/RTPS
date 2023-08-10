<style>
    .info-box .info-box-text, .info-box .progress-description {
        text-overflow: unset!important;
        white-space: unset!important;
    }
</style>
<!-- Main content -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">

                    

            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">SECOND APPEAL REPORTS<?php //$this->session->userdata("department_name")
                                                                ?></h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("mis"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div><!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Info boxes -->
                    
                     <div class="row">
                        <div class="col-sm-4">
                            <select name="departments" id="departments" class="form-control">
                                <option value="">Select Departments</option>
                                <?php
                                if (!empty($departments)) {
                                    foreach ($departments as $dep) { ?>
                                        <option value="<?= $dep->department_id ?>"><?= $dep->department_name ?></option>
                                <?php }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="services" id="services" class="form-control">
                                <option>Select Services</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="districts" id="districts" class="form-control">
                                <option>Select Districts</option>
                                <?php
                                if (!empty($districts)) {
                                    foreach ($districts as $dis) { ?>
                                        <option value="<?= $dis->distname ?>"><?= $dis->distname ?></option>
                                <?php }
                                }
                                ?>
                                
                            </select>
                        </div>
                       
                    </div>
                    <br/>
                    <div class="card">
                    <div class="card-header">
                        <div class="row">
                        <div class="col-sm-8">
                        <h6> <span id="service_name"></span></h6>
                        </div>
                            
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                    <thead>
                        <tr>
                        <th scope="col">Total First Appeal</th>
                        <th scope="col">Total First Appeal Disposed</th>
                        <th scope="col">Total First Appeal Pending</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td><span id="total_appeal_count"></span></td>
                        <td>

                            <table class="table">
                                <thead>
                                    <tr>
                                    <th scope="col">Within Timeline</th>
                                    <th scope="col">After Timeline</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    <td>
                                        <div>
                                        
                                        <span id="delivered_within_timeline"></span>
                                            <br/>
                                    
                                        <span id="rejected_within_timeline"></span>
                                        </div>
                                        
                                    </td>
                                    <td>
                                        <div>
                                    
                                        <span id="delivered_after_timeline"></span>
                                        <br/>
                                        
                                        <span id="rejected_after_timeline"></span>
                                        </div>
                                
                                    </td>
                                    </tr>
                                    
                            </tbody>
                            </table>
                        </td>
                        <td>    
                            <div>
                        
                                        <span id="pending_within_timeline"></span>
                                            <br/>
                                
                                        <span id="pending_after_timeline"></span>
                            </div>
                                    

                        </td>
                        </tr>
                        
                            </tbody>
                        </table>
                    </div>
                    </div>
        </div>
            
        <!--/. container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<script src="<?= base_url("assets/"); ?>plugins/chart.js/Chart.min.js"></script>
<script>
    $(function() {
        'use strict'
      
        $(document).ready(function() {
          


            $("#departments").on('change', function() {
            let deparment_id = $("#departments").val();
            let url = '<?= base_url("mis/get_services/") ?>' + deparment_id;
            $.get(url, function(data, status) {
                let res = JSON.parse(data)
                if (res) {
                    $('#services').empty();
                    $('#services').append(`<option value=""> Select Services</option>`);
                    res.forEach(function(item, index) {
                        $('#services').append(`<option value="${item.service_id}">
                                   ${item.service_name}
                                 </option>`);
                    });
                }
            })
        })

        $("#services").on('change', function() {
            let district = $("#districts").val();
            let service_id = $(this).val();
      
            $.ajax({
                    url: '<?= base_url("mis/appeal/find-second-appeal-count-by-district") ?>',
                    type: 'POST',
                    data: {
                        service_id:service_id,
                        district:district
                    },
                    beforeSend: function(){
                        swal.fire({
                            html: '<h5>Processing...</h5>',
                            showConfirmButton: false,
                            allowOutsideClick: () => !Swal.isLoading(),
                            onOpen: function() {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                       var res=JSON.parse(response);
                       $('#service_name').text("Result Showing of "+res.service_name	);
                       $("#total_appeal_count").text(res.total_appeal)
                       $("#delivered_within_timeline").text("Service Delivered : "+res.delivered_within_timeline)
                       $("#rejected_within_timeline").text("Service Rejected : "+res.rejected_within_timeline)
                       $("#delivered_after_timeline").text("Service Delivered : "+res.delivered_after_timeline)
                       $("#rejected_after_timeline").text("Service Delivered : "+res.rejected_after_timeline)
                       $("#pending_within_timeline").text("Within Timeline : "+res.pending_within_timeline)
                       $("#pending_after_timeline").text("After Timeline : "+res.pending_after_timeline)
                    //    console.log(res);
                    },
                    error: function() {
                        console.log('error')
                    },
                }).always(function(){
                    swal.close();
                });



            }
            );

            $("#districts").on('change', function() {
            let service_id = $("#services").val();
            let district = $(this).val();
      
            $.ajax({
                    url: '<?= base_url("mis/appeal/find-second-appeal-count-by-district") ?>',
                    type: 'POST',
                    data: {
                        service_id:service_id,
                        district:district
                    },
                    beforeSend: function(){
                        swal.fire({
                            html: '<h5>Processing...</h5>',
                            showConfirmButton: false,
                            allowOutsideClick: () => !Swal.isLoading(),
                            onOpen: function() {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                       var res=JSON.parse(response);
                       $('#service_name').text("Result Showing of "+res.service_name	);
                       $("#total_appeal_count").text(res.total_appeal)
                       $("#delivered_within_timeline").text("Service Delivered : "+res.delivered_within_timeline)
                       $("#rejected_within_timeline").text("Service Rejected : "+res.rejected_within_timeline)
                       $("#delivered_after_timeline").text("Service Delivered : "+res.delivered_after_timeline)
                       $("#rejected_after_timeline").text("Service Delivered : "+res.rejected_after_timeline)
                       $("#pending_within_timeline").text("Within Timeline : "+res.pending_within_timeline)
                       $("#pending_after_timeline").text("After Timeline : "+res.pending_after_timeline)
                    //    console.log(res);
                    },
                    error: function() {
                        console.log('error')
                    },
                }).always(function(){
                    swal.close();
                });



            }
            );
           
        });

    });
</script>