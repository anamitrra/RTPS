


function myFunction() {
  var x = document.getElementById("elecdet");

  x.style.display = "none";

}

function opendialogbox() {
  if ($('#elecdet').css('display') == 'none') {
    $('#elecdet').css('display', "block");
  }


  // if ($('#elecdetbtn').css('display') == 'none') {
  //  $('#elecdet').css('display', "none");
  // }
}

function opendialogboxforchart() {
  if ($('#chartcontainer').css('display') == 'none') {
    $('#chartcontainer').css('display', "block");
  }
}

$(function() {
  $("#elecdet").draggable();
});

