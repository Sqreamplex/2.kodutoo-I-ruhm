<?php
	
	//functions.php
	require("../../config.php");
	//alustan sessiooni, et saaks kasutada
	//$_SESSSION muutujaid
	session_start();
	
	//********************
	//****** SIGNUP ******
	//********************
	//$name = "romil";
	//var_dump($GLOBALS);
	
	$database = "if16_mikuz_1";
	
	function signup ($email, $password, $gender, $name) {
		
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);

		$stmt = $mysqli->prepare("INSERT INTO user_sample (email, password, gender, name) VALUES (?, ?, ?, ?)");
		echo $mysqli->error;

		$stmt->bind_param("ssss", $email, $password, $gender, $name);
		
		if ($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
			echo "ERROR ".$stmt->error;
		}
		
	}
	
	
	function login ($email, $password) {
		
		$error = "";
		
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);

		$stmt = $mysqli->prepare("
			SELECT id, email, password, created 
			FROM user_sample
			WHERE email = ?
		");
		echo $mysqli->error;
		
		//asendan küsimärgi
		$stmt->bind_param("s", $email);
		
		//määran tulpadele muutujad
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $created);
		$stmt->execute();
		
		//küsin rea andmeid
		if($stmt->fetch()) {
			//oli rida
			// võrdlen paroole
			$hash = hash("sha512", $password);
			if($hash == $passwordFromDb) {
				echo "kasutaja ".$id." logis sisse";
				$_SESSION["userId"] = $id;
				$_SESSION["email"] = $emailFromDb;
				//suunaks uuele lehele
				header("Location: data.php");
				exit();
			} else {
				$error = "parool vale";
			}
		} else {
			//ei olnud 
			
			$error = "sellise emailiga ".$email." kasutajat ei olnud";
		}
		return $error;	
	}
	
	
	function bdayandtel ($sunnipaev, $telefon) {
		
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);

		$stmt = $mysqli->prepare("INSERT INTO bdayandtel (sunnipaev, telefon) VALUES (?, ?)");
		echo $mysqli->error;

		$stmt->bind_param("ss", $sunnipaev, $telefon);
		
		if ($stmt->execute()) {
			echo "Salvestamine Onnestus";
		} else {
			echo "ERROR ".$stmt->error;
		}
		
	}
	
	function dataAll () {
	
		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);

		$stmt = $mysqli->prepare("
			SELECT id, sunnipaev, telefon, loodud
			FROM bdayandtel
		");
		echo $mysqli->error;
		
		$stmt->bind_result($id, $sunnipaev, $telefon, $loodud );
		$stmt->execute();
		
		$result = array();
		
		//seni kuni on uks rida andmeid saada (10 rida = 10 korda)
		while($stmt->fetch()){
			
			$person = new StdClass();
			$person->id = $id;
			$person->sunnipaev = $sunnipaev;
			$person->telefon = $telefon;
			$person->loodud = $loodud;
			//vasakul pool voib ise sisestada, paremal poolt tuleb sql`s
			
			//echo $color."<br>";
			array_push($result, $person);
		}
		
		$stmt->close();
		$mysqli->close();
		
		return $result;
	}
	
	function cleanInput($input ) {
		
		//input = "mikuz@tlu.ee     "
		$input = trim($input);
		//input = "mikuz@tlu.ee"
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		
		return $input;
		
	}
?>