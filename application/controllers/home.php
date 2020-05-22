<?php
    /**
     * @package Home Controller
     * 
     * @author Yuu Hirokabe
     * @version 1.0.0
     * @copyright www.yuuhiroka.be 2020
     */
    class home extends controller{
        /**
         * @var object Language class
         */
        private static $lang;
        /**
         * @var string current language
         */
        private static $current_lang;
        /**
         * Storing database connection
         * 
         * @var object \Database
         */
        private static $db;
        /**
         * Construction function
         * 
         * @return void
         */
        public function __construct(){
            self::$current_lang = language::current();
            static::$db         = database::getConnection();
        }
        /**
         * Default viewing Home
         * 
         * @method GET
         * @return void
         */
        public static function index($lang = null){
            if(!$lang){
                $lang = self::$current_lang ?? 'en';
            }
            $view = __DIR__ . '/../views/home.html';
            if(!file_exists($view)){echo json_encode(['error' => 'file in views not found!']);exit;}
            $view = file_get_contents($view);
            foreach(language::get_translate()[$lang] as $key => $val){
                $view = str_replace($key, $val, $view);
            }
            $view = str_replace('{site.lang}', $lang, $view);
            echo $view;
        }
        /**
         * Setting language to english
         * 
         * @return void
         */
        public static function lang_en(){
            language::en();
            return self::index('en');
        }
        /**
         * Setting language to latvian
         * 
         * @return void
         */
        public static function lang_lv(){
            language::lv();
            return self::index('lv');
        }
        /**
         * Setting language to japanese
         * 
         * @return void
         */
        public static function lang_ja(){
            language::ja();
            return self::index('ja');
        }
        /**
         * Setting language to russian
         * 
         * @return void
         */
        public static function lang_ru(){
            language::ru();
            return self::index('ru');
        }
        /**
         * Receive message from #contact and store in databae
         * 
         * @method POST
         * @return json
         */
        public static function send_message(){
            if ($_SERVER['REQUEST_METHOD'] == 'GET') exit;
            /**
             * Storing POST to Objected array
             */
            $data = (object)[
                'name'      => $_POST['name'] ?? null,
                'email'     => $_POST['email'] ?? null,
                'message'   => $_POST['message'] ?? null,
            ];
            /**
             * Checking, if fields aren't empty
             */
            $check_for_null = function () use ($data) {
                foreach ($data as $key => $value){
                    if (empty($value))
                        return false;
                } return true;
            };
            if ($check_for_null()){
                $stmt = static::$db->prepare('
                    INSERT INTO `messages` (
                        `name`,
                        `email`,
                        `message`
                    ) VALUES (
                        :name,
                        :email,
                        :message
                    );
                ');
                $stmt->execute([
                    ':name' => $data->name,
                    ':email' => $data->email,
                    ':message' => $data->message
                ]);
                echo json_encode([
                    'error'     => false,
                    'message'   => ''
                ]);
            } else {
                echo json_encode([
                    'error'     => true,
                    'message'   => ''
                ]);
            }
        }
    }