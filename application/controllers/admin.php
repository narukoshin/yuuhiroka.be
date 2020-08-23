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
        public static function index(array $options = []){
            /**
             * Checking if user is logged in, if not, redirecting to login page
             */
            if (static::isUserLogged()) return static::redirect('admin/dashboard');
            /**
             * Setting error message to null
             */
            if (empty($options)) $options = array_merge($options, ['{login.error}' => null]);
            /**
             * Setting site url
             */
            $options = array_merge($options, ['{site.url}' => config::get()->site_url]);
            $view = __DIR__ . '/../views/admin.html';
            if(!file_exists($view)){echo json_encode(['error' => 'file in views not found!']);exit;}
            $view = file_get_contents($view);
            $view = strtr($view, $options);
            echo $view;
        }
        /**
         * Admin login auth
         * 
         * @method POST
         * @return void
         */
        public static function login(){
            // If REQUEST METHOD is NOT POST, then redirecting user back to login page
            if ($_SERVER['REQUEST_METHOD'] == 'GET') return static::redirect('admin');
            // Making auth
            self::make_auth();
        }
        /**
         * Authorization algo
         * 
         * @return void
         */
        private static function make_auth(){
            // Getting values from login form
            $body        = (object)app('request')->body;
            $username    = $body->username;
            $passwd      = $body->password;
            $db          = Database::getConnection();
            // Sql statament
            $stmt        = $db->prepare('SELECT `id`, `password`, `email` FROM `accounts` WHERE `username` = ? LIMIT 1;');
            // Sending sql statament to server
            $result      = $stmt->execute([$username]);
            // If sql statament was successfuly executed
            if ($result){
               // If username is valid and exists in database   
               if ($stmt->rowCount()){
                    // Extracting variables from query
                    extract($stmt->fetch());
                     // Checking, if user entered password match to password in database
                     // If password is valid
                    if (password_verify($passwd, $password)){
                        // Creates user auth session
                        static::createSession($db, $username, $passwd, time());

                        // Updates user last login time
                        static::updateUserLastLogin($db, $id);

                        // Redirecting user to dashboard
                        return static::redirect('admin/dashboard');
                     // If password is invalid
                    } else {
                        // Returning login page with invalid password error
                        return self::index(['{login.error}' => 'You entered wrong password, please try again..']);
                    }
                 // If username is invalid
               } else {
                    // Returning login page with invalid username error
                    return self::index(['{login.error}' => "Sorry, we can't find account with that username."]);
               }
            }
        }
        /**
         * Dashboard
         * 
         * @return string
         */
        public static function dashboard(){
            /**
             * Checking if user is logged in, if not, redirecting to login page
             */
            if (!static::isUserLogged()) return static::redirect('admin');
            $options = [
                '{user.name}'       => 'yuuhirokabe',
                '{user.email}'      => 'hello@yuuhiroka.be',
                '{session.token}'   => $_SESSION['session_hash'], // Token against CSRF attack
            ];
            $view = __DIR__ . '/../views/dashboard.html';
            if(!file_exists($view)){echo json_encode(['error' => 'file in views not found!']);exit;}
            $view = file_get_contents($view);
            $view = strtr($view, $options);
            echo $view;
        }
        /**
         * Checks if user is authorized
         * 
         * @return bool
         */
        private static function isUserLogged(){
            /**
             * Storing Sessions into variable
             */
            $hash       = $_SESSION['session_hash']     ?? false;
            $user       = $_SESSION['session_username'] ?? false;
            /**
             * Checking if sessions exists
             */
            if ($hash and $user){
                $db     = database::getConnection();
                $stmt   = $db->prepare('SELECT `id` FROM `sessions` WHERE `hash` = ? AND `username` = ? LIMIT 1;');
                $res    = $stmt->execute([$hash, $user]);
                /**
                 * If SQL executed successfully
                 */
                if ($res){
                    /**
                     * Checking, session is in database
                     */
                    if ($stmt->rowCount()){
                        // TODO: Need to check difference between current date and expire date
                        return (object)['hash' => $hash, 'user' => $user];}
                    else{return false;}
                } else {return false;}
            } else {return false;}
        }
        /**
         * Creates Login Session
         * 
         * @param object $db        PDO Object
         * @param string $username  Username
         * @param string $password  Password
         * @param int    $time      Current Time
         * 
         * @return void
         */
        public function dashboard(){
            return self::index();
        }
    }