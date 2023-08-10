<?php
$obj_id = $data[0]->{'_id'}->{'$id'};
$sub_date = isset($data[0]->form_data->submission_date) ? $data[0]->form_data->submission_date : '';
$oqtabcnt = count($data[0]->form_data->other_qualification_trainings_courses);
$params['data'] = base_url('spservices/employment-registration-nonaadhaar/preview/' . $obj_id);
$params['level'] = 'H';
$params['size'] = 10;
$params['savename'] = 'storage/temps/' . $obj_id . '.png';
$this->ciqrcode->generate($params);
?>

<style>
    table {
        border-spacing: 0px;
    }

    th,
    td {
        border: 1px solid #ccc;
        line-height: 24px;
        padding: 5px;
    }
</style>
<link rel="stylesheet" href="<?= base_url("assets/css/"); ?>acknowledgment.css">

<div class="content-wrapper">
    <div class="container">
        <div class="card my-4 " style="border: 1px solid;padding:20px">
            <div class="card-body">
                <div style="text-align: center; padding: 10px">
                    <img src="<?= FCPATH . 'assets/frontend/images/assam_logo.png' ?>" style="height: 70px; width: 60px" alt="Govt. of Assam" />

                    <div style="font-size: 14px; font-weight: bold; text-transform: uppercase">Government of Assam</div>

                    <div style="font-size: 14px; font-weight: bold; text-transform: uppercase">SKILL, EMPLOYMENT & ENTREPRENEURSHIP DEPARTMENT</div>

                    <div style="font-size: 12px; font-weight: bold;"><?= $data[0]->form_data->employment_exchange ?></div>

                    <div style="font-size: 12px; font-weight: bold;">CATEGORY: <?= $data[0]->form_data->caste ?></div>

                    <div style="font-size: 12px; font-weight: bold; text-transform: uppercase">IDENTITY CARD</div>

                    <div style="font-size: 10px; font-weight: bold;">(Not an Introduction Card for Interview with employer)</div>
                </div>

                <table class="table table-bordered" style="margin: 10px auto; width: 99%;">
                        <tbody style="border-top: none">
                            <tr>
                                <td style="font-size:10px;"> Name</td>
                                <td style="font-size:10px;"><?= $data[0]->form_data->applicant_name ?></td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;"> Date of Birth </td>
                                <td style=" font-size:10px;"><?= $data[0]->form_data->date_of_birth ?></td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;"> Date of Registration</td>
                                <td style="font-size:10px;"><?= $delivered_dt ?></td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;"> Registration No.</td>
                                <td style="font-size:10px;"><?= $certificate_no ?></td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;"> Permanent Address</td>
                                <td style="font-size:10px;"><?= $data[0]->form_data->vill_town_ward_city_p ?>, PO:<?= $data[0]->form_data->post_office_p ?> , PS: <?= $data[0]->form_data->police_station_p ?>, DISTRICT: <?= $data[0]->form_data->district_p ?></td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;"> Qualification</td>
                                <td style="font-size:10px;"><?= $data[0]->form_data->highest_examination_passed ?></td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;"> Other Qualification</td>
                                <?php if ($oqtabcnt != 0) { ?>
                                    <td style="font-size:10px;">
                                        <table class="table table-bordered" style="margin-top:10px; margin-bottom:10px;">
                                            <tbody style="border-top: none !important">
                                                <tr>
                                                    <td style="font-size:10px;">sl no:</td>
                                                    <td style="font-size:10px;">Certificate Name</td>
                                                </tr>
                                                <?php for ($i = 0; $i < $oqtabcnt; $i++) { ?>
                                                    <tr>
                                                        <td style="font-size:10px;"><?= $i + 1 ?></td>
                                                        <td style="font-size:10px;"><?= $data[0]->form_data->other_qualification_trainings_courses[$i]->certificate_name ?></td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </td>
                                <?php } ?>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;"> Occupation </td>
                                <td style="font-size:10px;"><?= $data[0]->form_data->occupation ?></td>
                            </tr>

                            <tr>
                                <td style="font-size:10px;"> Prominent Identification Mark </td>
                                <td style="font-size:10px;"><?= $data[0]->form_data->prominent_identification_mark ?></td>
                            </tr>

                        </tbody>
                    </table>



                <p style="padding:10px;font-size:10px;margin-left:10px">
                    Next Renewal Due On :<?= $renew_dt ?>
                </p>
                <p style="padding:10px;font-size:10px;margin-left:10px">
                    Employers shall be responsible for verification of Permanent Address of Assam/Educational Qualification/Age proof/Caste etc. of the applicant.
                </p>
                <div style="text-align: left; padding: 10px">
                    <img src="<?= FCPATH . 'storage/temps/' . $obj_id . '.png' ?>" style="width: 20mm; height: 20mm" />
                </div>

            </div>
        </div>
    </div>
</div>