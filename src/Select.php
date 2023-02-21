<?php

namespace Coincitymexico\DbFirebird;

use Exception;

/**
 * Clase para ejecutar un SELECT a una base de datos FIREBIRD
 */
class Select
{
    /**
     * Constructor para la consulta, dentro de arreglo $sql van las opciones EXPRESSIONS, TABLE, WHERE, GROUP ORDER.
     * @param $sql
     * @return array
     * @throws Exception
     */
    public function construct($sql): array
    {

        $Statement = "select ";

        $Statement .= $sql["expressions"] ?? " * ";

        if (isset($sql["table"])) {
            $Statement .= " from " . $sql["table"];
        } else {
            $error["type"] = "Error";
            $error["query"] = $Statement;
            $error["no"] = "200";
            $error["result"] = "Query sin tabla, incluye TABLE a tu consulta";
            return $error;
        }

        if (isset($sql["join"])) {
            $Statement .= " " . $sql["join"] . " ";
        }

        if (isset($sql["where"])) {
            $Statement .= " where " . $sql["where"];
        }

        if (isset($sql["group"])) {
            $Statement .= " group by " . $sql["group"];
        }

        if (isset($sql["order"])) {
            $Statement .= " order by " . $sql["order"];
        }

        return self::execute($Statement);

    }


    /**
     * Ejecuta la Query a la base de datos y dependiendo el resultado muestra el error Interbase, error generado
     * manual por si no hay valores devueltos o el resultado de la consulta. Todo como arreglo $view
     * @param $db_Query
     * @return array
     * @throws Exception
     */
    function execute($db_Query): array
    {
        //Obtenemos la Variable de conexion global
        //Realizamos la conexion a la base de datos con los datos de Config/conection-fb.php
        ConfigFB::connect();
        //echo $db_Query."<br>";
        //Ejecutamos la Query en la base de datos Firebird.
        $rs_Data = ConfigFB::$db_connection->queryFb($db_Query);
        //Si el resultado ha sido exitoso...
        if ($rs_Data) {
            //Guardamos los valores de la consulta en un arreglo.
            $i = 0;
            while ($row = $rs_Data->fetch()) {
                foreach ($row as $key => $value) {
                    $view[$i][$key] = $value;
                }
                $i++;
            }
            //Si $view esta declarado significa que se han obtenido registros y los enviamos de regreso para manipular esta informacion
            if (isset($view)) {
                $result["type"] = "Success";
                $result["query"] = $db_Query;
                $result["no"] = "100";
                $result["result"] = $view;
            } else {// De lo contrario devolvemos error sin resultados para la consulta
                $result["type"] = "error";
                $result["query"] = $db_Query;
                $result["no"] = "101";
                $result["result"] = "Sin resultados";
            }
        } else {//Si se ha producido un error recolectamos informacion para mostrar el numero de error Firebird, el mensaje y con que query se ha producido este error.
            $result["type"] = "error";
            $result["query"] = $db_Query;
            $result["no"] = ConfigFB::$db_connection->noError;
            $result["result"] = ConfigFB::$db_connection->isError;
            echo ConfigFB::$db_connection->isError;
        }
        //Liberamos la informacion de la Query
        unset($rs_Data);
        //Cerramos la conexion con la base de datos
        ConfigFB::$db_connection->closeFb();
        //var_dump($result); //Se imprime $result.
        return $result; //Retornamos el resultado del proceso, sea cual sera la respuesta.
    }
}