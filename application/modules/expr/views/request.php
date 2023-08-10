<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/fontawesome-free/css/all.min.css"> <!-- Ionicons -->
<link rel="stylesheet" href="<?= base_url("assets/"); ?>dist/css/adminlte.min.css">
<link rel="stylesheet" href="<?= base_url("assets/css/"); ?>custom.css">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
<link defer href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
<!-- PAGE LEVEL STYLES-->
<style>
    .parsley-errors-list{
        color: red;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                  <h2>Transportation API</h2><br/><br/>
                    <form  action="<?=base_url('request/action')?>" method="post">
                    <div class="row">

                        <input type="hidden" name="return_url" value="<?=$return_url?>" />
                        <input type="hidden" name="DEPARTMENT_ID" value="<?=$DEPARTMENT_ID?>" />
                        <div class="col-sm-2">
                            <button type="submit" value="S" name="btn" class="btn btn-success mb-2" >
                                <span id="btnProccedTxt">Success</span>
                            </button>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit"  value="P" name="btn" class="btn btn-warning mb-2" >
                                <span id="btnProccedTxt">Pending</span>
                            </button>

                        </div>
                        <div class="col-sm-2">
                            <button type="submit"  value="F" name="btn"  class="btn btn-danger mb-2" >
                                <span id="btnProccedTxt">Failure</span>
                            </button>
                        </div>

                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
