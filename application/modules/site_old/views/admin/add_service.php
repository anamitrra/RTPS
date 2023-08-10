<?php
$role = $this->session->userdata('designation');
?>


<link rel="stylesheet" href="<?= base_url('assets/site/admin/plugins/summernote/summernote-lite.min.css') ?>">

<script src="<?= base_url('assets/site/admin/plugins/summernote/summernote-lite.min.js') ?>"></script>
<script src="<?= base_url('assets/site/admin/js/add_service.js') ?>"></script>


<!-- Modal -->
<div class="modal fade" id="deleteModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?= form_open(base_url("site/admin/online/delete_service_document")); ?>

            <div class="modal-body">
                <h4>Delete this Document ?</h4>

                <input type="hidden" name="object_id" value="<?= $service_info->_id->{'$id'} ?>">
                <input type="hidden" name="doc_path" value="">

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn btn-secondary">YES</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">NO</button>
            </div>

            </form>

        </div>
    </div>
</div>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">

            <!-- Page Heading & Nav breadcrumbs  -->
            <div class="row mb-4">
                <div class="col-sm-8">
                    <p class="m-0 text-muted"><span class="font-weight-bold mr-2">Note: </span>Fields marked with <span class="text-danger font-weight-bold">*</span> are mandatory.</p>

                    <?php if (!isset($service_info->_id)) : ?>
                        <p class="m-0 text-muted"><span class="font-weight-bold mr-2">Note: </span> Please create the Service first. Then only you can add Requirements, Guidelines and Documents. </p>
                    <?php endif; ?>


                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("site/admin/dashboard"); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url("site/admin/online"); ?>">Services</a></li>
                        <li class="breadcrumb-item active"><?= $action_name_service ?></li>
                    </ol>
                </div>
            </div>


            <!-- Action Success/Fail alert messages  -->
            <?php if ($this->session->flashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show pl-2 text-center" role="alert">
                    <?= $this->session->flashdata('success'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show pl-2 text-center" role="alert">
                    <?= $this->session->flashdata('error'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>


            <!-- Tab Lists -->
            <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">

                <?php if ($role === 'System Administrator') : ?>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="service-tab" data-toggle="tab" href="#add-service" role="tab" aria-controls="add-service" aria-selected="true">SERVICE INFO</a>
                    </li>

                <?php endif; ?>

                <li class="nav-item" role="presentation">
                    <a class="nav-link <?= ($role == 'Administrator') ? 'active' : ''  ?>" id="guidelines-tab" data-toggle="tab" href="#add-guidelines" role="tab" aria-controls="add-guidelines" aria-selected="false">GUIDELINES</a>

                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="requirements-tab" data-toggle="tab" href="#add-requirements" role="tab" aria-controls="add-requirements" aria-selected="false"> REQUIREMENTS</a>

                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="documents-tab" data-toggle="tab" href="#upload-documents" role="tab" aria-controls="upload-documents" aria-selected="false">DOCUMENTS</a>

                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="notice-tab" data-toggle="tab" href="#service-notice" role="tab" aria-controls="service-notice" aria-selected="false">SERVICE NOTICE</a>

                </li>
            </ul>


            <!-- Tab Contents -->
            <div class="tab-content" id="myTabContent">

                <?php if ($role === 'System Administrator') : ?>

                    <div class="tab-pane fade show active" id="add-service" role="tabpanel" aria-labelledby="service-tab">

                        <?= form_open('site/admin/online/add_service_info_action'); ?>

                        <div class="form-group">
                            <label for="service_id"><span class="text-danger font-weight-bold">*</span> Service ID: </label><br>
                            <input type="text" id="service_id" name="service_id" placeholder="Enter service Id" value="<?= $service_info->service_id ?? ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <div>
                                <label for="dept_id"><span class="text-danger font-weight-bold">*</span> Choose a Department:</label>
                            </div>

                            <select id="dept_id" name="dept_id" required>
                                <?php foreach ($dept_list as $dept) : ?>
                                    <option value="<?= $dept->department_id ?>" <?php

                                                                                if (isset($service_info->department_id)) {

                                                                                    echo ($dept->department_id == $service_info->department_id ? 'selected' : '');
                                                                                }

                                                                                ?>>
                                        <?= $dept->department_name->en; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><span class="text-danger font-weight-bold">*</span> Enter Service Name: </label>
                            <div>
                                <input name="service_en" type="text" placeholder="english" value="<?= $service_info->service_name->en ?? ''; ?>" required>
                            </div>
                            <div>
                                <input name="service_as" type="text" placeholder="assamese" value="<?= $service_info->service_name->as ?? ''; ?>" required>
                            </div>
                            <div>
                                <input name="service_bn" type="text" placeholder="bangla" value="<?= $service_info->service_name->bn ?? ''; ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div>
                                <label for="cat_id"><span class="text-danger font-weight-bold">*</span> Choose a Category:</label>
                            </div>

                            <select id="cat_id" name="cat_id" required>
                                <?php foreach ($cat_list as $cat) : ?>
                                    <option value="<?= $cat->cat_id ?>" <?php

                                                                        if (isset($service_info->cat_id)) {

                                                                            echo ($cat->cat_id == $service_info->cat_id ? 'selected' : '');
                                                                        }

                                                                        ?>>
                                        <?= $cat->cat_name->en; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Online / Offline -->
                        <div class="form-group">
                            <p class="font-weight-bold d-inline-block mr-3"><span class="text-danger font-weight-bold">*</span> Make Available Online ?</p>

                            <?php if (isset($service_info->online)) : ?>

                                <input type="radio" id="true" name="online" value="1" <?= ($service_info->online == true) ? 'checked' : '' ?>>
                                <label class="font-weight-normal" for="true">Yes</label>
                                <input type="radio" id="false" name="online" value="0" <?= ($service_info->online == false) ? 'checked' : '' ?>>
                                <label class="font-weight-normal" for="false">No</label>

                            <?php else : ?>

                                <input type="radio" id="true" name="online" value="1">
                                <label class="font-weight-normal" for="true">Yes</label>
                                <input type="radio" id="false" name="online" value="0" checked>
                                <label class="font-weight-normal" for="false">No</label>

                            <?php endif; ?>

                        </div>


                        <!-- Recently Launched ? -->
                        <?php if (isset($service_info->is_new)) : ?>

                            <div class="form-group">
                                <p class="font-weight-bold d-inline-block mr-3"><span class="text-danger font-weight-bold">*</span> Has Recently Launched ?</p>

                                <input type="radio" id="is_new_t" name="is_new" value="1" <?= ($service_info->is_new == true) ? 'checked' : '' ?>>
                                <label class="font-weight-normal" for="is_new_t">Yes</label>
                                <input type="radio" id="is_new_f" name="is_new" value="0" <?= ($service_info->is_new == false) ? 'checked' : '' ?>>
                                <label class="font-weight-normal" for="is_new_f">No</label>


                            </div>


                        <?php else : ?>

                            <!-- In case of Adding New service is_new always true -->
                            <input type="hidden" name="is_new" value="1">

                        <?php endif; ?>


                        <!-- Enabled/Disabled Service ? -->
                        <?php if (isset($service_info->enabled)) : ?>

                            <div class="form-group">
                                <p class="font-weight-bold d-inline-block mr-3"><span class="text-danger font-weight-bold">*</span>
                                    Enable Service ? <small>(Apply & Regiter buttons will be available if enabled.)</small>
                                </p>


                                <input type="radio" id="enable" name="enable" value="1" <?= ($service_info->enabled == true) ? 'checked' : '' ?>>
                                <label class="font-weight-normal" for="enable">Yes</label>
                                <input type="radio" id="disable" name="enable" value="0" <?= ($service_info->enabled == false) ? 'checked' : '' ?>>
                                <label class="font-weight-normal" for="disable">No</label>


                            </div>


                        <?php else : ?>

                            <!-- In case of Adding New service ENABLED is true -->
                            <input type="hidden" name="enabled" value="1">

                        <?php endif; ?>


                        <!-- Service apply URL -->
                        <div class="form-group">
                            <label for="service_url">Service Apply URL (other than Service Plus): </label><br>
                            <input type="text" id="service_url" name="service_url" placeholder="service url" value="<?= $service_info->service_url ?? ''; ?>">
                        </div>

                        <!-- Service availability for RTPS Kiosk -->
                        <div class="form-group">
                            <label for="kiosk" class="mr-2"><span class="text-danger font-weight-bold">*</span> Kiosk Availability :</label>
                            <select id="kiosk" name="kiosk">
                                <option value="" <?= empty($service_info->kiosk_availability) ? 'selected' : '' ?>>None</option>
                                <option value="ALL" <?= (($service_info->kiosk_availability ?? '') == 'ALL') ? 'selected' : '' ?>>All</option>
                                <option value="PFC" <?= (($service_info->kiosk_availability ?? '') == 'PFC') ? 'selected' : '' ?>>PFC</option>
                                <option value="CSC" <?= (($service_info->kiosk_availability ?? '') == 'CSC') ? 'selected' : '' ?>>CSC</option>
                            </select>
                        </div>

                        <!-- Service Type (EODB/RTPS) -->
                        <div class="form-group">
                            <p class="font-weight-bold d-inline-block mr-3"><span class="text-danger font-weight-bold">*</span> Service Type: </p>

                            <input type="radio" id="service_type_rtps" name="service_type" value="RTPS" <?= (($service_info->service_type ?? 'RTPS') == 'RTPS') ? 'checked' : '' ?>>
                            <label class="font-weight-normal" for="service_type_rtps">RTPS</label>
                            <input type="radio" id="service_type_eodb" name="service_type" value="EODB" <?= (($service_info->service_type ?? '') == 'EODB') ? 'checked' : '' ?>>
                            <label class="font-weight-normal" for="service_type_eodb">EODB</label>

                        </div>


                        <!-- Incase of update, send the ObjectID also -->
                        <?php if (isset($service_info->_id)) : ?>

                            <input type="hidden" name="object_id" value="<?= $service_info->_id->{'$id'} ?>">

                        <?php endif; ?>


                        <button class="btn btn-secondary" type="submit">
                            <i class="fas fa-save mr-2"></i>
                            <?= $action_name_service ?>
                        </button>

                        <a class="btn btn-outline-secondary" href="<?= base_url('site/admin/online') ?>" role="button">Cancel</a>


                        </form>
                    </div>

                <?php endif; ?>


                <div class="tab-pane fade <?= ($role == 'Administrator') ? 'show active' : ''  ?>" id="add-guidelines" role="tabpanel" aria-labelledby="guidelines-tab">

                    <?= form_open('site/admin/online/add_service_guidelines_action'); ?>

                    <div class="form-group mb-5">
                        <label class=""><span class="text-danger font-weight-bold">*</span> Guidelines in English : </label>
                        <textarea class="summernote-guidelines" name="guidelines-en"><?= isset($service_info->guide_lines) ? html_entity_decode(htmlspecialchars_decode($service_info->guide_lines->en)) : "" ?></textarea>

                    </div>

                    <div class="form-group mb-5">
                        <label class=""><span class="text-danger font-weight-bold">*</span> Guidelines in Assamese : </label>
                        <textarea class="summernote-guidelines" name="guidelines-as"><?= isset($service_info->guide_lines) ? html_entity_decode(htmlspecialchars_decode($service_info->guide_lines->as)) : "" ?></textarea>

                    </div>

                    <div class="form-group mb-5">
                        <label class=""><span class="text-danger font-weight-bold">*</span> Guidelines in Bangla : </label>
                        <textarea class="summernote-guidelines" name="guidelines-bn"><?= isset($service_info->guide_lines) ? html_entity_decode(htmlspecialchars_decode($service_info->guide_lines->bn)) : "" ?></textarea>

                    </div>


                    <!-- Incase of update, send the ObjectID also -->
                    <?php if (isset($service_info->_id)) : ?>

                        <input type="hidden" name="object_id" value="<?= $service_info->_id->{'$id'} ?>">

                    <?php endif; ?>


                    <button class="btn btn-secondary" type="submit">
                        <i class="fas fa-save mr-2"></i>
                        <?= $action_name_guide ?>
                    </button>

                    <a class="btn btn-outline-secondary" href="<?= base_url('site/admin/online') ?>" role="button">Cancel</a>


                    </form>

                </div>


                <div class="tab-pane fade" id="add-requirements" role="tabpanel" aria-labelledby="requirements-tab">

                    <?= form_open('site/admin/online/add_service_requirements_action'); ?>

                    <div class="form-group mb-5">
                        <label class=""><span class="text-danger font-weight-bold">*</span> Requirements in English : </label>
                        <textarea class="summernote-requirements" name="requirements-en"><?= isset($service_info->requirements) ? html_entity_decode(htmlspecialchars_decode($service_info->requirements->en)) : "" ?></textarea>

                    </div>

                    <div class="form-group mb-5">
                        <label class=""><span class="text-danger font-weight-bold">*</span> Requirements in Assamese : </label>
                        <textarea class="summernote-requirements" name="requirements-as"><?= isset($service_info->requirements) ? html_entity_decode(htmlspecialchars_decode($service_info->requirements->as)) : "" ?></textarea>

                    </div>

                    <div class="form-group mb-5">
                        <label class=""><span class="text-danger font-weight-bold">*</span> Requirements in Bangla : </label>
                        <textarea class="summernote-requirements" name="requirements-bn"><?= isset($service_info->requirements) ? html_entity_decode(htmlspecialchars_decode($service_info->requirements->bn)) : "" ?></textarea>

                    </div>


                    <!-- Incase of update, send the ObjectID also -->
                    <?php if (isset($service_info->_id)) : ?>

                        <input type="hidden" name="object_id" value="<?= $service_info->_id->{'$id'} ?>">

                    <?php endif; ?>


                    <button class="btn btn-secondary" type="submit">
                        <i class="fas fa-save mr-2"></i>
                        <?= $action_name_req ?>
                    </button>

                    <a class="btn btn-outline-secondary" href="<?= base_url('site/admin/online') ?>" role="button">Cancel</a>


                    </form>

                </div>

                <div class="tab-pane fade" id="upload-documents" role="tabpanel" aria-labelledby="documents-tab">

                    <?php if (!empty($service_info->documents)) : ?>
                        <div class="table-responsive">

                            <table class="table table-sm table-hover">
                                <caption>Alerady uploaded documents.</caption>

                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col" colspan="2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php foreach ($service_info->documents as $key => $val) : ?>

                                        <tr>
                                            <th scope="row"><?= $key + 1 ?></th>
                                            <td>
                                                <ul class="list-unstyled">
                                                    <li><?= $val->name->en ?></li>
                                                    <li><?= $val->name->as ?></li>
                                                    <li><?= $val->name->bn ?></li>
                                                </ul>
                                            </td>
                                            <td>
                                                <a class="btn btn-outline-info" role="button" href="<?= base_url($val->path) ?>" target="_blank" rel="noopener noreferrer"><i class="fas fa-file-alt fa-lg mr-2"></i> View</a>
                                            </td>
                                            <td>
                                                <a class="btn btn-outline-danger delete" role="button" href="#" data-doc_path="<?= $val->path ?>"><i class="fas fa-trash-alt fa-lg mr-2"></i> Delete</a>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>

                                </tbody>

                            </table>
                        </div>

                    <?php endif; ?>

                    <p class="lead border-bottom">Upload Service Document</p>

                    <?= form_open_multipart('site/admin/online/upload_service_document'); ?>

                    <div class="form-group">
                        <label for=""><span class="text-danger font-weight-bold">*</span> Enter Document Name: </label>
                        <div>
                            <input name="doc_name_en" type="text" placeholder="english" required>
                        </div>
                        <div>
                            <input name="doc_name_as" type="text" placeholder="assamese" required>
                        </div>
                        <div>
                            <input name="doc_name_bn" type="text" placeholder="bengali" required>
                        </div>
                    </div>

                    <div class="form-group">

                        <div><label for="service-doc">
                                <span class="text-danger font-weight-bold">*</span>
                                Please Select a Document:
                                <small>(only PDF, JPG, JPEG are allowed)</small>
                            </label>
                        </div>
                        <input type="file" id="service-doc" name="service-doc" required>

                    </div>


                    <!-- Incase of update, send the ObjectID also -->
                    <?php if (isset($service_info->_id)) : ?>

                        <input type="hidden" name="object_id" value="<?= $service_info->_id->{'$id'} ?>">

                    <?php endif; ?>


                    <button class="btn btn-secondary" type="submit">
                        <i class="fas fa-upload mr-2"></i>
                        Upload
                    </button>

                    <a class="btn btn-outline-secondary" href="<?= base_url('site/admin/online') ?>" role="button">Cancel</a>


                    </form>

                </div>

                <!-- Service Notice -->
                <div class="tab-pane fade" id="service-notice" role="tabpanel" aria-labelledby="service-notice-tab">

                    <?= form_open('site/admin/online/add_service_notice_action'); ?>

                    <div class="form-group mb-5">
                        <label class=""><span class="text-danger font-weight-bold">*</span> Service Notice in English : </label>
                        <textarea class="summernote-notice" name="notice-en"><?= isset($service_info->notice) ? html_entity_decode(htmlspecialchars_decode($service_info->notice->en)) : "" ?></textarea>

                    </div>

                    <div class="form-group mb-5">
                        <label class=""><span class="text-danger font-weight-bold">*</span> Service Notice in Assamese : </label>
                        <textarea class="summernote-notice" name="notice-as"><?= isset($service_info->notice) ? html_entity_decode(htmlspecialchars_decode($service_info->notice->as)) : "" ?></textarea>

                    </div>

                    <div class="form-group mb-5">
                        <label class=""><span class="text-danger font-weight-bold">*</span> Service Notice in Bangla : </label>
                        <textarea class="summernote-notice" name="notice-bn"><?= isset($service_info->notice) ? html_entity_decode(htmlspecialchars_decode($service_info->notice->bn)) : "" ?></textarea>

                    </div>


                    <!-- Incase of update, send the ObjectID also -->
                    <?php if (isset($service_info->_id)) : ?>

                        <input type="hidden" name="object_id" value="<?= $service_info->_id->{'$id'} ?>">

                    <?php endif; ?>


                    <button class="btn btn-secondary" type="submit">
                        <i class="fas fa-save mr-2"></i>
                        <?= $action_name_notice ?>
                    </button>

                    <a class="btn btn-outline-secondary" href="<?= base_url('site/admin/online') ?>" role="button">Cancel</a>


                    </form>

                </div>

            </div>

        </div><!-- /.container-fluid -->
    </div><!-- Main content -->

    <!-- /.content -->
</div>