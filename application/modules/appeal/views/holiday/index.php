<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')?>">
<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')?>">
<link rel="stylesheet" href="<?= base_url("assets/plugins/datepicker/datepicker3.css") ?>">
<script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url("assets/plugins/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script src="<?=base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>
<style>
    .parsley-errors-list {
        list-style-type: none;
        padding-left: 0px;
    }

    .parsley-errors-list li {
        color: red;
    }
</style>
<div class="content-wrapper" style="min-height: 1376.4px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>First Appeals</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=base_url('appeal/dashboard')?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Holiday List</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Holiday List</h3>
                    <a href="#" class="float-right btn btn-sm btn-success" data-toggle="modal" data-target="#addHolidayListModel">Add Holiday</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <?php
                        if($this->session->flashdata('errors')){
                    ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error :</strong> <?=$this->session->flashdata('errors')?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php
                        }
                    ?>
                    <?php
                        if($this->session->flashdata('success')){
                    ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success :</strong> <?=$this->session->flashdata('success')?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php
                        }
                    ?>
                    <?php
                        if($this->session->flashdata('fail')){
                    ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Failed :</strong> <?=$this->session->flashdata('fail')?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php
                        }
                    ?>
                    <table id="holidayListDT" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Title</th>
                            <th>Year</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th width="5%">#</th>
                            <th>Title</th>
                            <th>Year</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="addHolidayListModel" class="modal fade show" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Holiday</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="addHolidayForm" method="POST" action="<?=base_url('appeal/holiday/add')?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" id="title" placeholder="Enter title" required>
                    </div>
                    <div class="form-group">
                        <label for="year">Year</label>
                        <input type="text" class="form-control" name="year" list="yearList" id="year" placeholder="YYYY" maxlength="4" minlength="4" required>
                        <datalist id="yearList">
                            <option value="<?=date('Y')?>">
                            <option value="<?=date('Y')+1?>">
                            <option value="<?=date('Y')+2?>">
                        </datalist>
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="text" class="form-control datepicker" name="date" id="date" placeholder="dd/mm/YYYY" autocomplete="off" readonly="readonly" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="reset" class="btn btn-default" >Reset</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="updateHolidayListModel" class="modal fade show" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Holiday</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="updateHolidayForm" method="POST" action="<?=base_url("appeal/holiday/update")?>">
                <input type="hidden" name="id" value="" id="holidayId">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" id="titleU" placeholder="Enter title" required>
                    </div>
                    <div class="form-group">
                        <label for="year">Year</label>
                        <input type="text" class="form-control" name="year" list="yearList" id="yearU" placeholder="YYYY" maxlength="4" minlength="4" required>
                        <datalist id="yearList">
                            <option value="<?=date('Y')?>">
                            <option value="<?=date('Y')+1?>">
                            <option value="<?=date('Y')+2?>">
                        </datalist>
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="text" class="form-control datepicker" name="date" id="dateU" placeholder="dd/mm/YYYY" autocomplete="off" readonly="readonly" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="reset" class="btn btn-default" >Reset</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#addHolidayForm').parsley();
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            multidate: true
        });
        var st = $('#traveling_as').val();
        var table = $('#holidayListDT').DataTable({
            "processing": true,
            language: {
                processing: '<div class="lds-ripple"><div></div><div></div></div>',
                searchPlaceholder: "Search by Title or Year"
            },
            "pagingType": "full_numbers",
            "pageLength": 25,
            "serverSide": true,
            "orderMulti": false,
            "columnDefs": [{
                "width": "15%",
                "targets": 0
            },
                {
                    "targets": 4,
                    "orderable": false
                }
            ],
            "columns": [
                {
                    "data": "sl_no"
                },
                {
                    "data": "title"
                },
                {
                    "data": "year"
                },
                {
                    "data": "dates"
                },
                {
                    "data": "action"
                },
            ],
            "ajax": {
                url: "<?php echo site_url("appeal/holiday/get_records") ?>",
                type: 'POST',
                beforeSend: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
                },
                complete: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
                }
            },
        });

        $('#year, #yearU').on('change',function(){
            let selectedYear = $(this).val();
            let minDate = new Date(selectedYear, 1, 1);
            let maxDate = new Date(selectedYear, 12, 31);
            $('.datepicker').datepicker('setStartDate', minDate);
            $('.datepicker').datepicker('setEndDate', maxDate);
        });
    });
    function openEditHolidayModal(that){
        $('#holidayId').val($(that).data('id'));
        let tr = $(that).closest('tr').find('td');
        let title = tr.eq(1).text();
        let year = tr.eq(2).text();
        let dateArray = tr.eq(3).text().split(',');
        // $('#dateU').datepicker({date:dateArray}).datepicker('update').children("input").val(dateArray);
        $('#dateU').datepicker('setDates',dateArray);
        $('#titleU').val(title);
        $('#yearU').val(year);
        $('#updateHolidayListModel').modal('show');
    }
</script>

