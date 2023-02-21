<?php

namespace Coincitymexico\DbFirebird;
/**
 *
 */
class IbaseResult
{
    public $ibase;
    private $queryFb;

    //Auxiliar para almacenar datos de la consulta
    function __construct(&$ibase, $queryFb)
    {
        $this->ibase =& $ibase;
        $this->queryFb = $queryFb;
    }

    //Recupera informacion resultado de Query
    function fetch(): object|bool
    {
        return ibase_fetch_object($this->queryFb);
    }

    //Lista la cantidad de Campos de la tabla consulta
    function num_fields(): int
    {
        return ibase_num_fields($this->queryFb);
    }
}
