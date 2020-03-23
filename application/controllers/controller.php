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
    }