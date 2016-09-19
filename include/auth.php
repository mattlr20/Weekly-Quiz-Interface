<?php 
$server = "";
$dbname = "";
$hostname = "";
$pwd  = "";
try {  
    $dbh = new PDO("sqlsrv:Server=$server;Database=$dbname","$hostname","$pwd");  
	$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch(PDOException $e) {  
	die( "Error connecting to SQL Server" ); 
}  
?>
