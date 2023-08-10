<style>
/* body {visibility:hidden;} */
.print {visibility:visible;}

</style>
<div class="content-wrapper">
<div class="container">
    <div class="row " id="print">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4" style="border: 1px solid;padding:20px">
                <div class="card-body">
                 
                  <div class="row" >
                    <h3 class="h2"><u>External Page simulation</u></h3>
                  </div>

                  <div class="row" style="padding:20px">
                    <form action="<?=base_url("iservices/external/handler")?>" method="post">
                        <input type="hidden" name="data" value="<?=$enc?>"/>
                        <button type="submit" class="btn btn-primary" >Apply for cast certificate</button>
                    </form>


                  </div>



                </div>
            </div>
        </div>
    </div>

</div>
</div>
