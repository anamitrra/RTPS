<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">


<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>

<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<style>
  .parsley-errors-list {
    color: red;
  }

  .mbtn {
    width: 100% !important;
    margin-bottom: 3px;
  }

  .blk {
    display: block;
  }
.snp{
    clear:both;
     float: left;
}
  /* ul li::before {
    content: "\f005";
    font-family: "Font Awesome 5 Free";
} */
  ul {
    list-style-type: none;

  }

  ul li {
    margin-bottom: 5px;
    width: 100% !important;
  }
  .ank {
      float: right;
      margin: 5px;
  }
</style>
<div class="content-wrapper">

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url("iservices/admin/dashboard"); ?>">Home</a></li>
        <li class="breadcrumb-item active">All Services</a></li>
        <!-- <li class="breadcrumb-item active">My Applications</li> -->
      </ol>
    </div><!-- /.container-fluid -->
  </div>


 
    <div class="container">
      <div class="card my-12">
        <div class="card-header">
          <h3>All Services</h3>
        </div>
        <div class="card-body">
        <table class="table table-striped table-hover m-1 trans_table" id="example">
                          <thead>
                              <tr>
                                  <th>Service Name</th>
                                  <th>Action</th>
                              </tr>
                          </thead>
                          <tbody id="">
                              <?php foreach($service_list as $item){ 
                                // $path = trim(parse_url($item->service_url)['path'], " \/\n\t") ?? '';
                                ?>
                                <tr> 
                                    <td><?=$item->service_name->en ?></td>
                                    <td><a  class="btn btn-sm btn-outline-primary ank"   href="<?= base_url("site/service-apply/").$item->seo_url ?>">Apply</a></td>
                                </tr>
                                  <?php }?>
                           
                          </tbody>
        </table>

          

        </div>
      </div>
    </div>
</div>
</div>




<script>
  $(document).ready(function() {
    $('#example').DataTable();
  });
</script>