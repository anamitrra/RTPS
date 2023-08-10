<div class=" d-flex justify-content-center" style="margin-top:40px">
  <section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card border-0">
          <!-- <div class="card-header">
            <h5 class="card-title"> Appleal user registration</h5>
          </div> -->

          <div class="card-body">
          <div class="row">
                        <div class="col-md-12 text-center">
                            <?php if ($this->session->userdata('message') <> '') { ?>
                                <div class="alert <?=$this->session->userdata('class')?>  fade show" role="alert">
                                    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                                    <!-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button> -->
                                </div>
                            <?php } ?>
                        </div>
             </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
