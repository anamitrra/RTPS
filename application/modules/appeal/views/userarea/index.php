<!-- Main content -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">DASHBOARD<?php //$this->session->userdata("department_name")
                        ?></h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("appeal/userarea"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div><!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-university"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Appeals</span>
                            <span class="info-box-number" id="total">
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
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-list"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">New Appeals</span>
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
                            <span class="info-box-text">Processed Appeals</span>
                            <span class="info-box-number" id="processed">
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
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-check-square"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Resolved Appeals</span>
                            <span class="info-box-number" id="resolved">
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
            <!-- /.row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Monthly Appeal Application Count</h5>
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
                                        <strong>Appeal Applications: Year 2020</strong>
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
            $.get('<?= base_url("appeal/appeals/count_appeals") ?>', function(data, status) {
                $('#total').text(data.total);
                $('#new').text(data.new);
                $('#processed').text(data.processed);
                $('#resolved').text(data.resolved);

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