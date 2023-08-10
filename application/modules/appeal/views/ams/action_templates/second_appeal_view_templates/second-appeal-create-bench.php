<?php
  $this->load->model('roles_model');
  $filter=[
    'slug'=>'MOC'
  ];
  $users=$this->roles_model->get_role_wise_user_list($filter);
  $users=(array)$users;
  if($users){
    $users=$users[0]->users;
  }

  $filter_chairman=[
    'slug'=>'RA'
  ];
  $chairman_users=$this->roles_model->get_role_wise_user_list($filter_chairman);
  $chairman_users=(array)$chairman_users;
  if($chairman_users){
    $chairman_users=$chairman_users[0]->users;
  }

 // pre($chairman_users);
 ?>
<form id="RevertBackToDAForm">
    <input type="hidden" name="appeal_id" id="appeal_id">

    <div class="row forward-box mt-3">
      <div class="col-md-6">
          <div class="form-group">
              <label for="bench_member">Bench member <span class="text-danger">*</span></label>

              <select class="select2" name="bench_member[]" id="bench_member"
                      data-placeholder="Select a Bench member" style="width: 100%;" required multiple>
                  <option value=""></option>
                  <?php
                  $chair_id = '';
                  if (!empty($users)) {
                      foreach ($users as $value) {
                          $isAlreadyMember = false;
                          foreach ($previous_bench_members as $previous_member){
                              if($previous_member->userId === strval($value->_id)){
                                  $isAlreadyMember = true;
                              }
                              if($previous_member->slug === 'RA'){
                                  $chair_id = $previous_member->userId;
                              }
                          }
                          ?>
                          <option value="<?= strval($value->_id) ?>" data-slug="MOC" <?=($isAlreadyMember)?'selected':''?>><?= $value->name ?></option>
                          <?php
                      }
                  }
                  if (!empty($chairman_users)) {
                      foreach ($chairman_users as $chair) {
                          ?>
                          <option value="<?= strval($chair->_id) ?>" data-slug="RA" <?=($chair_id === strval($chair->_id))?'selected':''?>><?= $chair->name ?></option>
                          <?php
                      }
                  }
                   ?>
              </select>
          </div>
      </div>
      <div class="col-md-6">
          <div class="form-group">
              <label for="delegate_to_chairman">Authorized person <span class="text-danger">*</span> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Person who will update the system on behalf of the Chairman in case the chairman is not a member of the bench"></i> </label>
              <select class="select2" name="delegate_to_chairman" id="delegate_to_chairman"
                      data-placeholder="Select a Authorized person" style="width: 100%;" data-errors-container="#delegate_to_chairman_errors_container" required>
              </select>
              <span id="delegate_to_chairman_errors_container" class="text-danger"></span>
          </div>
      </div>
    </div>


    <div class="row mt-3">
        <div class="col-12">
            <label for="remarks">Remarks <span class="text-danger">*</span></label>
            <textarea name="remarks" id="remarks" class="form-control" placeholder="Write remarks here e.g. reason, query, remark .etc" required=""></textarea>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <label for="file_for_action">Attachments (optional)</label>
            <div class="file-loading">
                <input id="file_for_action" name="file_for_action[]" type="file" multiple>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <button class="btn btn-success" id="processSubmit" type="button">
                <span id="actionSubmitProgress" class="d-none">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing ...
                </span>
                <span id="submitBtnTxt">Submit</span>
            </button>
        </div>
    </div>
</form>

