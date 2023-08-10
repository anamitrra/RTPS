<?php
$lang = $this->lang;

?>

<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')?>">
<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')?>">

<script src="<?=base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>" defer></script>
<script src="<?=base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>" defer></script>
<script src="<?=base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>" defer></script>
<script src="<?=base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>" defer></script>

<script src="<?= base_url('assets/site/theme1/js/service_wise_data.js') ?>" defer></script>


<main id="main-contenet">
    <div class="container">
    <nav aria-label="breadcrumb" class="nav-bread d-flex justify-content-start align-items-baseline mb-4">
  <ol class="breadcrumb m-0">

  <?php foreach ($table_header->nav as $key => $link): ?>

    <li class="breadcrumb-item <?= empty($link->url) ? 'active' : ''?>" <?= empty($link->url) ? 'aria-current="page"' : ''?> >

    <?php if(isset($link->url)): ?>
        <a href="<?=base_url($link->url) ?>"><?=  $link->$lang?></a>

    <?php else: ?>
        <?=  $link->$lang ?>


    <?php endif; ?>

    </li>
    <?php endforeach; ?>

    
    <!-- <li class="breadcrumb-item active" aria-current="page">Data</li> -->
  </ol>
</nav>

      
        <!-- Tabs navs -->
<ul class="nav nav-tabs my-3" id="ex1" role="tablist">
  <li class="nav-item" role="role1">
    <a class="nav-link active" id="ex1-tab-1" data-bs-toggle="tab" data-bs-target="#ex1-tabs-1" type="button" role="tab" aria-controls="ex1-tabs-1" aria-selected="true">
    
    <?= $table_header ? $table_header->servicewise_applications->$lang : "Servicewise Applications"?>
    </a>
  </li>
  <li class="nav-item" role="role1">
  <a class="nav-link" id="ex1-tab-2" data-bs-toggle="tab" data-bs-target="#ex1-tabs-2" type="button" role="tab" aria-controls="ex1-tabs-2" aria-selected="false">
  <?= $table_header ? $table_header->officewise_applications->$lang : "Officewise Applications"?>
  </a>
  </li>

</ul>
<!-- Tabs navs -->

<!-- Tabs content -->
<div class="tab-content" id="ex1-content">
  <div
    class="tab-pane fade show active"
    id="ex1-tabs-1"
    role="tabpanel"
    aria-labelledby="ex1-tab-1"
  >
        <table id="tableServiceWise" class="table table-bordered table-striped" style="width:100%">
            <thead>
                <tr>
                    <th><?= $table_header ? $table_header->service->$lang : "Service"?></th>

                    <!-- id="app" -->
                    <th class="appl-received "> <?= $table_header ? $table_header->applications_received->$lang : "Applications Received"?> </th>
                    <th class="appl-pending"> <?= $table_header ? $table_header->applications_pending->$lang : "Applications Pending"?> </th>
                    <th class="appl-pending-timely"> <?= $table_header ? $table_header->pending_within_time->$lang : "Pending within Time"?> </th>
                    <th class="appl-disposed"> <?= $table_header ? $table_header->applications_delivered->$lang : "Applications Delivered"?></th>
                    <th class="appl-timely"> <?= $table_header ? $table_header->timely_delivered->$lang : "Timely Delivered"?> </th>
                </tr>
            </thead>
        </table>
  </div>
  <div class="tab-pane fade" id="ex1-tabs-2" role="tabpanel" aria-labelledby="ex1-tab-2">

        <table id="tableOfficeWise" class="table table-bordered table-striped" style="width:100%" >
                <thead>
                    <tr>
                        <th ><?= $table_header ? $table_header->office->$lang : "Office"?></th>
                        <th ><?= $table_header ? $table_header->department->$lang : "Department"?></th>
                        <th class="appl-received "> <?= $table_header ? $table_header->applications_received->$lang : "Applications Received"?> </th>
                        <th class="appl-pending"> <?= $table_header ? $table_header->applications_pending->$lang : "Applications Pending"?> </th>
                        <th class="appl-pending-timely"> <?= $table_header ? $table_header->pending_within_time->$lang : "Pending within Time"?> </th>
                        <th class="appl-disposed"> <?= $table_header ? $table_header->applications_delivered->$lang : "Applications Delivered"?></th>
                        <th class="appl-timely"> <?= $table_header ? $table_header->timely_delivered->$lang : "Timely Delivered"?> </th>
                    </tr>
                </thead>
        </table>
  </div>
  
</div>
<!-- Tabs content -->
    </div>
</main>
<script>
  var male="<?= $table_header ? $table_header->genders->m->$lang : "Male"?>";
  var female="<?= $table_header ? $table_header->genders->f->$lang : "Female"?>";
  var others="<?= $table_header ? $table_header->genders->o->$lang : "Others"?>";
  var na="<?= $table_header ? $table_header->genders->na->$lang : "N/A"?>";

  var langPath = "<?= base_url('assets/site/theme1/plugins/datatables/language/dt-' . $lang . '.json') ?>";

  const url_service = "<?= base_url('mis/api/online/status') ?>";
  const url_office = "<?= base_url('mis/api/office/status') ?>";

</script>
