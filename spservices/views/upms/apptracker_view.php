<style type="text/css">
    .loading {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid blue;
        border-right: 16px solid green;
        border-bottom: 16px solid red;
        border-left: 16px solid pink;
        width: 100px;
        height: 100px;
        margin: 10px auto;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
    }

    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<script type="text/javascript">
    $(document).on("click", "#search_btn", function(){
            let search_by = $("#search_by").val();
            if(search_by.length >= 10) {
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('spservices/upms/apptracker/get_details')?>",
                    data: {"search_by":search_by},
                    beforeSend:function(){
                        $("#matched_data").html('<div class="loading"></div>');
                    },
                    success:function(res){ //alert(res);
                        $("#matched_data").html(res);
                    }
                });
            } else {
                alert("Please enter a valid input");
                $("#search_by").val("");
                $("#search_by").focus();
            }//End of if else            
        });
</script>
<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php }//End of if ?>
    <div class="card shadow-sm mt-2">
        <div class="card-body">
            <div class="row">
                <div class="offset-md-3 col-md-6 offset-md-3">                            
                    <div class="input-group mb-3">
                        <input id="search_by" type="text" class="form-control" placeholder="Enter RTPS Reference" />
                        <div class="input-group-append">
                            <button id="search_btn" class="btn btn-outline-secondary" style="border-color:#ced4da" type="button">Get Details</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div id="matched_data" class="col-md-12"></div>
            </div>
        </div><!--End of .card-body-->
    </div><!--End of .card-->
</div>