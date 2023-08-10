    
    <link href="<?=base_url("assets/site/".$theme."/chatbot/css/chatBot.css")?>" rel="stylesheet" type="text/css"/>
    <link href="<?=base_url("assets/site/".$theme."/chatbot/css/timeline.css")?>" rel="stylesheet" type="text/css"/>

    <script src="<?=base_url("assets/site/" . $theme . "/chatbot/js/chatbot.js")?>" defer></script>

    <!-- <script src="<?=base_url("assets/site/" . $theme . "/chatbot/js/rocket-loader.min.js")?>" defer></script> -->


<!-- Chat bot UI start -->
<div class="chat-screen">
    <div class="chat-header">
        <div class="chat-header-title">
            Xohari
        </div>
        <div class="chat-header-option hide">
            <span class="dropdown custom-dropdown">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink1" style="will-change: transform;">

                </div>
            </span>
        </div>
    </div>
    <div class="chat-mail">
        <div class="row">
            <div class="col-md-12 text-center mb-2">
                <p>Hi 👋! Welcome to Xohari</p>
            </div>
        </div>
        <div class="row">



            <div class="col-md-12" style="align-items: center;display: grid;">
                <button class="btn btn-primary btn-rounded btn-block">Start Chat</button>
            </div>
           <div class="col-md-12">
               <div class="powered-by">Powered by RTPS Assam</div>
           </div>
        </div>
    </div>
    <div class="chat-body hide">

        <div class="chat-bubble you">Welcome to RTPS Assam, if you need help simply reply to this message, we are ready to help.</div>



    </div>

    <div class="chat-input hide">
        <input class="chat-input" placeholder="Type a message...">
        <!-- <div class="input-action-icon">

            <a><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg></a>
        </div> -->
    </div>


</div>
<div class="chat-bot-icon">
    <img src="<?=base_url("assets/site/" . $theme . "/chatbot/img/help.jpg")?>" title="Xohari"/>
    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square animate"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg> -->
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x "><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>

</div>
<!-- Chat Bot UI Ends -->
