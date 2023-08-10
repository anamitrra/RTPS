const domain = "https://rtps.assam.gov.in/services";

let frame = '<iframe class="mt-2" is="x-frame-bypass" id="iframeIdLogin" src="' + domain + '/loginWindow.do?servApply=N&&lt;csrf:token%20uri=%27loginWindow.do%27/&gt;" style="width: 100%; height: 430px; border: none;"></iframe>';

if (serviceId != '0000') {
    let sendurl = domain + "/directApply.do?serviceId=" + serviceId;
    // console.log(sendurl);

    frame = '<iframe class="mt-2" is="x-frame-bypass" id="iframeIdLogin" src="' + sendurl + '" style="width: 100%; height: 430px; border: none;"></iframe>';
}

$('.citizen-iframe').append(frame);
