<!-- <h1>Hello</h1> -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.css">

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.js"></script>

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

    /* search style */
    label[for="search"],
    label[for="el-search"] {
        transform: translateX(2rem);
    }

    [type="search"]:not(.dataTables_wrapper input, #appl-no) {
        border: 1px solid #362f2d;
        border-radius: 0.25rem;
        outline: none;
        background-color: rgba(199, 178, 153, 0.2);

        padding: 0.5rem 1rem 0.5rem 3rem;
        width: 55%;
    }

    .service-serach-btn {
        padding: 0.5rem 2rem;
        background-color: #362f2d;
        color: whitesmoke;
        font-size: 1rem;
        transform: translateX(-1.5rem);
        border-radius: 0.5rem;
    }

    .service-serach-btn:focus,
    .service-serach-btn:hover {
        color: whitesmoke;
        box-shadow: none;
    }

    /* search style end */

    /* For categorywise service list */
    .service-categories>div {
        background-color: rgb(246 223 195 / 20%);
    }

    .service-categories h4 {
        color: #362f2d;
    }

    .service-categories img {
        align-self: flex-start;
    }

    /* For mobile phones: */
    @media only screen and (max-width: 768px) {
        .service-categories {
            flex-direction: column;
            row-gap: 1em;
        }
    }

  .table-container {
     overflow-x: auto;
    }
    table {
    width: 100%;
    border-collapse: collapse;
    }

    th, td {
    padding: 8px;
    border: 1px solid #ddd;
    }

    @media (max-width: 600px) {
        th, td {
            font-size: 12px;
        }
    }

    
