<?php
    /**
     * @package Database controller
     * 
     * @author Yuu Hirokabe
     * @version 1.0.0
     * @copyright www.yuuhiroka.be 2020
     */
    class database{
        /**
         * Store database connection
         * 
         * @var object
         */
        private static $db;
        /**
         * @param string $host      Databas hostname
         * @param string $user      Database username
         * @param string $pass      Database password
         * @param string $database  Database name
         * 
         * @return object
         */
        public function __construct(string $host, string $user, string $pass, string $database){
            try {
                static::$db = new PDO("mysql:host={$host};dbname={$database}", $user, $pass);
            } catch (Exception $e){
                echo $e->getMessage();
                exit;
            }
            return static::$db;
        }
        /**
         * Converting database connection to static
         * 
         * @return object
         */
        public static function getConnection(){
            return static::$db;
        }
    }