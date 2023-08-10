<?php $useractivities = $this->useractivities_model->get_rows(array("user_id"=>$this->session->userId->{'$id'})); ?>
<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')?>">
<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')?>">
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#codeModal").on("show.bs.modal", function (e) {
            var objId = e.relatedTarget.id; //alert(objId);
            $("#before_div").html($("#before-"+objId).html());
            $("#after_div").html($("#after-"+objId).html());
        });
    });
</script>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Activity Logs</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("dashboard"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Activity Logs</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Activity Logs</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="">
                            <table id="ticket-table" class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr class="table-header">
                                        <th>Date &AMP; Time</th>
                                        <th>User name</th>
                                        <th>Activity</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody class="small-text">
                                     <?php if ($useractivities) {
                                         foreach ($useractivities as $key => $row) {
                                            $obj_id = $row->_id->{'$id'};
                                            $userRow = $this->users_model->get_by_doc_id($row->user_id);
                                            if(count((array)$userRow)) {
                                                $userName = $userRow->name.' ('.$userRow->designation.')';
                                            } else {
                                                $userName = 'Unable to match';
                                            }//End of if else ?>
                                            <tr>
                                                <td><?=format_mongo_date($row->activity_time)?></td>
                                                <td><?=$userName?></td>
                                                <td>
                                                    <a id="<?=$obj_id?>" href="javascript:void(0" data-toggle="modal" data-target="#codeModal">
                                                        <?=$row->activity_title?>
                                                    </a>
                                                    <p id="before-<?=$obj_id?>" style="display:none"><?=json_encode($row->data_before_update)?></p>
                                                    <p id="after-<?=$obj_id?>" style="display:none"><?=json_encode($row->data_after_update)?></p>
                                                </td>
                                                <td><?=$row->activity_description?></td>
                                            </tr>
                                        <?php }//End of foreach()
                                     }//End of if ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="codeModal" tabindex="-1" role="dialog" aria-labelledby="codeModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Code comparison</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 bg-secondary font-weight-bold text-white" style="border-bottom:1px solid #000; text-align: center">
                            Data before update
                        </div>
                        <div class="col-md-6 bg-warning font-weight-bold text-white" style="border-bottom:1px solid #000; text-align: center">
                            Data after update
                        </div>
                    </div>
                    <div class="row">
                        <div id="before_div" class="col-md-6 bg-secondary overflow-auto"></div>
                        <div id="after_div" class="col-md-6 bg-warning overflow-auto"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
