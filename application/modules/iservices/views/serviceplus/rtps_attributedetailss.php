<div class="content-wrapper">
    <div class="container mt-3 mb-3">
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
        </div>
        <div class="row">
            <div class="col-md-12 mt-3 text-center">
                <?php if ($this->session->userdata('errmessage') <> '') { ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Alert:</strong>
                        <?php echo $this->session->userdata('errmessage') <> '' ? $this->session->userdata('errmessage') : ''; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="card shadow">
            <div class="card-header" style="background:#9edad8">Application Form</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5"><label>Application Ref. No. : </label></div>
                    <div class="col-md-7"><label><span class="text-danger"><?= isset($data->appl_ref_no) ? $data->appl_ref_no : ""; ?></span></label></div>
                </div>
                <div class="row">
                    <div class="col-md-5"><label>Service Name</label></div>
                    <div class="col-md-7"><?= $data->service_name ?></div>
                </div>
                <?php foreach ($attribute_details as $key => $val) : ?>

                    <?php if (is_numeric($key) ||  is_array($val) || is_object($val) || preg_match('/^(\/opt03\/).*/', strval($val)) === 1 || preg_match('/^(\/opt\/).*/', strval($val)) === 1 || preg_match('/(\/opt_new)/', strval($val)) === 1) : continue; ?>

                    <?php else : ?>
                        <div class="row">
                            <div class="col-md-5"><label><?= ucwords(join(' ', explode('_', $key))) ?></label></div>
                            <div class="col-md-7"><?= $val ?></div>
                        </div>
                    <?php endif; ?>

                <?php endforeach; ?>


                <!-- Display nested data -->
                <?php
                //pre($attribute_details);
                foreach ($attribute_details as $key => $val) {
                    if (is_object($val) || is_array($val)) :   $new_val_array = (array)$val;
                ?>

                        <p class="rtps-table-header"><?= ucwords(join(' ', explode('_', $key))) ?></p>
                        <div class="rtps_table">
                            <?php foreach ($new_val_array as $obj) : ?>
                                <div class="rtps_row">
                                    <?php foreach ($obj as $o_key => $o_val) :
                                        if (!empty($o_val)) { ?>

                                            <div class="rtps_col">
                                                <span class="td-header"><b><?= ucwords(join(' ', explode('_', $o_key))) ?>:</b></span>
                                                <span class="td-value"><?= $o_val ?></span>
                                            </div>

                                    <?php }
                                    endforeach; echo '<br>';?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                <?php endif;
                }
                ?>



            </div>
        </div>

        <div class="card shadow">
            <div class="card-header" style="background:#9edad8">List of Annexure(s)</div>
            <div class="card-body">
                <?php
                if (!empty($enclosure_details)) {
                    $i = 1;
                    foreach ($enclosure_details as $key => $value) { //pre($enclosure_details);
                        echo '<div class="row">';
                        foreach ($value as $sub_key => $sub_val) {

                            // If sub_val is an array then again
                            // iterate through each element of it
                            // else simply print the value of sub_key
                            // and sub_val
                            if (is_array($sub_val)) {
                                echo $sub_key . " : \n";
                                foreach ($sub_val as $k => $v) {
                                    echo "\t" . $k . " = " . $v . "\n";
                                }
                            } else {
                                if ($sub_key == "file_path") {

                ?>
                                    <div class="col-md-4"><a href="<?= base_url("iservices/serviceplus/rtps_annexures/") . $i . '/' . $object_id ?>">View Document</a></div>

                                <?php       } else { ?>
                                    <div class="col-md-4"><label><?= 'Annexure-'.$i//$sub_vale ?></label></div>
                <?php
                                }
                            }
                        }
                        $i++;
                        echo '</div>';
                    }
                } else {
                    echo 'No annexure(s) found!';
                }
                ?>
            </div>
        </div>


    </div>
</div>