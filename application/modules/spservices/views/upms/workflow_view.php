<?php
$trees = array();
$serviceDetails = '<i class="fa fa-book chart-icon"></i><br /><strong>'.$dbrow->service_name.'</strong><br /> Service Code : '.$dbrow->service_code;
$root = array(
    'id' => $dbrow->service_code,
    'name' => $serviceDetails,
    'parent' => '0',
    'redirect_url' => base_url('spservices/upms/svcs/index/').$dbrow->_id->{'$id'}
);
array_push($trees, $root);

$levels = $this->levels_model->get_rows(array("level_services.service_code" => $dbrow->service_code));
if($levels) {
    foreach($levels as $level) {
        $level_rights = $level->level_rights??array();
        $levelRights = array();
        if(count($level_rights)) {
            foreach ($level_rights as $levelRight) {
                if(!in_array($levelRight->right_code, $levelRights, true)){
                    array_push($levelRights, $levelRight->right_code);
                }//End of if
            }//End of foreach()
        }//End of if 
        
        $levelDetails = '<i class="fa fa-users chart-icon"></i><br /><strong>'.$level->level_name.'</strong><br /> Role : '.$level->level_roles->role_name;
        $child = array(
            'id' => $level->level_no,
            'name' => $levelDetails.'<br /> Rights : <i class="text-danger">'.implode(', ', $levelRights).'</i>',
            'parent' => $dbrow->service_code,
            'redirect_url' => base_url('spservices/upms/levels/index/').$level->_id->{'$id'}
        );
        array_push($trees, $child);
        
        $role_code = $level->level_roles->role_code;
        $users = $this->users_model->get_rows(array("user_services.service_code"=>$dbrow->service_code,"user_roles.role_code" => $role_code));
        if($users) {
            $userCounter = 0;
            foreach($users as $user) {
                $userDetails = '<i class="fa fa-user chart-icon"></i><br /><strong>'.$user->user_fullname.'</strong><br /> Username : '.$user->login_username;
                $child = array(
                    'id' => $user->login_username,
                    'name' => $userDetails,
                    'parent' => $level->level_no,
                    'redirect_url' => base_url('spservices/upms/users/profile/').$user->_id->{'$id'}
                );
                if($userCounter < 3) {
                    array_push($trees, $child);
                }//End of if                
                $userCounter++;
            }//End of foreach()
        }//End of if
    }//End of foreach()
}//End of if
$treejson = json_encode($trees); ?>
<link rel="stylesheet" href="<?=base_url('assets/plugins/orgchart/css/jquerysctipttop.css')?>" type="text/css" />
<link rel="stylesheet" href="<?=base_url('assets/plugins/orgchart/css/jquery.orgchart.css')?>" type="text/css" />
<style type="text/css">
    .chart-icon {
        font-size: 28px;
        font-weight: bold;
        line-height: 32px;
    }
    .table td {
        padding: 10px 5px;
    }
    div.orgChart td {
        padding: 0;
    }
    .timg {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }
    .process-direction-left {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        position: absolute; 
        top: 0%; 
        left: 2%;
        font-weight:bold; 
        font-size: 24px; 
        color:#28a745;
    }
    .process-direction-right {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        position: absolute; 
        top: 0%; 
        right: 2%;
        font-weight:bold; 
        font-size: 24px; 
        color:#dc3545;
    }
    .rotated {
        writing-mode: tb-rl;
        transform: rotate(-180deg);
    }
</style>
<script src="<?=base_url('assets/plugins/orgchart/js/jquery.orgchart.js')?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#btree").orgChart({
            data: <?=$treejson?>,
            dataType: "json",
            onClickNode: function(node){
                var redirect_url = node.data.redirect_url;
                if (confirm('You will be redirect to '+redirect_url+'?')) {
                    window.location.href = redirect_url;
                } else {
                    return true;
                }//End of if else                
            }
        }); // End of orgChart()
    });
</script>
<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php }//End of if ?>
    <div class="card shadow-sm table-responsive">
        <div class="card-header">
            <span class="h5 text-center border-0">Work-flow for <strong><?=$dbrow->service_name?></strong></span>
        </div>
        <div class="card-body" id="btree"></div><!--End of .card-body-->
        <div class="process-direction-left">
            <div class="rotated">START</div>
            <i class="fa fa-angle-double-right"></i>  
            <i class="fa fa-angle-double-right"></i>          
        </div>
        <div class="process-direction-right">
            <i class="fa fa-angle-double-right"></i>   
            <i class="fa fa-stop-circle"></i>  
            <div class="rotated">END</div>       
        </div>
        <div class="card-footer">
            
        </div><!--End of .card-footer-->
        
            
    </div><!--End of .card-->
</div><!--End of .container-->