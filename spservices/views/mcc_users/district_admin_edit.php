<style type="text/css">
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


$admin = json_decode(json_encode($admin), true);
$admin = $admin;
// print_r($admin[0]["_id"]['$id']);


$district_name = set_value("district_name");
$name = $admin[0]["name"];
$district_name = $admin[0]["district_name"];
$email = $admin[0]["email"];
$mobile = $admin[0]["mobile"];
$password = '';
$username = $admin[0]["username"] ?? '';

?>
<style type="text/css">
    legend {
        display: inline;
        width: auto;
    }

    ol li {
        font-size: 14px;
        font-weight: bold;
    }

    #district_name {
        opacity: 0.6;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $("#clrBtn").click(function() {
            $('#district_name').val('');
            $('#name').val('');
            $('#email').val('');
            $('#mobile').val('');
            $('#password').val('');
            $('#username').val('');
        });
    });
</script>
<div class="content-wrapper">
    <main class="rtps-container">
        <form id="myfrm" method="POST" action="<?php echo base_url('spservices/mcc_users/district_admin/update/' . $admin[0]["_id"]['$id']) ?>" enctype="multipart/form-data">
            <div class="row">
                <div class="col">
                    <div class="container  my-2">
                        <div class="row">
                            <div class="col">
                                <?php if ($this->session->flashdata('dist_admin_update_error') != null) { ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Failed!</strong> <?= $this->session->flashdata('dist_admin_update_error') ?>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php }
                                if ($this->session->flashdata('dist_admin_update_success') != null) { ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Success!</strong> <?= $this->session->flashdata('dist_admin_update_success') ?>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                <?php } //End of if 
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 ">
                                    <label for="exampleFormControlInput1" class="form-label">Select district <span class="text-danger">*</span></label>
                                    <select name="district_name" id="district_name" class="form-control">
                                        <option value="<?= $district_name ?>"><?= strlen($district_name) ? $district_name : 'Please Select' ?></option>
                                        <?php if ($districts) {
                                            foreach ($districts as $dist) {
                                                $selectedDist = ($district_name === $dist->district_name) ? 'selected' : '';
                                                echo '<option value="' . $dist->district_name . '">' . $dist->district_name . '</option>';
                                            } //End of foreach()
                                        } //End of if 
                                        ?>
                                    </select>
                                    <?= form_error("district_name") ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" value="<?= $name ?>" name="name" id="name" class="form-control" placeholder="name">
                                    <?= form_error("name") ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" value="<?= $email ?>" name="email" class="form-control" id="email" placeholder="email">
                                    <?= form_error("email") ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Mobile <span class="text-danger">*</span></label>
                                    <input type="number" value="<?= $mobile ?>" name="mobile" class="form-control" id="number" placeholder="mobile">
                                    <?= form_error("mobile") ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Username <span class="text-danger">*</span></label>
                                    <input type="text" value="<?= $username ?>" name="username" class="form-control" id="username" placeholder="username">
                                    <?= form_error("username") ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Password</label>
                                    <input type="password" value="<?= $password ?>" name="password" class="form-control" id="password" placeholder="password">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <p>Password must contain: atleast one number, one uppercase letter, one lowercase letter, one special character and atleast 8 characters long.</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <!-- <a href="<?php echo base_url() . 'spservices/mcc_users/district_admin/update/' . $admin[0]["_id"]['$id']  ?>" type="button" class="btn btn-success btn-md">Edit</a> -->
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a class="btn btn-warning" href="<?php echo base_url() . 'spservices/mcc_users/district_admin/' ?>" type="button" class="btn btn-warning btn-sm">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col">
                <div class="container  my-3">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info"><strong>All district admins</strong></div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered table-hover m-1 " id="example" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="border-top: none" scope="col">#</th>
                                        <th style="border-top: none" scope="col">District</th>
                                        <th style="border-top: none" scope="col">Name</th>
                                        <th style="border-top: none" scope="col">Email</th>
                                        <th style="border-top: none" scope="col">Mobile</th>
                                        <th style="border-top: none" scope="col">Username</th>
                                        <th style="border-top: none" scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($admins)) { ?>
                                        <?php $i = 1;
                                        foreach ($admins as $admin) {
                                        ?>
                                            <tr>
                                                <td><?= $i++; ?></td>
                                                <td><?= (isset($admin->district_name)) ? $admin->district_name : '' ?></td>
                                                <td><?= (isset($admin->name)) ? $admin->name : '' ?></td>
                                                <td><?= (isset($admin->email)) ? $admin->email : '' ?></td>
                                                <td><?= (isset($admin->mobile)) ? $admin->mobile : '' ?></td>
                                                <td><?= (isset($admin->username)) ? $admin->username : '' ?></td>
                                                <td><a href="<?php echo base_url() . 'spservices/mcc_users/district_admin/view/' . $admin->_id->{'$id'}   ?>" type="button" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a></td>
                                            </tr>
                                        <?php }  ?>
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
    </main>
</div>
<script>
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
</script>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>