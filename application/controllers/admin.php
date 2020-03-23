<?php
    /**
     * @author Yuu Hirokabe
     * @version 1.0.0
     * @copyright yuuhiroka.be 2020
     */
    class admin extends controller{
        /**
         * Admin page view
         * 
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
         * @return void
         */
        public static function login(){}
    }