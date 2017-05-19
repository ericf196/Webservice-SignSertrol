<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require 'vendor/autoload.php';
require 'db_config.php'; // no funciona
//echo getcwd();


$app = new \Slim\App;



$app->get('/empresas[/{codigoEmpresa:.*}]',  function (Request $request, Response $response, $args) { // busca por codigo de la empresa y sin codigo
					
	$db_host = "localhost";
	$db_user = "root";
	$db_pass = "";
	$db_name = "signsertrol";

    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

	//$codigoEmpresa=$request->getParam('codigoEmpresa');
	

	if($args){		
		$sql= "SELECT * FROM empresa WHERE CodigoEmpresa= '". $args['codigoEmpresa'] ."' ";
	}else{
		$sql= "SELECT * FROM empresa";
	}
    

    $result = $mysqli->query($sql);
    
    if($result->num_rows!=0){

	    while($row = $result->fetch_assoc()){
	    	  $json[]= $row;	    	
	  	}

   	}else{
   		$json = array( "Mensaje"  => "Valor no encontrado en la base de datos");
   	}

   	$data['empresas'] = $json;
    
    echo json_encode($data);
});


$app->get('/proyectos[/{codigoProyecto:.*}]',  function (Request $request, Response $response, $args) { // busca por codigo de proyectos
					
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




$app->post('/login/{nombreUser}/{passwordUser}',  function (Request $request, Response $response , $args) { // login de el usuario se trae los proyectos relacionados con ese usuario    FALTA VALIDAR LOS CAMPOS
		
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

    $sql1= "SELECT CodigoProyecto, NombreProyecto, DescripcionProyecto, NombreEmpresa FROM (((userxproyecto UXP INNER JOIN proyecto P ON UXP.IdProyecto = P.IdProyecto) INNER JOIN user U ON U.IdUser = UXP.IdUser) INNER JOIN empresa EMP ON EMP.IdEmpresa = P.IdEmpresa) WHERE UXP.IdUser=(SELECT IdUser FROM user WHERE NombreUser='". $variableNombre ."')";


    $result = $mysqli->query($sql); 
	if ($result->num_rows!=0) {
		
	    //$json_response="";  //Create an array
	    while ($row = $result->fetch_assoc()){
	        $row_array = array();
	        	     
	        $row_array['NombreUser'] = $row['NombreUser'];
	        $row_array['PasswordUser'] = $row['PasswordUser'];
	        
	        $row_array['proyectos'] = array();
	         
	        $result1 = $mysqli->query($sql1); 

	        $numero_filas =  mysqli_num_rows($result1);

	    	if($numero_filas>0){
		        while ($proyectos_user = $result1->fetch_assoc()){
		            $row_array['proyectos'][] = array(
		                'CodigoProyecto' => $proyectos_user['CodigoProyecto'],
		                'NombreProyecto' => $proyectos_user['NombreProyecto'],
		                'DescripcionProyecto' => $proyectos_user['DescripcionProyecto'],
		                'NombreEmpresa' => $proyectos_user['NombreEmpresa']
		            );

		        }
	        }else{
	        		$row_array['proyectos'][] = array(
		                'Mensaje' => 'Este Usuario no tiene proyectos asociados'
		            );
			}

	        //$json =array_push($json_response, $row_array); //push the values in the array
	    }

	}else{
			$json_response = array('Mensaje' => 'Se ha equivocado en el usuario o el password');
	}

    //$data['data'] = $json_response;
    
    echo json_encode($row_array);
    
});



$app->post('/loginprueba/{nombreUser}/{passwordUser}',  function (Request $request, Response $response , $args) { // login de el usuario se trae los proyectos relacionados con ese usuario    FALTA VALIDAR LOS CAMPOS
		
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

    $sql1= "SELECT CodigoProyecto, NombreProyecto, DescripcionProyecto, NombreEmpresa FROM (((userxproyecto UXP INNER JOIN proyecto P ON UXP.IdProyecto = P.IdProyecto) INNER JOIN user U ON U.IdUser = UXP.IdUser) INNER JOIN empresa EMP ON EMP.IdEmpresa = P.IdEmpresa) WHERE UXP.IdUser=(SELECT IdUser FROM user WHERE NombreUser='". $variableNombre ."')";


    $result = $mysqli->query($sql); 
	if ($result->num_rows!=0) {
			  
	    while ($row = $result->fetch_assoc()){
	         
	        $result1 = $mysqli->query($sql1); 

	        $numero_filas =  mysqli_num_rows($result1);

	    	if($numero_filas>0){
	    		$row_array['encontrado']= 1;
	    		$row_array['mensaje']= "El usuario tiene proyectos asociados";
		        while ($proyectos_user = $result1->fetch_assoc()){
		            $row_array['proyectos'][] = array(
		            	
		                'CodigoProyecto' => $proyectos_user['CodigoProyecto'],
		                'NombreProyecto' => $proyectos_user['NombreProyecto'],
		                'DescripcionProyecto' => $proyectos_user['DescripcionProyecto'],
		                'NombreEmpresa' => $proyectos_user['NombreEmpresa']
		            );

		        }
	        }else{
	        		$row_array['encontrado']= 1;
	        		$row_array['mensaje']= "El usuario no tiene proyectos asociados";
	        		$row_array['proyectos'][] = array(
	        			'CodigoProyecto' => null,
		                'NombreProyecto' => null,
		                'DescripcionProyecto' => null,
		                'NombreEmpresa' => null	          
		            );
			}
	       
	    }

	}else{
			$row_array['encontrado']= 0;
			$row_array['mensaje']= "El usuario no existe o su password es incorrecto";
			$row_array['proyectos'][] = array(
	        			'CodigoProyecto' => null,
		                'NombreProyecto' => null,
		                'DescripcionProyecto' => null,
		                'NombreEmpresa' => null	              
		            );;
	}

    echo json_encode($row_array);
    
});






$app->get('/proyectouser/{nombreUser}',  function (Request $request, Response $response, $args) { // proyectos relacionados al usuario
		
	$db_host = "localhost";
	$db_user = "root";
	$db_pass = "";
	$db_name = "signsertrol";

    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($args) {
		$variableNombre= (string)$args['nombreUser'];
	}


    $sql= "SELECT CodigoProyecto, NombreProyecto, DescripcionProyecto, NombreEmpresa FROM (((userxproyecto UXP INNER JOIN proyecto P ON UXP.IdProyecto = P.IdProyecto) INNER JOIN user U ON U.IdUser = UXP.IdUser) INNER JOIN empresa EMP ON EMP.IdEmpresa = P.IdEmpresa) WHERE UXP.IdUser=(SELECT IdUser FROM user WHERE NombreUser='". $variableNombre ."')";

    $result = $mysqli->query($sql);
    
    if($result->num_rows!=0){

	    while($row = $result->fetch_assoc()){	

	    	 $numero_filas =  mysqli_num_rows($result);

	    	if($numero_filas>0){
	    		$row_array['encontrado']= 1;
	    		$row_array['mensaje']= "El usuario tiene proyectos asociados";
		        while ($proyectos_user = $result->fetch_assoc()){
		            $row_array['proyectos'][] = array(		           
		                'CodigoProyecto' => $proyectos_user['CodigoProyecto'],
		                'NombreProyecto' => $proyectos_user['NombreProyecto'],
		                'DescripcionProyecto' => $proyectos_user['DescripcionProyecto'],
		                'NombreEmpresa' => $proyectos_user['NombreEmpresa']
		            );

		        }
	        }    		    	 
	  	}

   	}else{
   						$row_array['encontrado']= 1;
	        			$row_array['mensaje']= "El usuario no tiene proyectos asociados";
	        			$row_array['proyectos'][] = array(
	        			'CodigoProyecto' => null,
		                'NombreProyecto' => null,
		                'DescripcionProyecto' => null,
		                'NombreEmpresa' => null	          
		            );
			}
   
   
    echo json_encode($row_array);
});



$app->post('/insertarempresas',  function (Request $request, Response $response) {	//insertar empresas		
	//echo (string)$args['codigoProyecto'];
	
	$db_host = "localhost";
	$db_user = "root";
	$db_pass = "";
	$db_name = "signsertrol";

 	$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

 	$nombreEmpresa=$request->getParam('NombreEmpresa');
 	$telefonoEmpresa=$request->getParam('TelefonoEmpresa');
 	$direccionEmpresa=$request->getParam('DireccionEmpresa');


 	$sql="INSERT INTO empresa(CodigoEmpresa, NombreEmpresa, TelefonoEmpresa, DireccionEmpresa) VALUES ('". generarCodigo($variable="empresa") ."' , '". $nombreEmpresa ." ','". $telefonoEmpresa ."','". $direccionEmpresa ."')";

 	if($nombreEmpresa && $telefonoEmpresa && $direccionEmpresa){

			$result = $mysqli->query($sql);
			
		    
		    if($result){
		    	$json = array( "Mensaje"  => "Se inserto la empresa " . generarCodigo($variable="empresa"));
		   	}else{
		   		$json = array( "Mensaje"  => "No se ha insertado la empresa");
		   	}

	}else{
		$json = array( "Mensaje"  => "Faltan campos en la peticion");
	}
		
   	$data['data'] = $json;
   	
    echo json_encode($json);

});



$app->post('/insertarproyectos[/{user:.*}]',  function (Request $request, Response $response, $args) {	//Insertar proyectos relacionarlos con un usuario	
	
	$db_host = "localhost";
	$db_user = "root";
	$db_pass = "";
	$db_name = "signsertrol";

 	$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

 	$nombreProyecto=$request->getParam('NombreProyecto');
 	$descripcionProyecto=$request->getParam('DescripcionProyecto');
 	$fechaCreacion=$request->getParam('FechaCreacion');
 	$nombreEmpresa=$request->getParam('NombreEmpresa');

 	$sql_empresa="SELECT IdEmpresa FROM empresa WHERE NombreEmpresa= '". $nombreEmpresa ."' ";

	$variable_user= (string)$args['user'];
	$sql_id_user="SELECT IdUser FROM user WHERE NombreUser= '". $variable_user ."' "; //valida si el user que viene por parametro existe
	$result_id_user = $mysqli->query($sql_id_user);
	$id_user[]= $result_id_user->fetch_assoc();
	$userValido = ($result_id_user->num_rows!=0) ? TRUE : FALSE;

 	if($nombreProyecto && $descripcionProyecto && $fechaCreacion && $nombreEmpresa && $userValido){
 		$result_empresa = $mysqli->query($sql_empresa);
		
 		if($result_empresa->num_rows!=0){
				$idEmpresa[]= $result_empresa->fetch_assoc();

				//GENERARCODIGO SIN PROBAR

	 			$sql="INSERT INTO proyecto(CodigoProyecto ,NombreProyecto, DescripcionProyecto, FechaCreacion, IdEmpresa) VALUES ('". generarCodigo($variable="proyecto") ."','". $nombreProyecto ." ','". $descripcionProyecto ."','2017-05-06','". $idEmpresa[0]['IdEmpresa'] ."')";
	 																//. $fechaCreacion .
				$result = $mysqli->query($sql);

				if ($args && $result){

					$variable_user= (string)$args['user'];
					$sql_id_proyecto_max="SELECT Max(IdProyecto) AS IdProyecto FROM proyecto";
					//$sql_id_user="SELECT IdUser FROM user WHERE NombreUser= '". $variable_user ."' ";

					$result_id_proyecto_max = $mysqli->query($sql_id_proyecto_max);
					//$result_id_user = $mysqli->query($sql_id_user);

					$id_proyecto_max[]= $result_id_proyecto_max->fetch_assoc();
					//$id_user[]= $result_id_user->fetch_assoc();

					$sql_user="INSERT INTO userxproyecto(IdProyecto, IdUser) VALUES ('". $id_proyecto_max[0]['IdProyecto'] ." ','". $id_user[0]['IdUser'] ."')";

					$result_user = $mysqli->query($sql_user);
		    
				    if($result_user){
				    	$json = array( "Mensaje"  => "Se inserto el proyecto con un usuario relacionado");
				   	}else{
				   		$json = array( "Mensaje"  => "No inserto el proyecto con un usuario relacionado");
				   	}

				}else{

					if($result){
			    		$json = array( "Mensaje"  => "Se inserto el proyecto correctamente");
				   	}else{
				   		$json = array( "Mensaje"  => "No se ha insertado el proyecto"  );
				   	}

				}
			    
		}else{
			$json = array( "Mensaje"  => "La empresa no se ha encontrado");
		}

	}else{
		$json = array( "Mensaje"  => "Faltan campos en la peticion o el usuario por parametro no exite");
	}
		
   	//$data['data'] = $json;
   	
    echo json_encode($json);


});


function generarCodigo($favcolor) {

	$db_host = "localhost";
	$db_user = "root";
	$db_pass = "";
	$db_name = "signsertrol";

 	$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

switch ($favcolor) {
    case "empresa":

    	$sql="SELECT Max(IdEmpresa) AS IdEmpresa FROM empresa";    
    	$result = $mysqli->query($sql);
    	$id_empresa_max[]= $result->fetch_assoc();

        return "EMP-0".($id_empresa_max[0]['IdEmpresa']+1);
        break;
    case "proyecto":

    	$sql="SELECT Max(IdProyecto) AS IdProyecto FROM proyecto";    
    	$result = $mysqli->query($sql);
    	$id_proyecto_max[]= $result->fetch_assoc();

        return "PRO-0".($id_proyecto_max[0]['IdProyecto']+1);
        
        break;   
    default:
        return "";
}
}


$app->run();
