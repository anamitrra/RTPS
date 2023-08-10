<?php
$obj_id = $dbrow->{'_id'}->{'$id'};
$split = explode("-", $dbrow->office_name);
$office_name = trim($split[1],')');

$plots = $dbrow->plots;      
$patta_nos = array();
$dag_nos = array();
$land_areas= array();

if(count($plots)) {
    foreach($plots as $plot) {
        array_push($patta_nos, $plot->patta_no);
        array_push($dag_nos, $plot->dag_no);
        array_push($land_areas, $plot->land_area);
    }//End of foreach()
}//End of if

$generatedAt = isset($dbrow->generated_at)?date('d-m-Y', strtotime($this->mongo_db->getDateTime($dbrow->generated_at))):date('d-m-Y');    
$currentYear = date('Y');
if($dbrow->searched_to==$currentYear) {
    $searchTo = $generatedAt;
} else {
    $searchTo = '31-12-'.$dbrow->searched_to;
}//End of if else
?>
<style type="text/css">
    table {
        border-spacing: 0px;
    }
    th, td {
        border:1px solid #ccc;
        line-height: 24px;
        padding: 5px;
    }
</style>
<div class="content-wrapper">
    <div class="container mt-3 mb-3">
        <div class="card shadow">
            <div class="card-body" style="border: 4px double #ccc; margin: 2px; padding: 0px;">
                <div style="text-align: center; border-bottom: 2px solid #ccc; padding: 10px">
                    <img src="<?=base_url('assets/frontend/images/assam_logo.png')?>" style="height: 110px; width: 100px" alt="Govt. of Assam" />
                    <div style="font-size: 18px; font-weight: bold; text-transform: uppercase">Government of Assam</div>
                    <div style="font-size: 16px; font-weight: bold; margin: 5px auto"><?=isset($dbrow->office)?$dbrow->office:''?></div>
                </div>
                
                <table class="table table-borderless" style="margin: 10px auto; width: 100%">
                    <tbody style="border-top: none">
                        <tr>
                            <td style="padding-left: 10px; border: none"><!--No. : <strong><?=$dbrow->rtps_trans_id?>--></strong></td>
                            <td style="padding-right:10px; text-align: right; border: none">
                                Date : <strong><?=$generatedAt?></strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <div style="text-align: justify; margin: 10px auto; padding: 10px; line-height: 24px; font-size: 14px; ">
                    Name of the applicant <strong><?=$dbrow->applicant_name?></strong><br>
                    S/O, D/O, W/O : <strong><?=$dbrow->father_name?></strong><br>
                    Certified that the properties indicated below have been searched in this office records 
                    from <strong>01-01-<?=$dbrow->searched_from?></strong> to <strong><?=$searchTo?></strong> 
                    and found the following result.
                
                    <table class="table table-bordered" style="margin: 10px auto; width: 100%">
                        <thead>
                            <tr>
                                <th style="text-align: left;">CIRCLE</th>
                                <?=isset($dbrow->mouza_name)?'<th style="text-align: left;">MOUZA</th>':''?>
                                <th style="text-align: left;">VILLAGE</th>
                                <th style="text-align: left;">PATTA NO.</th>
                                <th style="text-align: left;">DAG NO.</th>
                                <th style="text-align: left;">AREA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: left;"><?=$dbrow->circle_name?></td>
                                <?=isset($dbrow->mouza_name)?'<td style="text-align: left;">'.$dbrow->mouza_name.'</td>':''?>
                                <td style="text-align: left;"><?=$dbrow->village_name?></td>
                                <td style="text-align: left;"><?=implode(',', $patta_nos)?></td>
                                <td style="text-align: left;"><?=implode(',', $dag_nos)?></td>
                                <td style="text-align: left;"><?=implode(',', $land_areas)?></td>
                            </tr>
                        </tbody>
                    </table>
                                    
                    <table class="table table-borderless" style="margin: 0px auto; width: 100%">
                        <tbody style="border-top: none">
                            <tr>
                                <td style="padding-left: 0px; text-align: left !important; border: none">
                                    Result : <strong><?=$dbrow->result??''?></strong><br>
                                    Reference No. : <strong><?=$dbrow->ref_no??''?></strong><br>
                                    Searched by : <strong><?=$dbrow->search??''?></strong><br>
                                    RTPS Ref. No. : <strong><?=$dbrow->rtps_trans_id?></strong>
                                </td>
                                <td style="text-align: center; border: none">
                                    <img src="<?=base_url('storage/temps/'.$obj_id.'.png')?>" style="width: 15mm; height: 15mm" />
                                    <div style="font-style: italic; line-height: 16px">
                                        <?=$dbrow->designation??''?><br>
                                        <?=isset($dbrow->office)?$dbrow->office:''?>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>                    
                </div>                
                <p style="text-align:center; margin-top: 100px; font-style: italic">
                    This is a system generated certificate and hence does not require any physical signature.
                </p>
            </div>
        </div>
    </div>
</div>