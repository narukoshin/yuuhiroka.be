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
            $stmt        = $db->prepare('SELECT `id`, `password`, `locked` FROM `accounts` WHERE `username` = ? LIMIT 1;');
            // Sending sql statament to server
            $result      = $stmt->execute([$username]);
            // If sql statament was successfuly executed
            if ($result){
               // If username is valid and exists in database   
               if ($stmt->rowCount()){
                    // Extracting variables from query
                    extract($stmt->fetch());
                    // If user password is null, redirecting user to create new password
                    if (is_null($password)){
                        return static::redirect('admin/create-password');
                    }
                    // Checking, if user entered password match to password in database
                    // If password is valid
                    if (password_verify($passwd, $password)){
                        // If account is locked for security purposes
                        if ($locked) return static::index(['{login.error}' => 'Sorry, account is locked for security purposes.']);

                        // Creates user auth session
                        static::createSession($db, $username, $passwd, time());

                        // Updates user last login time
                        static::updateUserLastLogin($db, $id);

                        // Creating login history
                        static::createLoginHistory($db, $username);

                        // Redirecting user to dashboard
                        return static::redirect('admin/dashboard');
                     // If password is invalid
                    } else {
                        // Insert user failed login into database
                        static::updateUserFailedLogin($db, $username, 'wrong-password');
                        // Returning login page with invalid password error
                        return self::index(['{login.error}' => 'You entered wrong password, please try again..']);
                    }
                 // If username is invalid
               } else {
                    // Insert user failed login to database
                    static::updateUserFailedLogin($db, $username, 'wrong-username');
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
            $user = static::isUserLogged();
            if (!$user) return static::redirect('admin');
            $options = [
                '{log.out}'         => config::get()->site_url . '/admin/logout/' . $user->hash,
                '{site.url}'        => config::get()->site_url
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
        private static function createSession(object $db, string $username, string $password, int $time){
            /**
             * Creating user session unique hash
             */
            $hash       = hash('sha256', $password.$time);
            /**
             * Session expire date
             */
            $expires    = time() + 60*60*24*7; // After 7 days
            $stmt       = $db->prepare('INSERT INTO `sessions` (`username`, `hash`, `auth_date`, `expire_date`, `user_agent`, `ip_address`) VALUES(:username, :hash, :auth_date, :expire_date, :user_agent, :ip_address);');
            $result     = $stmt->execute([
                ':username'     => $username,
                ':hash'         => $hash,
                ':auth_date'    => $time,
                ':expire_date'  => $expires,
                ':user_agent'   => $_SERVER['HTTP_USER_AGENT'],
                ':ip_address'   => static::getUserIP()
            ]);
            /**
             * Creating Session
             */
            $_SESSION['session_hash']       = $hash;
            $_SESSION['session_username']   = $username;
        }
        /**
         * Updates user last logged in time
         * 
         * @param object $db    PDO Object
         * @param int $id   User ID
         * @return void
         */
        private static function updateUserLastLogin(object $db, int $id){
            $stmt = $db->prepare('UPDATE `accounts` SET `last_logged` = current_timestamp WHERE `id` = ?;');
            $stmt->execute([$id]);
        }
        /**
         * Logging out authenticated user
         * 
         * @param string|null $token Token against CSRF attack
         * @return void
         */
        public static function logout($token = null){
            // If token exists
            if ($token){
                // Checking, if user is logged in
                $isLogged = static::isUserLogged();
                if ($isLogged){
                    // if token match with session token
                    if ($token == $isLogged->hash){
                        // Getting database connection
                        $db     = database::getConnection();
                        // Deleting session from database
                        $stmt   = $db->prepare('DELETE FROM `sessions` WHERE `username` = ? AND `hash` = ?;');
                        $res    = $stmt->execute([$_SESSION['session_username'], $_SESSION['session_hash']]);
                        // If query was executed successfuly
                        if ($res){
                            // Removing sessions
                            unset($_SESSION['session_username']);
                            unset($_SESSION['session_hash']);
                            // Redirect user to login page
                            return static::redirect('admin');
                        } else return static::redirect('dashboard');
                    } else return static::redirect('admin');
                } else return static::redirect('admin');
            } else return static::redirect('admin');
        }
        /**
         * Update database if user failed to login to admin panel
         * 
         * @param object 
         * @return void
         */
        private static function updateUserFailedLogin(object $db, string $username, string $type){
            $stmt = $db->prepare('INSERT INTO `failed_logins` (`username`, `type`, `user_agent`, `ip_address`) VALUES(:username, :type, :user_agent, :ip_address);');
            $stmt->execute([
                ':username'     => $username,
                ':type'         => $type,
                ':user_agent'   => $_SERVER['HTTP_USER_AGENT'],
                ':ip_address'   => static::getUserIP()
            ]);
        }
        /**
         * Creating login history
         * 
         * @param object $db    PDO Object
         * @param string $username Username
         * @return void
         */
        private static function createLoginHistory(object $db, string $username){
            $stmt = $db->prepare('INSERT INTO `login_history` (`username`, `user_agent`, `ip_address`) VALUES (:username, :user_agent, :ip_address);');
            $stmt->execute([
                ':username'         => $username,
                ':user_agent'       => $_SERVER['HTTP_USER_AGENT'],
                ':ip_address'       => static::getUserIP()
            ]);
        }
        /**
         * if user password is null, redirect to create-password page, to create new passwrod
         * 
         * @return void
         */
        public function create_password(){
            return static::index(['{login.error}' => 'Sorry, this function is not available right now.']);
        }
    }