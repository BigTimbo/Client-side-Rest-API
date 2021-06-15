<?php
    // used to debug code and display any errors if there are any
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);
    class Api
    {
        // Define database connection variables
        private $host = '############';
        private $user = '############';
        private $pwd = '############';
        private $dbName = '############';
        private $charSet = 'utf8mb4';
        // initialise variables used for the Api methods to define and use
        private $pdo;
        private $isPost;
        private $response;

        /**
         * the construct function is run at the start when the Api object is first created
         * thereby initiating the PDO connection immediately
         *
         * uses a try to initiate the connection using PDO and setting attributes to fetch associative arrays
         * or results a catch to echo out the PDOException message and set a 500 Http response code
         *
         * Api constructor.
         */
        public function __construct()
        {
            try {
                $this->pdo = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbName . ';charset=' . $this->charSet, $this->user, $this->pwd);
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            }catch (PDOException $e){
                echo "Connection failed: ".$e->getMessage();
                http_response_code(500);
            }
        }

        /**
         * the request method is called upon to begin the Api script and handle the request method
         *
         * if the request method is Post then set isPost true and call the write method
         * else if the request method is Get then set isPost false and call the read method
         */
        public function requestMethod()
        {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $this->isPost = true;
                $this->write();
            } else if ($_SERVER["REQUEST_METHOD"] == "GET") {
                $this->isPost = false;
                $this->read();
            }
        }

        /**
         * the write function handles the variables, validation check and actions for the Post request
         *
         * defines the Post variables to pass for validation and minimise $_POST verbosity
         *
         * sends the variables to the validator function and continue if returned true
         *
         * prepares mysql statement and execute connection
         *
         * checks for execution
         *
         * grabs the newly inserted id casting as an int for the json response, json encode, and then call the display method
         */
        public function write()
        {
            $name = $_POST['name'];
            $comment = $_POST['comment'];
            $oid = $_POST['oid'];
            if ($this->validator($oid, $name)) {
                $stmt = $this->pdo->prepare("INSERT INTO comments (oid, name, comment) VALUES (?, ?, ?)");
                $stmt->execute([$oid, urlencode($name), urlencode($comment)]);
                http_response_code(201);
                $this->response['id'] = (int)$this->pdo->lastInsertId();
                $this->display();
            }
        }

        /**
         * the read function handles the variables, validation check and actions for the Get request
         *
         * prepares mysql statement and execute connection
         *
         * if there are any rows in the database on this oid then continue
         *
         * sets Http response 200 and constructs the json response from the results of the SQL statement and call the display method
         *
         * else set the Http response 204
         */
        public function read()
        {
            $oid = $_GET['oid'];
            if ($this->validator($oid)) {
                $stmt = $this->pdo->prepare("SELECT name, comment FROM comments WHERE oid = ?");
                $stmt->execute([$oid]);
                if ($stmt->rowCount() > 0) {
                    http_response_code(200);
                    $this->response = array();
                    $this->response['oid'] = $oid;
                    $this->response['comments'] = [];
                    while ($row = $stmt->fetch()) {
                        $this->response['comments'][] = $row;
                    }
                    $this->display();
                }else{
                    http_response_code(204);
                }
            }
        }

        /**
         * The validateEmpty method checks if the given parameter arrays exist within another
         *
         * if there are no other differences return true, otherwise return false
         *
         * @param array $data
         * @param array $fieldNames
         * @return bool
         */
        public function validateEmpty(array $data, array $fieldNames): bool
        {
            if (count(array_diff($data, $fieldNames)) == 0) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * The validateRegex method uses a switch to validate the given parameters match their equivalent regex
         *
         * if so return true, otherwise return false
         *
         * @param $data
         * @param $fieldName
         * @return bool
         */
        public function validateRegex($data, $fieldName): bool
        {
            switch ($fieldName) {
                case 'name':
                    return preg_match("/^.{1,64}$/", $data);
                case 'oid':
                    return preg_match("/^[a-zA-Z0-9]{1,32}$/", $data);
                default:
                    return false;
            }
        }

        /**
         * the validator method acts as mediator for all validation checks to return a single boolean or 400 Http response
         *
         * if the isPost variable and all post validation checks are true, return true
         *
         * or if if the isPost variable is false and all Get validation checks are true, return true
         *
         * Otherwise set the Http response 400 and return false
         *
         * @param $oid
         * @param string $name
         * @return bool
         */
        public function validator($oid, string $name = ""): bool
        {
            if ($this->isPost &&
                $this->validateEmpty(array_keys($_POST), ['comment', 'name', 'oid']) &&
                $this->validateRegex($oid, 'oid') &&
                $this->validateRegex($name, 'name')) {
                return true;
            } elseif (!$this->isPost &&
                $this->validateEmpty(array_keys($_GET), ['oid']) &&
                $this->validateRegex($oid, 'oid')) {
                return true;
            } else {
                http_response_code(400);
                return false;
            }
        }

        /**
         * The display function is used to echo out either the Get or Post Api response
         */
        public function display()
        {
            echo json_encode($this->response, JSON_PRETTY_PRINT);
        }
    }
    // create a new database handler object and calls the handleRequest function
    $api = new Api();
    $api->requestMethod();
