<?php
	// Configuration:
	$config = array();
	$config["mysql_host"] = "";
	$config["mysql_user"] = "";
	$config["mysql_pass"] = "";
	
	// Errors:
	$error = array();
	$error["missing_state"] = '{"result": "false", "error": {"id": "0", "message": "Missing state parameter"}}';
	$error["misformatted_state"] = '{"result": "false", "error": {"id": "1", "message": "Misformatted state"}}';
	$error["missing_input"] = '{"result": "false", "error": {"id": "2", "message": "Missing input in state"}}';
	$error["bad_database"] = '{"result": "false", "error": {"id": "3", "message": "Internal database error"}}';
	$error["invalid_application"] = '{"result": "false", "error": {"id": "4", "message": "Invalid application. Make sure your ID and secret token is correct"}}';
	$error["key_exists"] = '{"result": "false", "error": {"id": "5", "message": "Product key already exists"}}';
	
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
			if(!isset($state["input"]) || $state["input"] == null){
				echo $error["missing_input"];
				exit;
			}else{
				$uuid = mysqli_real_escape_string($state["input"]);
				$sql = "SELECT valid FROM product_keys where uuid='" . $uuid . "';";
				$result = mysqli_fetch_assoc(mysqli_query($mysqli, $sql));
				
				if(!$result){
					echo $error["bad_database"];
					exit;
				}
				
				if($result["valid"] == true){
					echo '{"result": "true"}';
					exit;
				}else{
					echo '{"result": "false"}';
					exit;
				}
			}
		
		// If the request was to insert a new key into the database...
		case "add_application_key":
			if(!isset($state["input"]) || !isset($state["input"]["application"]) || !isset($state["input"]["application_authorization"]) || !isset($state["input"]["new_key"])){
				echo $error["missing_input"];
				exit;
			}else{
				// We first need to check if application exists
				$sql = "SELECT * FROM applications WHERE id='" . mysqli_real_escape_string($state["input"]["application"]) . "' AND secret='" . mysqli_real_escape_string($state["input"]["application_authorization"]) . "';";
				$result = mysqli_fetch_assoc(mysqli_query($mysqli, $sql));
				
				if(!$result){
					echo $error["bad_database"];
				}
				
				if($result["valid"] == true){
					$sql = "SELECT * FROM product_keys WHERE id='" . mysqli_real_escape_string($state["input"]["new_key"]) . "' AND application='" . mysqli_real_escape_string($state["input"]["application"]) . "';";
					$result = mysqli_fetch_assoc(mysqli_query($mysqli, $sql));
					
					if(mysqli_num_rows($result) > 0){
						echo $error["key_exists"];
						exit;
					}else{
						$sql = "INSERT INTO product_keys(id, application, valid) VALUES('" . mysqli_real_escape_string($state["input"]["new_key"]) . "', '" . mysqli_real_escape_string($state["input"]["application"]) . "', true);";
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
	}
?>