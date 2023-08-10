'use strict';

// const API_BASE_URL = 'http://localhost/NIC/chatbot/apis/index.php/';
// console.log(baseURL);

const API_BASE_URL = `${baseURL}/site/api/chatbot/`;


$(document).ready(function () {

    // Hide the chatbox
    $('#chat-close').click(function (event) {
        $('.chatwindow').fadeToggle('fast');
    });

    // Chatbot icon actions
    $('.chatbot-btn').click(function (event) {    // chatbox
        $('.chatwindow').fadeToggle('fast');
    });

    // Language Choice
    document.querySelector('.lang-btns').addEventListener('click', function (event) {
        let lang;

        if (event.target.matches('.lang-btns button:nth-child(1)')) {
            // English
            lang = 'en';
            addToChat(createUserResponse('English'));
        }
        else if (event.target.matches('.lang-btns button:nth-child(2)')) {
            // Assamese
            lang = 'as';
            addToChat(createUserResponse('Assamese'));
        }
        else if (event.target.matches('.lang-btns button:nth-child(3)')) {
            // Bangla
            lang = 'bn';
            addToChat(createUserResponse('Bangla'));
        }

        getChatData(lang, null)    // all questions

    });

    // Start Over
    $('.chatbox button').click(function (event) {

        document.querySelector('.frame').scrollTo(0, 0);

        window.setTimeout(function () {
            $('.frame li').remove(':nth-child(n+3)')   // n->0
        }, 500)
    });


    //  FAQ buttons 
    document.querySelector('.chatwindow .frame > ul').addEventListener('DOMNodeInserted', function name(event) {

        const faqNode = event.target;

        if (faqNode instanceof Element && faqNode.matches('.bot-response') && faqNode.querySelector('div.faq-btns')) {
            // add listener on FAQ buttons

            faqNode.querySelector('div.faq-btns').addEventListener('click', function (event) {

                //    create user response as selected faq question
                addToChat(createUserResponse(event.target.textContent.trim()));


                // Now create bot response
                getChatData(event.target.dataset.lang, event.target.dataset.qid);

            });
        }

    });


    /* ******************************************************************** */
    // Display "New" label on notification button

    const hasNotiSeenLocal = Boolean(window.localStorage.getItem('notification-seen'));
    const notiNumLocal = Number(window.localStorage.getItem('notification-count'));

    // console.log('local:', hasNotiSeenLocal, notiNumLocal, 'server:', hasNewNotiServer, totalNotiServer);

    if (hasNewNotiServer && (totalNotiServer > notiNumLocal)) {
        $('.noti-btn > span').removeClass('d-none');
        $('.noti-btn').addClass('rimnotification');
    }

    // Notification Button and 'X' Button action
    $('.noti-btn, .noti-cls-btn').click(function (event) {
        $('#noti-area').fadeToggle('fast');

        // Hide new icon on Notification if it's showing

        $('.noti-btn > span').addClass('d-none');
        window.localStorage.setItem('notification-seen', true);
        window.localStorage.setItem('notification-count', totalNotiServer);
        $('.noti-btn').removeClass('rimnotification');

    });

});


function createUserResponse(text = '') {
    const html = `
    <li class="user-response p-3 align-self-end">
        <p class="mb-1 text-dark">${text}</p>
        <span class="dtm text-muted">${formatAMPM(new Date())}</span>
    </li>
    `;

    return html;
}

function createBotResponse(response) {
    var html = '';

    // console.log(response);

    if (typeof response === 'object' && 'questions' in response.data) {
        // List of faqs
        html = `
        <li class="bot-response p-2">
            ${response.data.faq || ''}
            <div class="mt-2 faq-btns d-flex faq-btns flex-column gap-3 mt-2">
        `;

        html += response.data.questions.reduce(function name(acc, val) {

            return `${acc}<button data-qid="${val.id}" data-lang="${val.lang}"  type="button" class="btn btn-sm btn-outline-primary">
            ${val.q}
            </button>`;

        }, '');

        html += '</div></li>';
    }
    else if (typeof response === 'object' && 'ans' in response.data) {
        // FAQ answer

        html = `
           <li class="bot-response p-2">${response.data.ans}</li>
           `;

    }
    else if (typeof response === 'string') {
        // Error

        html = `
           <li class="bot-response p-2">${response}</li>
           `;
    }

    return html;
}

function formatAMPM(date) {
    let hours = date.getHours();
    let minutes = date.getMinutes();
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0' + minutes : minutes;
    var strTime = hours + ':' + minutes + ' ' + ampm;
    return strTime;
}

function addToChat(html) {
    $('.chatwindow .frame > ul').append(html);
    // scroll to bottom
    document.querySelector('.frame li:last-child').scrollIntoView();
}


function getChatData(lang = 'en', qID = null) {

    $.ajax({
        url: API_BASE_URL + (qID ? `get_single_question?lang=${lang}&id=${qID}` : `get_all_questions?lang=${lang}`),
        type: 'GET',
        timeout: 1000,    // 1 sec

        beforeSend: function name(xhr) {
            // show loader
        },
        success: function (result, status, xhr) {

            addToChat(createBotResponse(result));

        },
        error: function (xhr, status, error) {
            addToChat(createBotResponse("Sorry, couldn't connect to server"));
        },
        complete: function (xhr, status) {
            // hide loader
        }

    })
}
