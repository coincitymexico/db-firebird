<?php

namespace Coincitymexico\DbFirebird;

use Exception;

class ConfigFB
{
    /**
     * @var bool $exceptions
     */
    public static $exceptions = true;
    /**
     * @var
     */
    public static $db_connection;

    /**
     * @var string
     */
    public static string $db_user = 'sysdba', $db_host = '127.0.0.1:test.FDB', $db_pass = 'masterkey';

    /**
     * @return void
     * @throws Exception
     */
    public static function connect(): void
    {
        self::$db_connection = new Firebird(self::$db_host, self::$db_user, self::$db_pass);
    }

    /**
     * @param string $db_host
     * @param string $db_user
     * @param string $db_pass
     * @return void
     * @throws Exception
     */
    public static function connectCustom(string $db_host, string $db_user, string $db_pass): void
    {
        self::$db_connection = new Firebird($db_host, $db_user, $db_pass);
    }
}