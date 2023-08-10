<?php
//Application entered Data
$objId = $dbrow->{'_id'}->{'$id'};

$applicant_name = $dbrow->form_data->applicant_name;
$applicant_gender = $dbrow->form_data->applicant_gender;
$father_name_app = $dbrow->form_data->father_name;
$mother_name_app = $dbrow->form_data->mother_name;
$mobile = $dbrow->form_data->mobile;
$email = !empty($dbrow->form_data->email) ? $dbrow->form_data->email : 'NA';

$p_comp_permanent_address = $dbrow->form_data->p_comp_permanent_address;
$p_state = $dbrow->form_data->p_state;
$p_district = $dbrow->form_data->p_district;
$p_police_st = $dbrow->form_data->p_police_st;
$p_post_office = $dbrow->form_data->p_post_office;
$p_pin_code = $dbrow->form_data->p_pin_code;

$c_comp_permanent_address = $dbrow->form_data->c_comp_permanent_address;
$c_state = $dbrow->form_data->c_state;
$c_district = $dbrow->form_data->c_district;
$c_police_st = $dbrow->form_data->c_police_st;
$c_post_office = $dbrow->form_data->c_post_office;
$c_pin_code = $dbrow->form_data->c_pin_code;

$ahsec_reg_session = $dbrow->form_data->ahsec_reg_session;
$ahsec_reg_no = $dbrow->form_data->ahsec_reg_no;
$ahsec_admit_roll = $dbrow->form_data->ahsec_admit_roll;
$ahsec_admit_no = $dbrow->form_data->ahsec_admit_no;
$ahsec_inst_name = $dbrow->form_data->institution_name;
$ahsec_yearofappearing = $dbrow->form_data->ahsec_yearofappearing;
$results = !empty($dbrow->form_data->results) ? $dbrow->form_data->results : 'NA';

$candidate_name_checkbox = $dbrow->form_data->candidate_name_checkbox;
$father_name_checkbox = $dbrow->form_data->father_name_checkbox;
$mother_name_checkbox = $dbrow->form_data->mother_name_checkbox;

$incorrect_candidate_name = $dbrow->form_data->incorrect_candidate_name;
$incorrect_father_name = $dbrow->form_data->incorrect_father_name;
$incorrect_mother_name = $dbrow->form_data->incorrect_mother_name;

$correct_candidate_name = !empty($dbrow->form_data->correct_candidate_name) ? $dbrow->form_data->correct_candidate_name : 'Correction Not Required';
$correct_father_name = !empty($dbrow->form_data->correct_father_name) ? $dbrow->form_data->correct_father_name : 'Correction Not Required';
$correct_mother_name = !empty($dbrow->form_data->correct_mother_name) ? $dbrow->form_data->correct_mother_name : 'Correction Not Required';
$delivery_mode = $dbrow->form_data->delivery_mode;

$passport_photo_type = $dbrow->form_data->passport_photo_type ?? '';
$passport_photo = $dbrow->form_data->passport_photo ?? '';

$signature_type = $dbrow->form_data->signature_type ?? '';
$signature = $dbrow->form_data->signature ?? '';

$affidavit_type = $dbrow->form_data->affidavit_type ?? '';
$affidavit = $dbrow->form_data->affidavit ?? '';

$registration_card_type = $dbrow->form_data->registration_card_type ?? '';
$registration_card = $dbrow->form_data->registration_card ?? '';

$admit_card_type = $dbrow->form_data->admit_card_type ?? '';
$admit_card = $dbrow->form_data->admit_card ?? '';

$pass_certificate_type = $dbrow->form_data->pass_certificate_type ?? '';
$pass_certificate = $dbrow->form_data->pass_certificate ?? '';

$marksheet_type = $dbrow->form_data->marksheet_type ?? '';
$marksheet = $dbrow->form_data->marksheet ?? '';

$soft_copy_type = $dbrow->form_data->soft_copy_type ?? '';
$soft_copy = $dbrow->form_data->soft_copy ?? '';

// End /////////
// Start Master Data - Marksheet Data
$roll = $marksheet_data->Roll ?? set_value("mrk_roll");
$no = $marksheet_data->No ?? set_value("mrk_no");
$stream = $marksheet_data->Stream ?? null;
$commencing_day = $marksheet_data->commencing_day ?? null;
$commencing_month = $marksheet_data->commencing_month ?? null;
$Year_of_Examination = $marksheet_data->Year_of_Examination ?? null;
$ms_no = $marksheet_data->Mark_Sheet_No ?? set_value("ms_no");
$issue_date1 = $marksheet_data->Marksheet_Date ?? '';
// pre($marksheet_data);
$mrk_date = "";
if (!empty($marksheet_data->Marksheet_Date)) {
    $mrk_date1 = explode('-', $issue_date1);
    $mrk_date = $mrk_date1[2] . "-" . $mrk_date1[1] . "-" . $mrk_date1[0];
}
// pre($mrk_date);

$mrk_candidate_name = $marksheet_data->Candidate_Name ?? '';
$mrk_father_name = $marksheet_data->Father_Name ?? '';
$mrk_mother_name = $marksheet_data->Mother_Name ?? '';
$mrk_school_name = $marksheet_data->School_College_Name ?? '';
$mrk_roll = $marksheet_data->Roll ?? '';
$mrk_no = $marksheet_data->No ?? '';
$mrk_rc_no = $marksheet_data->Registration_No ?? '';
$mrk_rc_session = $marksheet_data->Registration_Session ?? '';

$j = 1;
for ($i = 1; $i <= 16; $i++) {

    if (!empty($marksheet_data->{'Sub' . $i . '_Code'})) {
        $mrk_data['Sl' . $j . '_id'] = $i;
        $mrk_data['Sub' . $j . '_Code'] = $marksheet_data->{'Sub' . $i . '_Code'};
        $mrk_data['Sub' . $j . '_Pap_Indicator'] = $marksheet_data->{'Sub' . $i . '_Pap_Indicator'};
        $mrk_data['Sub' . $j . '_Name'] = $marksheet_data->{'Sub' . $i . '_Name'};

        $thMarks = $marksheet_data->{'Sub' . $i . '_Th_Marks'};
        $actualMarks = $thMarks;
        $graceMarks = $marksheet_data->{'Sub' . $i . '_Grace_Marks'} ?? '';
        if (strpos($thMarks, '+') !== false && ($marksheet_data->{'Sub' . $i . '_Code'} == "BIOL")) {
            $biolPracsMarks = $marksheet_data->{'Sub' . $i . '_Pr_Marks'};
            $totalBiologyMarks = $marksheet_data->{'Sub' . $i . '_Tot_Marks'};

            if (strpos($thMarks, '+') !== false) {
                list($botanyMarks, $zoologyMarks) = explode('+', $thMarks);
            }
            if (strpos($graceMarks, '+') !== false) {
                list($botanyGrace, $zoologyGrace) = explode('+', $graceMarks);
            }
            if (strpos($biolPracsMarks, '+') !== false) {
                list($botanyPracsMarks, $zoologyPracsMarks) = explode('+', $biolPracsMarks);
            }
            if (strpos($totalBiologyMarks, '+') !== false) {
                list($botanyTotalMarks, $zoologyTotalMarks) = explode('+', $totalBiologyMarks);
            }
            $mrk_data['Sub' . $j . '_Botany_Marks'] = $botanyMarks;
            $mrk_data['Sub' . $j . '_Botany_Grace_Marks'] = $botanyGrace;
            $mrk_data['Sub' . $j . '_Botany_Pr_Marks'] = $botanyPracsMarks;
            $mrk_data['Sub' . $j . '_Botany_Tot_Marks'] = $botanyTotalMarks;

            $mrk_data['Sub' . $j . '_Zoology_Marks'] = $zoologyMarks;
            $mrk_data['Sub' . $j . '_Zoology_Grace_Marks'] = $zoologyGrace;
            $mrk_data['Sub' . $j . '_Zoology_Pr_Marks'] = $zoologyPracsMarks;
            $mrk_data['Sub' . $j . '_Zoology_Tot_Marks'] = $zoologyTotalMarks;
        } else if (strpos($thMarks, '+') == false && ($marksheet_data->{'Sub' . $i . '_Code'} == "BIOL")) {
            $mrk_data['Sub' . $j . '_Botany_Marks'] = '';
            $mrk_data['Sub' . $j . '_Botany_Grace_Marks'] = '';
            $mrk_data['Sub' . $j . '_Botany_Pr_Marks'] = '';
            $mrk_data['Sub' . $j . '_Botany_Tot_Marks'] = '';

            $mrk_data['Sub' . $j . '_Zoology_Marks'] = '';
            $mrk_data['Sub' . $j . '_Zoology_Grace_Marks'] = '';
            $mrk_data['Sub' . $j . '_Zoology_Pr_Marks'] = '';
            $mrk_data['Sub' . $j . '_Zoology_Tot_Marks'] = '';
        } else if ((strpos($thMarks, '+') != false) && $marksheet_data->{'Sub' . $i . '_Code'} != "BIOL") {
            list($actualMarks, $graceMarks) = explode('+', $thMarks);
            $mrk_data['Sub' . $j . '_Actual_Marks'] = $actualMarks;
            $mrk_data['Sub' . $j . '_Grace_Marks'] = $graceMarks;

            $mrk_data['Sub' . $j . '_Pr_Marks'] = $marksheet_data->{'Sub' . $i . '_Pr_Marks'};
            $mrk_data['Sub' . $j . '_Tot_Marks'] = $marksheet_data->{'Sub' . $i . '_Tot_Marks'};
        } else {
            $mrk_data['Sub' . $j . '_Actual_Marks'] = $actualMarks;
            $mrk_data['Sub' . $j . '_Grace_Marks'] = $graceMarks;

            $mrk_data['Sub' . $j . '_Pr_Marks'] = $marksheet_data->{'Sub' . $i . '_Pr_Marks'};
            $mrk_data['Sub' . $j . '_Tot_Marks'] = $marksheet_data->{'Sub' . $i . '_Tot_Marks'};
        }

        $j++;
    }
}

$mrk_data['Total_Marks_in_Words'] = $marksheet_data->Total_Marks_in_Words ?? '';
$mrk_data['Total_Marks_in_Figure'] = $marksheet_data->Total_Marks_in_Figure ?? '';
$mrk_data['Total_Grace_in_Figure'] = $marksheet_data->Total_Grace_in_Figure ?? '';
$mrk_data['Result'] = $marksheet_data->Result ?? '';
$mrk_data['Remarks2'] = $marksheet_data->Remarks2 ?? '';
$mrk_data['ENVE_Grade'] = $marksheet_data->ENVE_Grade ?? '';
$mrk_data['Core_Indicator'] = $marksheet_data->Core_Indicator ?? '';
// End Master Data - Marksheet Data

// Master Data - Registration Details
$mobile_no = $reg_data->mobile_no ?? '';
$sl_no = "";
if ($dbrow->service_data->service_id == "AHSECCRC")
    $sl_no = $reg_data->sl_no ?? '';
