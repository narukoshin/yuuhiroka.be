<?php
    /**
     * @package Language Controller
     * 
     * @author Yuu Hirokabe
     * @version 1.0.0
     * @copyright www.yuuhiroka.be 2020
     */
    class Language{
        /**
         * @var string current language
         */
        private static $currentLanguage;
        /**
         * @var string default language
         */
        private static $defaultLanguage = 'en';
        /**
         * @var array translated content
         */
        private static $translate;
        /**
         * @return void
         */
        public function __construct(){
            self::$currentLanguage = $_COOKIE['language'] ?? self::$defaultLanguage;
        }
        /**
         * Setting language to english
         * 
         * @return void
         */
        public static function en(){
            setcookie('language', 'en', time()+60);
        }
        /**
         * Setting language to latvian
         * 
         * @return void
         */
        public static function lv(){
            setcookie('language', 'lv', time()+60);
        }
        /**
         * Setting language to russian
         * 
         * @return void
         */
        public static function ru(){
            setcookie('language', 'ru', time()+60);
        }
        /**
         * Setting language to japanese
         * 
         * @return void
         */
        public static function ja(){
            setcookie('language', 'ja', time()+60);
        }
        /**
         * Returning current language
         * 
         * @return string
         */
        public static function current(){
            return self::$currentLanguage;
        }
        /**
         * Translating site content
         * 
         * @param array $lang
         * @return void
         */
        public static function translate(array $lang){
            self::$translate = $lang;
        }
        /**
         * Returning translated content to views
         * 
         * @return array
         */
        public static function get_translate(){
            return self::$translate;
        }
    }