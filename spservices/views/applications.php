<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<style>
    .parsley-errors-list{
        color: red;
    }
    .mbtn{
      width: 100% !important;
      margin-bottom: 3px;
    }
    .blk{
      display: block;
    }
</style>
<div class="content-wrapper">
<div class="container">
      <div class="row">
        <div class="col-md-12 mt-3 text-center">
            <?php if ($this->session->userdata('message') <> '') {?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success</strong> <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
        </div>
     </div>   
     <div class="row">
        <div class="col-md-12 mt-3 text-center">
            <?php if ($this->session->userdata('errmessage') <> '') {?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Alert</strong> <?php echo $this->session->userdata('errmessage') <> '' ? $this->session->userdata('errmessage') : ''; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
        </div>
     </div>
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <?php if (!empty($applications)) {
                if(!empty($applications['registered_deed'])){
                  $this->load->view('applications_view/registereddeed',$applications['registered_deed']);
                }
                //For Non-Encumbrance Certificates
                if(!empty($applications['necertificates'])){
                  $this->load->view('applications_view/necertificates_view',$applications['necertificates']);
                }//End of if
              } ?>
        </div>
    </div>
</div>
</div>

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
