<?php print_r($attribute_detais);?>
div class="row">
    <div class="col-md-5"><label>Selected Office</label></div>
    <div class="col-md-7"><?=clean_data($data->attribute_details->select_office)?></div>
</div>
<div class="row">
    <div class="col-md-5"><label>M/S</label></div>
    <div class="col-md-7"><?=$data->attribute_details->of_m_s?></div>
</div>
<div class="row">
    <div class="col-md-5"><label>Special Storage Accommodation</label></div>
    <div class="col-md-7"><?=$data->attribute_details->special_storage_accommodation?></div>
</div>
<div class="row">
    <div class="col-md-5"><label>Premises Situated At</label></div>
    <div class="col-md-7"><?=$data->attribute_details->premises_situated_at?></div>
</div>
<div class="row">
    <div class="col-md-5"><label>I / We</label></div>
    <div class="col-md-7"><?=$data->attribute_details->i_we?></div>
</div>
<div class="row">
    <div class="col-md-5"><label>Date</label></div>
    <div class="col-md-7"><?=$data->attribute_details->date?></div>
</div>

<table class="table text-font10">
    <thead>
        <tr>
            <th >#</th>
            <th >Category</th>
        </tr>
    </thead>
    <tbody>
        <?php
foreach ($data->attribute_details->categories_of_drugs as $key => $val) {
    ?>
 <tr>
            <td ><?=($key + 1)?></td>
            <td><?=$val->categories?></td>
        </tr>
        <?php }
?>
    </tbody>
</table>

<table class="table text-font10">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Qualification</th>
        </tr>
    </thead>
    <tbody>
        <?php
foreach ($data->attribute_details->qualified_persons as $key => $val) {
    ?>
 <tr>
            <td scope="col"><?=($key + 1)?></td>
            <td scope="col"><?=$val->name?></td>
            <td scope="col"><?=$val->qualification?></td>
        </tr>
        <?php }
?>
    </tbody>
</table>

