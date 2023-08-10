
const viewAppealDetailsRef = $('#viewAppealDetails');

function showAppealDetails(){
    let appealDetailsRef     = $('#appealDetails');
    let applicationNumberRef = $('#applicationNumber');
    let contactNumberRef     = $('#contactNumber');
    if(appealDetailsRef.hasClass('d-none') && applicationNumberRef.val().length && contactNumberRef.val().length){
        appealDetailsRef.removeClass('d-none');
    }
}

viewAppealDetailsRef.click(function () {
    alert('test');
    if(appealDetailsRef.hasClass('d-none')){
        appealDetailsRef.removeClass('d-none');
    }
});