<?php

require_once 'vendor/autoload.php';

$app = new \Slim\Slim();

$db=new mysqli('localhost','root','','curso_angular4');

$app->get("/pruebas", function() use($app,$db){
    echo "hola mundo desde Slim php";
    
});
$app->get("/probando", function() use($app){
    echo "texto cualquiera";
});

// LISTAR TODOS LOS PRODUCTOS
$app -> get('/productos', function() use($db,$app){
    $sql = 'SELECT * FROM productos ORDER BY id DESC;';
    $query = $db->query($sql);
    $productos = array();

    while($producto = $query->fetch_assoc()){
        $productos[]=$producto;
    }
    $result = array(
        'status' => 'success',
        'code' => 200,
        'data' => $productos  
    );
    echo json_encode($result);
});


// DEVOLVER UN SOLO PRODUCTO
$app->get('/producto/:id', function($id) use($db,$app){
    $sql='SELECT * FROM productos where id='.$id;
    $query = $db->query($sql);
    
    $result = array(
        'status'=>'error',
        'code' => 404,
        'message' => 'producto no disponible'
    );

    if($query->num_rows == 1){
        $producto = $query->fetch_assoc();
        $result = array(
            'status'=>'success',
            'code' => 200,
            'message' => $producto
        );
    }
    echo json_encode($result);
});

// ELIMINA UN PRODUCTO

// ACTUALIZAR UN PRODUCTO

// SUBIR UNA IMAGEN A UN PRODUCTO


// GUARDAR PRODUCTOS
$app -> post('/productos', function() use($app, $db){
    $json = $app->request->post('json');
    $data = json_decode($json, true);
    
    if(!isset($data['nombre'])){
        $data['nombre']=null;
    }
    if(!isset($data['descripcion'])){
        $data['descripcion']=null;
    }
    if(!isset($data['precio'])){
        $data['precio']=null;
    }
    if(!isset($data['imagen'])){
        $data['imagen']=null;
    }

    $query = "INSERT INTO productos VALUES(NULL,".
    "'{$data['nombre']}',".
    "'{$data['descripcion']}',".
    "'{$data['precio']}',".
    "'{$data['imagen']}'".
    ");";

    $insert = $db->query($query);
    
    $result=array(
        'status' => 'error',
        'code' => 404,
        'message' => 'Producto NO se ha creado'  
    );

    if($insert){
        $result=array(
            'status' => 'success',
            'code' => 200,
            'message' => 'Producto creado correctamente'  
        );
    }

    echo json_encode($result);
});

$app->run();
?>