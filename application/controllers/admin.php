<?php
    /**
     * @package Admin Controller
     * 
     * @author Yuu Hirokabe
     * @version 1.0.0
     * @copyright www.yuuhiroka.be 2020
     */
    class admin extends controller{
        /**
         * Admin page view
         * 
         * @method GET
         * @return void
         */
        public static function index(){
            $view = __DIR__ . '/../views/admin.html';
            if(!file_exists($view)){echo json_encode(['error' => 'file in views not found!']);exit;}
            $view = file_get_contents($view);
            echo $view;
        }
        /**
         * Admin login auth
         * 
         * @method POST
         * @return void
         */
        public static function login(){
            // Making auth
            self::make_auth();
            return self::index();
        }
        /**
         * Making auth
         * 
         * @return void
         */
        private static function make_auth(){
            $body        = (object)app('request')->body;
            $username    = $body->username;
            $password    = $body->password;

            echo $password;
        }
        /**
         * Dashboard
         * 
         * @return void
         */
        public function dashboard(){
            return self::index();
        }
    }