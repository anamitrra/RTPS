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
                        <h3 class="card-title">Edit Portal</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="<?= base_url("expr/portals/edit_action/").$editData->_id->{'$id'} ?>" method="post">

                	         <div class="form-group">
                             <label for="varchar">Portal Name </label>
                             <input type="text" class="form-control" name="portal_name"  placeholder="" value="<?=$editData->portal_name?>" required />
                           </div>
                	         <div class="form-group">
                             <label for="varchar">Department Name </label>
                             <input type="text" class="form-control" name="department_name"  placeholder="" value="<?=$editData->department_name?>" required />
                           </div>
                	         <div class="form-group">
                             <label for="varchar">Service Name </label>
                             <input type="text" class="form-control" name="service_name"  placeholder="" value="<?=$editData->service_name?>" required />
                           </div>
                	         <div class="form-group">
                             <label for="varchar">Timeline Days </label>
                             <input type="text" class="form-control" name="timeline_days"  placeholder="" value="<?=$editData->timeline_days?>" required />
                           </div>
                	         <div class="form-group">
                             <label for="varchar">Portal Number </label>
                             <input type="text" class="form-control" name="portal_no" readonly  placeholder="" value="<?=$editData->portal_no?>" />
                           </div>
                	         <div class="form-group">
                             <label for="varchar">Service Id </label>
                             <input type="text" class="form-control" name="service_id" readonly placeholder="" value="<?=$editData->service_id?>" />
                           </div>
                	         <div class="form-group">
                             <label for="varchar">URL </label>
                             <input type="text" class="form-control" name="url"  placeholder="" value="<?=$editData->url?>" required />
                           </div>
                	         <div class="form-group">
                             <label for="varchar">Status URL </label>
                             <input type="text" class="form-control" name="status_url"  placeholder="" value="<?=$editData->status_url?>" required />
                           </div>
                	         <div class="form-group">
                             <label for="varchar">Guidelines </label>
                           </div>
                            <?php if (!empty($editData->guidelines)): ?>
                              <?php foreach ($editData->guidelines as $key => $value): ?>
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                      <input type="text" class="form-control" name="guidelines[]"  value="<?=$value?>"/>
                                    </div>
                                 </div>
                              <?php endforeach; ?>

                            <?php endif; ?>
                           <div id="additional_fields">

                           </div>
                           <div class="row">
                             <div class="form-group col-sm-2">

                               <button type="button" class="form-control" id="add_more">Add more</button>
                             </div>
                           </div>

      	                    <button type="submit" class="btn btn-primary">Update</button>
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
