<?php
    /**
     * @package Home Controller
     * 
     * @author Yuu Hirokabe
     * @version 1.0.0
     * @copyright www.yuuhiroka.be 2020
     */
    class home extends controller{
        /**
         * @var object Language class
         */
        private static $lang;
        /**
         * @var string current language
         */
        private static $current_lang;
        /**
         * Storing database connection
         * 
         * @var object \Database
         */
        private static $db;
        /**
         * @var string User Agent
         */
        private static $userAgent;
        /**
         * @var string CSRF token
         */
        private static $csrf_token;
        /**
         * Construction function
         * 
         * @return void
         */
        public function __construct(){
            self::$current_lang = language::current();
            static::$db         = database::getConnection();
            static::$userAgent  = $_SERVER['HTTP_USER_AGENT'];
            static::$csrf_token = $_SESSION['token'] ?? false;

        }
        /**
         * Default viewing Home
         * 
         * @method GET
         * @return void
         */
        public static function index($lang = null){
            if(!$lang){
                $lang = self::$current_lang ?? 'en';
            }
            $view = __DIR__ . '/../views/home.html';
            if(!file_exists($view)){echo json_encode(['error' => 'file in views not found!']);exit;}
            $view = file_get_contents($view);
            foreach(language::get_translate()[$lang] as $key => $val){
                $view = str_replace($key, $val, $view);
            }
            $view = str_replace('{site.lang}', $lang, $view);
            $view = str_replace('{csrf_token}', static::generate_csrf_token(), $view);
            echo $view;
        }
        /**
         * Setting language to english
         * 
         * @return void
         */
        public static function lang_en(){
            language::en();
            return self::index('en');
        }
        /**
         * Setting language to latvian
         * 
         * @return void
         */
        public static function lang_lv(){
            language::lv();
            return self::index('lv');
        }
        /**
         * Setting language to japanese
         * 
         * @return void
         */
        public static function lang_ja(){
            language::ja();
            return self::index('ja');
        }
        /**
         * Setting language to russian
         * 
         * @return void
         */
        public static function lang_ru(){
            language::ru();
            return self::index('ru');
        }
        /**
         * Receive message from #contact and store in databae
         * 
         * @method POST
         * @return json
         */
        public static function send_message(){
            if ($_SERVER['REQUEST_METHOD'] == 'GET') exit;
            /**
             * Storing POST to Objected array
             */
            $data = (object)[
                'name'      => $_POST['name'] ?? null,
                'email'     => $_POST['email'] ?? null,
                'message'   => $_POST['message'] ?? null,
                'token'     => $_POST['csrf'] ?? null,
            ];
            /**
             * Checking, if fields aren't empty
             */
            $check_for_null = function () use ($data) {
                foreach ($data as $key => $value){
                    if (empty($value))
                        return false;}
                return true;
            };
            if ($check_for_null()){
                /**
                 * Testing section
                 */
                static::verify_csrf_token($data->token);
                static::test_userAgent();
                static::test_name($data->name);
                static::test_email($data->email);
                static::test_message($data->message);
                /*****/
                $stmt = static::$db->prepare('
                    INSERT INTO `messages` (
                        `name`,
                        `email`,
                        `message`,
                        `user_agent`
                    ) VALUES (
                        :name,
                        :email,
                        :message,
                        :agent
                    )
                ');
                $stmt->execute([
                    ':name' => trim($data->name),
                    ':email' => $data->email,
                    ':message' => $data->message,
                    ':agent' => static::$userAgent
                ]);
                echo json_encode([
                    'error'     => false,
                    'message'   => 'Message successfuly saved...'
                ]);
            } else {
                /**
                 * If one of field was empty
                 */
                echo json_encode([
                    'error'     => true,
                    'message'   => 'One or more fields are empty!'
                ]);
                exit;
            }
        }
        /**
         * Testing email
         * 
         * @param string    $email Email from #contact_form
         * @return json|void
         */
        private static function test_email(string $email){
            /**
             * Testing, if email pattern is correct
             */
           if(filter_var($email, FILTER_VALIDATE_EMAIL)){
               /**
                * Testing, if email domain exists
                */
                $domain = explode('@', $email)[1];
                $content = @file_get_contents('http://'.$domain) ? true:false;
                if (!$content){
                    /**
                     * If email domain don't exists
                     */
                    echo json_encode([
                        'error' => true,
                        'element' => 'email',
                        'message' => 'Email domain is not available.'
                    ]);
                    exit;
                }
           } else {
               /**
                * If email pattern is invalid
                */
                echo json_encode([
                    'error' => true,
                    'element' => 'email',
                    'message' => 'Email is incorrect.'    
                ]);
                exit;
           }
        }
        /**
         * Testing name
         * 
         * @param string    $name Name from #contact_form
         * @return json|void
         */
        private static function test_name(string $name){
            /**
             * Testing if name pattern is correct
             */
            preg_match('/^([a-zA-Z\W]+)$/', $name, $matches);
            if (!$matches){
                /**
                 * If name pattern does not match
                 */
                echo json_encode([
                    'error'     => true,
                    'element'   => 'name',
                    'message'   => 'name does not match pattern!'
                ]);
                exit;
            }
        }
        /**
         * Testing message
         * 
         * @param string    $message Message from #contact_form
         * @return json|void
         */
        private static function test_message(string $message){}
        /**
         * Testing User-Agent
         * 
         * 
         * @return json:void
         */
        private static function test_userAgent(){
            if (empty(static::$userAgent)) {
                echo json_encode([
                    'error' => true,
                    'mesage' => 'No User Agent detected'
                ]);
                exit;
            }
        }
        /**
         * Generates csrf token and saves in session
         * 
         * @return string   MD5 token
         */
        private static function generate_csrf_token(){
            $token = md5(time().'www.yuuhiroka.be');
            $_SESSION['token'] = $token;
            return $token;
        }
        /**
         * Verify CSRF token from input with session
         * 
         * @param string $token  MD5 token
         * @return void
         */
        private static function verify_csrf_token(string $token){
            if (!static::$csrf_token) {
                echo json_encode([
                    'error'     => true,
                    'message'   => 'No CSRF Token'
                ]);
                exit;
            } else {
                if (!static::$csrf_token == $token){
                    echo json_encode([
                        'error'     => true,
                        'message'   => 'CSRF Token does not match'
                    ]);
                    exit;
                }
            }
        }
    }