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
         * Construction function
         * 
         * @return void
         */
        public function __construct(){
            self::$current_lang = language::current();
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
    }