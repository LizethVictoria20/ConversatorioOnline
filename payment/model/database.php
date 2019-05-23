<?php

class Database {
 private static $options = array(
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
  PDO::MYSQL_ATTR_SSL_KEY => '<configdir>/client-key.pem',
  PDO::MYSQL_ATTR_SSL_CERT  => '<configdir>/client-cert.pem',
  PDO::MYSQL_ATTR_SSL_CA => '<configdir>/ca.pem',
  PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
   );

    private static $dbName = 'pagos_conversatorio';
    private static $dbHost = '104.248.124.101:3306';
    private static $dbUsername = 'admin';
    private static $dbUserPassword = 'Xhsus756Q'; 
    private static $cont = null;

    public function __construct() {
        die('Init function is not allowed');
    }

    public static function connect() {
        // One connection through whole application
        if (null == self::$cont) {
            try {
                self::$cont = new PDO("mysql:host=" . self::$dbHost . ";" . "dbname=" . self::$dbName, self::$dbUsername, self::$dbUserPassword, array(
    PDO::MYSQL_ATTR_SSL_KEY    =>'/path/to/client-key.pem',
    PDO::MYSQL_ATTR_SSL_CERT   =>'/path/to/client-cert.pem',
    PDO::MYSQL_ATTR_SSL_CA     =>'/path/to/server-ca.pem',
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
));
            } catch (PDOException $e) {
                die("*1* Error al intentar conectarse a la base de datos : " . $e->getMessage());
            }
        }
        return self::$cont;
    }

    public static function disconnect() {
        self::$cont = null;
    }

}
/*

class Database {

    //private static $dbName = 'demo_mofongo';
	private static $dbName = 'invertirmejor';
    private static $dbHost = 'localhost';
    private static $dbUsername = 'root';
    private static $dbUserPassword = '';
    private static $cont = null;
    private static $mysqli  = null;

    public function __construct() {
        die('Init function is not allowed');
    }

    public static function connect() {
        // One connection through whole application
        if (null == self::$cont) {
            try {
                self::$cont = new PDO("mysql:host=" . self::$dbHost . ";" . "dbname=" . self::$dbName, self::$dbUsername, self::$dbUserPassword);
            } catch (PDOException $e) {
                die("*1* Error al intentar conectarse a la base de datos : " . $e->getMessage());
            }
        }
        return self::$cont;
    }

    public static function disconnect() {
        self::$cont = null;
        self::$mysqli=null;
    }

    public static function connect_mysqli() {

        if (null == self::$mysqli) {
            try {
                self::$mysqli = new mysqli(self::$dbHost, self::$dbUsername, self::$dbUserPassword, self::$dbName);
            } catch (Exception $e) {
                die("*1* Error al intentar conectarse a la base de datos : " . $e->getMessage());
            }
        }
         return self::$mysqli;
    }

}
*/
?>
