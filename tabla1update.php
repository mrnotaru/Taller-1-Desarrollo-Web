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
    if (count($_POST) < 2) {
        echo json_encode(["mensaje" => "Error, faltan argumentos"]);
        return;
    }

    $id = $_POST['id'];
    $qry = "SET @id = ?; UPDATE tabla_1 SET "; 
    foreach (array_slice($_POST, 1) as $field => $value)
    {
        $qry .= $field . " = ?, ";
    }
    $qry = substr($qry, 0, -2) . " WHERE id = @id;";
    // Asignamos los datos de las variables
    $id = $_POST['id'];
    
    $statement=$mbd->prepare("SELECT * FROM tabla_1 WHERE id = :id");
    $statement->bindParam(':id', $id);
    $statement->execute();

    $registro = $statement->fetch(PDO::FETCH_ASSOC);
    
    // Creamos una sentencia preparada
    $statement=$mbd->prepare($qry);

    // Actualizar
    $statement->execute(array_values($_POST));

    $salida = [
        "mensaje" => "Registro actualizado satisfactoriamente",
        "data" => $registro,
    ];
    
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

