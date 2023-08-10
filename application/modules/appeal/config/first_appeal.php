<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
use MongoDB\BSON\ObjectId;
$config['fap_extension_time']="30"; // within this extension time appellant have to provide reason for delay
$config['fap_processing_time']="30"; // within this time appellant can apply without reason
$config['process_users'] = [
                                [
                                    'userId' => new ObjectId('5faccf886a4c0000e20045db'),
                                    'action' => ["view","process",'remark','in-progress','hearing','seek-info','issue-order']
                                ],
                                [
                                    "userId" => new ObjectId('5fbe2ab9bf5600000a00080b'),
                                    "action" => ["view","process",'remark','in-progress','hearing','seek-info','issue-order']
                                ]
                            ]; // todo : add from service configuration later // add in official mapping

$config['action']['DA']  = ['view','process','seek-info-from-appellant','forward-to-aa','generate-hearing-order','upload-hearing-order','forward-for-approval','upload-disposal-order','generate-disposal-order','upload-rejection-order','generate-rejection-order'];
$config['action']['DPS'] = ['view','process','dps-reply'];
$config['action']['AA']  = ['view','process','remark','in-progress','hearing','seek-info','issue-order','resolved','rejected','provide-hearing-date','revert-back-to-da','approve-hearing-order'];

$config['da_action']  = ['view','process','seek-info-from-appellant','forward-to-aa','generate-hearing-order','upload-hearing-order','forward-for-approval','upload-disposal-order','generate-disposal-order','upload-rejection-order','generate-rejection-order'];
$config['dps_action'] = ['view','process','dps-reply'];
$config['aa_action']  = ['view','process','remark','in-progress','hearing','seek-info','issue-order','resolved','rejected','provide-hearing-date','revert-back-to-da','approve-hearing-order'];