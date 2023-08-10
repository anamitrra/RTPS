$(document).ready(function () {

const API_URL='http://10.177.15.95:5005/webhooks/rest/webhook';

 //Toggle fullscreen
 $(".chat-bot-icon").click(function (e) {
    $(this).children('img').toggleClass('hide');
    $(this).children('svg').toggleClass('animate');
    $('.chat-screen').toggleClass('show-chat');
});
$('.chat-mail button').click(function () {
    $('.chat-mail').addClass('hide');
    $('.chat-body').removeClass('hide');
    $('.chat-input').removeClass('hide');

});


var me={};
var you={};
    function formatAMPM(date) {
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0' + minutes : minutes;
    var strTime = hours + ':' + minutes + ' ' + ampm;
    return strTime;
}
function insertChat(who, text, time) {
    if (time === undefined) {
        time = 0;
    }
    var control = "";
    var date = formatAMPM(new Date());

    if (who == "me") {
        control = ' <div class="chat-bubble me">'+text+'<small>' +' ' +date + '</small></div>';
    } else {
        control = '<div class="chat-bubble you">'+text+'<small>' + ' '+date + '</div>';
    }
    setTimeout(
        function () {
            $(".chat-body").append(control).scrollTop($(".chat-body").prop('scrollHeight'));
        }, time);

}



$(".chat-input").on("keydown", function (e) {
    if (e.which == 13) {
        var text = $(this).val();
        if (text !== "") {
            insertChat("me", text);
            $(this).val('');
            $.ajax({
                url: API_URL,
                type: 'POST',
                data: JSON.stringify({ "sender": Math.random(), "message": text }),
                dataType: 'json',
                crossDomain: true,
                success: function (result) {
                    console.log(result);

                    $(result).each(function (key, val) {
                        insertChat("you", val.text, 500);
                        $(".chat-body").scrollTop($(".chat-body")[0].scrollHeight);
                    });

                }
            });
        }
    }
});

$('body > div > div > div:nth-child(2) > span').click(function () {
    $(".chat-input").trigger({ type: 'keydown', which: 13, keyCode: 13 });

});


});