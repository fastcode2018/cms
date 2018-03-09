<?php
    namespace library;

    use PDO;

    class System {

        private $DB;
        private $Boostrap;
        private $Loader;
        #private $Vaildator;
        private $Twig;


        # Variables
        private $default = true;

        function __construct()
        {
            $this->Boostrap = new Boostrap();
            #$this->Vaildator = new Vaildator;
            $this->Loader = new \Twig_Loader_Filesystem('../apps/Views/');
            $this->Twig = new \Twig_Environment($this->Loader);
            switch(SQL_TYPE)
            {
                case 'MYSQL_PDO':
                    $this->DB = new PDO('mysql:host='.SQL_HOST.';dbname='.SQL_DBNAME.';port='.SQL_PORT,SQL_USERNAME,SQL_PASS);
                break;
                case 'MYSQLI':
                    $this->DB = mysqli_connect(SQL_HOST,SQL_USERNAME,SQL_PASS,SQL_DBNAME,SQL_PORT);
                    if(mysqli_connect_errno())
                        die('error');
                break;
                case 'SQLITE':
                    $this->DB = new PDO('sqlite:'.SQLITE_PATH);
                break;
            }

            if(SQL_TYPE == 'MySQL_PDO' || SQL_TYPE == 'SQLITE')
                $this->DB->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
  
        }

        /**
         * Template
         */
        public function Get($path,$func)
        {
            if($this->CheckURL($this->CheckCurrentURL(),$path))
            {
                $this->default = false;
                $this->Boostrap->Record($this,$path,$func);
            }
        }

        function Default($func)
        {
            if($this->default == true)
                $this->Boostrap->Record($this,$this->CheckCurrentURL(),$func);
        }

        public function import($template,$Args,$Data)
        {
            echo  $this->Twig->render($template, ['args'=>$Args,'data'=>$Data]);
        }

        function GetArguments($path,$type = 'GET')
        {
            switch($type)
            {
                case 'GET':
                    $arr = $_GET;
                    #unset($arr['url']);
                    return $arr;
                break;
                case 'POST':
                    return $_POST;
                break;               
            }
        }

        function CheckURL($url,$check)
        {
            $per;
            #similar_text($url,$check,$per);
            #return ($per >= 90);
            return (levenshtein($url,$check) <= 1);
        }

        function CheckCurrentURL()
        {
            return '/'.(isset($_GET['url']) ? $_GET['url'] : '');
        }

        function RegexReady($raw)
        {
            return str_replace('/','\/',$raw);
        }

        /**
         * SQL
        */

        public function Query($str)
        {
            if(SQL_TYPE == 'MYSQL_PDO' || SQL_TYPE == 'SQLITE')
            {
                $q = $this->DB->prepare($str);
                $q->execute();
                $q->setFetchMode(PDO::FETCH_ASSOC);
                return $q->fetchAll();
            }
            else {
                $arr = [];
                if($q = mysqli_query($this->DB,$str))
                    while($r = mysqli_fetch_assoc($q))
                        $arr[] = $r;

                return $arr;
            }

        }

        public function Escape_String($str)
        {
            return (SQL_TYPE == 'MYSQLI' ? mysql_real_escape_string($this->DB,$str) : strtr($str, array( "\x00" => '\x00', "\n" => '\n', "\r" => '\r', '\\' => '\\\\', "'" => "\'", '"' => '\"', "\x1a" => '\x1a' )));
        }
    }
?>