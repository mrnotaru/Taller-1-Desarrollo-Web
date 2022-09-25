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
    
    // Asignamos los datos de las variables
    $id = $_GET['id'];
    
    $statement=$mbd->prepare("SELECT * FROM tabla_1 WHERE id = :id");
    $statement->bindParam(':id', $id);
    $statement->execute();

    $registro = $statement->fetch(PDO::FETCH_ASSOC);
    
    // Retornamos resultados
    header('Content-type:application/json;charset=utf-8');    
    echo json_encode($registro);

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