.loader_2 {
    position: static;
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid blue;
  border-bottom: 16px solid blue;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
  margin:auto
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

</style>

<?php
$lang = "en";
?>
<div class="container">
    <div class="row">
        <div class="col-md-12 mt-3 text-center">
            <?php if ($this->session->userdata('message') <> '') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success</strong>
                    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
        </div>
        <div class="col-md-12 mt-3 text-center">
            <?php if ($this->session->userdata('errmessage') <> '') { ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Warning</strong>
                    <?php echo $this->session->userdata('errmessage') <> '' ? $this->session->userdata('errmessage') : ''; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
        </div>

    </div>
    <!-- Search Service, Login panel -->
    <div class="container-xxl">
        <div class="container">
            <div class="row">
                <div class="col-12 ">
                    <form action="<?= base_url('site/search') ?>" method="get" id="service-search" class="d-flex flex-column flex-md-row justify-content-md-center align-items-baseline">
                        <label for="search" class="search-icons d-none d-md-inline">
                            <img src="https://rtps.assam.gov.in/assets/site/theme1/images/esearch.webp" alt="Serices icon" width="20" height="20">
                        </label>
                        <input class="mb-2 mb-md-0" autocomplete="off" type="search" name="service_name" list="services" id="search" placeholder="Are you looking for a particular service?" required="" title="Provide at least 3 characters">
                        <button class="btn align-self-stretch service-serach-btn" type="submit">
                            Search
                        </button>
                    </form>
                    <datalist id="services">
                        <?php foreach ($services_list as $service) : ?>
                            <option value="<?= $service->service_name->en ?>">
                            <?php endforeach; ?>
                    </datalist>


                    <!-- Categorywise Services List -->

                    <section class="my-5 d-flex justify-content-evenly service-categories">

                        <div class="w-auto border border-1 rounded-4 shadow p-2">
                            <a class="text-decoration-none" href="<?= base_url('site/online/citizen') ?>" title="Click here to go to Citizen Services">
                                <div class="d-flex p-3">
                                    <img src="<?= base_url('assets/site/sewasetu/assets/images/citizen.png') ?>" alt="citizen icon">
                                    <div class="flex-grow-1 text-start">
                                        <span class="d-inline-block mb-1 ms-3 fw-bold fs-4 text-danger" id="citizen">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </span>
                                        <h4 class="ms-3 fw-bold">Citizen Services</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="w-auto border border-1 rounded-4 shadow p-2">
                            <a class="text-decoration-none" href="<?= base_url('site/online/eodb') ?>" title="Click here to go to Citizen Services">
                                <div class="d-flex p-3">
                                    <img src="<?= base_url('assets/site/sewasetu/assets/images/business.png') ?>" alt="business icon">
                                    <div class="flex-grow-1 text-start">
                                        <span id="business" class="d-inline-block mb-1 ms-3 w-100 fw-bold fs-4 text-danger">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </span>
                                        <h4 class="ms-3 fw-bold">Business Services</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="w-auto border border-1 rounded-4 shadow p-2">
                            <a class="text-decoration-none" href="<?= base_url('site/online/utility') ?>" title="Click here to go to Utility Services">
                                <div class="d-flex p-3">
                                    <img src="<?= base_url('assets/site/sewasetu/assets/images/utility.png') ?>" alt="citizen icon">
                                    <div class="flex-grow-1 text-start">
                                        <span id="utility" class="d-inline-block mb-1 ms-3 w-100 fw-bold fs-4 text-danger">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </span>
                                        <h4 class="ms-3 fw-bold">Utility Services</h4>
                                    </div>
                                </div>
                            </a>
                        </div>


                    </section>

                    <!-- END -->
                  
                    <h5>This is the new version of your application list. For Old Version Please click   <a class=" btn btn-outline-primary" href="<?=base_url('iservices/myapplications/switchview/old')?>">Here<a/> </h5>
                    
                    
                    <div class="card">
                       
                        <div class="card-body">
                            <div class="row">
                              
                                <div class="col-12 col-md-3">
                                    <label for="date-range">Search by Application Ref No</label>
                                    <input type="text" class="form-control" id="app_ref_no" placeholder="Application Ref No"/>
                                </div>
                                <div class="col-12 col-md-2">
                                <label for="date-range"></label>
                                    <button class="btn btn-primary form-control" id="btn_find">Find</button>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="date-range">Filter By Status</label>
                                    <select class="form-control" name="app_status" id="app_status">
                                        <option selected value="P">Under Process</option>
                                        <option value="D">Delivered</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="date-range">Filter By Date</label>
                                    <input id="date-range" class="form-control" type="text" name="daterange" value=""/>
                                    <input type="hidden" id="start_date" name="start_date" value="" />
                                    <input type="hidden" id="end_date" name="end_date" value="" />
                                </div>
                            </div>
                        </div>
                    </div>

                  
                    <div class="row">
                        <div class="col-sm-12 mx-auto">
                            <div id="transactions" class="table-container">
                            <div class="loader_2"></div>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
    <!--Search Bar end-->
   
</div>

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function(event) {
   
        call=function(){
            $('#transactions').html('<div class="loader_2"></div>');
            $("#btn_find").text('Loading...');
            $("#btn_find").attr('disabled',true);
            $.ajax({
                type:'POST',
                url:'<?=base_url('iservices/Myapplications/getMyApps') ?>',
                data:{"app_ref_no":"","from_date":$("#start_date").val(),"end_date":$("#end_date").val(),"status":$("#app_status").val()},
                beforeSend:function(){
                    // $("#transactions").html();
                },
                success:function(res){
                    $("#btn_find").text('Find');
                    $("#btn_find").attr('disabled',false);
                    $("#transactions").html(res);
                    $('.table').DataTable({searching:false});
                }
            })
        }
        $("#btn_find").on('click',function(){
           
            let app=$("#app_ref_no").val();
            if(app.length > 0){
                $('#transactions').html('<div class="loader_2"></div>');
                $('#start_date').val("");
                $('#end_date').val("");
                $('input[name="daterange"]').val("");
                $("#btn_find").text('Loading...');
                $("#btn_find").attr('disabled',true);
                $.ajax({
                type:'POST',
                url:'<?=base_url('iservices/Myapplications/getMyApps') ?>',
                data:{"app_ref_no":app,"from_date":$("#start_date").val(),"end_date":$("#end_date").val()},
                beforeSend:function(){
                    // $("#transactions").html();
                },
                success:function(res){
                    $("#btn_find").text('Find');
                    $("#btn_find").attr('disabled',false);
                    $("#transactions").html(res)
                    $('.table').DataTable({searching:false});
                }
                })
            }
        })

        $("#app_status").on('change',function(){
            call();
        })
        // Load service category counts
        window.fetch("<?= base_url('site/categorywise_services_api') ?>")
            .then(response => response.json())
            .then(data => {
                // console.log(data);
                document.querySelector('span#citizen').textContent = data.data.total_citizen;
                document.querySelector('span#utility').textContent = data.data.total_utility;
                document.querySelector('span#business').textContent = data.data.total_business;

            })
            .catch(error => alert(error));


            //load my applications
           
            call();


       
            $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
            $('#start_date').val("");
            $('#end_date').val("");
            $('input[name="daterange"]').val("");
            call();
            });
        $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
            $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
            $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
            $("#app_ref_no").val('');
            call();
        });
        $("#clear").click(function() {
            $("select").val("");
            $("#filters").empty();
        });
        $('input[name="daterange"]').daterangepicker({
            opens: 'left',
            linkedCalendars: false,
            showDropdowns: true,
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),
            "maxSpan": {
                    "days": 30
                },
            locale: {
                "format": "DD/MM/YYYY",
                "separator": " - ",
                "applyLabel": "Apply Date Range",
                "cancelLabel": "Clear Selection",
                "fromLabel": "From",
                "toLabel": "To",
                "customRangeLabel": "Custom",
                "weekLabel": "W",
                "daysOfWeek": [
                    "Su",
                    "Mo",
                    "Tu",
                    "We",
                    "Th",
                    "Fr",
                    "Sa"
                ],
                "monthNames": [
                    "January",
                    "February",
                    "March",
                    "April",
                    "May",
                    "June",
                    "July",
                    "August",
                    "September",
                    "October",
                    "November",
                    "December"
                ],
                "firstDay": 1
            },
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));

            //table.draw();
        });



    });
</script>