else if ($dbrow->service_data->service_id == "AHSECCADM")
    $sl_no = $marksheet_data->Admit_Card_Serial_No
        ?? '';
else if ($dbrow->service_data->service_id == "AHSECCPC")
    $sl_no = $marksheet_data->Certificate_Serial_No
        ?? '';
$institution_code = $reg_data->institution_code ?? '';
$candidate_name = $reg_data->candidate_name ?? '';
$father_name = $reg_data->father_name ?? '';
$mother_name = $reg_data->mother_name ?? '';
$gender = $reg_data->gender ?? '';
// pre($gender);
$institution_name = $reg_data->institution_name ?? '';
$registration_number = $reg_data->registration_number ?? '';
$issue_date = $reg_data->issue_date ?? '';

$new_issue_date1 = "";
if (!empty($issue_date)) {
    $new_issue_date = explode('-', $issue_date);
    $new_issue_date1 = $new_issue_date[2] . "-" . $new_issue_date[1] . "-" . $new_issue_date[0];
}


$registration_session = $reg_data->registration_session ?? '';
$sub_1 = $reg_data->sub_1 ?? '';
$sub_2 = $reg_data->sub_2 ?? '';
$sub_3 = $reg_data->sub_3 ?? '';
$sub_4 = $reg_data->sub_4 ?? '';
$sub_5 = $reg_data->sub_5 ?? '';
$sub_6 = $reg_data->sub_6 ?? '';
// End /////////

//result table data
$roll = $marksheet_data->Roll ?? null;
$no = $marksheet_data->No ?? null;
$stream = $marksheet_data->Stream ?? null;
$commencing_day = $marksheet_data->commencing_day ?? null;
$commencing_month = $marksheet_data->commencing_month ?? null;
$exam_year = $marksheet_data->Year_of_Examination ?? null;
// $exam_year=$marksheet_data->exam_year ?? null;
?>



<style>
    /* body {font-family: Arial;} */

    /* Style the tab */
    .tab {
        overflow: hidden;
        /* border: 1px solid #ccc; */
        /* background-color: #f1f1f1; */
    }

    /* Style the buttons inside the tab */
    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 4px 6px;
        transition: 0.3s;
        font-size: 13px;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #fff;
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: #fff;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        /* border-top: none; */
    }

    legend {
        display: inline;
        width: auto;
    }

    ol li {
        font-size: 14px;
        font-weight: bold;
    }

    td {
        font-size: 14px;
    }

    .blink {
        animation: blinker 2s linear infinite;
    }

    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }

    .message {
        color: red;
        font-weight: bold;
    }
