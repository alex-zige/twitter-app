<?php

    abstract class Database_Object
    {
        protected static $DB_Name;
        protected static $DB_Open;
        protected static $DB_Conn;

        protected function __construct($database, $hostname, $username, $password)
        {
            self::$DB_Name = $database;
            self::$DB_Conn = mysql_connect($hostname, $username, $password);
            if (!self::$DB_Conn) { die('Critical Stop Error: Database Error<br />' . mysql_error()); }
            mysql_select_db(self::$DB_Name, self::$DB_Conn);
        }

        private function __clone() {}

        public function __destruct()
        {
        //        mysql_close(self::$DB_Conn);  <-- commented out due to current shared-link close 'feature'.  If left in, causes a warning that this is not a valid link resource.
        }
    }
    final class DB extends Database_Object
    {
        public static function Open($database = 'twitter', $hostname = 'localhost', $username = 'root', $password = 'root')
        {   

            if (!self::$DB_Open)
            {
                self::$DB_Open = new self($database, $hostname, $username, $password);
            }
            else
            {
                self::$DB_Open = null;
                self::$DB_Open = new self($database, $hostname, $username, $password);
            }
            return self::$DB_Open;
        }

        public function qry($sql, $return_format = 0)
        {
            $query = mysql_query($sql, self::$DB_Conn) OR die(mysql_error());
            switch ($return_format)
            {
                case 1:
                    $query = mysql_fetch_row($query);
                    return $query;
                    break;
                case 2:
                    $query = mysql_fetch_array($query);
                    return $query;
                    break;
                case 3:
                    $query = mysql_fetch_row($query);
                    $query = $query[0];
                    return $query;
                default:
                    return $query;
            }
        }
    }
