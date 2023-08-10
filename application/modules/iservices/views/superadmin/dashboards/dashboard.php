<!-- Main content -->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">

         <div class="row mb-2">
          <div class="col-sm-8">
            <h1 class="m-0 text-dark">Welcome, <?=$this->session->userdata("department_name")?></h1>
          </div>
          <div class="col-sm-4">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=base_url("iservices/superadmin/dashboard");?>">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
        
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
<section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 id="total-applications">
                  <div class="spinner-grow text-light" role="status">
  <span class="sr-only">Loading...</span>
</div>
                </h3>

                <p>Total Applications</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 id="applications-processing">
                <div class="spinner-grow text-light" role="status">
  <span class="sr-only">Loading...</span>
</div>
                </h3>

                <p>Under Process</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3 id="applications-delivered">
                <div class="spinner-grow text-light" role="status">
  <span class="sr-only">Loading...</span>
</div>
                </h3>

                <p>Completed/Delivered</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->

          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 id="applications-rejected">
                <div class="spinner-grow text-light" role="status">
  <span class="sr-only">Loading...</span>
</div>
                </h3>

                <p>Rejected</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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
 <script>
     $(function() {
         'use strict'
        //  var ticksStyle = {
        //      fontColor: '#495057',
        //      fontStyle: 'bold'
        //  }
        //  var mode = 'index'
        //  var intersect = true
        //  // Get context with jQuery - using jQuery's .get() method.
        //  var monthly_application_countCanvas = $('#monthly_application_count').get(0).getContext('2d')
        //  var monthly_application_countData = {
        //      labels: [],
        //      datasets: [{
        //              label: 'Applications Received',
        //              backgroundColor: 'rgba(60,141,188,0.9)',
        //              borderColor: 'rgba(60,141,188,0.8)',
        //              pointRadius: false,
        //              pointColor: '#3b8bba',
        //              pointStrokeColor: 'rgba(60,141,188,1)',
        //              pointHighlightFill: '#fff',
        //              pointHighlightStroke: 'rgba(60,141,188,1)',
        //              data: [0, 0, 0, 0, 0, 0, 0]
        //          },
        //          {
        //              label: 'Applications Processed',
        //              backgroundColor: 'rgba(210, 214, 222, 1)',
        //              borderColor: 'rgba(210, 214, 222, 1)',
        //              pointRadius: false,
        //              pointColor: 'rgba(210, 214, 222, 1)',
        //              pointStrokeColor: '#c1c7d1',
        //              pointHighlightFill: '#fff',
        //              pointHighlightStroke: 'rgba(220,220,220,1)',
        //              data: [0, 0, 0, 0, 0, 0, 0]
        //          },
        //      ]
        //  }
        //  var monthly_application_countOptions = {
        //      maintainAspectRatio: false,
        //      responsive: true,
        //      legend: {
        //          display: false
        //      },
        //      scales: {
        //          xAxes: [{
        //              gridLines: {
        //                  display: true,
        //              }
        //          }],
        //          yAxes: [{
        //              gridLines: {
        //                  display: false,
        //              },
        //              ticks: {
        //                  reverse: false
        //              }
        //          }]
        //      }
        //  }
         // This will get the first returned node in the jQuery collection.
        //  var monthly_application_count = new Chart(monthly_application_countCanvas, {
        //      type: 'line',
        //      data: monthly_application_countData,
        //      options: monthly_application_countOptions
        //  })
         $(document).ready(function() {
             $.get('<?= base_url("iservices/superadmin/applications/count_applications") ?>', function(data, status) {
                 $('#total-applications').text(data.total)
                 $('#applications-processing').text(data.under_process)
                 $('#applications-delivered').text(data.delivered)
                 $('#applications-rejected').text(data.rejected)

                //  monthly_application_count.data.datasets.pop();
                //  monthly_application_count.data.datasets.pop();
                 //var arr=['January', 'February', 'March', 'April', 'May', 'June', 'July'];
                //  var data_arr1 = [];
                //  var data_arr2 = [];
                //  console.log(data.application_month_wise);
                //  $(data.application_month_wise).each(function(index, val) {
                //      monthly_application_count.data.labels.push(val[0]);
                //      data_arr1.push(val[1]);
                //      data_arr2.push(val[2]);
                //  });
                //  console.log(data_arr1);
                //  console.log(data_arr2);
                //  monthly_application_count.data.datasets.push({
                //      label: 'Applications Received',
                //      backgroundColor: 'rgba(210, 214, 222, 1)',
                //      borderColor: 'rgba(210, 214, 222, 1)',
                //      pointRadius: false,
                //      pointColor: 'rgba(210, 214, 222, 1)',
                //      pointStrokeColor: '#c1c7d1',
                //      pointHighlightFill: '#fff',
                //      pointHighlightStroke: 'rgba(220,220,220,1)',
                //      data: data_arr2
                //  });
                //  monthly_application_count.data.datasets.push({
                //      label: 'Applications Processed',
                //      backgroundColor: 'rgba(60,141,188,0.9)',
                //      borderColor: 'rgba(60,141,188,0.8)',
                //      pointRadius: false,
                //      pointColor: '#3b8bba',
                //      pointStrokeColor: 'rgba(60,141,188,1)',
                //      pointHighlightFill: '#fff',
                //      pointHighlightStroke: 'rgba(60,141,188,1)',
                //      data: data_arr1
                //  });
                //  monthly_application_count.update(2000);
             });
         });
        //  var $salesChart = $('#offices-chart')
        //  var salesChart = new Chart($salesChart, {
        //      type: 'bar',
        //      data: {
        //          labels: ['SRO Mangaldoi', 'Guwahati CO', 'GMC Guwahati', 'Sivsagar DC Office'],
        //          datasets: [{
        //                  backgroundColor: '#007bff',
        //                  borderColor: '#007bff',
        //                  data: [1000, 2000, 3000, 2500]
        //              },
        //              {
        //                  backgroundColor: '#ced4da',
        //                  borderColor: '#ced4da',
        //                  data: [700, 1700, 2700, 2000]
        //              }
        //          ]
        //      },
        //      options: {
        //          maintainAspectRatio: false,
        //          tooltips: {
        //              mode: mode,
        //              intersect: intersect
        //          },
        //          hover: {
        //              mode: mode,
        //              intersect: intersect
        //          },
        //          legend: {
        //              display: false
        //          },
        //          scales: {
        //              yAxes: [{
        //                  // display: false,
        //                  gridLines: {
        //                      display: true,
        //                      lineWidth: '4px',
        //                      color: 'rgba(0, 0, 0, .2)',
        //                      zeroLineColor: 'transparent'
        //                  },
        //                  ticks: $.extend({
        //                      beginAtZero: true,
        //                      // Include a dollar sign in the ticks
        //                      callback: function(value, index, values) {
        //                          if (value >= 1000) {
        //                              value /= 1000
        //                              value += 'k'
        //                          }
        //                          return value
        //                      }
        //                  }, ticksStyle)
        //              }],
        //              xAxes: [{
        //                  display: true,
        //                  gridLines: {
        //                      display: false
        //                  },
        //                  ticks: ticksStyle
        //              }]
        //          }
        //      }
        //  })

     });
 </script>