<?php
    /**
     * @author Yuu Hirokabe
     * @version 1.0.0
     * @copyright www.yuuhiroka.be 2020
     */
    abstract class controller{
        /**
         * @abstract
         * Default viewing home
         * 
         * @return void
         */
        abstract static function index();
        /**
         * @static
         * Templating content
         * 
         * @return void
         */
        public static function replace(){
            // code here
        }
        /**
         * @static
         * Redirect function
         * 
         * @param string $url   Redirection page
         * @param bool $local   Local redirect or to another website
         * @return void
         */
        public function redirect(string $url, bool $local = true){
            if ($local){
                header('Location: ' . config::get()->site_url . '/' . $url);
                exit;
            } else {
                header('Location: ' . $url);
                exit;
            }
        }
        /**
         * @static
         * Get User IP address
         * 
         * @return string
         */
        public static function getUserIP(){
            return @filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP);
        }
    }