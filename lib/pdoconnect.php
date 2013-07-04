<?php
$host = 'localhost';
$username = 'root';
$password = 'buflax';
$dbname='synafoos';
$conn = new PDO('mysql:host='.$host.';dbname='.$dbname, $username, $password);

$player_id = 1;

try {  
  $stmt = $conn->prepare('SELECT * FROM players WHERE player_id = :player_id');
  $stmt->execute(array('player_id' => $player_id));
 
  $result = $stmt->fetchAll();
 
  if ( count($result) ) { 
    foreach($result as $row) {
      print_r($row);
    }   
  } else {
    echo "No rows returned.";
  }
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}
?>