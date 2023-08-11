<?php
$userdata = ($this->session->userdata());
$username = $userdata['username'];
// echo ($username);
// return;


?>
<style>
    .info-box .info-box-text, .info-box .progress-description {
        text-overflow: unset!important;
        white-space: unset!important;
    }
</style>
<!-- Main content -->
<!-- Content Wrapper. Contains page content -->
<!-- <?php if((!$username)){
    echo '<div class="text-danger">No user name</div>';
}
else{
    echo "User name present";
}
?> -->

<?php
if (empty($username)) {
    // Show the modal
    echo '<script>';
    echo '$(document).ready(function(){';
    echo '$("#myModal").modal("show");';
    echo '});';
    echo '</script>';
}
?>

<!--No Username Modal -->
<div class="modal fade" id="myModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title font-weight-bold" id="myModalLabel">Dear User!</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span> -->
                </button>
            </div>
            <div class="modal-body">
                <p class="font-weight-bold">Dear User, you are requested to set your unique username by clicking on the proceed link below. Please note that this is a one time activity which will enable you to login with the username in future.</p>
            </div>
            <div class="modal-footer">
                <!-- <button type="submit" href="<?=base_url("appeal/profile");?>" type="button" class="btn btn-info">Proceed</button> -->
                <!-- <a href="<?=base_url("appeal/profile");?>" class="dropdown-item">
          <i class="fas fa-user-alt mr-2"></i> Profile
          </a> -->
          <a href="<?=base_url("appeal/profile");?>">
  <button type="button" class="btn btn-info">Proceed</button>
</a>

            </div>
        </div>
    </div>
