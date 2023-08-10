<style>
    body {
        overflow-x: hidden;
    }
</style>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
</head>
<?php
$districts = $this->districts_model->get_rows(array("state_id" => 1));
?>
<div class="content-wrapper">
    <main class="rtps-container">
        <div class="row">
            <div class="col">
                <div class="container  my-3">
                    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <b><?= $this->session->flashdata('flashMsg') ?></b>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    <?php } ?>
                    <div class="card shadow-sm">
                        <div class="card-header bg-info"><strong>All users</strong></div>
                        <div class="card-body" style="">
                            <div class="row" style="display: grid">
                                <table class="table table-striped table-bordered table-hover m-1 " id="example" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="border-top: none" scope="col">Sl. No.</th>
                                            <th style="border-top: none" scope="col">Name</th>
                                            <th style="border-top: none" scope="col">Designation</th>
                                            <th style="border-top: none" scope="col">District</th>
                                            <th style="border-top: none" scope="col">Circle</th>
                                            <th style="border-top: none" scope="col">Mobile</th>
                                            <th style="border-top: none" scope="col">Current status</th>
                                            <th style="border-top: none" scope="col">Remarks</th>
                                            <th style="border-top: none" scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($users)) { ?>
                                            <?php $i = 1;
                                            foreach ($users as $user) { ?>
                                                <tr>
                                                    <td><?= $i++ ?></td>
                                                    <td><?= (isset($user->name)) ? $user->name : '' ?></td>
                                                    <td><?= (isset($user->designation)) ? $user->designation : '' ?> <?= ($user->role_slug_name == 'DPS') ? ' (<b class="text-danger">DPS</b>)' : '' ?></td>
                                                    <td><?= (isset($user->district_name)) ? $user->district_name : '' ?></td>
                                                    <td><?= (isset($user->circle_name)) ? $user->circle_name : '' ?></td>
                                                    <td><?= (isset($user->mobile)) ? $user->mobile : '' ?></td>
                                                    <td style="font-weight: bold;text-align:center">
                                                        <?php if ($user->is_active == 1) { ?>
                                                            <span class="text-success">Active</span>
                                                        <?php } else { ?>
                                                            <span class="text-danger">Inactive</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $status_change_count = count($user->sts_txn);
                                                        if ($status_change_count == 0) {
                                                            echo 'New User';
                                                        } else { ?>
                                                            <button class="btn btn-warning btn-sm p-1 fetch_status_txn" data-bs-toggle="modal" data-bs-target="#statusChangeTxn<?php echo ($user->_id->{'$id'}) ?>"><small>View remarks</small></button>
                                                            <div class="modal fade" id="statusChangeTxn<?php echo ($user->_id->{'$id'}) ?>" tabindex="-1" aria-labelledby="statusChangeTxnLabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-dark py-2">
                                                                            <h3 class="modal-title" id="statusChangeTxnLabel">Status Change History</h3>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <table class="table table-bordered table-stripped">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>#</th>
                                                                                        <th>Remarks</th>
                                                                                        <th>Action</th>
                                                                                        <th>Status change date</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php $j=1;
                                                                                    foreach ($user->sts_txn as $txn) { ?>
                                                                                        <tr>
                                                                                            <td><?= $j ?></td>
                                                                                            <td><?= $txn->remarks ?></td>
                                                                                            <td><?= ($txn->status == 1) ? 'Active' : 'Deactive' ?></td>
                                                                                            <td><?php echo date('d/m/Y', strtotime($this->mongo_db->getDateTime($txn->txn_date))); ?></td>
                                                                                        </tr>
                                                                                    <?php $j++; } ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <a type="button" class="btn btn-danger text-white btn-md" data-bs-dismiss="modal">No</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php }
                                                        ?>
                                                    </td>
                                                    <td class="text-align:center">
                                                        <div class="container">
                                                            <form action="<?= base_url('spservices/mcc_users/users/updateUser') ?>" method="post">
                                                                <div class="modal fade" id="exampleModal<?php echo ($user->_id->{'$id'}) ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-dark py-2">
                                                                                <h3 class="modal-title" id="exampleModalLabel">Are you sure ?</h3>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <p style="font-size:15px">Do you really want to change the status ?</p>
                                                                                <input type="hidden" value="<?= ($user->_id->{'$id'}) ?>" name="objId">
                                                                                <div class="form-group">
                                                                                    <label for="">Remarks <span class="text-danger">*</span></label>
                                                                                    <textarea name="status_change_remks" id="" cols="30" rows="2" class="form-control" required></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="submit" class="btn btn-md btn-success"><i class="fa fa-check"></i> Yes</button>
                                                                                <a type="button" class="btn btn-danger text-white btn-md" data-bs-dismiss="modal">No</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <a type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo ($user->_id->{'$id'}) ?>">Change status</a>
                                                        <a href="<?php echo base_url() . 'spservices/mcc_users/users/view/' . $user->_id->{'$id'} ?>" type="button" class="btn btn-primary btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
                                                                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
                                                            </svg>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td>Records not found! </td>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
    function myFunction() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
    $(document).ready(function() {
        $('#example').DataTable();

        // $('.fetch_status_txn').on('click', function() {
        //     alert('hi');
        // })
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>