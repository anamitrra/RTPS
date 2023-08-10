<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<style type="text/css">
    .parsley-errors-list {
        color: red;
    }

    .mbtn {
        width: 100% !important;
        margin-bottom: 3px;
    }

    .mybtn {
        float: right;
        font-size: 14px;
        font-style: italic;
        font-weight: bold;
        text-transform: uppercase;
        border: 2px dotted #F40080;
        border-radius: 5px;
        padding: 2px 5px;
        background: #F40080;
        color: #fff;
    }
</style>
<div class="row">
    <div class="col-sm-12 mx-auto">
        <div class="card my-4">
            <div class="card-body">
                <h4>
                    Trade Licence
                    <a href="<?= base_url('spservices/tradelicence/application') ?>" class="mybtn" target="_blnk">
                        NEW APPLICATION <i class="fa fa-plus"></i>
                    </a>
                </h4>
                <?php if ($this->session->flashdata('pay_message') != null) { ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong></strong> <?= $this->session->flashdata('pay_message') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php }
                if ($tradelicence) : ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Application Ref No</th>
                                <th>Service Name</th>
                                <th>Submission Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="">
                            <?php foreach ($tradelicence as $key => $value) :
                                $obj_id = $value->_id->{'$id'};
                            ?>
                                <tr>
                                    <td><?= ($key + 1) ?></td>
                                    <td><a target="_blank" href="<?= base_url("spservices/tradelicence/application/tradepreview_view/") . $obj_id ?>"><?= $value->service_data->appl_ref_no ?></a></td>
                                    <td><?= isset($value->service_data->service_name) ? $value->service_data->service_name : "" ?></td>
                                    <td><?= (!empty($value->service_data->submission_date)) ? format_mongo_date($value->service_data->submission_date) : "" ?></td>
                                    <td><?= ($value->service_data->appl_status == "F") ? "Forwarded" : getstatusname($value->service_data->appl_status); ?></td>
                                    <td>
                                        <?php if ($value->service_data->appl_status == "DRAFT") { ?>
                                            <a class="btn btn-primary btn-sm mbtn" href="<?= base_url("spservices/tradelicence/application/index/") . $obj_id ?>">Complete Your Application</a>
                                        <?php } elseif ($value->service_data->appl_status == "submitted") { ?>
                                            <a class="btn btn-success btn-sm mbtn" href="<?= base_url("spservices/tradelicence/application/acknowledgement/") . $obj_id ?>">Acknowledgement</a>

                                            <a href="<?= base_url('spservices/tradelicence/application/track/' . $obj_id) ?>" class="btn btn-secondary btn-sm mbtn" target="_blank">Track status</a>
                                            <a href="<?= base_url('spservices/tradelicence/application/certificate/' . $obj_id) ?>" class="btn btn-secondary btn-sm mbtn" target="_blank">Download Certificate</a>
                                        <?php } //End of if else 
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p>No Application Found
                    <p>
                    <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>