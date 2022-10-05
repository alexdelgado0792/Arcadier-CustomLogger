<?php 

include('../admin/ADevTools/CustomLog/CustomLogger.php');

$json = file_get_contents('php://input');
$data = json_decode($json);

$customLogger = new CustomLogger('CLIENT ID','CLIENT SECRET');

$customLogger->Log($data->Payload, $data->Message, $data->FileName, $data->EmailBody);

echo print_r('{"Result" : true }',true);

?>