<?php

// Appeals demo  TODO :: Remove Later
$route['appeal/demo/send-otp'] = 'appeal/appeals_demo/send_otp';
$route['appeal/demo/process-login'] = 'appeal/appeals_demo/process_appeal_login';
$route['appeal/demo/apply'] = 'appeal/appeals_demo/apply_for_appeal';
$route['appeal/demo/process'] = 'appeal/appeals_demo/process';
$route['appeal/demo/ack'] = 'appeal/appeals_demo/acknowledgement';
$route['appeal/demo/preview-n-track'] = 'appeal/appeals_demo/preview_and_track';
$route['appeal/demo/comment'] = 'appeal/appeals_demo/submit_comment';
$route['appeal/demo/process/show-attachments/(:any)'] = 'appeal/appeals_demo/show_process_attachment_list/$1';
$route['appeal/demo/process/refresh/(:any)'] ['GET']= 'appeal/appeals_demo/refresh_process_table/$1';