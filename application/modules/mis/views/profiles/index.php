<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/fileupload/themes/fas/theme.js') ?>" type="text/javascript"></script>
<?php 
if(isset($photo) && !empty($photo)){
    $avatar=base_url($photo);
}else{
    $avatar=base_url("storage/images/avatar.png");
}
?>
<style>
    .kv-avatar .krajee-default.file-preview-frame,
    .kv-avatar .krajee-default.file-preview-frame:hover {
        margin: 0;
        padding: 0;
        border: none;
        box-shadow: none;
        text-align: center;
    }

    .kv-avatar {
        display: inline-block;
    }

    .kv-avatar .file-input {
        display: table-cell;
        width: 250px;
    }

    .kv-reqd {
        color: red;
        font-family: monospace;
        font-weight: normal;
    }
</style>
<div class="content-wrapper" style="min-height: 1376.4px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=base_url('mis/view')?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    Edit Profile
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <?php if (isset($success)) {
                        echo '<div class="alert alert-success">' . $success . '</div>';
                    } elseif (isset($error)) {
                        echo '<div class="alert alert-danger">' . $error . '</div>';
                    }
                    ?>
                    <div class="row">
                        <div id="profilePhoto" class="col-12 col-md-4">
                                <div class="text-center pl-2">
                                    <div class="kv-avatar">
                                        <div class="file-loading">
                                            <input id="avatar" name="avatar[]" type="file" required>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="col-12 col-md-8">
                            <form method="post" id="profile-update" action="<?= base_url('mis/profile/update') ?>">
                                <div class="row">
                                    <div class="col-6 form-group">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" name="name" class="form-control" value="<?= $name ?>" required>
                                    </div>
                                    <div class="col-6 form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" id="email" name="email" class="form-control" value="<?= $email ?>" required>
                                    </div>
                                    <div class="col-6 form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="tel" id="phone" name="phone" class="form-control" value="<?= $mobile ?>" maxlength="10" minlength="10" required>
                                    </div>
                                    <div class="col-12 text-right">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" id="update" class="btn btn-primary float-right">Update</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
</section>
</div>
</div>
<?php
$this->session->unset_userdata("avatar");// Unset previous uploaded files
?>
<script>
    $(document).ready(function() {
        $('#update').on("click", function(e) {
            e.preventDefault();
            $('#profile-update').unbind('submit').submit();
            return true;
        });
        var $el1 = $("#avatar");
        $el1.fileinput({
            theme: 'fas',
            uploadUrl: '<?= base_url("upload") ?>',
            overwriteInitial: true,
            maxFileSize: 1500,
            showClose: false,
            showCaption: false,
            showBrowse: false,
            browseOnZoneClick: true,
            elErrorContainer: '#kv-avatar-errors-2',
            msgErrorClass: 'alert alert-block alert-danger',
            defaultPreviewContent: '<img class="img-responsive avatar img-circle img-thumbnail" src="<?= $avatar ?>" alt="Your Avatar"><h6 class="text-muted">Click to Upload</h6>',
            layoutTemplates: {
                main2: '{preview}{upload}\n{browse}\n',
            },
            uploadExtraData: {
                "filename": "avatar"
            },
            allowedFileExtensions: ['jpg', 'png', 'jpeg'],
        }).on("filebatchselected", function(event, files) {
            $el1.fileinput("upload");
        });
        /*         $(document).on('change', '#file', function() {
                    if (this.files && this.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('#profile_photo')
                                .attr('src', e.target.result);
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                });
                //Modal Open
                $(document).on('click', '.profile-box', function() {
                    var img_link = $(this).find('.img-responsive').attr('src');
                    console.log(img_link);
                    $('#profileModal').find('#profile_photo').attr('src', img_link);
                })
                //Upload profile
                $(document).on('click', '#upload-btn', function() {
                    var input_field = $('#file');
                    var field = input_field.val();
                    if(field == null || field == ''){
                        input_field.addClass('is-invalid');
                        input_field.next('.error').text('Please choose your photo.');
                    }
                    else{
                        $('.is-invalid').removeClass('is-invalid');
                        $('.error').text('');
                        var property      = input_field.prop('files')[0];
                        var image_name    = property.name;
                        var image_ext     = image_name.split('.').pop().toLowerCase();
                        var image_size    = property.size;
                        if($.inArray(image_ext, ['png', 'jpg', 'jprg']) == -1){
                            input_field.addClass('is-invalid');
                            input_field.next('.error').text('Profile picture should be in png, jpg or jpeg format.');
                        }
                        else if(image_size > 250000){
                            input_field.addClass('is-invalid');
                            input_field.next('.error').text('Profile picture size should be less than 250kb.');
                        }
                        else{
                            $('.is-invalid').removeClass('is-invalid');
                            $('.error').text('');
                            var form_data = new FormData();
                            form_data.append("file", property);
                            $.ajax({
                                url : "<?= base_url('ams/admin/profile/photo') ?>",
                                type : "post",
                                data : form_data,
                                contentType : false,
                                enctype : 'multipart/form_data',
                                cache: false,
                                processData: false,
                                beforeSend : function () {
                                    $(this).html("Image Uploading..");
                                },
                                success: function(data){
                                    $(this).html("Upload");
                                    $('.profile-box').html('<img class="img-responsive" src="/storage/uploads/profiles/'+data.filename+'" style="max-height: 100%; max-weight: 100%;">');
                                    $('#profileModal').modal('hide');
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: 'Profile has been successfully uploaded.',
                                        showConfirmButton: false,
                                        timer: 2500
                                    });
                                },
                                error: function(data){
                                    $('#profileModal').modal('hide');
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Something went wrong! Please try again',
                                    })
                                }
                            })
                        }
                    }
                })
                $('#profileModal').on('hidden.bs.modal', function (e) {
                    $(this).find('#upload-btn').text('Upload');
                    $(this).find('#profile_photo').removeAttr('src');
                }) */
    }); //ready function
</script>