</style>
<script src="<?= base_url("assets/plugins/jQuery-MultiSelect/jquery.multiselect.js") ?>"></script>
<script type="text/javascript">
</script>
<link rel="stylesheet" href="<?= base_url("assets/plugins/jQuery-MultiSelect/jquery.multiselect.css") ?>" type="text/css">
<script type="text/javascript">
    // openDiv(event, 'preview');

    function openDiv(evt, cityName) {

        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
    $(document).ready(function() {
        let delivery_mode = 'SHOW_MESSAGE';
        let index = 1;
        showMessage(delivery_mode);

        // Function to calculate the total marks for a given row
        function calculateTotal(varName) {
            return function() {
                var thMarks = parseFloat(document.getElementById(varName + "_Actual_Marks").value) || 0;
                var prMarks = parseFloat(document.getElementById(varName + "_Pr_Marks").value) || 0;
                var graceMarks = parseFloat(document.getElementById(varName + "_Grace_Marks").value) || 0;

                var totalMarks = thMarks + prMarks + graceMarks;

                document.getElementById(varName + "_Tot_Marks").value = totalMarks;

                // Recalculate the overall total marks, overall grace marks, and total marks in words
                calculateOverallTotal();
                calculateOverallGrace();
                calculateOverallTotalInWords();
            };
        }

        // Function to calculate the total marks for a given row for Botany
        function calculateBotanyTotal(varName) {
            return function() {
                var thMarks = parseFloat(document.getElementById(varName + "_Botany_Marks").value) || 0;
                var prMarks = parseFloat(document.getElementById(varName + "_Botany_Pr_Marks").value) || 0;
                var graceMarks = parseFloat(document.getElementById(varName + "_Botany_Grace_Marks").value) || 0;

                var totalMarks = thMarks + prMarks + graceMarks;

                document.getElementById(varName + "_Botany_Tot_Marks").value = totalMarks;

                // Recalculate the overall total marks, overall grace marks, and total marks in words
                calculateOverallTotal();
                calculateOverallGrace();
                calculateOverallTotalInWords();
            };
        }


        // Function to calculate the total marks for a given row for Zoology
        function calculateZoologyTotal(varName) {
            return function() {
                var thMarks = parseFloat(document.getElementById(varName + "_Zoology_Marks").value) || 0;
                var prMarks = parseFloat(document.getElementById(varName + "_Zoology_Pr_Marks").value) || 0;
                var graceMarks = parseFloat(document.getElementById(varName + "_Zoology_Grace_Marks").value) || 0;

                var totalMarks = thMarks + prMarks + graceMarks;

                document.getElementById(varName + "_Zoology_Tot_Marks").value = totalMarks;

                // Recalculate the overall total marks, overall grace marks, and total marks in words
                calculateOverallTotal();
                calculateOverallGrace();
                calculateOverallTotalInWords();
            };
        }



        // Function to calculate the overall total marks
        function calculateOverallTotal() {
            var overallTotal = 0;

            for (var i = 1; i <= 6; i++) {
                var varName = "Sub" + i;
                if (document.getElementById(varName + "_Tot_Marks")) {
                    var totalMarks = parseFloat(document.getElementById(varName + "_Tot_Marks").value) || 0;
                    overallTotal += totalMarks;
                }
                if (document.getElementById(varName + "_Botany_Tot_Marks")) {
                    var totalMarks = parseFloat(document.getElementById(varName + "_Botany_Tot_Marks").value) || 0;
                    overallTotal += totalMarks;
                }
                if (document.getElementById(varName + "_Zoology_Tot_Marks")) {
                    var totalMarks = parseFloat(document.getElementById(varName + "_Zoology_Tot_Marks").value) || 0;
                    overallTotal += totalMarks;
                }

            }

            document.getElementById("Total_Marks_in_Figure").value = overallTotal;
            document.getElementById("Total_Marks_in_Figure").readOnly = true;
        }

        // Function to calculate the overall grace marks
        function calculateOverallGrace() {
            var overallGrace = 0;

            for (var i = 1; i <= 6; i++) {
                var varName = "Sub" + i;
                if (document.getElementById(varName + "_Grace_Marks")) {
                    var graceMarks = parseFloat(document.getElementById(varName + "_Grace_Marks").value) || 0;
                    overallGrace += graceMarks;
                }
                if (document.getElementById(varName + "_Botany_Grace_Marks")) {
                    var graceMarks = parseFloat(document.getElementById(varName + "_Botany_Grace_Marks").value) || 0;
                    overallGrace += graceMarks;
                }
                if (document.getElementById(varName + "_Zoology_Grace_Marks")) {
                    var graceMarks = parseFloat(document.getElementById(varName + "_Zoology_Grace_Marks").value) || 0;
                    overallGrace += graceMarks;
                }
            }

            document.getElementById("Total_Grace_in_Figure").value = overallGrace;
            document.getElementById("Total_Grace_in_Figure").readOnly = true;
        }

        // Function to calculate the overall total marks in words and update the corresponding field
        function calculateOverallTotalInWords() {
            var overallTotal = parseFloat(document.getElementById("Total_Marks_in_Figure").value) || 0;
            var totalInWords = convertNumberToWords(overallTotal);
            document.getElementById("Total_Marks_in_Words").value = totalInWords;
            document.getElementById("Total_Marks_in_Words").readOnly = true;
        }

        // Function to convert a number to words
        function convertNumberToWords(number) {
            var words = {
                0: "ZERO",
                1: "ONE",
                2: "TWO",
                3: "THREE",
                4: "FOUR",
                5: "FIVE",
                6: "SIX",
                7: "SEVEN",
                8: "EIGHT",
                9: "NINE"
            };

            var numberStr = Math.floor(number).toString();
            var wordsArr = [];

            for (var i = 0; i < numberStr.length; i++) {
                var digit = parseInt(numberStr[i]);
                var word = words[digit];

                wordsArr.push(word);
            }

            return wordsArr.join(" ");
        }

        // Event listeners to trigger the calculation when the fields change for other subjects except Botany and Zoology
        for (var i = 1; i <= 6; i++) {
            var varName = "Sub" + i;
            // Check if the input fields exist for the current row (varName)
            if (document.getElementById(varName + "_Actual_Marks")) {
                var totMarksInput = document.getElementById(varName + "_Tot_Marks");
                totMarksInput.readOnly = true; // Set the textbox as read-only

                document.getElementById(varName + "_Actual_Marks").addEventListener('keyup', calculateTotal(varName));
                document.getElementById(varName + "_Pr_Marks").addEventListener('keyup', calculateTotal(varName));
                document.getElementById(varName + "_Grace_Marks").addEventListener('keyup', calculateTotal(varName));
            }
        }


        // Event listeners to trigger the calculation when the fields change for Botany
        for (var j = 1; j <= 6; j++) {
            var varName = "Sub" + j;

            // Check if the input fields exist for the current row (varName)
            if (document.getElementById(varName + "_Botany_Marks")) {
                var totMarksInput = document.getElementById(varName + "_Botany_Tot_Marks");
                totMarksInput.readOnly = true; // Set the textbox as read-only
                document.getElementById(varName + "_Botany_Marks").addEventListener('keyup', calculateBotanyTotal(varName));
                document.getElementById(varName + "_Botany_Pr_Marks").addEventListener('keyup', calculateBotanyTotal(varName));
                document.getElementById(varName + "_Botany_Grace_Marks").addEventListener('keyup', calculateBotanyTotal(varName));
            }
        }

        // Event listeners to trigger the calculation when the fields change for Zoology
        for (var k = 1; k <= 6; k++) {
            var varName = "Sub" + k;

            // Check if the input fields exist for the current row (varName)
            if (document.getElementById(varName + "_Zoology_Marks")) {
                var totMarksInput = document.getElementById(varName + "_Zoology_Tot_Marks");
                totMarksInput.readOnly = true; // Set the textbox as read-only
                document.getElementById(varName + "_Zoology_Marks").addEventListener('keyup', calculateZoologyTotal(varName));
                document.getElementById(varName + "_Zoology_Pr_Marks").addEventListener('keyup', calculateZoologyTotal(varName));
                document.getElementById(varName + "_Zoology_Grace_Marks").addEventListener('keyup', calculateZoologyTotal(varName));
            }
        }


        // Calculate and update the initial overall total, overall grace, and total marks in words
        calculateOverallTotal();
        calculateOverallGrace();
        calculateOverallTotalInWords();
        //=============================================================================================================================================================
        $(document).on("click", "#addlatblrow", function() {
            let totRows = $('#marksheetEntrytbl tr').length;
            index++;
            var trow = `<tr>
        <td>
            <select name="sub_sl_no[]" class="form-control" id="sub_sl_no" required>
                <option value="">Sl No</option>
                <?php for ($i = 1; $i <= 16; $i++) { ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                <?php } ?>
            </select>
        </td>
        <td>
            <select name="Sub_Code[]" class="form-control" id="Sub_Code" required>
                                                                    <option value="">Select Any One</option>
                                                                    <?php foreach ($subjects as $subject) { ?>
                                                                        <option value="<?= $subject->subject_code . '|' . $subject->subject_name ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                                                    <?php } ?>
                                                                </select>
        </td>
        <td>
                                                                            <select name="Sub_Pap_Indicator[]" class="form-control" id="Sub` + index + `_Pap_Indicator" required>
                                                                    <option value="">Select</option>
                                                                    <option value="CORE">CORE</option>
                                                                    <option value="EL-I">EL-I</option>
                                                                    <option value="EL-II">EL-II</option>
                                                                    <option value="EL-III">EL-III</option>
                                                                    <option value="EL-IV">EL-IV</option>
                                                                </select>
        </td>
                                                            <td>
                                                                <input type="text" maxlength="3" class="form-control" id="Sub` + index + `_Actual_Marks" name="Sub_Actual_Marks[]"  required>
                                                            </td>
                                                            <td>
                                                                <input type="text" maxlength="3" class="form-control" id="Sub` + index + `_Pr_Marks" name="Sub_Pr_Marks[]">
                                                            </td>
                                                            <td>
                                                                <input type="text" maxlength="3" class="form-control" id="Sub` + index + `_Grace_Marks" name="Sub_Grace_Marks[]">
                                                            </td>
                                                            <td>
                                                                <input type="text" maxlength="3" class="form-control" id="Sub` + index + `_Tot_Marks" name="Sub_Tot_Marks[]" required readonly>
                                                            </td>
        <td style="text-align:center"><button class="btn btn-danger deletetblrow" type="button"><i class='deletetblrow fas fa-trash-alt'></i></button></td>
    </tr>`;
            if (totRows <= 7) {
                $('#marksheetEntrytbl tr:last').after(trow);
            }
        });

        // $(document).on("click", ".deletetblrow", function() {
        //     $(this).closest("tr").remove();
        //     return false;
        // });


        document.getElementById('marksheetEntrytbl').addEventListener('click', function(event) {
            var target = event.target;
            console.log(target);
            // Check if the clicked element is a delete button with class "deletetblrow"
            if (target.classList.contains('deletetblrow')) {
                var row = target.closest('tr'); // Find the parent row containing the delete button
                var varName = row.querySelector('select[name="sub_sl_no[]"]').value; // Get the varName from the select input

                // Check if the row being deleted is not the last row (i.e., dynamically added Intermediate row)
                if (!row.isEqualNode(row.parentNode.lastElementChild)) {
                    alert("Cannot delete intermediate rows. Deletion is allowed only from the bottom row.");
                    return; // Prevent further execution of the code
                }

                row.remove(); // Remove the row from the table

                // Call the necessary calculation functions to recalculate the totals after deletion
                calculateOverallTotal();
                calculateOverallGrace();
                calculateOverallTotalInWords();
            }
        });

        // Add the event listener to the parent table or a container that exists on page load
        document.getElementById('marksheetEntrytbl').addEventListener('keyup', function(event) {
            var target = event.target;

            // Check if the event is triggered by the input fields you want to target
            if (target.matches('[id^="Sub"][id$="_Actual_Marks"], [id^="Sub"][id$="_Pr_Marks"], [id^="Sub"][id$="_Grace_Marks"]')) {
                var varName = target.id.split('_')[0]; // Extracts "SubX" from the input's ID
                calculateTotalNewInsert(varName);
            }
        });

        // Function to calculate the total marks for a given row
        function calculateTotalNewInsert(varName) {
            var thMarks = parseFloat(document.getElementById(varName + "_Actual_Marks").value) || 0;
            var prMarks = parseFloat(document.getElementById(varName + "_Pr_Marks").value) || 0;
            var graceMarks = parseFloat(document.getElementById(varName + "_Grace_Marks").value) || 0;

            var totalMarks = thMarks + prMarks + graceMarks;

            document.getElementById(varName + "_Tot_Marks").value = totalMarks;

            // Recalculate the overall total marks, overall grace marks, and total marks in words
            calculateOverallTotal();
            calculateOverallGrace();
            calculateOverallTotalInWords();
        }


    });

    function delRow(ids) {

        var x = document.getElementById("Sub" + ids + "_Tot_Marks").value;
        alert(x);
    }
</script>
<!-- 

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->

<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('error') != null) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('error') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php } //End of if 
    ?>
    <?php if ($this->session->flashdata('success') != null) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <a href='<?= base_url('spservices/ahsec_correction/actions/preview/' . $objId) ?>' class='btn btn-primary btn-sm' target='_blank'>View</a>
        <?php $processLink = base_url('spservices/upms/myapplications/process/' . $objId); ?>
        <a href="<?= $processLink ?>" class="btn btn-warning btn-sm">Process</a>
    <?php } //End of if 
    ?>
    <div class="accordion" id="accordionTasks">
        <div class="accordion-item">

        </div>
    </div>

    <div class="card shadow-sm mt-2">
        <div class="card-header bg-info">
            <div class="tab">
                <button class="btn-sm tablinks" onclick="openDiv(event, 'preview')">Applicant Data</button>
                <button class="tablinks" onclick="openDiv(event, 'edit')">
                    <?php if ($dbrow->service_data->service_id == "AHSECCRC") { ?>
                        Edit Registration Card Details
                    <?php } ?>
                    <?php if ($dbrow->service_data->service_id == "AHSECCADM") { ?>
                        Edit Admit Card Details
                    <?php } ?>
                    <?php if ($dbrow->service_data->service_id == "AHSECCPC") { ?>
                        Edit Pass Certificate Details
                    <?php } ?>
                    <?php if ($dbrow->service_data->service_id == "AHSECCMRK") { ?>
                        Edit Marksheet Details
                    <?php } ?>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div id="preview" class="tabcontent" style="display: block;">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-striped" style="overflow: scroll;">
                            <tr>
                                <th>ARN</th>
                                <td><?= $dbrow->service_data->appl_ref_no ?></td>
                            </tr>
                            <tr>
                                <th>Name </th>
                                <td><?= $applicant_name ?></td>
                            </tr>
                            <tr>
                                <th>Applied For</th>
                                <td><?= $dbrow->service_data->service_name ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><?= $dbrow->service_data->appl_status ?></td>
                            </tr>
                            <tr>
                                <th>Details</th>
                                <td>
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <th>FIELD THAT NEED CORRECTION</th>
                                            <td>NEED TO BE CORRECTED AS</td>
                                        </tr>
                                        <tr>
                                            <th>Candidate's Name</th>
                                            <td><?= $dbrow->form_data->correct_candidate_name ?></td>
                                        </tr>
                                        <tr>
                                            <th>Father's Name</th>
                                            <td><?= $dbrow->form_data->correct_father_name ?></td>
                                        </tr>
                                        <tr>
                                            <th>Mother's Name</th>
                                            <td><?= $dbrow->form_data->correct_mother_name ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th>Office Copy</th>
                                <td><a class="btn btn-sm btn-primary" href="<?= base_url('/spservices/ahsec_correction/actions/officeCopy/' . $dbrow->service_data->service_id . '/' . $objId) ?>">Download Office Copy</a></td>
                            </tr>
                            <tr>
                                <th>Delivery Preference</th>
                                <td><?= $delivery_mode ?></td>
                            </tr>
                            <tr>
                                <th>Application Recieved</th>
                                <td><?= (!empty($dbrow->service_data->submission_date)) ? format_mongo_date($dbrow->service_data->submission_date) : "" ?>
                                </td>
                            </tr>
                        </table>
                        <!-- <h3>Registration Details</h3> -->
                        <br />
                        <br />
                    </div>

                    <div class="col-md-6">
                        <table class="table table-bordered table-striped" style="overflow: scroll;">
                            <tr>
                                <th colspan=2>Applicant Details</th>
                            </tr>
                            <tr>
                                <th>Photo</th>
                                <td> <img src="<?= base_url($dbrow->form_data->passport_photo) ?>" style="width:130px; height: 130px; margin: 3px;" /></td>
                            </tr>
                            <tr>
                                <th>Signature</th>
                                <td><img src="<?= base_url($dbrow->form_data->signature) ?>" style="width:130px;  height: 40px; margin: 3px;" /></td>
                            </tr>
                            <tr>
                                <th>Registrtion Number</th>
                                <td><?= $ahsec_reg_no ?></td>
                            </tr>
                            <tr>
                                <th>Registrtion Session</th>
                                <td><?= $ahsec_reg_session ?></td>
                            </tr>
                            <?php if ($applicant_name != $candidate_name) { ?>
                                <tr style="border: 2px solid red;">
                                    <th>Name of the Applicant</th>
                                    <td>
                                        <?= $applicant_name ?>
                                    </td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <th>Name of the Applicant</th>
                                    <td>
                                        <?= $applicant_name ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if ($father_name_app != $father_name) { ?>
                                <tr style="border: 2px solid red;">
                                    <th>Father&apos;s Name</th>
                                    <td>
                                        <?= $father_name_app ?>
                                    </td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <th>Father&apos;s Name</th>
                                    <td><?= $father_name_app ?></td>
                                </tr>
                            <?php } ?>
                            <?php if ($mother_name_app != $mother_name) { ?>
                                <tr style="border: 2px solid red;">
                                    <th>Mother&apos;s Name</th>
                                    <td>
                                        <?= $mother_name_app ?>
                                    </td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <th>Mother&apos;s Name</th>
                                    <td><?= $mother_name_app ?></td>
                                </tr>
                            <?php } ?>
                            <?php if ($mobile_no != $mobile) { ?>
                                <tr style="border: 2px solid red;">
                                    <th>Mobile Number</th>
                                    <td><?= $mobile ?></td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <th>Mobile Number</th>
                                    <td><?= $mobile ?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th>Email</th>
                                <td><?= $email ?></td>
                            </tr>
                            <?php if ($institution_name != $ahsec_inst_name) { ?>
                                <tr style="border: 2px solid red;">
                                    <th>Institution Name</th>
                                    <td><?= $ahsec_inst_name ?></td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <th>Institution Name</th>
                                    <td><?= $ahsec_inst_name ?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th colspan="2">Academic Details</th>
                            </tr>
                            <?php if (isset($dbrow->form_data->ahsec_yearofappearing)) { ?>
                                <tr>
                                    <th>Years of Passing of H.S. 2nd Year Examination</th>
                                    <td><?= $dbrow->form_data->ahsec_yearofappearing ?></td>
                                </tr>
                            <?php } ?>
                            <?php if (isset($dbrow->form_data->ahsec_admit_roll)) { ?>
                                <tr>
                                    <th>Valid H.S. 2nd Year Admit Roll</th>
                                    <td><?= $dbrow->form_data->ahsec_admit_roll ?></td>
                                </tr>
                            <?php } ?>
                            <?php if (isset($dbrow->form_data->ahsec_admit_no)) { ?>
                                <tr>
                                    <th>Valid H.S. 2nd Year Admit Number</th>
                                    <td><?= $dbrow->form_data->ahsec_admit_no ?></td>
                                </tr>
                            <?php } ?>
                            <?php if (!empty($dbrow->form_data->affidavit_type)) { ?>
                                <tr>
                                    <th><?= $dbrow->form_data->affidavit_type ?></th>
                                    <td>
                                        <a href="<?= base_url($dbrow->form_data->affidavit) ?>" class="btn font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>

                            <?php if (!empty($dbrow->form_data->registration_card_type)) { ?>
                                <tr>
                                    <th><?= $dbrow->form_data->registration_card_type ?></th>
                                    <td>
                                        <a href="<?= base_url($dbrow->form_data->registration_card) ?>" class="btn font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>

                            <?php if (!empty($dbrow->form_data->admit_card_type)) { ?>
                                <tr>
                                    <th><?= $dbrow->form_data->admit_card_type ?></th>
                                    <td>
                                        <a href="<?= base_url($dbrow->form_data->admit_card) ?>" class="btn font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>

                            <?php if (!empty($dbrow->form_data->pass_certificate_type)) { ?>
                                <tr>
                                    <th><?= $dbrow->form_data->pass_certificate_type ?></th>
                                    <td>
                                        <a href="<?= base_url($dbrow->form_data->pass_certificate) ?>" class="btn font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <?php if ($reg_data_cnt == 0) { ?>
                            <p>No Record found in Master Database</p>
                        <?php } else if ($reg_data_cnt < 2) { ?>
                            <table class="table table-bordered table-striped" style="overflow: scroll;">
                                <tr>
                                    <th></th>
                                    <th>Registration Details</th>
                                </tr>
                                <tr>
                                    <th>Photo</th>
                                    <td> <img src="<?= base_url("storage/ahsec/photos/0" . $reg_data->photo_key_value . ".jpg") ?>" style="width:130px; height: 130px; margin: 3px;" /></td>
                                </tr>
                                <tr>
                                    <th>Signature</th>
                                    <td><img src="<?= base_url("storage/ahsec/signature/0" . $reg_data->photo_key_value . ".jpg") ?>" style="width:130px;  height: 40px; margin: 3px;" /></td>
                                </tr>
                                <tr>
                                    <th>Registration Numbers</th>
                                    <td><?= $registration_number ?></td>
                                </tr>
                                <tr>
                                    <th>Session</th>
                                    <td><?= $registration_session ?></td>
                                </tr>

                                <tr>
                                    <th>Candidate Name</th>
                                    <td><?= $candidate_name ?></td>
                                </tr>
                                <tr>
                                    <th>Father&apos;s Name</th>
                                    <td><?= $father_name ?></td>
                                </tr>
                                <tr>
                                    <th>Mother&apos;s Name</th>
                                    <td><?= $mother_name ?></td>
                                </tr>
                                <tr>
                                    <th>Mobile Number</th>
                                    <td><?= $mobile_no ?></td>
                                </tr>
                                <tr>
                                    <th>Institution Name</th>
                                    <td><?= $institution_name ?></td>
                                </tr>
                                <tr>
                                    <th colspan="2">CORE SUBJECTS</th>
                                </tr>
                                <tr>
                                    <td><?= $sub_1 ?></td>
                                    <td><?= $sub_2 ?></td>
                                </tr>
                                <tr>
                                    <th colspan="2">ELECTIVE SUBJECTS</th>
                                </tr>
                                <tr>
                                    <td><?= $sub_3 ?></td>
                                    <td><?= $sub_4 ?></td>
                                </tr>

                                <tr>
                                    <td><?= $sub_5 ?></td>
                                    <td><?= $sub_6 ?></td>
                                </tr>
                            </table>
                        <?php } else { ?>
                            <p>We have found multiple records in Master Database agaist Registration No. and Registration
                                Session.</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div id="edit" class="tabcontent" style="color: black;">
                <?php if ($reg_data_cnt == 0) { ?>
                    <p>No Record found in Master Database</p>
                    <form class="form-horizontal" action="<?= base_url('spservices/ahsec_correction/actions/insert_reg_master_data/' . $objId) ?>" method="POST" autocomplete="off">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="sl_no">Sl No:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="sl_no" placeholder="Enter Sl No" name="sl_no" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="code_no">Code No:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="code_no" placeholder="Enter Code No" name="code_no" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-12" for="candidate_name">Candidate Name:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="candidate_name" placeholder="Enter Candidate Name" name="candidate_name" required>
                                <input type="hidden" value="<?= $dbrow->service_data->service_id ?>" name="service_id" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-12" for="father_name">Father's Name: </label></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="father_name" placeholder="Enter Father's Name" name="father_name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-12" for="mother_name">Mother's Name: </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="mother_name" placeholder="Enter Mother's Name" name="mother_name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-12" for="gender">Gender: </label>
                            <div class="col-sm-10">
                                <select name="gender" class="form-control" id="gender" required>
                                    <option selected disabled>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Transgender">Transgender</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="institution_name">Institution Name:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="institution_name" placeholder="Enter Institution Name" name="institution_name" required>
                            </div>
                        </div>
                        <?php if ($dbrow->service_data->service_id != "AHSECDRC") { ?>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="stream">Stream:</label>
                                <div class="col-sm-10">
                                    <select class="form-control" required id="stream" name="stream">
                                        <option selected disabled>Stream</option>
                                        <option value="ARTS">ARTS</option>
                                        <option value="SCIENCE">SCIENCE</option>
                                        <option value="COMMERCE">COMMERCE</option>
                                        <option value="VOCATIONAL">VOCATIONAL</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="roll">Roll :</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="roll" placeholder="Enter Roll" name="roll" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="no">No :</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="no" placeholder="Enter No" name="no" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="exam_year">Exam Year:</label>
                                <div class="col-sm-10">
                                    <select class="form-control" required id="exam_year" name="exam_year" required>
                                        <option selected disabled>Select Any One</option>
                                        <?php foreach ($commencing_dates as $cm_date) { ?>
                                            <option value="<?= $cm_date->year ?>"><?= $cm_date->year ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-8" for="commencing_day">Exam Commencing Day:</label>
                                <div class="col-sm-10">
                                    <select class="form-control" required id="commencing_day" name="commencing_day">
                                        <option selected disabled>Select Any One</option>
                                        <?php for ($i = 1; $i <= 31; $i++) { ?>
                                            <option value="<?= $i ?>">
                                                <?= $i ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="commencing_month">Exam Commencing Month:</label>
                                <div class="col-sm-10">
                                    <?php $monthArray = [
                                        '1' => 'January',
                                        2 => 'February',
                                        3 => 'March',
                                        4 => 'April',
                                        5 => 'May',
                                        6 => 'June',
                                        7 => 'July',
                                        8 => 'August',
                                        9 => 'September',
                                        10 => 'October',
                                        11 => 'November',
                                        12 => 'December',
                                    ]; ?>
                                    <select class="form-control" required id="commencing_month" name="commencing_month">
                                        <option selected disabled>Select Any One</option>
                                        <?php foreach ($monthArray as $i => $monthName) { ?>
                                            <option value="<?= $monthName ?>"><?= $monthName ?></option>
                                        <?php }     ?>
                                    </select>

                                </div>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="reg_no">Registration Numbers:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="reg_no" placeholder="Enter Registration Numbers" name="reg_no" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-12" for="date">Date Issued:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="<?= date('d-m-Y') ?>" id="date" name="date" required readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-12" for="session">Session:</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="session" name="session" required>
                                    <option selected disabled>Please Select</option>
                                    <?php foreach ($sessions as $sessionss) { ?>
                                        <option value="<?php echo $sessionss ?>"><?php echo $sessionss ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="mobile_no">Mobile No:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="mobile_no" placeholder="Enter Mobile No" name="mobile_no" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="core_sub_1">Core Subject 1:</label>
                            <div class="col-sm-10">

                                <select class="form-control" required id="core_sub_1" name="core_sub_1">
                                    <option disabled selected>Select Any One</option>
                                    <?php foreach ($subjects as $subject) { ?>
                                        <option value="<?= $subject->subject_code ?>">
                                            <?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="core_sub_2">Core Subject 2:</label>
                            <div class="col-sm-10">
                                <select class="form-control" required id="core_sub_2" name="core_sub_2">
                                    <option disabled selected>Select Any One</option>
                                    <?php foreach ($subjects as $subject) { ?>
                                        <option value="<?= $subject->subject_code ?>">
                                            <?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="elective_sub_3">Elective Subject 1:</label>
                            <div class="col-sm-10">
                                <select class="form-control" required id="elective_sub_3" name="elective_sub_3">
                                    <option disabled selected>Select Any One</option>
                                    <?php foreach ($subjects as $subject) { ?>
                                        <option value="<?= $subject->subject_code ?>">
                                            <?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="elective_sub_4">Elective Subject 2:</label>
                            <div class="col-sm-10">
                                <select class="form-control" required id="elective_sub_4" name="elective_sub_4">
                                    <option disabled selected>Select Any One</option>
                                    <?php foreach ($subjects as $subject) { ?>
                                        <option value="<?= $subject->subject_code ?>">
                                            <?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="elective_sub_5">Elective Subject 3:</label>
                            <div class="col-sm-10">
                                <select class="form-control" required id="elective_sub_5" name="elective_sub_5">
                                    <option disabled selected>Select Any One</option>
                                    <?php foreach ($subjects as $subject) { ?>
                                        <option value="<?= $subject->subject_code ?>">
                                            <?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="elective_sub_6">Elective Subject 4:</label>
                            <div class="col-sm-10">
                                <select class="form-control" required id="elective_sub_6" name="elective_sub_6">
                                    <option disabled selected>Select Any One</option>
                                    <?php foreach ($subjects as $subject) { ?>
                                        <option value="<?= $subject->subject_code ?>">
                                            <?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Submit Master Data</button>
                            </div>
                        </div>
                    </form>
                <?php } else if (($reg_data_cnt < 2) || ($marksheet_data_cnt < 2)) { ?>

                    <?php if ($dbrow->service_data->service_id == "AHSECCRC" || $dbrow->service_data->service_id == "AHSECCADM" || $dbrow->service_data->service_id == "AHSECCPC") { ?>
                        <form class="form-horizontal" action="<?= base_url('spservices/ahsec_correction/actions/update_reg_master_data/' . $objId) ?>" method="POST" autocomplete="off">
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="sl_no">Sl No:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="sl_no" placeholder="Enter Sl No" name="sl_no" value="<?= $sl_no ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="code_no">Code No:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="code_no" placeholder="Enter Code No" name="code_no" value="<?= $institution_code ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-12" for="candidate_name">Candidate
                                    Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <span style="color: red;">To be corrected as-
                                        <?= $dbrow->form_data->correct_candidate_name ?></span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="candidate_name" placeholder="Enter Candidate Name" value="<?= $candidate_name ?>" name="candidate_name" required>

                                    <input type="hidden" value="<?= $dbrow->service_data->service_id ?>" name="service_id" readonly>


                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-12" for="father_name">Father's Name:
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="color: red;">To be corrected as-
                                        <?= $dbrow->form_data->correct_father_name ?></span></label></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="<?= $father_name ?>" id="father_name" placeholder="Enter Father's Name" name="father_name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-12" for="mother_name">Mother's Name:
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="color: red;">To be corrected as-
                                        <?= $dbrow->form_data->correct_mother_name ?></span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="<?= $mother_name ?>" id="mother_name" placeholder="Enter Mother's Name" name="mother_name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-12" for="gender">Gender: </label>
                                <div class="col-sm-10">
                                    <select name="gender" class="form-control" required>
                                        <option value="">Please Select</option>
                                        <option value="MALE" <?= ($gender === "MALE") ? 'selected' : '' ?>>Male
                                        </option>
                                        <option value="FEMALE" <?= ($gender === "FEMALE") ? 'selected' : '' ?>>Female
                                        </option>
                                        <option value="TRANSGENDER" <?= ($gender === "TRANSGENDER") ? 'selected' : '' ?>>
                                            Transgender</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="institution_name">Institution Name:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="institution_name" placeholder="Enter Institution Name" value="<?= $institution_name ?>" name="institution_name" required>
                                </div>
                            </div>
                            <?php if ($dbrow->service_data->service_id != "AHSECCRC") { ?>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="stream">Stream:</label>
                                    <div class="col-sm-10">

                                        <select class="form-control" required id="stream" name="stream">
                                            <option value="<?= $stream ?>"><?= $stream ?></option>
                                            <option value="ARTS">ARTS</option>
                                            <option value="SCIENCE">SCIENCE</option>
                                            <option value="COMMERCE">COMMERCE</option>
                                            <option value="VOCATIONAL">VOCATIONAL</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="roll">Roll :</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="roll" value="<?= $roll ?>" placeholder="Enter Roll" name="roll" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="no">No :</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="no" value="<?= $no ?>" placeholder="Enter No" name="no" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="exam_year">Exam Year:</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" required id="exam_year" name="exam_year" readonly>
                                            <option value="">Select Any One</option>
                                            <?php foreach ($commencing_dates as $cm_date) { ?>
                                                <option <?php if ($cm_date->year == $exam_year) { ?> selected <?php } ?> value="<?= $cm_date->year ?>"><?= $cm_date->year ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-8" for="commencing_day">Exam Commencing Day:</label>
                                    <div class="col-sm-10">

                                        <select class="form-control" required id="commencing_day" name="commencing_day">
                                            <option value="">Select Any One</option>
                                            <?php for ($i = 1; $i <= 31; $i++) { ?>
                                                <option <?php if ($i == $commencing_day) { ?> selected <?php } ?> value="<?= $i ?>"><?= $i ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="commencing_month">Exam Commencing Month:</label>
                                    <div class="col-sm-10">
                                        <!-- <input type="text" class="form-control" id="commencing_month" value="<?= $commencing_month ?>" placeholder="Enter Commencing Month"  name="commencing_month" required> -->
                                        <?php $monthArray = [
                                            '1' => 'January',
                                            2 => 'February',
                                            3 => 'March',
                                            4 => 'April',
                                            5 => 'May',
                                            6 => 'June',
                                            7 => 'July',
                                            8 => 'August',
                                            9 => 'September',
                                            10 => 'October',
                                            11 => 'November',
                                            12 => 'December',
                                        ]; ?>
                                        <select class="form-control" required id="commencing_month" name="commencing_month">
                                            <?php foreach ($monthArray as $i => $monthName) { ?>
                                                <option <?php if ($monthName == $commencing_month) { ?> selected <?php } ?> value="<?= $monthName ?>"><?= $monthName ?></option>
                                            <?php }     ?>
                                        </select>

                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="reg_no">Registration Numbers:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="reg_no" value="<?= $registration_number ?>" placeholder="Enter Registration Numbers" name="reg_no" required readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="date">Registration Date:</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" value="<?= $new_issue_date1 ?>" id="date" placeholder="Enter Date" name="date" required readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-12" for="date">Date Issued:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="<?= date('d-m-Y') ?>" id="date" name="current_date" required readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="session">Session:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="<?= $registration_session ?>" id="session" placeholder="Enter Session" name="session" required readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="mobile_no">Mobile No:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="mobile_no" placeholder="Enter Mobile No" name="mobile_no" value="<?= $mobile_no ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="core_sub_1">Core Subject 1:</label>
                                <div class="col-sm-10">
                                    <!-- <input type="text" class="form-control" id="core_sub_1" value="<?= $sub_1 ?>"
                                placeholder="Enter Core Subject 1" name="core_sub_1" required> -->

                                    <select class="form-control" required id="core_sub_1" name="core_sub_1">
                                        <option value="">Select Any One</option>
                                        <?php foreach ($subjects as $subject) { ?>
                                            <option <?php if ($subject->subject_code == $sub_1) { ?> selected <?php } ?> value="<?= $subject->subject_code ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="core_sub_2">Core Subject 2:</label>
                                <div class="col-sm-10">
                                    <!-- <input type="text" class="form-control" id="core_sub_2" placeholder="Enter Core Subject 2" value="<?= $sub_2 ?>" name="core_sub_2" required> -->
                                    <select class="form-control" required id="core_sub_2" name="core_sub_2">
                                        <option value="">Select Any One</option>
                                        <?php foreach ($subjects as $subject) { ?>
                                            <option <?php if ($subject->subject_code == $sub_2) { ?> selected <?php } ?> value="<?= $subject->subject_code ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="elective_sub_3">Elective Subject 1:</label>
                                <div class="col-sm-10">
                                    <!-- <input type="text" class="form-control" value="<?= $sub_3 ?>" id="elective_sub_3"  placeholder="Enter Elective Subject 3" name="elective_sub_3" required> -->
                                    <select class="form-control" required id="elective_sub_3" name="elective_sub_3">
                                        <option value="">Select Any One</option>
                                        <?php foreach ($subjects as $subject) { ?>
                                            <option <?php if ($subject->subject_code == $sub_3) { ?> selected <?php } ?> value="<?= $subject->subject_code ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="elective_sub_4">Elective Subject 2:</label>
                                <div class="col-sm-10">
                                    <!-- <input type="text" class="form-control" value="<?= $sub_4 ?>" id="elective_sub_4"  placeholder="Enter Elective Subject 4" name="elective_sub_4" required> -->
                                    <select class="form-control" required id="elective_sub_4" name="elective_sub_4">
                                        <option value="">Select Any One</option>
                                        <?php foreach ($subjects as $subject) { ?>
                                            <option <?php if ($subject->subject_code == $sub_4) { ?> selected <?php } ?> value="<?= $subject->subject_code ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="elective_sub_5">Elective Subject 3:</label>
                                <div class="col-sm-10">
                                    <!-- <input type="text" class="form-control" value="<?= $sub_5 ?>" id="elective_sub_5" placeholder="Enter Elective Subject 5" name="elective_sub_5" required> -->
                                    <select class="form-control" required id="elective_sub_5" name="elective_sub_5">
                                        <option value="">Select Any One</option>
                                        <?php foreach ($subjects as $subject) { ?>
                                            <option <?php if ($subject->subject_code == $sub_5) { ?> selected <?php } ?> value="<?= $subject->subject_code ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="elective_sub_6">Elective Subject 4:</label>
                                <div class="col-sm-10">
                                    <!-- <input type="text" class="form-control" value="<?= $sub_6 ?>" id="elective_sub_6" placeholder="Enter Elective Subject 6" name="elective_sub_6" required> -->
                                    <select class="form-control" required id="elective_sub_6" name="elective_sub_6">
                                        <option value="">Select Any One</option>
                                        <?php foreach ($subjects as $subject) { ?>
                                            <option <?php if ($subject->subject_code == $sub_6) { ?> selected <?php } ?> value="<?= $subject->subject_code ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">Update Master Data</button>
                                </div>
                            </div>
                        </form>
                    <?php } else if ($dbrow->service_data->service_id == "AHSECCMRK") { ?>

                        <form class="form-horizontal" action="<?= base_url('spservices/ahsec_correction/actions/update_marksheet_data/' . $objId) ?>" method="POST" autocomplete="off">
                            <h3><b>MARKSHEET INFO</b></h3>
                            <p id="message" class="message"></p>
                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label>Marksheet No <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="ms_no" placeholder="Enter Marksheet No" name="ms_no" value="<?= $ms_no; ?>" required>
                                </div>
                                <div class="col-sm-6">
                                    <label>Stream <span class="text-danger">*</span></label>
                                    <select class="form-control" required id="stream" name="stream">
                                        <option value="<?= $stream ?>"><?= $stream ?></option>
                                        <option value="ARTS">ARTS</option>
                                        <option value="SCIENCE">SCIENCE</option>
                                        <option value="COMMERCE">COMMERCE</option>
                                        <option value="VOCATIONAL">VOCATIONAL</option>
                                    </select>
                                </div>
                            </div>
                            <?php if (empty($marksheet_data)) { ?>
                                <div class="row form-group">
                                    <div class="col-sm-6">
                                        <label>Admit Card Sl No <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="Admit_Card_Serial_No" placeholder="Admit Card Serial No" name="Admit_Card_Serial_No" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Certificate Sl No <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="Certificate_Serial_No" placeholder="Certificate Serial No" name="Certificate_Serial_No" required>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label>Date of Issued <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control dp" value="<?= date('d/m/Y'); ?>" id="mrk_issue_date" placeholder="Enter Issue Date" name="mrk_issue_date" required readonly>
                                </div>
                                <div class="col-sm-6">
                                    <label>Marksheet Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control dp" value="<?= $mrk_date; ?>" id="mrk_date" placeholder="Enter Date" name="mrk_date" required>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label>Candidate Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="<?= $mrk_candidate_name ?>" id="mrk_candidate_name" placeholder="Enter Candidate Name" name="mrk_candidate_name" required>
                                </div>
                                <div class="col-sm-6">
                                    <label>Father's Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="<?= $mrk_father_name ?>" id="mrk_father_name" placeholder="Enter Father Name" name="mrk_father_name" required>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label>Mother's Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="<?= $mrk_mother_name ?>" id="mrk_mother_name" placeholder="Enter Mother Name" name="mrk_mother_name" required>
                                </div>
                                <div class="col-sm-6">
                                    <label>School / College Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="<?= $mrk_school_name ?>" id="mrk_school_name" placeholder="Enter School Name" name="mrk_school_name" required>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label>Roll <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="<?= $mrk_roll ?>" id="mrk_roll" placeholder="Enter Roll" name="mrk_roll" required <?php if (!empty($marksheet_data)) echo 'readonly'; ?>>
                                </div>
                                <div class="col-sm-6">
                                    <label>No <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="<?= $mrk_no ?>" id="mrk_no" placeholder="Enter No" name="mrk_no" required <?php if (!empty($marksheet_data)) echo 'readonly'; ?>>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label>Registration Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="mrk_rc_no" value="<?= $mrk_rc_no ?>" placeholder="Enter Registration Numbers" name="mrk_rc_no" required <?php if (!empty($marksheet_data)) echo 'readonly'; ?>>
                                </div>
                                <div class="col-sm-6">
                                    <label>Session <span class="text-danger">*</span> [Format should be yyyy-yy]</label>
                                    <input type="text" class="form-control" value="<?= $mrk_rc_session ?>" id="mrk_rc_session" placeholder="Enter Session" name="mrk_rc_session" required <?php if (!empty($marksheet_data)) echo 'readonly'; ?>>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label>Year of Examination <span class="text-danger">*</span></label>
                                    <select class="form-control" required id="Year_of_Examination" name="Year_of_Examination" required <?php if (!empty($marksheet_data)) echo 'readonly'; ?>>
                                        <option value="">Select Any One</option>
                                        <?php foreach ($commencing_dates as $cm_date) { ?>
                                            <option <?php if ($cm_date->year == $Year_of_Examination) { ?> selected <?php } ?> value="<?= $cm_date->year ?>"><?= $cm_date->year ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <input type="checkbox" id="update_marks" name="update_marks" value="yes">
                            <label for="update_marks"> Update Marks</label>
                            <div id="update_marks">
                                <h3><b>ENTER MARKS</b></h3>
                                <?php if (!empty($marksheet_data) && $checkSubCodeFieldsAvailability) { ?>
                                    <input type="hidden" class="form-control" value="UPDATE_DATA" name="status">

                                    <?php if (!empty($mrk_data['Sl1_id'])) { ?>
                                        <div class="row form-group">
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sl1_id'] ?>" id="<?= $mrk_data['Sl1_id'] ?>" name="Sl1_id">
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sub1_Code'] ?>" id="<?= $mrk_data['Sub1_Code'] ?>" name="Sub1_Code">
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sub1_Pap_Indicator'] ?>" id="<?= $mrk_data['Sub1_Pap_Indicator'] ?>" name="Sub1_Pap_Indicator">
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sub1_Name'] ?>" id="<?= $mrk_data['Sub1_Name'] ?>" name="Sub1_Name">
                                            <div class="col-sm-2">
                                                <label>Core 1 <span class="text-danger">*</span></label>
                                                <select class="form-control" required id="core_sub_1" name="core_sub_1">
                                                    <option value="">Select Any One</option>
                                                    <?php foreach ($subjects as $subject) { ?>
                                                        <option <?php if ($subject->subject_code == $mrk_data['Sub1_Code']) { ?> selected <?php } ?> value="<?= $subject->subject_code . '|' . $subject->subject_name ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <label>Core 1 Theory Marks <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="<?= $mrk_data['Sub1_Actual_Marks'] ?>" id="Sub1_Actual_Marks" name="Sub1_Actual_Marks" required>
                                            </div>
                                            <div class="col-sm-2">
                                                <label>Core 1 Grace Marks</label>
                                                <input type="text" class="form-control" value="<?= $mrk_data['Sub1_Grace_Marks'] ?>" id="Sub1_Grace_Marks" name="Sub1_Grace_Marks">
                                            </div>
                                            <div class="col-sm-2">
                                                <label>Core 1 Practical Marks</label>
                                                <input type="text" class="form-control" value="<?= $mrk_data['Sub1_Pr_Marks'] ?>" id="Sub1_Pr_Marks" name="Sub1_Pr_Marks">
                                            </div>
                                            <div class="col-sm-2">
                                                <label>Core 1 Total Marks <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="<?= $mrk_data['Sub1_Tot_Marks'] ?>" id="Sub1_Tot_Marks" name="Sub1_Tot_Marks" required>
                                            </div>
                                        </div>
                                    <?php }
                                    if (!empty($mrk_data['Sl2_id'])) { ?>
                                        <div class="row form-group">
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sl2_id'] ?>" id="<?= $mrk_data['Sl2_id'] ?>" name="Sl2_id">
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sub2_Code'] ?>" id="<?= $mrk_data['Sub2_Code'] ?>" name="Sub2_Code">
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sub2_Pap_Indicator'] ?>" id="<?= $mrk_data['Sub2_Pap_Indicator'] ?>" name="Sub2_Pap_Indicator">
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sub2_Name'] ?>" id="<?= $mrk_data['Sub2_Name'] ?>" name="Sub2_Name">

                                            <div class="col-sm-2">
                                                <label>Core 2 <span class="text-danger">*</span></label>
                                                <select class="form-control" required id="core_sub_2" name="core_sub_2">
                                                    <option value="">Select Any One</option>
                                                    <?php foreach ($subjects as $subject) { ?>
                                                        <option <?php if ($subject->subject_code == $mrk_data['Sub2_Code']) { ?> selected <?php } ?> value="<?= $subject->subject_code . '|' . $subject->subject_name  ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <label>Core 2 Theory Marks <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="<?= $mrk_data['Sub2_Actual_Marks'] ?>" id="Sub2_Actual_Marks" name="Sub2_Actual_Marks" required>
                                            </div>
                                            <div class="col-sm-2">
                                                <label>Core 2 Grace Marks</label>
                                                <input type="text" class="form-control" value="<?= $mrk_data['Sub2_Grace_Marks'] ?>" id="Sub2_Grace_Marks" name="Sub2_Grace_Marks">
                                            </div>
                                            <div class="col-sm-2">
                                                <label>Core 2 Practical Marks</label>
                                                <input type="text" class="form-control" value="<?= $mrk_data['Sub2_Pr_Marks'] ?>" id="Sub2_Pr_Marks" name="Sub2_Pr_Marks">
                                            </div>
                                            <div class="col-sm-2">
                                                <label>Core 2 Total Marks <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="<?= $mrk_data['Sub2_Tot_Marks'] ?>" id="Sub2_Tot_Marks" name="Sub2_Tot_Marks" required>
                                            </div>
                                        </div>
                                    <?php }
                                    if (!empty($mrk_data['Sl3_id'])) { ?>
                                        <?php if ($mrk_data['Sub3_Code'] == "BIOL") { ?>
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sl3_id'] ?>" id="<?= $mrk_data['Sl3_id'] ?>" name="Sl3_id">
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sub3_Code'] ?>" id="<?= $mrk_data['Sub3_Code'] ?>" name="Sub3_Code">
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sub3_Pap_Indicator'] ?>" id="<?= $mrk_data['Sub3_Pap_Indicator'] ?>" name="Sub3_Pap_Indicator">
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sub3_Name'] ?>" id="<?= $mrk_data['Sub3_Name'] ?>" name="Sub3_Name">
                                            <!-- Botany -->
                                            <div class="row form-group">
                                                <div class="col-sm-2">
                                                    <label>Elective 1-A <span class="text-danger">*</span></label>
                                                    <select class="form-control" required id="elective_sub_3_bot" name="elective_sub_3_bot">
                                                        <option value="">Select Any One</option>
                                                        <?php foreach ($subjects as $subject) { ?>
                                                            <option <?php if ($subject->subject_code == 'BOTA') { ?> selected <?php } ?> value="<?= $subject->subject_code . '|' . $subject->subject_name ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Botany Theory Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub3_Botany_Marks'] ?>" id="Sub3_Botany_Marks" name="Sub3_Botany_Marks" required>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Botany Grace Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub3_Botany_Grace_Marks'] ?>" id="Sub3_Botany_Grace_Marks" name="Sub3_Botany_Grace_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Botany Practical Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub3_Botany_Pr_Marks'] ?>" id="Sub3_Botany_Pr_Marks" name="Sub3_Botany_Pr_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Botany Total Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub3_Botany_Tot_Marks'] ?>" id="Sub3_Botany_Tot_Marks" name="Sub3_Botany_Tot_Marks" required>
                                                </div>
                                            </div>
                                            <!-- Zoology -->
                                            <div class="row form-group">
                                                <div class="col-sm-2">
                                                    <label>Elective 1-B <span class="text-danger">*</span></label>
                                                    <select class="form-control" required id="elective_sub_3_zoo" name="elective_sub_3_zoo">
                                                        <option value="">Select Any One</option>
                                                        <?php foreach ($subjects as $subject) { ?>
                                                            <option <?php if ($subject->subject_code == 'ZOOL') { ?> selected <?php } ?> value="<?= $subject->subject_code . '|' . $subject->subject_name ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Zoology Theory Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub3_Zoology_Marks'] ?>" id="Sub3_Zoology_Marks" name="Sub3_Zoology_Marks" required>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Zoology Grace Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub3_Zoology_Grace_Marks'] ?>" id="Sub3_Zoology_Grace_Marks" name="Sub3_Zoology_Grace_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Zoology Practical Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub3_Zoology_Pr_Marks'] ?>" id="Sub3_Zoology_Pr_Marks" name="Sub3_Zoology_Pr_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Zoology Total Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub3_Zoology_Tot_Marks'] ?>" id="Sub3_Zoology_Tot_Marks" name="Sub3_Zoology_Tot_Marks" required>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="row form-group">
                                                <input type="hidden" class="form-control" value="<?= $mrk_data['Sl3_id'] ?>" id="<?= $mrk_data['Sl3_id'] ?>" name="Sl3_id">
                                                <input type="hidden" class="form-control" value="<?= $mrk_data['Sub3_Code'] ?>" id="<?= $mrk_data['Sub3_Code'] ?>" name="Sub3_Code">
                                                <input type="hidden" class="form-control" value="<?= $mrk_data['Sub3_Pap_Indicator'] ?>" id="<?= $mrk_data['Sub3_Pap_Indicator'] ?>" name="Sub3_Pap_Indicator">
                                                <input type="hidden" class="form-control" value="<?= $mrk_data['Sub3_Name'] ?>" id="<?= $mrk_data['Sub3_Name'] ?>" name="Sub3_Name">
                                                <div class="col-sm-2">
                                                    <label>Elective 1 <span class="text-danger">*</span></label>
                                                    <select class="form-control" required id="elective_sub_1" name="elective_sub_1">
                                                        <option value="">Select Any One</option>
                                                        <?php foreach ($subjects as $subject) { ?>
                                                            <option <?php if ($subject->subject_code == $mrk_data['Sub3_Code']) { ?> selected <?php } ?> value="<?= $subject->subject_code . '|' . $subject->subject_name ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Elective 1 Theory Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub3_Actual_Marks'] ?>" id="Sub3_Actual_Marks" name="Sub3_Actual_Marks" required>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Elective 1 Grace Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub3_Grace_Marks'] ?>" id="Sub3_Grace_Marks" name="Sub3_Grace_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Elective 1 Practical Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub3_Pr_Marks'] ?>" id="Sub3_Pr_Marks" name="Sub3_Pr_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Elective 1 Total Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub3_Tot_Marks'] ?>" id="Sub3_Tot_Marks" name="Sub3_Tot_Marks" required>
                                                </div>
                                            </div>
                                        <?php }
                                    }
                                    if (!empty($mrk_data['Sl4_id'])) {
                                        if ($mrk_data['Sub4_Code'] == "BIOL") { ?>
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sl4_id'] ?>" id="<?= $mrk_data['Sl4_id'] ?>" name="Sl4_id">
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sub4_Code'] ?>" id="<?= $mrk_data['Sub4_Code'] ?>" name="Sub4_Code">
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sub4_Pap_Indicator'] ?>" id="<?= $mrk_data['Sub4_Pap_Indicator'] ?>" name="Sub4_Pap_Indicator">
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sub4_Name'] ?>" id="<?= $mrk_data['Sub4_Name'] ?>" name="Sub4_Name">
                                            <!-- Botany -->
                                            <div class="row form-group">
                                                <div class="col-sm-2">
                                                    <label>Elective 2-A <span class="text-danger">*</span></label>
                                                    <select class="form-control" required id="elective_sub_4_bot" name="elective_sub_4_bot">
                                                        <option value="">Select Any One</option>
                                                        <?php foreach ($subjects as $subject) { ?>
                                                            <option <?php if ($subject->subject_code == 'BOTA') { ?> selected <?php } ?> value="<?= $subject->subject_code . '|' . $subject->subject_name ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Botany Theory Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub4_Botany_Marks'] ?>" id="Sub4_Botany_Marks" name="Sub4_Botany_Marks" required>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Botany Grace Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub4_Botany_Grace_Marks'] ?>" id="Sub4_Botany_Grace_Marks" name="Sub4_Botany_Grace_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Botany Practical Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub4_Botany_Pr_Marks'] ?>" id="Sub4_Botany_Pr_Marks" name="Sub4_Botany_Pr_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Botany Total Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub4_Botany_Tot_Marks'] ?>" id="Sub4_Botany_Tot_Marks" name="Sub4_Botany_Tot_Marks" required>
                                                </div>
                                            </div>
                                            <!-- Zoology -->
                                            <div class="row form-group">
                                                <div class="col-sm-2">
                                                    <label>Elective 2-B <span class="text-danger">*</span></label>
                                                    <select class="form-control" required id="elective_sub_4_zoo" name="elective_sub_4_zoo">
                                                        <option value="">Select Any One</option>
                                                        <?php foreach ($subjects as $subject) { ?>
                                                            <option <?php if ($subject->subject_code == 'ZOOL') { ?> selected <?php } ?> value="<?= $subject->subject_code . '|' . $subject->subject_name ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Zoology Theory Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub4_Zoology_Marks'] ?>" id="Sub4_Zoology_Marks" name="Sub4_Zoology_Marks" required>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Zoology Grace Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub4_Zoology_Grace_Marks'] ?>" id="Sub4_Zoology_Grace_Marks" name="Sub4_Zoology_Grace_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Zoology Practical Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub4_Zoology_Pr_Marks'] ?>" id="Sub4_Zoology_Pr_Marks" name="Sub4_Zoology_Pr_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Zoology Total Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub4_Zoology_Tot_Marks'] ?>" id="Sub4_Zoology_Tot_Marks" name="Sub4_Zoology_Tot_Marks" required>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="row form-group">
                                                <input type="hidden" class="form-control" value="<?= $mrk_data['Sl4_id'] ?>" id="<?= $mrk_data['Sl4_id'] ?>" name="Sl4_id">
                                                <input type="hidden" class="form-control" value="<?= $mrk_data['Sub4_Code'] ?>" id="<?= $mrk_data['Sub4_Code'] ?>" name="Sub4_Code">
                                                <input type="hidden" class="form-control" value="<?= $mrk_data['Sub4_Pap_Indicator'] ?>" id="<?= $mrk_data['Sub4_Pap_Indicator'] ?>" name="Sub4_Pap_Indicator">
                                                <input type="hidden" class="form-control" value="<?= $mrk_data['Sub4_Name'] ?>" id="<?= $mrk_data['Sub4_Name'] ?>" name="Sub4_Name">
                                                <div class="col-sm-2">
                                                    <label>Elective 2 <span class="text-danger">*</span></label>
                                                    <select class="form-control" required id="elective_sub_2" name="elective_sub_2">
                                                        <option value="">Select Any One</option>
                                                        <?php foreach ($subjects as $subject) { ?>
                                                            <option <?php if ($subject->subject_code == $mrk_data['Sub4_Code']) { ?> selected <?php } ?> value="<?= $subject->subject_code . '|' . $subject->subject_name ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Elective 2 Theory Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub4_Actual_Marks'] ?>" id="Sub4_Actual_Marks" name="Sub4_Actual_Marks" required>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Elective 2 Grace Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub4_Grace_Marks'] ?>" id="Sub4_Grace_Marks" name="Sub4_Grace_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Elective 2 Practical Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub4_Pr_Marks'] ?>" id="Sub4_Pr_Marks" name="Sub4_Pr_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Elective 2 Total Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub4_Tot_Marks'] ?>" id="Sub4_Tot_Marks" name="Sub4_Tot_Marks" required>
                                                </div>
                                            </div>
                                        <?php }
                                    }
                                    if (!empty($mrk_data['Sl5_id'])) {
                                        if ($mrk_data['Sub5_Code'] == "BIOL") { ?>
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sl5_id'] ?>" id="<?= $mrk_data['Sl5_id'] ?>" name="Sl5_id">
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sub5_Code'] ?>" id="<?= $mrk_data['Sub5_Code'] ?>" name="Sub5_Code">
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sub5_Pap_Indicator'] ?>" id="<?= $mrk_data['Sub5_Pap_Indicator'] ?>" name="Sub5_Pap_Indicator">
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sub5_Name'] ?>" id="<?= $mrk_data['Sub5_Name'] ?>" name="Sub5_Name">
                                            <!-- Botany -->
                                            <div class="row form-group">
                                                <div class="col-sm-2">
                                                    <label>Elective 3-A <span class="text-danger">*</span></label>
                                                    <select class="form-control" required id="elective_sub_5_bot" name="elective_sub_5_bot">
                                                        <option value="">Select Any One</option>
                                                        <?php foreach ($subjects as $subject) { ?>
                                                            <option <?php if ($subject->subject_code == 'BOTA') { ?> selected <?php } ?> value="<?= $subject->subject_code . '|' . $subject->subject_name ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Botany Theory Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub5_Botany_Marks'] ?>" id="Sub5_Botany_Marks" name="Sub5_Botany_Marks" required>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Botany Grace Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub5_Botany_Grace_Marks'] ?>" id="Sub5_Botany_Grace_Marks" name="Sub5_Botany_Grace_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Botany Practical Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub5_Botany_Pr_Marks'] ?>" id="Sub5_Botany_Pr_Marks" name="Sub5_Botany_Pr_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Botany Total Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub5_Botany_Tot_Marks'] ?>" id="Sub5_Botany_Tot_Marks" name="Sub5_Botany_Tot_Marks" required>
                                                </div>
                                            </div>
                                            <!-- Zoology -->
                                            <div class="row form-group">
                                                <div class="col-sm-2">
                                                    <label>Elective 3-B <span class="text-danger">*</span></label>
                                                    <select class="form-control" required id="elective_sub_5_zoo" name="elective_sub_5_zoo">
                                                        <option value="">Select Any One</option>
                                                        <?php foreach ($subjects as $subject) { ?>
                                                            <option <?php if ($subject->subject_code == 'ZOOL') { ?> selected <?php } ?> value="<?= $subject->subject_code . '|' . $subject->subject_name ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Zoology Theory Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub5_Zoology_Marks'] ?>" id="Sub5_Zoology_Marks" name="Sub5_Zoology_Marks" required>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Zoology Grace Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub5_Zoology_Grace_Marks'] ?>" id="Sub5_Zoology_Grace_Marks" name="Sub5_Zoology_Grace_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Zoology Practical Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub5_Zoology_Pr_Marks'] ?>" id="Sub5_Zoology_Pr_Marks" name="Sub5_Zoology_Pr_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Zoology Total Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub5_Zoology_Tot_Marks'] ?>" id="Sub5_Zoology_Tot_Marks" name="Sub5_Zoology_Tot_Marks" required>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="row form-group">
                                                <input type="hidden" class="form-control" value="<?= $mrk_data['Sl5_id'] ?>" id="<?= $mrk_data['Sl5_id'] ?>" name="Sl5_id">
                                                <input type="hidden" class="form-control" value="<?= $mrk_data['Sub5_Code'] ?>" id="<?= $mrk_data['Sub5_Code'] ?>" name="Sub5_Code">
                                                <input type="hidden" class="form-control" value="<?= $mrk_data['Sub5_Pap_Indicator'] ?>" id="<?= $mrk_data['Sub5_Pap_Indicator'] ?>" name="Sub5_Pap_Indicator">
                                                <input type="hidden" class="form-control" value="<?= $mrk_data['Sub5_Name'] ?>" id="<?= $mrk_data['Sub5_Name'] ?>" name="Sub5_Name">
                                                <div class="col-sm-2">
                                                    <label>Elective 3 <span class="text-danger">*</span></label>
                                                    <select class="form-control" required id="elective_sub_3" name="elective_sub_3">
                                                        <option value="">Select Any One</option>
                                                        <?php foreach ($subjects as $subject) { ?>
                                                            <option <?php if ($subject->subject_code == $mrk_data['Sub5_Code']) { ?> selected <?php } ?> value="<?= $subject->subject_code . '|' . $subject->subject_name ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Elective 3 Theory Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub5_Actual_Marks'] ?>" id="Sub5_Actual_Marks" name="Sub5_Actual_Marks" required>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Elective 3 Grace Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub5_Grace_Marks'] ?>" id="Sub5_Grace_Marks" name="Sub5_Grace_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Elective 3 Practical Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub5_Pr_Marks'] ?>" id="Sub5_Pr_Marks" name="Sub5_Pr_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Elective 3 Total Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub5_Tot_Marks'] ?>" id="Sub5_Tot_Marks" name="Sub5_Tot_Marks" required>
                                                </div>
                                            </div>
                                        <?php }
                                    }
                                    if (!empty($mrk_data['Sl6_id'])) {
                                        if ($mrk_data['Sub6_Code'] == "BIOL") { ?>
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sl6_id'] ?>" id="<?= $mrk_data['Sl6_id'] ?>" name="Sl6_id">
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sub6_Code'] ?>" id="<?= $mrk_data['Sub6_Code'] ?>" name="Sub6_Code">
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sub6_Pap_Indicator'] ?>" id="<?= $mrk_data['Sub6_Pap_Indicator'] ?>" name="Sub6_Pap_Indicator">
                                            <input type="hidden" class="form-control" value="<?= $mrk_data['Sub6_Name'] ?>" id="<?= $mrk_data['Sub6_Name'] ?>" name="Sub6_Name">
                                            <!-- Botany -->
                                            <div class="row form-group">
                                                <div class="col-sm-2">
                                                    <label>Elective 4-A <span class="text-danger">*</span></label>
                                                    <select class="form-control" required id="elective_sub_6_bot" name="elective_sub_6_bot">
                                                        <option value="">Select Any One</option>
                                                        <?php foreach ($subjects as $subject) { ?>
                                                            <option <?php if ($subject->subject_code == 'BOTA') { ?> selected <?php } ?> value="<?= $subject->subject_code . '|' . $subject->subject_name ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Botany Theory Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub6_Botany_Marks'] ?>" id="Sub6_Botany_Marks" name="Sub6_Botany_Marks" required>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Botany Grace Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub6_Botany_Grace_Marks'] ?>" id="Sub6_Botany_Grace_Marks" name="Sub6_Botany_Grace_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Botany Practical Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub6_Botany_Pr_Marks'] ?>" id="Sub6_Botany_Pr_Marks" name="Sub6_Botany_Pr_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Botany Total Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub6_Botany_Tot_Marks'] ?>" id="Sub6_Botany_Tot_Marks" name="Sub6_Botany_Tot_Marks" required>
                                                </div>
                                            </div>
                                            <!-- Zoology -->
                                            <div class="row form-group">
                                                <div class="col-sm-2">
                                                    <label>Elective 4-B <span class="text-danger">*</span></label>
                                                    <select class="form-control" required id="elective_sub_6_zoo" name="elective_sub_6_zoo">
                                                        <option value="">Select Any One</option>
                                                        <?php foreach ($subjects as $subject) { ?>
                                                            <option <?php if ($subject->subject_code == 'ZOOL') { ?> selected <?php } ?> value="<?= $subject->subject_code . '|' . $subject->subject_name ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Zoology Theory Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub6_Zoology_Marks'] ?>" id="Sub6_Zoology_Marks" name="Sub6_Zoology_Marks" required>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Zoology Grace Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub6_Zoology_Grace_Marks'] ?>" id="Sub6_Zoology_Grace_Marks" name="Sub6_Zoology_Grace_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Zoology Practical Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub6_Zoology_Pr_Marks'] ?>" id="Sub6_Zoology_Pr_Marks" name="Sub6_Zoology_Pr_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Zoology Total Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub6_Zoology_Tot_Marks'] ?>" id="Sub6_Zoology_Tot_Marks" name="Sub6_Zoology_Tot_Marks" required>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="row form-group">
                                                <input type="hidden" class="form-control" value="<?= $mrk_data['Sl6_id'] ?>" id="<?= $mrk_data['Sl6_id'] ?>" name="Sl6_id">
                                                <input type="hidden" class="form-control" value="<?= $mrk_data['Sub6_Code'] ?>" id="<?= $mrk_data['Sub6_Code'] ?>" name="Sub6_Code">
                                                <input type="hidden" class="form-control" value="<?= $mrk_data['Sub6_Pap_Indicator'] ?>" id="<?= $mrk_data['Sub6_Pap_Indicator'] ?>" name="Sub6_Pap_Indicator">
                                                <input type="hidden" class="form-control" value="<?= $mrk_data['Sub6_Name'] ?>" id="<?= $mrk_data['Sub6_Name'] ?>" name="Sub6_Name">
                                                <div class="col-sm-2">
                                                    <label>Elective 4 <span class="text-danger">*</span></label>
                                                    <select class="form-control" required id="elective_sub_4" name="elective_sub_4">
                                                        <option value="">Select Any One</option>
                                                        <?php foreach ($subjects as $subject) { ?>
                                                            <option <?php if ($subject->subject_code == $mrk_data['Sub6_Code']) { ?> selected <?php } ?> value="<?= $subject->subject_code . '|' . $subject->subject_name ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Elective 4 Theory Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub6_Actual_Marks'] ?>" id="Sub6_Actual_Marks" name="Sub6_Actual_Marks" required>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Elective 4 Grace Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub6_Grace_Marks'] ?>" id="Sub6_Grace_Marks" name="Sub6_Grace_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Elective 4 Practical Marks</label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub6_Pr_Marks'] ?>" id="Sub6_Pr_Marks" name="Sub6_Pr_Marks">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Elective 4 Total Marks <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $mrk_data['Sub6_Tot_Marks'] ?>" id="Sub6_Tot_Marks" name="Sub6_Tot_Marks" required>
                                                </div>
                                            </div>
                                    <?php }
                                    }
                                } else {
                                    ?>
                                    <input type="hidden" class="form-control" value="INSERT_DATA" name="status">
                                    <fieldset class="border border-success" style="margin-top:40px">
                                        <legend class="h5">Fresh Entry </legend>
                                        <div class="row form-group" style="padding:10px">
                                            <div class="col-md-12">
                                                <table class="table table-bordered" id="marksheetEntrytbl">
                                                    <thead>
                                                        <tr>
                                                            <th>Sub Sl. No<span class="text-danger">*</span></th>
                                                            <th>Subject Code & Name<span class="text-danger">*</span></th>
                                                            <th>Pap Indicator<span class="text-danger">*</span></th>
                                                            <th>Th. marks<span class="text-danger">*</span></th>
                                                            <th>Pr. marks<span class="text-danger">*</span></th>
                                                            <th>Grc. marks<span class="text-danger">*</span></th>
                                                            <th>Total marks<span class="text-danger">*</span></th>
                                                            <th style="width:65px;text-align: center">#</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <select name="sub_sl_no[]" class="form-control" id="sub_sl_no" required>
                                                                    <option value="">Sl No</option>
                                                                    <?php
                                                                    for ($i = 1; $i <= 16; $i++) {
                                                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="Sub_Code[]" class="form-control" id="Sub_Code" required>
                                                                    <option value="">Select Any One</option>
                                                                    <?php foreach ($subjects as $subject) { ?>
                                                                        <option value="<?= $subject->subject_code . '|' . $subject->subject_name ?>"><?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="Sub_Pap_Indicator[]" class="form-control" id="Sub_Pap_Indicator" required>
                                                                    <option value="">Select</option>
                                                                    <option value="CORE">CORE</option>
                                                                    <option value="EL-I">EL-I</option>
                                                                    <option value="EL-II">EL-II</option>
                                                                    <option value="EL-III">EL-III</option>
                                                                    <option value="EL-IV">EL-IV</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" maxlength="3" class="form-control" id="Sub1_Actual_Marks" name="Sub_Actual_Marks[]" required>
                                                            </td>
                                                            <td>
                                                                <input type="text" maxlength="3" class="form-control" id="Sub1_Pr_Marks" name="Sub_Pr_Marks[]">
                                                            </td>
                                                            <td>
                                                                <input type="text" maxlength="3" class="form-control" id="Sub1_Grace_Marks" name="Sub_Grace_Marks[]">
                                                            </td>
                                                            <td>
                                                                <input type="text" maxlength="3" class="form-control" id="Sub1_Tot_Marks" name="Sub_Tot_Marks[]" required>
                                                            </td>
                                                            <td style="text-align:center">
                                                                <button class="btn btn-info" id="addlatblrow" type="button">
                                                                    <i class="fa fa-plus-circle"></i>
                                                                </button>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </fieldset>
                                <?php } ?>

                                <hr>
                                <div class="row form-group">
                                    <div class="col-sm-6">
                                        <label>Total Marks in Words <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="Total_Marks_in_Words" placeholder="Total Marks in Words" name="Total_Marks_in_Words" value="<?= $mrk_data['Total_Marks_in_Words']; ?>" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Total Marks in Figure <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="Total_Marks_in_Figure" placeholder="Total Marks in Figure" name="Total_Marks_in_Figure" value="<?= $mrk_data['Total_Marks_in_Figure']; ?>" required>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-6">
                                        <label>Total Grace in Figure <span class="text-danger">*</span></label>
                                        <input type="input" class="form-control" value="<?= $mrk_data['Total_Grace_in_Figure']; ?>" id="Total_Grace_in_Figure" placeholder="Total Grace in Figure" name="Total_Grace_in_Figure" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Result <span class="text-danger">*</span></label>
                                        <select class="form-control" required id="Result" name="Result">
                                            <option value="<?= $mrk_data['Result'] ?>"><?= $mrk_data['Result'] ?></option>
                                            <option value="FIRST DIVISION">FIRST DIVISION</option>
                                            <option value="SECOND DIVISION">SECOND DIVISION</option>
                                            <option value="THIRD DIVISION">THIRD DIVISION</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-6">
                                        <label>Remarks2</label>
                                        <input type="textarea" class="form-control" id="Remarks2" placeholder="Remarks2" name="Remarks2" value="<?= $mrk_data['Remarks2']; ?>">
                                    </div>
                                    <div class="col-sm-6">
                                        <label>ENVE Grade <span class="text-danger">*</span></label>
                                        <select class="form-control" required id="ENVE_Grade" name="ENVE_Grade">
                                            <option value="<?= $mrk_data['ENVE_Grade'] ?>"><?= $mrk_data['ENVE_Grade'] ?></option>
                                            <option value="A+">A+</option>
                                            <option value="A">A</option>
                                            <option value="B+">B+</option>
                                            <option value="B">B</option>
                                            <option value="C+">C+</option>
                                            <option value="C">C</option>
                                            <option value="D+">D+</option>
                                            <option value="D">D</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-8 col-sm-4">
                                    <button type="submit" class="btn btn-primary">Update Records</button>
                                </div>
                            </div>
                        </form>
                    <?php } ?>

                <?php } else { ?>
                    <p>We have found multiple records in Master Database !</p>
                <?php } ?>
            </div>
            <!--End of .card-->
        </div>
    </div>
</div>

<script>
    function showMessage(value) {
        var messageElement = document.getElementById("message");

        if (value === "SHOW_MESSAGE") {
            messageElement.innerHTML = "<i class='icon fas fa-hand-point-right'></i> PLEASE EXERCISE CAUTION WHILE CHANGING/UPDATING CANDIDATE ACADEMIC DETAILS TO ENSURE ACCURACY AND RELIABILITY OF OUR RECORDS";
            messageElement.classList.add("blink");
        } else {
            messageElement.innerHTML = "";
            messageElement.classList.remove("blink");
        }
    }
</script>