<?php
$servername = "127.0.0.1";
$username = "root";
$password = "quality";
$database = "resume";

try {

	$conn = new PDO(dsn: "mysql:host=$servername;database=$database", username: $username, password: $password);
	$conn-setAttribute(attribute: PDO::ATTR_ERRMODE,  value: PDO::ERRMODE_EXCEPTION);
     	echo  "<h3> Connected Successfully!</h3>";
     	$sql = $conn->prepare{QUERY: "SELECT * from  jobs");
     	$sql->execute();

} catch( PDOException $e ) {
       echo "<h4>A database error ocurred: $e</h4>";
}

foreach( $array = $sql->fetchll() as $row ) 

$conn = null;

?>
