<?php 
$district=$this->mongo_db->get("districts");
$dist_arr=array();
if(!empty($district)){
    $dist_arr=(array)$district;
}

?>
<div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                  
                                    <input type="text" class="form-control" id="village"
                                              name="village"
                                              required
                                              value="<?=isset($appealApplicationPrevious[0]->village)?$appealApplicationPrevious[0]->village:''?>"
                                              placeholder="Village"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <?php if(!empty($dist_arr)) :   ?>
                                        <select name="district" class="form-control"  required>
                                             <option value="">Select District</option>
                                            <?php  foreach($dist_arr as $key=>$value):  ?>
                                            <option <?php if(isset($appealApplicationPrevious[0]->district) && ($appealApplicationPrevious[0]->district ===$value->distname)){echo "selected";}?> value="<?=$value->distname?>"><?=$value->distname?></option>
                                            <?php endforeach;  ?>
                                        </select>
                                    <?php endif; ?>
                                    <!-- <input type="text" class="form-control" id="district"
                                              name="district"
                                              required
                                              value="<?=isset($appealApplicationPrevious[0]->district)?$appealApplicationPrevious[0]->district:''?>"
                                              placeholder="District"/> -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                  
                                    <input type="text" class="form-control" id="policestation"
                                              name="policestation"
                                              required
                                              value="<?=isset($appealApplicationPrevious[0]->police_station)?$appealApplicationPrevious[0]->police_station:''?>"
                                              placeholder="Police Station"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    
                                    <input type="text" class="form-control" id="circle" name="circle" value="<?=isset($appealApplicationPrevious[0]->circle)?$appealApplicationPrevious[0]->circle:''?>" placeholder="Circle"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                  
                                    <input type="text" class="form-control" id="postoffice"
                                              name="postoffice"
                                              required
                                              value="<?=isset($appealApplicationPrevious[0]->post_office)?$appealApplicationPrevious[0]->post_office:''?>"
                                              placeholder="Post Office"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    
                                    <input type="text" class="form-control" id="pincode"
                                              name="pincode"
                                              required  minlength="6" maxlength="6" pattern="[0-9]{6}"
                                              value="<?=isset($appealApplicationPrevious[0]->pincode)?$appealApplicationPrevious[0]->pincode:''?>"
                                              placeholder="Pincode"/>
                                </div>
                            </div>
                        </div>