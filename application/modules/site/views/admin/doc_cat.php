<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')?>">
<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')?>">

<script src="<?=base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>


<!-- Modal -->
<div class="modal fade" id="docsModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <?= form_open( base_url("site/admin/document_category/delete_doc_cat") ); ?>

        <div class="modal-body">
            <h4>Delete this Document Category ?</h4>
            <p>This will delete relevant documents as well.</p>

            <input type="hidden" name="short_name" value="">
        
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
                    <h1 class="m-0 text-dark">Document Categories</h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("site/admin/dashboard"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Document Categories</li>
                    </ol>
                </div>
            </div>
          
            <section class="text-right">
            
                <a class="btn btn-secondary " href="<?= base_url("site/admin/document_category/add_doc_category"); ?>" role="button">
                <i class="fa fa-plus-square mr-2" aria-hidden="true"></i>
                Add New Category</a>
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
                            <th>Document Category Name</th>
                            <th>Short Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#Sl No.</th>
                            <th>Document Category Name</th>
                            <th>Short Name</th>
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
            processing: true,
            deferRender: true,
            ajax: {
                url: '<?= base_url("site/admin/document_category/doc_category_api"); ?>',
                dataSrc: ''
            },
            columns: [
                { data: 'sl_no' },
                { data: 'title.en' },
                { 
                    data: 'short_name',
                },
                { 
                    data: {},
                    render: function(data, type, row, meta) {
                        
                        return type === 'display' ? `
                        <a title="view/edit" href="<?= base_url("site/admin/document_category/add_doc_category/")?>${row.short_name}">


                        

                        <i class="fas fa-edit fa-lg mr-2"></i>
                        </a>

                        <a title="delete" class="delete" data-short_name="${row.short_name}" href="#">
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

            $('[name="short_name"]').val($(this).data('short_name'));
            
            $('#docsModel').modal('show');
        });

});

</script>