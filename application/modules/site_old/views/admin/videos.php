<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')?>">
<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')?>">

<script src="<?=base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>


<!-- Modal -->
<div class="modal fade" id="videoModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <?= form_open( base_url("site/admin/videos/delete_video") ); ?>

        <div class="modal-body">
            <h4>Delete this Video ?</h4>

            <input type="hidden" name="object_id" value="">
        
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn btn-secondary">YES</button>
            <button type="button" class="btn btn-light" data-dismiss="modal">NO</button>
        </div>

      </form>


    </div>
  </div>
</div>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">

            <div class="row mb-4">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">Videos</h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("site/admin/dashboard"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Videos</li>
                    </ol>
                </div>
            </div>
          

            <section class="text-right">
                <a class="btn btn-secondary" href="<?= base_url("site/admin/videos/upload_video"); ?>" role="button">
                    <i class="fas fa-upload mr-2"></i>Upload New Video
                </a>
            </section>
            

            <!-- Action Success/Fail alert messages  -->            
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show pl-2 text-center" role="alert">
                    <?= $this->session->flashdata('success'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show pl-2 text-center" role="alert">
                    <?= $this->session->flashdata('error'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>


            <section class="mt-4">
                <table id="my-table" class="table table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>#Sl No.</th>
                            <th>Video Name</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#Sl No.</th>
                            <th>Video Name</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </section>

        </div><!-- /.container-fluid -->
    </div><!-- Main content -->
   
    <!-- /.content -->
</div>


<script>

$(document).ready(function(){

    const table = $('#my-table')
        .DataTable({
            pageLength: 25,
            order: [[0, 'asc']],
            scrollX: true,
            responsive: true,
            processing: true,
            deferRender: true,
            ajax: {
                url: '<?= base_url("site/admin/videos/all_videos_api"); ?>',
                dataSrc: ''
            },
            columns: [
                { data: 'sl_no' },
                { data: 'name.en' },
                { 
                    data: 'category.title.en',
                },
                { 
                    data: '_id',
                    render: function(data, type, row, meta) {
                        
                        return type === 'display' ? `
                        <a title="view/edit" href="<?= base_url("site/admin/videos/upload_video/")?>${data['$id']}">

                        
                        <i class="fas fa-edit fa-lg mr-2"></i>
                        </a>

                        <a  title="delete" class="delete text-danger" data-ob_id="${data['$id']}" href="#">
                        <i class="far fa-trash-alt fa-lg"></i>
                        </a>
                        
                        ` : data;
                    }
                },
              
            ],
        });


        // Delete action handler
        $('#my-table tbody').on('click', 'a.delete', function (e) { 
            e.preventDefault();

            $('[name="object_id"]').val($(this).data('ob_id'));
          
            $('#videoModel').modal('show');
        });

});

</script>
