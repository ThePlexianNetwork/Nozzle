<?php
	// Configuration:
	$config = array();
	$config["mysql_host"] = "";
	$config["mysql_user"] = "";
	$config["mysql_pass"] = "";
	
	// Errors:
	$error = array();
	$error["missing_state"] = '{"result": "error", "error": {"id": "0", "message": "Missing state parameter"}}';
	$error["misformatted_state"] = '{"result": "error", "error": {"id": "1", "message": "Misformatted state"}}';
	$error["missing_input"] = '{"result": "error", "error": {"id": "2", "message": "Missing input in state"}}';
	$error["bad_database"] = '{"result": "error", "error": {"id": "3", "message": "Internal database error"}}';
	$error["invalid_application"] = '{"result": "error", "error": {"id": "4", "message": "Invalid application. Make sure your ID and secret token is correct and that your application is enabled"}}';
	$error["key_exists"] = '{"result": "error", "error": {"id": "5", "message": "Product key already exists"}}';
	$error["misformatted_result"] = '{"result": "error", "error": {"id": "6", "message": "Misformatted result"}}';
	$error["key_doesnt_exist"] = '{"result": "error", "error": {"id": "7", "message": "Key doesn\'t exist!"}}';
	
	$raw_state = $_GET["state"];

	// If we are missing our API state than exit early with an error response.
	if(!isset($raw_state)){
		echo $error_missing_state;
		exit;
	}

	// Convert the JSON state to a PHP array
	$state = json_decode($raw_state);
	
	// If the inputed state was somehow misformated
	if($state == null){
		echo $error["misformated_state"];
		exit;
	}
	
	$mysqli = new mysqli($config["mysql_host"], $config["mysql_user"], $config["mysql_pass"], "distribution_api");

	switch($state["request"]){
		// If the request was to validate a product key...
		case "validate_product_key":
			if(!isset($state["input"]) ||
			   !isset($state["input"]["key"]) ||
			   !isset($state["input"]["application"])){
				echo $error["missing_input"];
				exit;
			}else{
				$uuid = mysqli_real_escape_string($state["input"]["key"]);
				$application_id = mysqli_real_escape_string($state["input"]["application"]);
				$sql = "SELECT valid FROM product_keys where uuid='" . $uuid . "' AND application='" . $application_id . "';";
				$result = mysqli_fetch_assoc(mysqli_query($mysqli, $sql));
				
				if(!$result){
					echo $error["bad_database"];
					exit;
				}
				
				if($result["valid"] == "true"){
					echo '{"result": "true"}';
					exit;
				}else{
					echo '{"result": "false"}';
					exit;
				}
			}
		
		// If the request was to insert a new key into the database...
		case "add_application_key":
			if(!isset($state["input"]) ||
			   !isset($state["input"]["application"]) ||
			   !isset($state["input"]["application_authorization"]) ||
			   !isset($state["input"]["new_key"])){
				echo $error["missing_input"];
				exit;
			}else{
			  $application_id = mysqli_real_escape_string($state["input"]["application"]);
			  $application_secret = mysqli_real_escape_string($state["input"]["application_authorization"]);
			  $key = mysqli_real_escape_string($state["input"]["new_key"]);
			  
				// We first need to check if application exists
				$sql = "SELECT * FROM applications WHERE id='" . $application_id . "' AND secret='" . $application_secret . "' AND enabled='true';";
	
				$result = mysqli_fetch_assoc(mysqli_query($mysqli, $sql));
				
				if(!$result){
					echo $error["invalid_application"];
					exit;
				}
				
				if($result["enabled"] == "true"){
					$sql = "SELECT * FROM product_keys WHERE id='" . $key . "' AND application='" . $application_id . "';";
					$result = mysqli_fetch_assoc(mysqli_query($mysqli, $sql));
					
					if(mysqli_num_rows($result) > 0){
						echo $error["key_exists"];
						exit;
					}else{
						$sql = "INSERT INTO product_keys(id, application, valid) VALUES('" . $key . "', '" . $application_id . "', 'true');";
						$result = mysqli_query($sql);
						
						if(!$result){
							echo $error["bad_database"];
							exit;
						}else{
							echo '{"result": "true"}';
						}
					}
				}else{
					echo $error["invalid_application"];
					exit;
				}
			}
		case "remove_product_key":
		  if(!isset($state["input"]) ||
		     !isset($state["input"]["application"]) ||
		     !isset($state["input"]["application_authorization"]) ||
		     !isset($state["input"]["key"])){
        echo $error["missing_input"];
        exit;
	    }else{
	      $application_id = mysqli_real_escape_string($state["input"]["application"]);
	      $application_secret = mysqli_real_escape_string($state["input"]["application_authorization"]);
	      $key = mysqli_real_escape_string($state["input"]["key"]);
	      
	      // We first need to check if application exists
				$sql = "SELECT * FROM applications WHERE id='" . $application_id . "' AND secret='" . $application_secret . "' AND enabled='true';";
				$result = mysqli_fetch_assoc(mysqli_query($mysqli, $sql));
				
				if(!$result){
					echo $error["invalid_application"];
					exit;
				}
				
				if($result["enabled"] == true){
					$sql = "SELECT * FROM product_keys WHERE id='" . $key . "' AND application='" . $application_id . "';";
					$result = mysqli_fetch_assoc(mysqli_query($mysqli, $sql));
					
					if(mysqli_num_rows($result) <= 0){
						echo $error["key_doesnt_exist"];
						exit;
					}else{
						$sql = "DELETE FROM product_keys WHERE id='" . $key . "' AND application='" . $application_id . "';";
						
						$result = mysqli_query($mysqli, $sql);
						
						if(!$result){
							echo $error["bad_database"];
							exit;
						}else{
							echo '{"result": "true"}';
						}
					}
				}else{
					echo $error["invalid_application"];
					exit;
				}
	    }
	 case "disable_product_key":
      if(!isset($state["input"]) ||
		     !isset($state["input"]["application"]) ||
		     !isset($state["input"]["application_authorization"]) ||
		     !isset($state["input"]["key"])){
        echo $error["missing_input"];
		    exit;
		  }else{
		    $application_id = mysqli_real_escape_string($state["input"]["application"]);
		    $application_secret = mysqli_real_escape_string($state["input"]["application_authorization"]);
		    $key = mysqli_real_escape_string($state["input"]["key"]);
		    
		    // We first need to check if application exists
				$sql = "SELECT * FROM applications WHERE id='" . $application_id . "' AND secret='" . $application_secret . "' AND enabled='true';";
				$result = mysqli_fetch_assoc(mysqli_query($mysqli, $sql));
				
				if(!$result){
					echo $error["invalid_application"];
					exit;
				}
				
				if($result["enabled"] == "true"){
				  $sql = "SELECT * FROM producy_keys WHERE id='" . key . "' AND application='" . $application_id . "';";
				  $result = mysqli_query($mysqli, $sql);
				  
				  if(mysqli_num_rows($result) <= 0){
				    echo $error["key_doesnt_exist"];
				    exit;
				  }else{
				    $sql = "UPDATE product_keys SET valid='false' WHERE id='" . $key . "';";
				    $result = mysqli_query($mysqli, $sql);
				    
				    if(!$result){
				      echo $error["bad_database"];
				      exit;
				    }else{
				      echo '{"result": "true"}';
				      exit;
				    }
				  }
				}
		  }
		default:
		  echo $error["misformatted_state"];
		  exit;
	}
?>