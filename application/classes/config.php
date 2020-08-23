<?php
    /**
     * @package Config Controller
     * 
     * @author Yuu Hirokabe
     * @version 1.0.0
     * @copyright www.yuuhiroka.be 2020
     */
    class config{
        /**
         * Storing config from config.json
         * 
         * @var object
         */
        private static $config;
        /**
         * Reading config file and setting all settings to variable
         * 
         * @param string $path  Config file path
         * @return void
         */
        public static function set(string $path){
            if (file_exists($path)){
                $configFile = file_get_contents($path);
                static::$config = json_decode($configFile);
            }
        }
        /**
         * Getting all settings for use in other functions
         * 
         * @return object
         */
        public static function get(){
            return static::$config;
        }
    }