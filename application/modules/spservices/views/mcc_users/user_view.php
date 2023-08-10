<style>
body {
  overflow-x: hidden; 
}
</style>
<div class="content-wrapper">
    <main class="rtps-container">
        <div class="row">
            <div class="col">
                <div class="container my-2">
                    <fieldset class="" style="">
                        <?php if (!empty($user[0])) { ?>
                            <?php foreach ($user as $x) { ?>
                                <div class="container">
                                    <div class="row">
                                        <div class="col">
                                            <ul class="list-group">
                                                <li class="list-group-item"><span style="font-weight: bold;">Name : </span><?php echo (isset($x->name)) ? $x->name : '' ?></li>
                                                <li class="list-group-item"><span style="font-weight: bold;">Mobile number : </span><?php echo (isset($x->mobile)) ? $x->mobile : '' ?></li>
                                                <li class="list-group-item"><span style="font-weight: bold;">Email : </span><?php echo (isset($x->email)) ? $x->email : '' ?></li>
                                                <li class="list-group-item"><span style="font-weight: bold;">District : </span><?php echo (isset($x->district_name)) ? $x->district_name : '' ?></li>
                                                <li class="list-group-item"><span style="font-weight: bold;">Circle : </span><?php echo (isset($x->circle_name)) ? $x->circle_name : '' ?></li>
                                                <li class="list-group-item"><span style="font-weight: bold;">Designation : </span><?php echo (isset($x->designation)) ? $x->designation : '' ?></li>
                                                <li class="list-group-item"><span style="font-weight: bold;">Role : </span><?php echo (isset($x->user_role)) ? $x->user_role : '' ?></li>
                                                <li class="list-group-item"><span style="font-weight: bold;">Status : </span><?php echo ($x->is_active == 1 ? "Active" : "Inactive") ?></li>
                                            </ul>
                                            <div class="mt-1">
                                                <a href="<?php echo base_url() . 'spservices/mcc_users/users' ?>" class="btn btn-primary btn-sm" id="SAVE" type="button">BACK</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </fieldset>
                </div>
            </div>
        </div>
    </main>
</div>