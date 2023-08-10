<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ChatBot extends Site_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
    }

    public function get_all_questions()
    {
        $lang_arr = [
            'en' => 'FAQs',
            'as' => 'সঘনাই সোধা প্ৰশ্ন সমূহ',
            'bn' => 'সচরাচর জিজ্ঞাসা করা প্রশ্ন সমূহ'
        ];

        $lang = in_array(trim($_GET['lang'] ?? 'en'), ['en', 'as', 'bn'], true) ? trim($_GET['lang'] ?? 'en') : 'en';


        $this->mongo_db->select(array("question.{$lang}"));
        $this->mongo_db->where([]);
        $docs = (array) $this->mongo_db->get('faq_chat');

        // pre($res);

        $res = array_map(
            function ($item) use ($lang) {

                return [
                    'id' => $item->_id->{'$id'},
                    'q' => $item->question->$lang,
                    'lang' => $lang,
                ];
            },
            $docs
        );

        $this->send_response(200, [
            'faq' => $lang_arr[$lang],
            'questions' => $res,
        ]);
    }

    public function get_single_question()
    {
        $lang = in_array(trim($_GET['lang'] ?? 'en'), ['en', 'as', 'bn'], true) ? trim($_GET['lang'] ?? 'en') : 'en';
        $id = trim($_GET['id'] ?? null);

        if (empty($id)) {
            $this->get_all_questions();
            return;
        }

        $this->mongo_db->where('_id', new MongoDB\BSON\ObjectId($id));
        $doc = $this->mongo_db->find_one('faq_chat');

        // pre($doc);

        $this->send_response(200, [
            'id' => $doc->_id->{'$id'},
            'q' => $doc->question->$lang,
            'ans' => $doc->ans->$lang,
        ]);
    }


    private function send_response($code = 200, $data)
    {
        // pre($data);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => ($code == 200) ? true : false,
                'data' => $data
            ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}
