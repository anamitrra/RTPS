<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Portal</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- <li class="breadcrumb-item"><a href="<?=base_url("dashboard")?>">Home</a></li>
                        <li class="breadcrumb-item active">Roles</li> -->
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
                        <h3 class="card-title">Add Portal</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="<?= base_url("iservices/portals/add_action")  ?>" method="post">
                	         <div class="form-group">
                             <label for="varchar">Portal Name </label>
                             <input type="text" class="form-control" name="portal_name"  placeholder="" value=""  required/>
                           </div>
                	         <div class="form-group">
                             <label for="varchar">Department Name </label>
                             <input type="text" class="form-control" name="department_name"  placeholder="" value="" required />
                           </div>
                	         <div class="form-group">
                             <label for="varchar">Service Name </label>
                             <input type="text" class="form-control" name="service_name"  placeholder="" value="" required />
                           </div>
                	         <div class="form-group">
                             <label for="varchar">Timeline Days </label>
                             <input type="text" class="form-control" name="timeline_days"  placeholder="" value="" required />
                           </div>
                	         <div class="form-group">
                             <label for="varchar">Portal Number </label>
                             <input type="text" class="form-control" name="portal_no"  placeholder="" value="" required />
                           </div>
                	         <div class="form-group">
                             <label for="varchar">Service Id </label>
                             <input type="text" class="form-control" name="service_id"  placeholder="" value="" required />
                           </div>
                           <div class="form-group">
                             <label for="varchar">External Service Id </label>
                             <input type="text" class="form-control" name="external_service_id"  placeholder=""  required/>
                           </div>
                           <div class="form-group">
                             <label for="varchar">Department Code </label>
                             <input type="text" class="form-control" name="dept_code"  placeholder=""  />
                           </div>
                           <div class="form-group">
                             <label for="varchar">Office Code </label>
                             <input type="text" class="form-control" name="office_code"  placeholder=""  />
                           </div>
                	         <div class="form-group">
                             <label for="varchar">URL </label>
                             <input type="text" class="form-control" name="url"  placeholder="" value="" required />
                           </div>
                	         <div class="form-group">
                             <label for="varchar">Status URL </label>
                             <input type="text" class="form-control" name="status_url"  placeholder="" value="" required />
                           </div>
                           <div class="form-group">
                             <label for="varchar">RTPS Service URL </label>
                             <input type="text" class="form-control" name="rtps_service_url"  placeholder="" value="" />
                           </div>

                           <!-- Payment -->
                           <div class="form-group">
                           <label for="varchar">Payment Required ?</label>
                           <div class="form-check">
                              
                          <input  value="1" class="form-check-input" type="radio" name="payment_required" id="status">
                          <label class="form-check-label" for="flexRadioDefault1">
                            Required
                          </label>
                        </div>
                        <div class="form-check">
                          <input  value="0" class="form-check-input" type="radio" name="payment_required" id="status" >
                          <label class="form-check-label" for="flexRadioDefault2">
                            Not required
                          </label>
                        </div>
                           <!-- Payment end -->

                           <!-- Status -->
                           <div class="form-group">
                           <label for="varchar">Status</label>
                           <div class="form-check">
                              
                          <input checked  value="1" class="form-check-input" type="radio" name="status" id="status">
                          <label  class="form-check-label" for="flexRadioDefault1">
                            Active
                          </label>
                        </div>
                        <div class="form-check">
                          <input  value="0" class="form-check-input" type="radio" name="status" id="status" >
                          <label class="form-check-label" for="flexRadioDefault2">
                            Inactive
                          </label>
                        </div>
                           <!-- Status end -->
                           
                	      
                           <fieldset  class="border border-success"  style="margin-top:40px">
                            <legend class="h5">External Portal(EP) Payment Info :</legend>
                            <div style="padding:20px">
                                

                                <div class="form-group">
                                <label for="varchar">Account Code For EP payment </label>
                                <input type="text" class="form-control" name="ep_payment_account_code"  placeholder="" />
                                </div>
                            </div>
                           
                           </fieldset>
                            
                            <br/>

      	                    <button type="submit" class="btn btn-primary">Add</button>
      	              </form>
                    </div>
                  </div>
          </div>
      </div>
    </section>
</div>
<script>
// Shorthand for $( document ).ready()
$(function() {
    $("#add_more").click(function(){
      $("#additional_fields").append(
        '<div class="row">\
            <div class="form-group col-sm-12">\
              <input type="text" class="form-control" name="guidelines[]" />\
            </div>\
         </div>'
      )
    })
})
</script>
