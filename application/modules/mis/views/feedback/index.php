<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis</title>

    <!--Bootstrap 4.5-->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/frontend/plugins/bootstrap-4.5.0/css/bootstrap.min.css') ?>">

    <link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url("assets/css/"); ?>feedback.css">

    <script type="text/javascript" src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <!--Popper-->
    <script type="text/javascript" src="<?= base_url('assets/frontend/plugins/bootstrap-4.5.0/js/popper.min.js') ?>"></script>
    <!--Bootstrap 4.5-->
    <script type="text/javascript" src="<?= base_url('assets/frontend/plugins/bootstrap-4.5.0/js/bootstrap.bundle.min.js') ?>"></script>

    <style>
        .stars {
            text-align: center;
        }

        .write_feedback {
            position: relative;
        }

        .feedback_sub-btn {
            margin-top: 2em;
        }
        #reset {
            margin-top: 2em;
        }
    </style>
    <div class="d-flex justify-content-center">
        <div class="row">
            <div class="col-md-12">
                <!--        <h1 class="text-center">Assam Right To Public Services</h1>-->
                <!--        <h3 class="text-center my-2 mt-4">Appeal Management System</h3>-->
                <div class="login-box">
                    <div class="login-logo">
                        <a href="#"><b></b></a>
                    </div>
                    <!-- /.login-logo -->
                    <div class="card mt-4">
                        <div class="card-body login-card-body">
                            <h3>Please provide your rating</h3>
                            <br />
                            <div>
                                Service Name : <?= $application_data->initiated_data->service_name ?><br />
                                Department Name : <?= $application_data->initiated_data->department_name ?><br />
                                Application Ref No : <?= $application_data->initiated_data->appl_ref_no ?><br />
                                Submission Date : <?= format_mongo_date($application_data->initiated_data->submission_date) ?><br />
                            </div>
                            <form id="form">
                                 <input type="hidden" id="dept_id" name="dept_id" value="<?=$application_data->initiated_data->department_id?>">
                                 <input type="hidden" id="service_id" name="service_id" value="<?=$application_data->initiated_data->base_service_id?>">
                                <input type="hidden" id="appl_ref_no" name="appl_ref_no" value="<?=$application_data->initiated_data->appl_ref_no?>">
                                <input type="hidden" id="department_name" name="department_name" value="<?=$application_data->initiated_data->department_name?>">
                                <input type="hidden" id="submission_location" name="submission_location" value="<?=$application_data->initiated_data->submission_location?>">
                                <input type="hidden" id="service_name" name="service_name" value="<?=$application_data->initiated_data->service_name?>">
                                
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="star-rating">
                                            <span class="far fa-star" data-rating="1"></span>
                                            <span class="far fa-star" data-rating="2"></span>
                                            <span class="far fa-star" data-rating="3"></span>
                                            <span class="far fa-star" data-rating="4"></span>
                                            <span class="far fa-star" data-rating="5"></span>
                                            <input type="hidden" name="rating" id="rating" class="rating-value" value="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                <div class="col-lg-12">
                                <textarea class="form-control write_feedback" id="write_feedback" placeholder="Write your feedback..."></textarea>
                                </div>
                                </div>
                                <div class="row">
                                <div class="col-lg-12">
                                <a href="#!" class="btn btn-primary feedback_sub-btn " id="sub">
                                    <span id="actionSubmitProgress" class="d-none">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing ...
                                    </span>
                                    <span id="submitBtnTxt">Submit</span>
                                </a>
                                    <a href="#!" class="btn btn-warning " id="reset">
                                        Reset
                                    </a>
                                 </div>
                                 </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
    <script>
        var $star_rating = $('.star-rating .fa-star');

        var SetRatingStar = function() {
            return $star_rating.each(function() {
                if (parseInt($star_rating.siblings('input.rating-value').val()) >= parseInt($(this).data('rating'))) {
                    return $(this).removeClass('far').addClass('fas').addClass('animation');
                } else {
                    return $(this).removeClass('fas').addClass('far').removeClass('animation');
                }
            });
        };

        $star_rating.on('click', function() {
            $star_rating.siblings('input.rating-value').val($(this).data('rating'));

            return SetRatingStar();
        });

        
        $(document).ready(function() {
            $('#reset').click(function(){
                $('#write_feedback').val('');
                return $star_rating.each(function() {
                    return $(this).removeClass('fas').addClass('far').removeClass('animation');
               });
               
            });
            var FormRef = $('#form');
            var actionSubmitProgressRef = $('#actionSubmitProgress');
            var submitBtnTxtRef = $('#submitBtnTxt');
            const excludedInputs = '#write_feedback';

            function showLoadingActionButton() {
                if (actionSubmitProgressRef.hasClass('d-none')) {
                    actionSubmitProgressRef.removeClass('d-none');
                }
                if (!submitBtnTxtRef.hasClass('d-none')) {
                    submitBtnTxtRef.addClass('d-none');
                }
            }
            $('#sub').click(function() {
                if (FormRef.parsley({
                        excluded: excludedInputs
                    }).validate()) {
                    showLoadingActionButton();
                    var stars = $('#rating').val();
                    var feedback_text = FormRef.find('#write_feedback').val();
                    var dept_id = FormRef.find('#dept_id').val();
                    var appl_ref_no = FormRef.find('#appl_ref_no').val();
                    var submission_location = FormRef.find('#submission_location').val();
                    var department_name = FormRef.find('#department_name').val();
                    var service_name = FormRef.find('#service_name').val();
                    var service_id = FormRef.find('#service_id').val();
                    $.post('<?= base_url('mis/feedback/save') ?>', {
                        stars: stars,
                        feedback_text: feedback_text,
                        dept_id: dept_id,
                        service_id: service_id,
                        appl_ref_no: appl_ref_no,
                        department_name: department_name,
                        submission_location: submission_location,
                        service_name: service_name,
                        feedback_on: "delivered",
                    }, function(data) {
                        console.log(data);
                        if (data.status == true) {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Thank you for the feedback.',
                                showConfirmButton: false,
                                timer: 2500
                            }).then((result) => {
                                window.location.replace("http://rtps.assam.gov.in/");
                            });
                            submitBtnTxtRef.removeClass('d-none');
                            actionSubmitProgressRef.addClass('d-none');
                        } else {
                            submitBtnTxtRef.removeClass('d-none');
                            actionSubmitProgressRef.addClass('d-none');
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html:data.validation_errors,
                            })
                            $(this).text('Save');
                        }
                    })

                }
            });

        });
    </script>