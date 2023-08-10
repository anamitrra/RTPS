<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">

<div class="content-wrapper" style="min-height: 1376.4px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Commission</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Commission</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Manage Commission</h3>
                </div>
                <div class="card-body">
                    <?php
                    if ($this->session->flashdata('error') != null) {
                        ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php
                    }
                    if ($this->session->flashdata('success') != null) {
                        ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php
                    }
                    ?>
                    <form method="post" action="<?=base_url('appeal/commission/save')?>">
                        <div class="row">
                            <div class="col-6">
                                <label for="">Chairman</label>
                                <select class="form-control select2" name="chairman">
                                    <?php
                                        if(!empty($userList)){
                                    ?>
                                    <option value="" hidden>Choose one</option>
                                    <?php
                                            foreach ($userList as $user){
                                                if($user->slug === 'RA' && $user->users[0]){
                                                    foreach($user->users as $ch){ ?>
                                                                    <option value="<?=strval($ch->_id)?>" <?=(!empty($activeCommission) && $activeCommission->reviewing_authority == $ch->_id) ?'selected':''?>><?=$ch->name?></option>
                                                    <?php }
                                 
                                                }
                                            }
                                        }else{
                                            ?>
                                            <option value="" hidden>No record found</option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="">Registrar</label>
                                <select class="form-control select2" name="registrar">
                                    <?php
                                    if(!empty($userList)){
                                        ?>
                                        <option value="" hidden>Choose one</option>
                                        <?php
                                        foreach ($userList as $user){
                                            if($user->slug === 'RR'){ 
                                                foreach($user->users as $u){ ?>
                                                    <option value="<?=strval($u->_id)?>" <?=(!empty($activeCommission) && $activeCommission->registrar == $u->_id) ?'selected':''?> ><?=$u->name?></option>
                                               <?php }
                                            }
                                        }
                                    }else{
                                        ?>
                                        <option value="" hidden>No record found</option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button class="btn btn-outline-success btn-block">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?=base_url('assets/plugins/select2/js/select2.min.js')?>"></script>
<script>
    const select2Ref = $('.select2');
    $(function(){
        select2Ref.select2();
    })
</script>