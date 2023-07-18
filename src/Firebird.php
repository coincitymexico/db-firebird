<?php

namespace Coincitymexico\DbFirebird;

use Exception;

/**
 * Clase para conexion a Firebird
 */
class Firebird
{
    //Variables Globales con los datos de conexión
    private string $host;
    private string $dbUser;
    private string $dbPass;
    public $dbConn;
    public static bool $connected = false;


    /**
     * Recibe valores de conexión
     * @param $host
     * @param $dbUser
     * @param $dbPass
     * @throws Exception
     */
    function __construct($host, $dbUser, $dbPass)
    {
        $this->host = $host;
        $this->dbUser = $dbUser;
        $this->dbPass = $dbPass;
        $this->connectToFb();
    }

    /**
     * Realiza la conexión a la base de datos
     * @return void
     * @throws Exception
     */
    function connectToFb(): void
    {
        if(ConfigFB::$exceptions){
            $this->dbConn = ibase_connect($this->host, $this->dbUser, $this->dbPass, "UTF8");
            if (!$this->dbConn) {
                Firebird::$connected = false;
                throw new Exception('Acceso Denegado al servidor Firebird!');
            }
            Firebird::$connected = true;
        }else {
            try {
                $this->dbConn = ibase_connect($this->host, $this->dbUser, $this->dbPass, "UTF8");
                if (!$this->dbConn && ConfigFB::$exceptions) {
                    Firebird::$connected = false;
                    throw new Exception('Acceso Denegado al servidor Firebird!');
                }
                if (!$this->dbConn){
                    Firebird::$connected = false;
                }
                Firebird::$connected = true;
            } catch (Exception $e) {
                Firebird::$connected = false;
                $this->dbConn = false;
            }
        }
    }

    /**
     * Verifica si hay conexion con la base de datos
     * @return bool
     */
    public function checkConnection(): bool
    {
        return Firebird::$connected;
    }

    /**
     * Cierra la conexión de Firebird
     * @return void
     */
    function closeFb(): void
    {
        ibase_close($this->dbConn);
    }

    /**
     * Desconecta de la base de datos
     * @return void
     */
    public function disconnect(): void
    {
        $this->closeFb();
    }

    /**
     * Función para enviar consultas a Firebird
     * @param $sql
     * @return IbaseResult|void
     * @throws Exception
     */
    function queryFB($sql): IbaseResult
    {
        if ($queryResource = ibase_query($this->dbConn, $sql)) {
            return new IbaseResult($this, $queryResource);
        } else {
            throw new Exception(ibase_errmsg(), ibase_errcode());
        }
    }
}