<?php 
	require("functions.php");
	
	// kas on sisseloginud, kui ei ole siis
	// suunata login lehele
	if (!isset ($_SESSION["userId"])) {
		
		header("Location: login.php");
		exit();
	}
	
	//kas logout on aadressireal?
	if (isset($_GET["logout"])) {
		
		session_destroy();
		
		header("Location: login.php");
		exit();
	}
	
	
	if ( isset($_POST["sunnipaev"]) &&
		 isset($_POST["telefon"]) &&
		 !empty($_POST["sunnipaev"]) &&
		 !empty($_POST["telefon"])
	  ) {
		
		bdayandtel(cleanInput($_POST["sunnipaev"]), cleanInput($_POST["telefon"]));
	}
	
	$infoall = dataAll();
	
?>
<h1>Data</h1>
<p>
	Tere tulemast <?=$_SESSION["email"];?>!
	<a href="?logout=1">Logi välja</a>
</p> 

<h1>Registreerimise lopetamine</h1>
<form method="POST">
  Sunnipaev:
  <input type="date" name="sunnipaev">
  <br></br>
  Telefoni number:
  <input type='tel' name="telefon" placeholder="Naide 56883412" pattern='\d{4}\d{4}' title='Telefoni numbri naide 9999-9999)'> 
  <br></br>
  <input type="submit" value="Salvesta">
</form>

<h2>Kontakti info</h2>
	<?php 
	$html = "<table>";
		$html .= "<tr>";
			$html .= "<th>id</th>";
			$html .= "<th>Sunnipaev</th>";
			$html .= "<th>Telefon</th>";
			$html .= "<th>Loodud</th>";
		$html .= "</tr>";

		foreach($infoall as $ia){
			$html .= "<tr>";
				$html .= "<td>".$ia->id."</td>";
				$html .= "<td>".$ia->sunnipaev."</td>";
				$html .= "<td>".$ia->telefon."</td>";
				$html .= "<td>".$ia->loodud."</td>";
			$html .= "</tr>";	
		}
		
	$html .= "</table>";
	echo $html;
?>