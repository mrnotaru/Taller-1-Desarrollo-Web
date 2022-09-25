<?php

/**
 * Iniciar servidor de pruebas
 * php -S localhost:8000
 */
 
// Content-type=application/x-www-form-urlencoded
// Content-type=application/multipart/form-data

// Conexión a la base de datos
try {
    $mbd = new PDO('mysql:host=localhost;dbname=taller1', 'root', '');
} catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
}


// Ejecución de la consulta
try 
{    
    // Creamos una sentencia preparada
    $statement=$mbd->prepare("INSERT INTO tabla_2 (fk_id, campo_varchar_1, campo_varchar_2, campo_datetime, campo_date, campo_int, campo_float, campo_email) VALUES (:fk, :vc1, :vc2, :dtm, :dt, :it, :flt, :mail)");

    // Asociamos los parametros de la consulta a las variables de los datos
    $statement->bindParam(':fk',   $fk_id);
    $statement->bindParam(':vc1',  $campo_varchar_1);
    $statement->bindParam(':vc2',  $campo_varchar_2);
    $statement->bindParam(':dtm',  $campo_datetime);
    $statement->bindParam(':dt',   $campo_date);
    $statement->bindParam(':it',   $campo_int);
    $statement->bindParam(':flt',  $campo_float);
    $statement->bindParam(':mail', $campo_email);

    // Asignamos los datos de las variables
    $fk_id           = $_POST['fk_id'];
    $campo_varchar_1 = $_POST['campo_varchar_1'];
    $campo_varchar_2 = $_POST['campo_varchar_2'];
    $campo_datetime  = $_POST['campo_datetime'];
    $campo_date      = $_POST['campo_date'];
    $campo_int       = $_POST['campo_int'];
    $campo_float     = $_POST['campo_float'];
    $campo_email     = $_POST['campo_email'];

    // Insertar
    if ($statement->execute()){
        // obtener id
        $statement=$mbd->prepare("SELECT * FROM tabla_2 WHERE id = LAST_INSERT_ID()");
        $statement->execute();
        $salida = $statement->fetch(PDO::FETCH_ASSOC);

        $statement=$mbd->prepare("SELECT * FROM tabla_1 WHERE id = :fk");
        $statement->bindParam(':fk', $fk_id);
        $statement->execute();
        $salida += array("data_fk" => $statement->fetch(PDO::FETCH_ASSOC)); 
    } else {
        $salida = [ "error" => "Error inesperado"];
    }
    
    // Retornamos resultados
    header('Content-type:application/json;charset=utf-8');    
    echo json_encode($salida);

} catch (PDOException $e) {
    header('Content-type:application/json;charset=utf-8');    
    echo json_encode([
        'error' => [
            'codigo' =>$e->getCode() ,
            'mensaje' => $e->getMessage()
        ]
    ]);
}


?>

