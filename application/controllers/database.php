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
         * Storing database connection
         * 
         * @var object \PDO
         */
        private $db;
        /**
         * construction function
         * 
         * @param string $host
         * @param string $user
         * @param string $pass
         * @param string $base
         * 
         * @return void
         */
        public function __construction(string $host, string $user, string $pass, string $base){
            try{
                $this->db = new pdo("mysql:host={$host};dbname={$base}", $user, $pass);
            } catch (Exception $e){
                echo $e->getMessage();
                exit;
            }
            return $this->db;
        }
        /**
         * Getting connection
         * 
         * @return object
         */
        public function getDatabase(){
            return $this->db;
        }
    }