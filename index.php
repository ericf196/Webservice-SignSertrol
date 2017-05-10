<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require 'vendor/autoload.php';
require 'db_config.php';
//echo getcwd();


$app = new \Slim\App;
/*$app->get('/hola/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("hola, $name");

    return $response;
});*/

$app->get('/empresas[/{codigoEmpresa:.*}]',  function (Request $request, Response $response, $args) { // parametros opcionales
					
	//echo $ticket_id = (string)$args['nombreEmpresa'];
	$db_host = "localhost";
	$db_user = "root";
	$db_pass = "";
	$db_name = "signsertrol";

    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

    $sql= "SELECT * FROM empresa";
    //$sql= "SELECT * FROM `empresa` WHERE idEmpresa='5'";


    $result = $mysqli->query($sql);
    
    if($result->num_rows!=0){

	    while($row = $result->fetch_assoc()){
	    	  $json[]= $row;
	    	  //print_r($json);
	  	}

   	}else{
   		$json[] = array( "Mensaje"  => "Valor no encontrado en la base de datos");
   	}


   	$data['data'] = $json;
    
    echo json_encode($data);
});


$app->get('/proyectos[/{codigoProyecto:.*}]',  function (Request $request, Response $response, $args) { // parametros opcionales
					
	//echo (string)$args['codigoProyecto'];
	
	if ($args) {
		$variable= (string)$args['codigoProyecto'];
	}else
		$variable=NULL;
	
	$db_host = "localhost";
	$db_user = "root";
	$db_pass = "";
	$db_name = "signsertrol";

 	$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);


	if($variable){
		$sql= "SELECT * FROM proyecto WHERE CodigoProyecto='". $variable . "'";
	}else{
		$sql= "SELECT * FROM proyecto";
	}

    $result = $mysqli->query($sql);
    
    if($result->num_rows!=0){

	    while($row = $result->fetch_assoc()){
	    	  $json[]= $row;
	    	  //print_r($json);
	  	}

   	}else{
   		$json[] = array( "Mensaje"  => "Valor no encontrado en la base de datos");
   	}


   	$data['data'] = $json;
    
    echo json_encode($data);
});




$app->post('/login/{nombreUser}/{passwordUser}',  function (Request $request, Response $response , $args) { 
		
	$db_host = "localhost";
	$db_user = "root";
	$db_pass = "";
	$db_name = "signsertrol";

    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($args) {
		$variableNombre= (string)$args['nombreUser'];
		$variablePassword= (string)$args['passwordUser'];
	}

    $sql= "SELECT * FROM user WHERE NombreUser= '". $variableNombre ."' AND  PasswordUser= '". $variablePassword ."'";
    //$sql= "SELECT * FROM `empresa` WHERE idEmpresa='5'";

    $result = $mysqli->query($sql);
    
    if($result->num_rows!=0){

	    while($row = $result->fetch_assoc()){
	    	//$row['Examenes']= $row;
	    	$json[]= $row;
			
	    	 //print_r($row['NombreUser']);
	    	  
	  	}

   	}else{
   		$json[] = array( "Mensaje"  => "El usuario no existe o introdujo mal sus datos");
   	}
		

   	$data['data'] = $json;
   	
    echo json_encode($data);
});


$app->get('/proyectouser/{nombreUser}',  function (Request $request, Response $response, $args) { 
		
	$db_host = "localhost";
	$db_user = "root";
	$db_pass = "";
	$db_name = "signsertrol";

    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($args) {
		$variableNombre= (string)$args['nombreUser'];
	}


    $sql= "SELECT CodigoProyecto, NombreProyecto, DescripcionProyecto, NombreEmpresa FROM (((userxproyecto UXP INNER JOIN proyecto P ON UXP.IdProyecto = P.IdProyecto) INNER JOIN user U ON U.IdUser = UXP.IdUser) INNER JOIN empresa EMP ON EMP.IdEmpresa = P.IdEmpresa) WHERE UXP.IdUser=(SELECT IdUser FROM user WHERE NombreUser='". $variableNombre ."')";
    //$sql= "SELECT * FROM `empresa` WHERE idEmpresa='5'";

    $result = $mysqli->query($sql);
    
    if($result->num_rows!=0){

	    while($row = $result->fetch_assoc()){
	    	//$row['Examenes']= $row;
	    	$json[]= $row;
			
	    	 //print_r($row['NombreUser']);
	    	  
	  	}

   	}else{
   		$json[] = array( "Mensaje"  => "El usuario no existe o no tiene proyectos asociados");
   	}
		
   	$data['data'] = $json;
   	
    echo json_encode($data);
});


$app->run();
