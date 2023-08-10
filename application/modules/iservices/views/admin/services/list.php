<!-- Main content -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Services</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url("admin/dashboard"); ?>">Home</a></li>
            <li class="breadcrumb-item active">Services</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"> Services</h3>
          </div>

          <div class="card-body">
            <div class="row">
                <?php if($service ==='vahan'){ ?>
                  <div class="col-sm-12 mx-auto">
                    <h4>SERVICES FOR VAHAN</h4>
                      <ul>
                        <li>Duplicate Registration Certificate for Non-transport Vehicle <a  href="<?=base_url("iservices/admin/vahan/3")?>">Apply</a></li>
                        <li>Duplicate Registration Certificate for Transport Vehicle <a  href="<?=base_url("iservices/admin/vahan/3")?>">Apply</a></li>
                        <li>Ownership Transfer for Non-Transport/Transport Vehicle <a  href="<?=base_url("iservices/admin/vahan/5")?>">Apply</a> </li>
                        <li>No Objection Certificate for Transfer of Record of Registration to Other Registering Authority <a href="<?=base_url("iservices/admin/vahan/9")?>">Apply</a></li>
                        <li>Certificate of Fitness of Transport Vehicles <a  href="<?=base_url("iservices/admin/vahan/2")?>">Apply</a></li>
                        <li>Address change in RC <a  href="<?=base_url("iservices/admin/vahan/4")?>">Apply</a></li>
                        <li>Hypothecation Addition <a  href="<?=base_url("iservices/admin/vahan/6")?>">Apply</a></li>
                        <li>Hypothecation Termination <a  href="<?=base_url("iservices/admin/vahan/7")?>">Apply</a></li>

                      </ul>

                  </div>
                <?php } ?>
                <?php if($service ==='noc'){ ?>
                  <div class="col-sm-12 mx-auto">
                      <h4>SERVICES FOR NOC</h4>
                      <ul>
                        <li>NOC for Same Class <a  href="<?=base_url("iservices/admin/guidelines/11/1")?>">Apply</a></li>
                        <li>NOC for Re Classification <a  href="<?=base_url("iservices/admin/guidelines/12/1")?>">Apply</a> </li>
                        <li>NOC for Re Classification cum transfer <a  href="<?=base_url("iservices/admin/guidelines/13/1")?>">Apply</a></li>
                        <li>NOC for Composite service  <a  href="<?=base_url("iservices/admin/guidelines/14/1")?>">Apply</a></li>

                      </ul>

                  </div>
                <?php } ?>

                <?php if($service ==='sarathi'){ ?>

                  <div class="col-sm-12 mx-auto">
                    <h4>SERVICES FOR SARATHI</h4>
                    <ul>
                      <li>Learner Licence for Transport <a target="_blank" href="<?=base_url("iservices/admin/sarathi/guidelines/2703/4")?>">Apply</a></li>
                      <li>Learner Licence for Non-Transport <a target="_blank" href="<?=base_url("iservices/admin/sarathi/guidelines/2703/4")?>">Apply</a></li>
                      <li>Diving Licence for Transport <a target="_blank" href="<?=base_url("iservices/admin/sarathi/guidelines/2222/4")?>">Apply</a></li>
                      <li>Diving Licence for Non-Transport <a target="_blank" href="<?=base_url("iservices/admin/sarathi/guidelines/2223/4")?>">Apply</a></li>
                      <li>Duplicate Driving License <a target="_blank" href="<?=base_url("iservices/admin/sarathi/guidelines/2744/4")?>">Apply</a></li>
                      <li> Renewal of Driving License for transport  <a target="_blank" href="<?=base_url("iservices/admin/sarathi/guidelines/2745/4")?>">Apply</a></li>
                      <li> Renewal of Driving License for Non Transport   <a target="_blank" href="<?=base_url("iservices/admin/sarathi/guidelines/2746/4")?>">Apply</a></li>
                    

                    </ul>

                  </div>
                <?php } ?>

                <?php if($service ==='basundhara'){ ?>

                  <div class="col-sm-12 mx-auto">
                      <h4>SERVICES FOR Basundhara</h4>
                      <ul>
                      <li>MUTATION BY INHERITANCE <a  href="<?=base_url("iservices/basundhara/guidelines/231/5")?>">Apply</a></li>
                        <li>MUTATION BY DEED  <a  href="<?=base_url("iservices/basundhara/guidelines/232/5")?>">Apply</a></li>
                        <li>Allotment Certificate to Periodic Patta <a  href="<?=base_url("iservices/basundhara/guidelines/235/5")?>">Apply</a></li>
                        <li>STRIKING OUT NAME <a  href="<?=base_url("iservices/basundhara/guidelines/238/5")?>">Apply</a></li>
                        <li>NAME CORRECTION  <a  href="<?=base_url("iservices/basundhara/guidelines/236/5")?>">Apply</a></li>
                        <li>AREA CORRECTION <a  href="<?=base_url("iservices/basundhara/guidelines/237/5")?>">Apply</a></li>
                        <li>MOBILE UPDATION  <a  href="<?=base_url("iservices/basundhara/guidelines/240/5")?>">Apply</a></li>
                        <li>Field Partition <a  href="<?=base_url("iservices/basundhara/guidelines/233/5")?>">Apply</a></li>
                        <li>Reclassification of land less than one bigha <a  href="<?=base_url("iservices/basundhara/guidelines/234/5")?>">Apply</a></li>
                        <!-- <li>LAND ALLOTMENT  <a  href="<?=base_url("iservices/basundhara/guidelines/235/5")?>">Apply</a></li> -->
                        
                        
                       
                       

                      </ul>

                  </div>
                  <?php } ?>

                  <?php if($service ==='gmdw'){ ?>

                      <div class="col-sm-12 mx-auto">
                          <h4>SERVICES FOR GMDW&SB</h4>
                          <ul>
                          <li>water connection <a  href="<?=base_url("iservices/admin/guidelines/901/9")?>">Apply</a></li>
                          </ul>

                      </div>
                   <?php } ?>

                   <?php if($service ==='birth_death'){ ?>

                    <div class="col-sm-12 mx-auto">
                        <h4>SERVICES FOR HEALTH</h4>
                        <ul>
                        <li>Birth registration <a  href="<?=base_url("iservices/admin/guidelines/801/8")?>">Apply</a></li>
                        <li>Death  registration <a  href="<?=base_url("iservices/admin/guidelines/802/8")?>">Apply</a></li>
                        </ul>

                    </div>
                    <?php } ?>

                    
                   <?php if($service ==='auwssb'){ ?>

                    <div class="col-sm-12 mx-auto">
                        <h4>SERVICES FOR AUWSSB </h4>
                        <ul>
                        <li>Online Application for Water Connection <a  href="<?=base_url("iservices/admin/guidelines/701/7")?>">Apply</a></li>
                        <li>Online Payment of Bill <a  href="<?=base_url("iservices/admin/guidelines/702/7")?>">Apply</a></li>
                        </ul>

                    </div>
                    <?php } ?>

                    <?php if($service ==='dohua'){ ?>

                      <div class="col-sm-12 mx-auto">
                          <h4>SERVICES FOR DOHUA (NON GMC ) </h4>
                          <ul>
                          <li>Property Assessment <a  href="<?=base_url("iservices/admin/guidelines/1101/11")?>">Apply</a></li>
                          <li>Property Tax payment<a  href="<?=base_url("iservices/admin/guidelines/1102/11")?>">Apply</a></li>
                          </ul>

                      </div>
                      <?php } ?>

                      <?php if($service ==='apdcl'){ ?>

                        <div class="col-sm-12 mx-auto">
                            <h4>SERVICES FOR APDCL</h4>
                            <ul>
                            <li>Application for new Low Tension Connection(APDCL)  <a  href="<?=base_url("spservices/apdcl_form")?>">Apply</a></li>
                            </ul>

                        </div>
                        <?php } ?>


                 

               
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
