<?php
if ($dbrow->service_data->appl_status === 'QS') { ?>
    <fieldset class="border border-danger" style="margin-top:40px; margin-bottom: 8px">
        <legend class="h5">QUERY DETAILS </legend>
        <div class="row">
            <div class="col-md-6" style="font-size:16px; margin-bottom: 10px; text-align: justify">
                <label>Official Remark: </label><?= (end($dbrow->processing_history)->remarks) ?? '' ?>
            </div>
            <div class="col-md-6" style="font-size:16px; margin-bottom: 10px; text-align: justify">
                <span style="float:right; font-size: 12px">
                    Query time : <?= isset(end($dbrow->processing_history)->processing_time) ? format_mongo_date(end($dbrow->processing_history)->processing_time) : '' ?>
                </span>
            </div>
        </div>

    </fieldset>
<?php } //End of if 
?>