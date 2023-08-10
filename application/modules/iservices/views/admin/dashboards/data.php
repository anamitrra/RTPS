<style>
    .cd{
        color: darkcyan;
        font-weight: bold;
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
            <h1 class="m-0 text-dark">Find Application Data</h1>
          </div>
          <div class="col-sm-4">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=base_url("dashboard");?>">Home</a></li>
              <li class="breadcrumb-item active">Data</li>
            </ol>
          </div>
        </div>

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
       


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title cd">iServices Data</h3>
                        
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="<?=base_url("iservices/admin/data/find")?>" method="post" >
                          <div class="row">
                              <div class="col-lg-6 col-6">  
                                  <input type="text" placeholder="Application No / RTPS Transaction No /GRN / Mobile" class="form-control" name="app_ref_no"/>
                              </div>
                           
                             <div class="col-lg-3 col-6">
                             <button  type="submit" class="btn btn-sm btn-primary">Find</button>
                             </div>
                          </div>
                          </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title cd">SP Services Data</h3>
                        
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="<?=base_url("iservices/admin/data/findspapplication")?>" method="post" >
                          <div class="row">
                              <div class="col-lg-6 col-6">  
                                  <input type="text" placeholder="Application No / RTPS Transaction No /GRN / Mobile" class="form-control" name="app_ref_no"/>
                              </div>
                              
                             <div class="col-lg-3 col-6">
                             <button  type="submit" class="btn btn-sm btn-primary">Find</button>
                             </div>
                          </div>
                          </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title cd">Update Payment Status (iservices,NEC,Certified Copies )</h3>
                        
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        
                          <div class="row">
                             <div class="col-lg-3 col-6">
                             <a  href="<?=base_url("iservices/admin/data/payment_info")?>" type="submit" class="btn btn-sm btn-primary">Find</a>
                             </div>
                          </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>