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
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <form action="<?=base_url("iservices/admin/data/find_payment")?>" method="post" >
                    <input type="text" placeholder="Application No" class="form-control" name="app_ref_no"/>
                    <br/><span>OR</span><br/>
                    <input type="text" placeholder="RTPS Trans No" class="form-control" name="rtps_trans_id"/><br/>
                    <select name="payment_type" class="form-control" >
                            <option value="KIOSK">KIOSK/User</option>
                            <option value="QUERY">Query</option>
                    </select>
                    <button  type="submit" class="btn btn-sm btn-primary">Find</button>
                    </form>
                </div>

                <!-- /.row -->
            </div>
        </div>
        <!--/. container-fluid -->
    </section>
    <!-- /.content -->
</div>