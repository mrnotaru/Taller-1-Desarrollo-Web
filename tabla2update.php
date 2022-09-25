<?php
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
        $qry = "SET @id = ?; UPDATE tabla_2 SET "; 
        foreach (array_slice($_POST, 1) as $field => $value)
        {
            $qry .= $field . " = ?, ";
        }
        $qry = substr($qry, 0, -2) . " WHERE id = @id;";
        
        $statement=$mbd->prepare("SELECT * FROM tabla_2 WHERE id = :id");
        $statement->bindParam(':id', $id);
        $statement->execute();
    
        $registro = $statement->fetch(PDO::FETCH_ASSOC);

        $statement=$mbd->prepare("SELECT * FROM tabla_1 WHERE id = :fk");
        $statement->bindParam(':fk', $registro["fk_id"]);
        $statement->execute();

        $fk = $statement->fetch(PDO::FETCH_ASSOC);
        
        // Creamos una sentencia preparada
        $statement=$mbd->prepare($qry);
        
        // Asociamos los parametros de la consulta a las variables de los datos
        // $statement->bindParam(count($_POST)-1, $id);
    
        // Actualizar
        $params = array_values(array_slice($_POST, 1));
        $params[] = $id;
        $statement->execute(array_values($_POST));
    
        $salida = [
            "mensaje" => "Registro actualizado satisfactoriamente",
            "data" => $registro + array("data_fk" => $fk),
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