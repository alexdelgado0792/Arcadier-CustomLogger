<!-- 
    Endpoint to sent logs via HTTP Request using authentication

 -->
<?php 

$json = file_get_contents('php://input');
$data = json_decode($json);
// include('ADevTools/CustomLog/')

?>

