<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">MIS DASHBOARD<?php //$this->session->userdata("department_name")
                        ?></h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("mis"); ?>">Home</a></li>
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
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 id="total">
                                <div class="spinner-grow text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </h3>

                            <p>Applications Received</p><br><br>
                        </div>
                        <div class="icon">
                            <i class="far fa-file nav-icon text-white"></i>
                        </div>
                        <a href="<?= base_url("mis/applications") ?>" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3 id="application_pending">
                                <div class="spinner-grow text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </h3>

                            <p>Applications Pending</p>

                            <span>In Time: <span id="pit"></span></span><br>
                            <span>Beyond Time: <span id="pbt"></span></span>
                        </div>
                        <div class="icon">
                            <i class="far fa-clock text-white"></i>
                        </div>
                        <a href="<?= base_url("mis/applications/pending") ?>" class="small-box-footer">More info
                            <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3 id="application_delivered">
                                <div class="spinner-grow text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </h3>

                            <p>Applications Delivered</p>
                            <span>In Time: <span id="dit"></span></span><br>
                            <span>Beyond Time: <span id="dbt"></span></span>

                        </div>
                        <div class="icon">
                            <i class="fas fa-award text-white"></i>
                        </div>
                        <a href="<?= base_url("mis/applications/delivered") ?>" class="small-box-footer">More info
                            <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3 id="application_rejected">
                                <div class="spinner-grow text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </h3>

                            <p>Applications rejected</p>
                            <span>In Time: <span id="rit"></span></span><br>
                            <span>Beyond Time: <span id="rbt"></span></span>
                        </div>
                        <div class="icon">
                            <i class="fas fa-ban text-white"></i>
                        </div>
                        <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                    </div>
                </div>
                <!-- ./col -->
            </div>

            <div class="card">
                <div class="card-body"><h4 class="text-center m-0 text-dark"> <a href="<?= base_url("mis/departments") ?>"> Departments Online </a>: <span
                                id="department_count"></span> <a href="<?= base_url("mis/online") ?>"> Total Services </a>: <span id="services_count"></span></h4>
                </div>
            </div>
            <div class="row">
                <!-- <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Monthly Application Count</h5>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>

                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div> -->
                        <!-- /.card-header -->
                        <!-- <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="text-center">
                                        <strong>Applications: Year 2020</strong>
                                    </p>
                                    <div class="chart"> -->
                                        <!-- Sales Chart Canvas -->
                                        <!-- <canvas id="monthly_application_count" height="180"
                                                style="height: 180px;"></canvas>
                                    </div> -->
                                    <!-- /.chart-responsive 
                                </div>-->

                                <!-- /.col 
                            </div>-->
                            <!-- /.row 
                        </div>-->
                        <!-- ./card-body -->
                        <!-- /.card 
                    </div>-->
                    <!-- /.col 
                </div>-->

                <!-- /.row -->
                <!-- Main row -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Leading Departments</h3>
                                <a href="javascript:void(0);">View Report</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex">
                            </div>
                            <!-- /.d-flex -->
                            <div class="position-relative mb-4">
                                <canvas id="leading-depts-graph" height="200"></canvas>
                                <section id="leading-depts-spinner" class="text-center">
                                    <div class="spinner-grow text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </section>
                            </div>
                            <div class="d-flex flex-row justify-content-end">
                                 <span class="mr-2">
                                     <i class="fas fa-square text-primary"></i> Recieved
                                 </span>
                                <span>
                                     <i class="fas fa-square text-gray"></i> Processed
                                 </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Top 5 Services</h3>
                                <a href="javascript:void(0);">View Report</a>
                            </div>
                        </div>
                        <div class="card-body">

                            <section id="top-services-graph" class="text-center">
                                <div class="spinner-grow text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </section>
                        </div>
                    </div>
                    <!-- /.card -->
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
    $(function () {
        'use strict'
        var ticksStyle = {
            fontColor: '#495057',
            fontStyle: 'bold'
     }
     var mode = 'index'
     var intersect = true
        // // Get context with jQuery - using jQuery's .get() method.
        // var monthly_application_countCanvas = $('#monthly_application_count').get(0).getContext('2d')
        // var monthly_application_countData = {
        //     labels: [],
        //     datasets: [{
        //         label: 'Applications Received',
        //         backgroundColor: 'rgba(60,141,188,0.9)',
        //         borderColor: 'rgba(60,141,188,0.8)',
        //         pointRadius: false,
        //         pointColor: '#3b8bba',
        //         pointStrokeColor: 'rgba(60,141,188,1)',
        //         pointHighlightFill: '#fff',
        //         pointHighlightStroke: 'rgba(60,141,188,1)',
        //         data: [0, 0, 0, 0, 0, 0, 0]
        //     },
        //         {
        //             label: 'Applications Processed',
        //             backgroundColor: 'rgba(210, 214, 222, 1)',
        //             borderColor: 'rgba(210, 214, 222, 1)',
        //             pointRadius: false,
        //             pointColor: 'rgba(210, 214, 222, 1)',
        //             pointStrokeColor: '#c1c7d1',
        //             pointHighlightFill: '#fff',
        //             pointHighlightStroke: 'rgba(220,220,220,1)',
        //             data: [0, 0, 0, 0, 0, 0, 0]
        //         },
        //     ]
        // }
        // var monthly_application_countOptions = {
        //     maintainAspectRatio: false,
        //     responsive: true,
        //     legend: {
        //         display: false
        //     },
        //     scales: {
        //         xAxes: [{
        //             gridLines: {
        //                 display: true,
        //             }
        //         }],
        //         yAxes: [{
        //             gridLines: {
        //                 display: false,
        //             },
        //             ticks: {
        //                 reverse: false
        //             }
        //         }]
        //     }
        // }
        // // This will get the first returned node in the jQuery collection.
        // var monthly_application_count = new Chart(monthly_application_countCanvas, {
        //     type: 'line',
        //     data: monthly_application_countData,
        //     options: monthly_application_countOptions
        // })
        $(document).ready(function () {
            $.get('<?= base_url("mis/applications/count_applications") ?>', function (data, status) {
                $('#department_count').text(data.departments);
                $('#services_count').text(data.services);
                $('#total').text(data.total);
                $('#application_delivered').text(data.application_delivered);
                $('#dit').text(data.application_delivered_in_time);
                $('#dbt').text(data.application_delivered_beyond_time);
                $('#pit').text(data.application_pending_in_time);
                $('#pbt').text(data.application_pending_beyond_time);
                $('#rit').text(data.application_rejected_in_time);
                $('#rbt').text(data.application_rejected_beyond_time);
                $('#application_rejected').text(data.application_rejected);
                $('#application_pending').text(data.application_pending);
                monthly_application_count.data.datasets.pop();
                monthly_application_count.data.datasets.pop();
                //var arr=['January', 'February', 'March', 'April', 'May', 'June', 'July'];
                var data_arr1 = [];
                var data_arr2 = [];
                console.log(data.application_month_wise);
                $(data.application_month_wise).each(function (index, val) {
                    monthly_application_count.data.labels.push(val[0]);
                    data_arr1.push(val[1]);
                    data_arr2.push(val[2]);
                });
                console.log(data_arr1);
                console.log(data_arr2);
                monthly_application_count.data.datasets.push({
                    label: 'Applications Received',
                    backgroundColor: 'rgba(210, 214, 222, 1)',
                    borderColor: 'rgba(210, 214, 222, 1)',
                    pointRadius: false,
                    pointColor: 'rgba(210, 214, 222, 1)',
                    pointStrokeColor: '#c1c7d1',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data: data_arr2
                });
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
        var $salesChart = $('#offices-chart')
        var salesChart = new Chart($salesChart, {
            type: 'bar',
            data: {
                labels: ['SRO Mangaldoi', 'Guwahati CO', 'GMC Guwahati', 'Sivsagar DC Office'],
                datasets: [{
                    backgroundColor: '#007bff',
                    borderColor: '#007bff',
                    data: [1000, 2000, 3000, 2500]
                },
                    {
                        backgroundColor: '#ced4da',
                        borderColor: '#ced4da',
                        data: [700, 1700, 2700, 2000]
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: mode,
                    intersect: intersect
                },
                hover: {
                    mode: mode,
                    intersect: intersect
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        // display: false,
                        gridLines: {
                            display: true,
                            lineWidth: '4px',
                            color: 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks: $.extend({
                            beginAtZero: true,
                            // Include a dollar sign in the ticks
                            callback: function (value, index, values) {
                                if (value >= 1000) {
                                    value /= 1000
                                    value += 'k'
                                }
                                return value
                            }
                        }, ticksStyle)
                    }],
                    xAxes: [{
                        display: true,
                        gridLines: {
                            display: false
                        },
                        ticks: ticksStyle
                    }]
                }
            }
        })
        //  Load top services
        $.get('<?= base_url("mis/applications/top_services"); ?>', function (data, status) {
            let content = '';
            data.forEach(element => {
                content += `<div class="progress-group">
        ${element[0]}
        <span class="float-right"><b>${element[1]}</b>/${element[2]}</span>
        <div class="progress progress-md">
        <div class="progress-bar" style="width: ${element[1] / element[2] * 100}%"></div>
        </div>
        </div>`;
            });
            $('#top-services-graph').html(content);
        });
        //  Load leading departments
        $.get('<?= base_url("mis/applications/leading_departments"); ?>', function (data, status) {
            // Hide the spinner
            $('#leading-depts-spinner').hide();
            const labels = [];
            const total = [];
            const processed = [];
            data.forEach(element => {
                labels.push(element[0]);
                processed.push(element[1]);
                total.push(element[2]);
            });
            var $salesChart = $('#leading-depts-graph')
            var salesChart = new Chart($salesChart, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        backgroundColor: '#007bff',
                        borderColor: '#007bff',
                        data: total
                    },
                        {
                            backgroundColor: '#ced4da',
                            borderColor: '#ced4da',
                            data: processed
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        mode: mode,
                        intersect: intersect
                    },
                    hover: {
                        mode: mode,
                        intersect: intersect
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            // display: false,
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            },
                            ticks: $.extend({
                                beginAtZero: true,
                                // Include a dollar sign in the ticks
                                callback: function (value, index, values) {
                                    if (value >= 1000) {
                                        value /= 1000
                                        value += 'k'
                                    }
                                    return value
                                }
                            }, ticksStyle)
                        }],
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: false
                            },
                            ticks: ticksStyle
                        }]
                    }
                }
            })
        });
    });
</script>