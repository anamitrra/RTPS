


<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<style>
    .parsley-errors-list{
        color: red;
    }
    .transport-appl-header {
    padding: 0.5em;
    margin-bottom: 1rem;
    text-align: center;
    font-weight: bold;
    border-bottom: 1px solid lightgray;
}
.transport-appl-para{
  font-size: 1.1rem;
  font-weight: bold;
  padding: 1rem 0;
  margin: 0;
}
ul.transport-appl-list {
    margin: 0.5rem 0;
    list-style-type: decimal;
    list-style-position: inside;
}
.my-datatable-button {
    display: block;
    min-width: 150px;
    background: #C06C84;
    border-radius: .30rem;
    color: aliceblue !important;
    box-shadow: 0 3px 2px 0 rgba(0, 0, 0, 0.1);
    cursor: pointer;
    text-align: center;
    padding: 8px;
    text-transform: uppercase;
    margin: 3px 0 8px 0;
        margin-top: 3px;
    border: none;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                  
                  <p class="transport-appl-para">Supporting Documents:</p>
                  
                
                  <form id="process_form" action="<?=$action?>" method="post">
                        <input type="text" name="encData" id="encData" value="<?=$encData?>">
                        
                        <input type="text" name="agencyid" id="agencyid" value="<?=$agencyid?>">
                        <input type="submit" value="post data">
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>

