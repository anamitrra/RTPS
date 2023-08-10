<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">RTPS SYSTEM DASHBOARD<?php
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
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <select name="departments" id="departments" class="form-control">
                                <option value="">Select Departments</option>
                                <?php
                                if (!empty($departments)) {
                                    foreach ($departments as $dep) { ?>
                                        <option value="<?= $dep->department_id ?>"><?= $dep->department_name ?></option>
                                <?php }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="offices" id="offices" class="form-control">
                                <option value="">Select Office</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="services" id="services" class="form-control d-none">
                                <option>Select Services</option>
                            </select>
                        </div>
                    </div>
                    <!-- <h4 class="text-center m-0 text-dark"> Departments Online : <span
                                id="department_count"></span> Total Services : <span id="services_count"></span></h4> -->
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-6">
                    <div class="small-box bg-white">
                        <div class="inner">
                            <p>Total Application :<span id="total_applications"></span></p>

                            <canvas id="myChart1" width="5" height="3"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-6">
                    <!-- small box -->
                    <div class="small-box bg-white">
                        <div class="inner">
                            <p>Delivery Timeline</p>
                            <canvas id="myChart2" width="5" height="3"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- ./col -->
                <div class="col-lg-6 col-6">
                    <!-- small box -->
                    <div class="small-box bg-white">
                        <div class="inner">
                            <p>Pendency Status</p>
                            <canvas id="myChart3" width="5" height="3"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-6">
                    <div class="small-box bg-white">
                        <div class="inner">
                            <p>Disposal - Service Status</p>
                            <canvas id="myChart4" width="5" height="3"></canvas>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
        </div>
        <!--/. container-fluid -->
    </section>
    <!-- /.content -->
</div>
<script src="<?= base_url("assets/"); ?>plugins/chart.js/Chart.min.js"></script>
<script src="<?= base_url("assets/"); ?>js/notify.min.js"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<script>
    var ctx = document.getElementById('myChart1').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [
                'Disposed',
                'Pending'
            ],
            datasets: [{
                label: 'Applications Received',
                data: [0, 0],
                backgroundColor: [
                    'rgb(10, 128, 43)',
                    'rgb(133, 129, 118)'
                ],
                hoverOffset: 4
            }]
        },
        options: {
            tooltips: {
                enabled: true,
                mode: 'single',
                callbacks: {
                    label: function(tooltipItems, data) {
                        return data.datasets[0].data[tooltipItems.index] + '%';
                    }
                }
            }
        }
    });
    var chart2 = document.getElementById('myChart2').getContext('2d');
    var myChart2 = new Chart(chart2, {
        type: 'doughnut',
        data: {
            labels: [
                'After Timeline',
                'Within Timeline'
            ],
            datasets: [{
                label: 'Delivery Timeline',
                data: [0, 0],
                backgroundColor: [
                    'rgb(247, 27, 7)',
                    'rgb(6, 25, 150)'
                ],
                hoverOffset: 4,
            }]
        },
        options: {
            tooltips: {
                enabled: true,
                mode: 'single',
                callbacks: {
                    label: function(tooltipItems, data) {
                        return data.datasets[0].data[tooltipItems.index] + '%';
                    }
                }
            }
        }
    });
    var chart3 = document.getElementById('myChart3').getContext('2d');
    var myChart3 = new Chart(chart3, {
        type: 'doughnut',
        data: {
            labels: [
                'After Timeline',
                'Within Timeline'
            ],
            datasets: [{
                label: 'Pendency Status',
                data: [0, 0],
                backgroundColor: [
                    'rgb(247, 27, 7)',
                    'rgb(10, 128, 43)'
                ],
                hoverOffset: 4
            }]
        },
        options: {
            tooltips: {
                enabled: true,
                mode: 'single',
                callbacks: {
                    label: function(tooltipItems, data) {
                        return data.datasets[0].data[tooltipItems.index] + '%';
                    }
                }
            }
        }
    });
    var chart4 = document.getElementById('myChart4').getContext('2d');
    var myChart4 = new Chart(chart4, {
        type: 'doughnut',
        data: {
            labels: [
                'Service Delivered',
                'Service Rejected'
            ],
            datasets: [{
                label: 'Disposal -  Status',
                data: [0, 0],
                backgroundColor: [
                    'rgb(83, 150, 6)',
                    'rgb(133, 129, 118)',
                ],
                hoverOffset: 4
            }]
        },
        options: {
            tooltips: {
                enabled: true,
                mode: 'single',
                callbacks: {
                    label: function(tooltipItems, data) {
                        return data.datasets[0].data[tooltipItems.index] + '%';
                    }
                }
            }
        }
    });

    function fireSweetalert() {
        swal.fire({
            html: '<h5>Processing...</h5>',
            showConfirmButton: false,
            allowOutsideClick: () => !Swal.isLoading(),
            onOpen: function() {
                Swal.showLoading();
            }
        });
    }

    function loadServices(deparment_id) {
        let url = '<?= base_url("mis/get_services/") ?>' + deparment_id;
        $.get(url, function(data, status) {
            Swal.close();
            let res = JSON.parse(data)
            if (res) {
                $('#services').empty();
                $('#services').append(`<option value="">All Services</option>`);
                res.forEach(function(item, index) {
                    $('#services').append(`<option value="${item.service_id}">
                                   ${item.service_name}
                                 </option>`);
                });
            }
        })
    }

    function loadOffices(deparment_id) {
        let url = '<?= base_url("mis/api/department/getoffices/") ?>' + deparment_id;
        $.get(url, function(data, status) {
            Swal.close();
            let res = (data)
            console.log(data)
            if (res.status) {
                Swal.close();
                $('#services').removeClass('d-none');

                $('#offices').empty();
                $('#offices').append(`<option value=""> Select Office</option>`);
                console.log(res.data);
                res.data.forEach(function(item, index) {
                    $('#offices').append(`<option value="${item.office_name}">
                                   ${item.office_name}
                                 </option>`);
                });
            } else {
                console.log("No Offices Found for the Department")
            }
        })
    }
    $(document).ready(function() {

        $("#departments").on('change', function() {
            fireSweetalert();
            const deparment_id = $("#departments").val();
            loadServices(deparment_id);
            loadOffices(deparment_id);
        })
        $("#offices,#services").on('change', function() {
            let office_name = $('#offices').val();
            let service_id = $('#services').val();
            let department_id = $('#departments').val();
            let data = {
                'office_name': btoa(office_name),
                'service_id': service_id,
                'department_id': department_id,
            };
            let url = '<?= base_url("mis/api/office/getofficewisedata") ?>';
            $.post(url, data, function(res, status) {
                Swal.close();
                if (res.status) {
                    $('#total_applications').empty().append(res.data.total_received);

                    //Chart 1 Data
                    myChart.data.datasets[0].data.pop();
                    myChart.data.datasets[0].data.pop();
                    myChart.data.datasets[0].data.push(Math.round(((res.data.delivered + res.data.rejected)/(res.data.delivered + res.data.rejected + res.data.pending))*100));
                    myChart.data.datasets[0].data.push(Math.round(((res.data.pending/(res.data.delivered + res.data.rejected + res.data.pending))*100)));
                    myChart.update(1000);
                    //Chart 2 Data
                    myChart2.data.datasets[0].data.pop();
                    myChart2.data.datasets[0].data.pop();
                    myChart2.data.datasets[0].data.push(Math.round(((res.data.delivered - res.data.timely_delivered)/res.data.delivered)*100));
                    myChart2.data.datasets[0].data.push(Math.round((res.data.timely_delivered/res.data.delivered)*100));
                    myChart2.update(1000);
                    //Chart 3 Data
                    myChart3.data.datasets[0].data.pop();
                    myChart3.data.datasets[0].data.pop();
                    myChart3.data.datasets[0].data.push(Math.round(((res.data.pending - res.data.pit)/((res.data.pending - res.data.pit)+res.data.pending))*100));
                    myChart3.data.datasets[0].data.push((Math.round(res.data.pit/((res.data.pending - res.data.pit)+res.data.pending))*100));
                    myChart3.update(1000);
                    //Chart 4 Data
                    myChart4.data.datasets[0].data.pop();
                    myChart4.data.datasets[0].data.pop();
                    myChart4.data.datasets[0].data.push(Math.round((res.data.delivered/(res.data.delivered+res.data.rejected))*100));
                    myChart4.data.datasets[0].data.push(Math.round((res.data.rejected/(res.data.delivered+res.data.rejected))*100));
                    myChart4.update(1000);
                } else {
                    $("#services").notify(
                        "No Data Found for the service", {
                            position: "top"
                        }
                    );
                    // Swal.fire({
                    //     icon: 'error',
                    //     title: 'Oops...',
                    //     text: 'No Data Found for the service'                        
                    // })
                    myChart.data.datasets.forEach((dataset) => {
                        dataset.data.pop();
                    });
                    myChart.data.datasets.forEach((dataset) => {
                        dataset.data.push(0);
                    });
                    myChart2.data.datasets.forEach((dataset) => {
                        dataset.data.pop();
                    });
                    myChart2.data.datasets.forEach((dataset) => {
                        dataset.data.push(0);
                    });
                    myChart3.data.datasets.forEach((dataset) => {
                        dataset.data.pop();
                    });
                    myChart3.data.datasets.forEach((dataset) => {
                        dataset.data.push(0);
                    });
                    myChart4.data.datasets.forEach((dataset) => {
                        dataset.data.pop();
                    });
                    myChart4.data.datasets.forEach((dataset) => {
                        dataset.data.push(0);
                    });
                    console.log("No data FOund")
                }
            })
        })
    })
</script>