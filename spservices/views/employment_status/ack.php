<style>
/* body {visibility:hidden;} */
.print {
    visibility: visible;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    padding: 8;
    margin: 0;
}
</style>

<link rel="stylesheet" href="<?= base_url("assets/css/"); ?>acknowledgment.css">

<div class="content-wrapper">
    <div class="container">
        <div class="row" id="print">
            <div class="col-sm-12 mx-auto">
                <div class="card my-4" style="border: 1px solid; padding: 20px">
                    <div class="card-body">
                        <div class="row">
                            <img class="img" src="<?= base_url("assets/frontend/images/logo_artps.png") ?>" width="100"
                                height="100" />
                        </div>
                        <div class="row" style="font-family: 'Times New Roman', Times, serif">
                            <h3 class="h2"><u>Application Acknowledgement</u></h3>
                        </div>
                        <div class="row akno" style="font-family: 'Times New Roman', Times, serif">
                            <span><u>Acknowledgement no:
                                    <?php echo isset($ack_data->ref_no) ? $ack_data->ref_no : ''; ?></u></span>
                            <span style="float: right;"><u>Date:
                                    <?php echo isset($ack_data->appl_date) ? format_mongo_date($ack_data->appl_date) : ''; ?></u></span>

                        </div>
                        <div class="row" style="font-family: 'Times New Roman', Times, serif;">
                            <p style="text-align: left; font-size: 18px; font-weight: bold; line-height: 24px;">
                                Dear <?php echo $ack_data->appl_name; ?>,
                            </p>
                            <p style="text-align: left; font-size: 16px; line-height: 24px;">
                                You have successfully updated your Employment Status against Employment Exchange
                                Registration Number <?php echo $ack_data->appl_reg_no ?>. Your present Employment Status
                                is:
                            </p>
                            <div style="text-align: left;">
                                <div style="text-align: center; margin: 0 auto; max-width: 600px;">
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <?php if ($dbRow->updated_data[count($dbRow->updated_data)-1]->employment == 'Self Employed'): ?>
                                        <tr>
                                            <th
                                                style="padding: 10px; background-color: #f2f2f2; text-align: left; width: 40%;">
                                                Type of Employment:</th>
                                            <td style="padding: 10px; width: 60%;">
                                                <?php echo $dbRow->updated_data[count($dbRow->updated_data)-1]->employment ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="padding: 10px; background-color: #f2f2f2; text-align: left;">
                                                Nature of Self-Employment:</th>
                                            <td style="padding: 10px;">
                                                <?php echo $dbRow->updated_data[count($dbRow->updated_data)-1]->natureOfSelfEmployment ?>
                                            </td>
                                        </tr>
                                        <?php if (!empty($dbRow->updated_data[count($dbRow->updated_data)-1]->otherNatureOfSelfEmployment)): ?>
                                        <tr>
                                            <th style="padding: 10px; background-color: #f2f2f2; text-align: left;">
                                                Description of Self-Employment:</th>
                                            <td style="padding: 10px;">
                                                <?php echo $dbRow->updated_data[count($dbRow->updated_data)-1]->otherNatureOfSelfEmployment ?>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php else: ?>
                                        <tr>
                                            <th
                                                style="padding: 10px; background-color: #f2f2f2; text-align: left; width: 40%;">
                                                Type of Employment:</th>
                                            <td style="padding: 10px; width: 60%;">
                                                <?php echo $dbRow->updated_data[count($dbRow->updated_data)-1]->employment ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="padding: 10px; background-color: #f2f2f2; text-align: left;">
                                                Organization:</th>
                                            <td style="padding: 10px;">
                                                <?php echo $dbRow->updated_data[count($dbRow->updated_data)-1]->organization ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="padding: 10px; background-color: #f2f2f2; text-align: left;">
                                                Department/Company/Sector:</th>
                                            <td style="padding: 10px;">
                                                <?php echo $dbRow->updated_data[count($dbRow->updated_data)-1]->department_name ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="padding: 10px; background-color: #f2f2f2; text-align: left;">
                                                Designation:</th>
                                            <td style="padding: 10px;">
                                                <?php echo $dbRow->updated_data[count($dbRow->updated_data)-1]->designation ?>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <br>
                        <br>
                        <div class="row" style="font-family: 'Times New Roman', Times, serif">
                            <b>Thank You.</b>
                        </div>
                        <div class="row" style="font-family: 'Times New Roman', Times, serif">
                            <b>Sewasetu</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" id="btnPrint" class="btn btn-primary mb-2" onclick="window.print()">
            <i class="fas fa-print"></i> Print
        </button>
        <?php if ($this->session->role) { ?>
        <a href="<?= base_url('spservices/employment_status/Update') ?>" class="btn btn-primary mb-2 pull-right">
            <i class="fas fa-angle-double-left"></i> Back
        </a>
        <?php } else { ?>
        <a href="<?= base_url('spservices/employment_status/Update') ?>" class="btn btn-primary mb-2 pull-right">
            <i class="fas fa-angle-double-left"></i> Back
        </a>
        <?php } //End of if else ?>
    </div>
</div>