<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" >

<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" >

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>

<div class="content-wrapper" style="min-height: 1376.4px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>Portals</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Portals</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List</h3>
                    <a href="<?=base_url("iservices/portals/create")?>" class="btn btn-primary btn-sm float-right" ><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;
                        Add</a>
                </div>
                <?php  if ($this->session->flashdata('status')=="error") {
                  ?>
                    <div class="alert alert-danger alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      <?php echo $this->session->flashdata('message'); ?>
                    </div>
                  <?php
                  }

                  if ($this->session->flashdata('status')=="success") {
                  ?>
                    <div class="alert alert-success alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('message'); ?>

                    </div>
                  <?php } ?>
                <!-- /.card-header -->
                <div class="card-body">
                <table class="table table-striped table-hover m-1 " id="example" style="width:100%">
<thead>
<tr>
<th style="border-top: none" scope="col">Portal Name</th>
<th style="border-top: none" scope="col">Portal No</th>
<th style="border-top: none" scope="col">Service Id</th>
<th style="border-top: none" scope="col">URL</th>
<th style="border-top: none" scope="col">External Service Id</th>
<th style="border-top: none" scope="col">Service Name</th>
<th style="border-top: none" scope="col">Timeline Days</th>
<th style="border-top: none" scope="col">Action</th>
</tr>
</thead>
<tbody>
<?php  if(!empty($portals))  { ?>

<?php foreach($portals as $portal)  {
?>
<tr>

<td><?= (isset($portal->portal_name)) ? $portal->portal_name : '' ?></td>
<td><?= (isset($portal->portal_no)) ? $portal->portal_no : '' ?></td>
<td><?= (isset($portal->service_id)) ? $portal->service_id : '' ?></td>
<td><?= (isset($portal->url)) ? $portal->url : '' ?></td>
<td><?= (isset($portal->external_service_id)) ? $portal->external_service_id : '' ?></td>
<td><?= (isset($portal->service_name)) ? $portal->service_name : '' ?></td>
<td><?= (isset($portal->timeline_days)) ? $portal->timeline_days : '' ?></td>


<td>

<!-- VIEW -->
<a href="<?php echo base_url().'iservices/portals/detail/'.($portal->_id->{'$id'})  ?>" type="button" class="btn btn-primary btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
<path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
<path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
</svg></a>

<!-- EDIT -->
<a href="<?php echo base_url().'iservices/portals/edit/'.($portal->_id->{'$id'})  ?>" type="button" class="btn btn-warning btn-sm mt-1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square " viewBox="0 0 16 16">
  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
</svg></a>

</td>




</tr>

<?php }  ?>

<?php }  else {?>

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
</div>
</div>
</div>
</section>
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
    $(document).ready(function () {
    $('#example').DataTable();
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>