<script>
    var appealIdRef = $('#appeal_id');
    var processSubmitRef = $('#processSubmit');
    var forwardFormRef = $('#RevertBackToDAForm');
    var forwardFormProcessUrl = '<?= base_url('appeal/second/process/create-bench') ?>';
    var delegate_users=[];
    var benchMember = [];
    var delegateToChairman;
    var previousDelegateToChairmanId = '<?=$previous_delegate_to_chairman->userId??''?>';
    $(function() {
        setTimeout(function(){
            $("#bench_member").trigger('change');
            // $("#delegate_to_chairman").trigger('change');
        },1000)
        forwardFormRef.parsley();
        $('[data-toggle="tooltip"]').tooltip();
        appealIdRef.val(appealId);
        var select2Ref = $('.select2');
        select2Ref.select2();
        $('.select2').select2({
            placeholder: "Choose one",
            //allowClear: true
        });
        //AppeleteFileUpload File Upload
        var $el2 = $("#file_for_action");
        $el2.fileinput({
            theme: 'explorer-fas',
            uploadUrl: '<?= base_url("upload") ?>',
            uploadAsync: true,
            dropZoneEnabled: false,
            overwriteInitial: false,
            maxFileSize: 1000,
            uploadExtraData: {
                "filename": "file_for_action"
            },
            allowedFileExtensions: ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
            browseOnZoneClick: true,
            initialPreviewAsData: false,
            showUpload: false
        }).on("filebatchselected", function(event, files) {
            $el2.fileinput("upload");
        });

        processSubmitRef.click(function() {

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm!'
            }).then((result) => {
                if (result.value) {
                    let formData = forwardFormRef.serializeArray();
                    if(benchMember.length){
                        formData.push({name:'benchMember', value: benchMember});
                    }
                    if(delegateToChairman.length){
                        formData.push({name:'delegateToChairman', value: delegateToChairman});
                    }
                    if (forwardFormRef.parsley().validate()) {
                        $.ajax({
                            url: forwardFormProcessUrl,
                            type: 'POST',
                            dataType: 'JSON',
                            data: formData,
                            beforeSend: function(){
                                forwardFormRef.find(":input").prop("disabled", true);
                                swal.fire({
                                    html: '<h5>Processing...</h5>',
                                    showConfirmButton: false,
                                    allowOutsideClick: () => !Swal.isLoading(),
                                    onOpen: function() {
                                        Swal.showLoading();
                                    }
                                });
                            },
                            success: function(response) {
                                if (response.success) {
                                    appellateProcessActionFormMsgContainerRef.html('' +
                                        '<div class="alert alert-success">\n' +
                                        '        <a class="close" data-dismiss="alert" title="close">x</a>' +
                                        response.msg +
                                        '</div>'
                                    );
                                    forwardFormRef.trigger('reset');
                                    $('#action').val('');
                                    $('#action').trigger('change');
                                    refreshProcess();
                                    setTimeout(function(){
                                        window.location.reload();
                                    },4000)
                                } else {
                                    appellateProcessActionFormMsgContainerRef.html('' +
                                        '<div class="alert alert-danger">\n' +
                                        '        <a class="close" data-dismiss="alert" title="close">x</a>' +
                                        response.error_msg +
                                        '</div>'
                                    );
                                    // Swal.fire('Failed', response.error_msg, 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Failed', 'Unable to submit hearing!!!', 'error');
                            }
                        }).always(function(){
                            forwardFormRef.find(":input").prop("disabled", false);
                            swal.close();
                        });
                    }
                }
            });
        });

        $("#delegate_to_chairman").on('change',function(e){
            let selectedElement = $(this).select2('data');
            delegateToChairman =  { "userId" : selectedElement[0].element.attributes[0].nodeValue, "slug" : selectedElement[0].element.attributes[1].nodeValue};
            delegateToChairman = JSON.stringify(delegateToChairman);
        });
        $("#bench_member").on('change',function(){
            benchMember = [];
            // $('#delegate_to_chairman').find(':selected').data('slug')
          var selections = $("#bench_member").select2('data');//( JSON.stringify() );
          $("#delegate_to_chairman").empty();
            $("#delegate_to_chairman").append('<option value="">Please select an option</option>');
          $.each(selections, function (i, item) {
              benchMember.push({ "userId" : item.element.attributes[0].nodeValue, "slug" : item.element.attributes[1].nodeValue});
              let dataToAppend = {
                  value: item.id,
                  text : item.text,
                  'data-slug': item.element.attributes[1].nodeValue
              };
              if(item.element.attributes[0].nodeValue === previousDelegateToChairmanId){
                  dataToAppend = {
                      value: item.id,
                      text : item.text,
                      'data-slug': item.element.attributes[1].nodeValue,
                      'selected': true
                  };
              }
              $('#delegate_to_chairman').append($('<option>', dataToAppend));
          });
          benchMember = JSON.stringify(benchMember)
        })
    });
</script>
