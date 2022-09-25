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
    $statement=$mbd->prepare("INSERT INTO tabla_1 (descripcion, campo1, campo2) VALUES (:de, :c1, :c2)");

    // Asociamos los parametros de la consulta a las variables de los datos
    $statement->bindParam(':de', $descripcion);
    $statement->bindParam(':c1', $campo1);
    $statement->bindParam(':c2', $campo2);

    // Asignamos los datos de las variables
    $descripcion = $_POST['descripcion'];
    $campo1      = $_POST['campo1'];
    $campo2      = $_POST['campo2'];

    // Insertar
    if ($statement->execute()){
        // obtener id
        $statement=$mbd->prepare("SELECT LAST_INSERT_ID()");
        $statement->execute();

        $salida = [
            "id" => $statement->fetch()[0],
            'descripcion' => $_POST['descripcion'],
            'campo1' => $_POST['campo1'],
            'campo2' => $_POST['campo2'],
        ];
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

