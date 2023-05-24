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
        $this->dbConn = ibase_connect($this->host, $this->dbUser, $this->dbPass, "UTF8");
        if (!$this->dbConn && ConfigFB::$exceptions) {
            throw new Exception('Acceso Denegado al servidor Firebird!');
        }
    }

    /**
     * Verifica si hay conexion con la base de datos
     * @return bool
     */
    public function checkConnection(): bool
    {
        $this->dbConn = ibase_connect($this->host, $this->dbUser, $this->dbPass, "UTF8");
        if (!$this->dbConn) {
            return false;
        } else {
            return true;
        }
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