<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php }//End of if ?>
    <div class="card shadow-sm mt-2">
        <div class="card-header">
        <form class="form-inline" action="<?= base_url($url) ?>" autocomplete="off" method="POST">
            <div class="form-group col-md-5">
                <label for="from_date" style="margin-left: 10px;">From Date</label>
                <input type="date" class="form-control" value="<?= set_value("from_date")?>" name="from_date"  style="margin-left: 10px;" required>
                <input type="time" class="form-control" name="from_time" value="<?= set_value("from_time")?>"  style="margin-left: 10px;" required>
            </div>
            <div class="form-group  col-md-5">
                <label for="to_date" style="margin-left: 10px;">To Date</label>
                <input type="date" class="form-control" value="<?= set_value("to_date")?>" name="to_date" style="margin-left: 10px;" required>
                <input type="time" class="form-control" name="to_time" value="<?= set_value("to_time")?>"  style="margin-left: 10px;" required>
            </div>
            <!-- <div class="form-group mt-4 col-md-6">
                <label for="to_date" style="margin-left: 10px;">Application Status</label>
                <select class="form-control" name="app_status" >
                    <option value="">Select Any One</option>
                    <option value="D">Delivered</option>
                    <option value="AA">Pending</option>
                </select>
            </div> -->
            <div class="form-group mt-4 text-center col-md-2">
                <button type="submit" style="margin-left: 10px;" class="btn btn-danger">Search</button>
            </div>
        </form>
        </div>
    </div>
    <div class="card shadow-sm mt-2">
        <div class="card-header">
            <span class="h5 text-dark">My applications</span>
            <span style="float: right; color:#000">
                Logged in as <strong><?=$this->session->loggedin_user_fullname?></strong>
                (Role <?=$this->session->loggedin_user_role_code?> of Level-<?=$this->session->loggedin_user_level_no?>)
            </span>
        </div>
        <div class="card-body">                
            <?php if ($dbRow): ?>
                <table id="dtbl" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Service Name</th>
                            <th>Ref. no.</th>
                            <th>Mode of Delivery</th>
                            <th>Postal Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="">
                        <?php
                        
                        foreach ($dbRow as $key => $row):
                            $obj_id = $row->_id->{'$id'}; 
                            $post_district = $row->form_data->pos_district ?? $row->form_data->c_district;
                          ?>

                          
                            <tr>
                                <td><?=sprintf("%02d", $key+1)?></td>
                                <td><?=$row->service_data->service_name ?? ''?></td>
                                <td><?=$row->service_data->appl_ref_no ?? ''?></td>
                                <td><?=$row->form_data->postal ?? $row->form_data->delivery_mode ?></td>
                                <td><?=$row->form_data->comp_postal_address ??  $row->form_data->c_comp_permanent_address?>, <?=$post_district[0] ?>, <?=$row->form_data->pos_pincode ?? $row->form_data->c_pin_code?>, <?=$row->form_data->pos_state ?? $row->form_data->c_state?></td>
                                <td> 
                                    <?php
                                    $certificatePath = (isset($row->form_data->certificate) && strlen($row->form_data->certificate))?base_url($row->form_data->certificate):'#';
                                    ?>
                                    <!-- <a href="" class="btn btn-primary">View</a> -->
                            
                                    <?php if($row->service_data->appl_status=='D') { ?>
                                    <a href='<?php echo $certificatePath ?>' class='btn btn-success btn-sm' target='_blank'>View Certificate</a> 
                                    <?php  } else { ?>                                         
                                        <a href="<?=base_url('spservices/upms/myapplications/process/'.$obj_id)?>" class='btn btn-warning btn-sm' >Process</a>                                        
                                    <?php } ?>                      
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No records found<p>
            <?php endif; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
      $('#datetimepicker').datetimepicker();
    });
  </script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" type="text/css" media='all' href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" media='all' href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" />
<script type="text/javascript">
$(document).ready(function () {
    $('#dtbl').DataTable();
});
</script>