</div>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">APPEAL DASHBOARD<?php //$this->session->userdata("department_name")
                                                                ?></h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("appeal/dashboard"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div><!-- Main content -->
    <section class="content">
        <div class="container-fluid">
        <?php if(($this->session->userdata("role")->slug !='SA')  && empty($this->session->userdata('location')) ) { ?>
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
                    <div class="info-box">
                        <!-- <span class="info-box-icon bg-info elevation-1"><i class="fas fa-warning"></i></span> -->
                        <div class="info-box-content">
                            <span class="info-box-text">There is no official mapping for Logged user. To view the appeal count user must be mapped with a location </span>
                           
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
            </div>
            <?php }else{ ?>
 <!-- Info boxes -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-university"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Appeals</span>
                            <a href="<?= base_url("appeal/reports/total") ?>"><span class="info-box-number" id="total">
                                    <div class="spinner-grow text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </span></a>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-list"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Appeal initiated</span>
                            <span class="info-box-number" id="new">
                                <div class="spinner-grow text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fa fa-file"></i></span>
                        <div class="info-box-content">
                       <span class="info-box-text">Pending 30 > < 45 </span>
                       <a href="<?=base_url("appeal/reports/pending")?>"> <span class="info-box-number" id="pending_appeals_beyond_30days_not_beyond_45days">
                                        <div class="spinner-grow text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </span></a>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-check-square"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pending beyond 45 days</span>
                            <span class="info-box-number" id="pending_appeals_beyond_45days">
                                <div class="spinner-grow text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-university"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Disposed Within 30 days</span>
                            <a href="<?= base_url("appeal/reports/disposed_within_30 ") ?>"> <span class="info-box-number" id="disposed_appeals_within_30days">
                                <div class="spinner-grow text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <!-- /.col -->
                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fa fa-file"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Rejected Appeals</span>
                            <a href="<?= base_url("appeal/reports/rejected") ?>"><span class="info-box-number" id="rejected">
                                    <div class="spinner-grow text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </span></a>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-check-square"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Resolved Appeals</span>
                            <a href="<?= base_url("appeal/reports/resolved") ?>"><span class="info-box-number" id="resolved">
                                    <div class="spinner-grow text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </span></a>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Service Wise Appeal Count</h5>
                            <div class="card-tools">
                                <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>

                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button> -->
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th>Service</th>
                                                <th style="width: 40px">Appeal Count</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody">
                                            <tr>
                                                <td colspan="3"><span class="info-box-number" id="resolved">
                                                        <div class="spinner-grow text-primary" role="status">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                    </span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- ./card-body -->
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
            </div>
            <!-- /.row -->
            <?php }
            ?>
           
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Monthly Appeal Application Count (All Appeals)</h5>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>

                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="text-center">
                                        <strong>Appeal Applications: Year <?=date('Y')?></strong>
                                    </p>
                                    <div class="chart">
                                        <!-- Sales Chart Canvas -->
                                        <canvas id="monthly_application_count" height="380" style="height: 280px;"></canvas>
                                    </div>
                                    <!-- /.chart-responsive -->
                                </div>

                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- ./card-body -->
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!--/. container-fluid -->
    </section>
    <!-- /.content -->
</div>
<script src="<?= base_url("assets/"); ?>plugins/chart.js/Chart.min.js"></script>
<script>
    $(function() {
        'use strict'
        // Get context with jQuery - using jQuery's .get() method.
        var monthly_application_countCanvas = $('#monthly_application_count').get(0).getContext('2d')
        var monthly_application_countData = {
            labels: [],
            datasets: [{
                    label: 'Applications Received',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    pointRadius: false,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: [0, 0, 0, 0, 0, 0, 0]
                },
                {
                    label: 'Applications Processed',
                    backgroundColor: 'rgba(210, 214, 222, 1)',
                    borderColor: 'rgba(210, 214, 222, 1)',
                    pointRadius: false,
                    pointColor: 'rgba(210, 214, 222, 1)',
                    pointStrokeColor: '#c1c7d1',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data: [0, 0, 0, 0, 0, 0, 0]
                },
            ]
        }
        var monthly_application_countOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        display: true,
                    }
                }],
                yAxes: [{
                    gridLines: {
                        display: false,
                    },
                    ticks: {
                        reverse: false
                    }
                }]
            }
        }
        // This will get the first returned node in the jQuery collection.
        var monthly_application_count = new Chart(monthly_application_countCanvas, {
            type: 'bar',
            data: monthly_application_countData,
            options: monthly_application_countOptions
        })
        $(document).ready(function() {
            $.get('<?= base_url("appeal/appeals/count_appeals_location") ?>', function(data, status) {
                $('#total').text(data.total);
                $('#new').text(data.new);
                $('#pending_appeals_beyond_30days_not_beyond_45days').text(data.pending_appeals_beyond_30days_not_beyond_45days);
                $('#pending_appeals_beyond_45days').text(data.pending_appeals_beyond_45days);
                $('#disposed_appeals_within_30days').text(data.disposed_appeals_within_30days);
                $('#resolved').text(data.resolved);
                $('#rejected').text(data.rejected);

            });
            $.get('<?= base_url("appeal/api/service_wise_appeal_count") ?>', function(data, status) {
                $('#tbody').empty();
                $(data.data).each(function(key, val) {
                    console.log(val);
                    $('#tbody').append('<tr><td>' + (key + 1) + '</td><td>' + val.service_name[0] + '</td><td>' + val.total + '</td></tr>');
                });
            });
            $.get('<?= base_url("appeal/appeals/application_month_wise") ?>', function(data, status) {
                monthly_application_count.data.datasets.pop();
                monthly_application_count.data.datasets.pop();
                //var arr=['January', 'February', 'March', 'April', 'May', 'June', 'July'];
                var data_arr1 = [];
                var data_arr2 = [];
                console.log(data.application_month_wise);
                $(data.application_month_wise).each(function(index, val) {
                    monthly_application_count.data.labels.push(val[0]);
                    data_arr1.push(val[1]);
                    data_arr2.push(val[2]);
                });
                console.log(data_arr1);
                console.log(data_arr2);

                monthly_application_count.data.datasets.push({
                    label: 'Applications Processed',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    pointRadius: false,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: data_arr1
                });
                monthly_application_count.update(2000);
            });
        });

    });
</script>