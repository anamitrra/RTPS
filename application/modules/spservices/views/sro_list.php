<main class="rtps-container">
    <div class="container-fluid my-4">
        <div class="card">
            <div class="card-header bg-dark text-white">All SRO</div>
            <div class="card-body">
                <?php 
                if ($this->session->flashdata('error') != null) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('success') != null) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                <?php } ?>
               
                <table class="table table-bordered table-striped table-responsive">
                    <thead class="table-info">
                        <tr>
                            <th>#</th>
                            <th>Parent</th>
                            <th>Sub Office</th>
                            <th>Office Code</th>
                            <th>Treasury Code</th>
                         
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; if($list) {
                            $sbl='org_unit_name-2';
                            foreach($list as $val) {  ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= $val->$sbl?></td>
                                    <td><?= $val->org_unit_name ?></td>
                                    <td><?= !empty($val->office_code) ? $val->office_code : "" ?></td>
                                    <td><?= !empty($val->treasury_code) ? $val->treasury_code : "" ?></td>
                                  
                                </tr>
                        <?php } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

