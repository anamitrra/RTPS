<style type="text/css">
    .info-box {
        display: block;
        text-align: center;
    }
    .info-box-icon {
        width: 50px !important;
        height: 50px !important;
        margin: 10px auto;
        font-size: 50px;
    }
    .info-box-text {
        font-size: 16px;
        font-style: italic;
    }
    .info-box-number {
        font-size: 34px;
    }
</style>
<script type="text/javascript">
$(function() {
  $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
<div class="content-wrapper p-2 pt-3">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php }//End of if ?>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <div class="info-box">
                        <a href="<?=base_url("spservices/upms/dashboard/appsbystatus/")?>" class="text-secondary" data-bs-toggle="tooltip" title="Click to view all received applications">
                            <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-hands"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-number"><?=sprintf("%03d", $received)?></span>                            
                                <span class="info-box-text">Received</span>
                            </div><!-- /.info-box-content -->
                        </a>
                    </div><!-- /.info-box -->
                </div><!-- /.col -->
                
                <div class="col-md-4 col-sm-6">
                    <div class="info-box mb-3">
                        <a href="<?=base_url("spservices/upms/dashboard/appsbystatus/p")?>" class="text-warning" data-bs-toggle="tooltip" title="Click to view all pending applications">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-box-open"></i></span>
                            <div class="info-box-content">                            
                                <span class="info-box-number"><?=sprintf("%03d", $pending)?></span>                            
                                <span class="info-box-text">Pending</span>
                            </div><!-- /.info-box-content -->
                        </a>
                    </div><!-- /.info-box -->
                </div><!-- /.col -->
                
                <div class="col-md-4 col-sm-6">
                    <div class="info-box mb-3">
                        <a href="<?=base_url("spservices/upms/dashboard/appsbystatus/qs")?>" class="text-info" data-bs-toggle="tooltip" title="Click to view all queried applications">
                            <span class="info-box-icon bg-info elevation-1"><i class="fa fa-retweet"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-number"><?=sprintf("%03d", $queried)?></span>                            
                                <span class="info-box-text">Queried</span>
                            </div><!-- /.info-box-content -->
                        </a>
                    </div><!-- /.info-box -->
                </div><!-- /.col -->     
                
                <div class="col-md-4 col-sm-6">
                    <div class="info-box mb-3">
                        <a href="<?=base_url("spservices/upms/dashboard/appsbystatus/r")?>" class="text-danger" data-bs-toggle="tooltip" title="Click to view all rejected applications">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fa fa-trash"></i></span>
                            <div class="info-box-content">                            
                                <span class="info-box-number"><?=sprintf("%03d", $rejected)?></span>                            
                                <span class="info-box-text">Rejected</span>
                            </div><!-- /.info-box-content -->
                        </a>
                    </div><!-- /.info-box -->
                </div><!-- /.col -->                          
                
                <div class="col-md-4 col-sm-6">
                    <div class="info-box mb-3">
                        <a href="<?=base_url("spservices/upms/dashboard/appsbystatus/d")?>" class="text-success" data-bs-toggle="tooltip" title="Click to view all delivered applications">
                            <span class="info-box-icon bg-success elevation-1"><i class="fa fa-check-square"></i></span>
                            <div class="info-box-content">                           
                                <span class="info-box-number"><?=sprintf("%03d", $delivered)?></span>                            
                                <span class="info-box-text">Delivered</span>
                            </div><!-- /.info-box-content -->
                        </a>
                    </div><!-- /.info-box -->
                </div><!-- /.col -->
                
                <div class="col-md-4 col-sm-6">
                    <div class="info-box mb-3">
                        <a href="<?=base_url("spservices/upms/dashboard/appsbystatus/pending")?>" data-bs-toggle="tooltip" title="Progress = (Delivered+Rejected+Queried) / Received)*100">
                            <span class="info-box-icon bg-primary elevation-1"><i class="fa fa-chart-line"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-number"><?=sprintf("%0.2f", $progress)?>%</span>                            
                                <span class="info-box-text">Progress</span>                            
                            </div><!-- /.info-box-content -->
                        </a>
                    </div><!-- /.info-box -->
                </div><!-- /.col -->
            </div><!-- .row -->
        </div><!-- .container-fluid -->
    </div><!-- .content -->
</div>