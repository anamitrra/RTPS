<?php
$serviceId = $form_row->service_data->service_id??'';
$serviceRow = $this->services_model->get_row(['service_code' => $serviceId]);
if($serviceRow) {
    $previewLink = $serviceRow->preview_link??'';
} else {
    $previewLink = '';
}//End of if else
$processing_history = $form_row->processing_history??array();
?>
<div class="content-wrapper mt-2 p-2">    
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php }//End of if ?>
    
    <div class="card shadow-sm mt-2">
        <div class="card-header bg-success">
            <span class="h5 text-white">Application preview</span>
        </div>
        <div class="card-body">
            <?php
            $rawFilePath = FCPATH.'application/modules/spservices/views/'.$previewLink;
            $absFilePath = str_replace('\\', '/', $rawFilePath);//echo "File exists : ".file_exists($absFilePath.'.php')?'YES':'NO';
            if(strlen($previewLink) && file_exists(FCPATH.'application/modules/spservices/views/'.$previewLink)) {
                $data = array(
                    "service_name" => $form_row->service_data->service_name,
                    "dbrow" => $form_row,
                    "user_type" => $form_row->form_data->user_type??''
                );
                $this->load->view($previewLink, $data);
            } else {
                echo '<h2 style="text-align:center; font-size:18px !important; line-height:28px">Unable to locate the view file</h2>';
            }//End of if else ?>
        </div><!--End of .card-body-->
    </div><!--End of .card-->
        
    <div class="card shadow-sm mt-2">
        <div class="card-header bg-info">
            <span class="h5 text-white">Application processing history</span>
        </div>
        <div class="card-body">
            <?php if(count($processing_history)) { ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Processed on</th>
                            <th>Processed by</th>
                            <th>Action taken</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($processing_history as $prows) { ?>
                            <tr>
                                <td><?= isset($prows->processing_time)?date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($prows->processing_time))):''?></td>
                                <td><?=$prows->processed_by??''?></td>
                                <td><?=$prows->action_taken??''?></td>
                                <td>
                                    <?php echo $prows->remarks;
                                    if(isset($prows->file_uploaded) and strlen($prows->file_uploaded)) {
                                        echo '<br><a href="'.base_url($prows->file_uploaded).'" class="btn btn-info" target="_blank"><i class="fa fa-file"></i> View uploaded file</a>';
                                    } ?>                                    
                                </td>
                            </tr>
                        <?php }//End of foreach() ?>
                    </tbody>
                </table>
            <?php }//End of if ?>
        </div><!--End of .card-body-->
    </div><!--End of .card-->
</div>