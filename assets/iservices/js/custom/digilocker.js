// var window1;

// function openLoginWindow(url, rurl) {
//   $("#loadingIcon").removeClass("d-none");
//   $("#agree-btn").hide();
//   window1 = window.open(
//     url,
//     "popUpWindow",
//     "height=650,width=600,left=350,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes"
//   );
//   checkChild(rurl);
// }

// function test(){
//   checkChild('google.com');
// }

// function checkChild(redirectUrl) {
//   if (window1.closed) {
//     location.replace(redirectUrl);
//   } else setTimeout("checkChild()", 1);
// }



$(window).on("load", function () {
  $(".digilocker_fetch_doc").css({
    "background-color": "lightgoldenrodyellow",
    padding: "2px",
    border: "1px solid gray",
  });
});

// update login and file fetch button on login success
function updateLoginBtn(btnClass) {
  $("." + btnClass).html(
    "<i class='fa fa-lock'></i> Account linked successfully."
  );
  $("." + btnClass).removeAttr("onclick");
  // $("." + btnClass).removeClass("btn-info");
  $("." + btnClass).addClass("btn-info");
  $(".digilocker_fetch_doc").removeAttr("disabled");
}

function updateConsent(btnClass) {
  $("." + btnClass).html(
    "<i class='fa fa-lock'></i> Account linked successfully."
  );
}

// redirect after consent
// function redirectConsent() {
//   window.location.replace("https://www.google.com");
// }

// enableLogin
// function enableLogin() {
//   // $(".digilogin_btn").html("<img src=".<?= base_url('assets/iservices/images/digilocker.jpg') ?>" alt="" width="20px"> Link your digilocker");
//   $(".digilogin_btn").prop("onclick", true);
//   $(".digilogin_btn").removeClass("btn-info");
//   $(".digilocker_fetch_doc").prop("disabled", true);
// }
// window.enableLogin = enableLogin;

// for downloading files from digilocker
$(document).on("click", ".fetch_btn", function () {
  var docUri = $("input[name='document_list']:checked").val();
  if (typeof docUri != "undefined") {
    $("body").addClass("loading");
    var docName = $("input[name='document_list']:checked").attr("data-name");
    $.ajax({
      url: fetchUrl,
      type: "POST",
      data: {
        file_uri: docUri,
        file_name: docName,
      },
      dataType: "json",
      success: function (data) {
        console.log(data);
        $("body").removeClass("loading");
        if (data.status == "success") {
          window.opener.updateFile(data.file_path, fileID);

          Swal.fire({
            position: "center",
            icon: "success",
            title: "File shared successfully.",
            showConfirmButton: false,
            timer: 1500,
          }).then(function (result) {
            window.close();
          });
        } else if (data.format == false) {
          Swal.fire({
            position: "center",
            icon: "error",
            title: "Oops..",
            text: "Your document is not compatible with our portal. Please upload the file by other means.",
            showConfirmButton: true,
          });
        } else {
          Swal.fire({
            position: "center",
            icon: "error",
            title: "Oops..",
            text: "Something went wrong! Please try again.",
            showConfirmButton: true,
          });
        }
      },
    });
  } else {
    alert("Please select your document");
  }
});

// show success message on downloading file into our server
function updateFile(filePath, fileID) {
  $("." + fileID + "_msg").html(
    '<i class="fa fa-check-circle" aria-hidden="true"></i> Shared successfully. &nbsp;'
  );
  $("." + fileID).val(filePath);
}
