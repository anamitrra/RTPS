<style>
/* body {visibility:hidden;} */
.print {visibility:visible;}
</style>
<div class="container">
    <div class="row " id="print">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                  <h4>Acknowledgement </h4>
                  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
                  <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>


                </div>
            </div>
        </div>
    </div>
    <button type="button" id="btnPrint" class="btn btn-primary mb-2" >
      Print
    </button>
</div>
<script>
$(function(){
  $("#btnPrint").on('click',function(){
    var prtContent = document.getElementById("print");
var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
WinPrint.document.write(prtContent.innerHTML);
WinPrint.document.close();
WinPrint.focus();
WinPrint.print();
WinPrint.close();
  })
})
</script>
