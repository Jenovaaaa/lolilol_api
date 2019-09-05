<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: X-PINGOTHER, Content-Type', 'Accept');

include('Apicall.php');
include('Settings.php');
$name = '';
$region = 'euw1';
$apiCall = new Apicall();
$summonerDetails = $apiCall->getSummonersGameInformations($region, $name);  
print_r($summonerDetails);
/*
if (isset($_POST)) {
    try {
        $playersTab = [];
        $requestData = file_get_contents('php://input');
        $requestData = json_decode($requestData, true);
        $name = isset($requestData['name']) ? $requestData['name'] : '';
        $region = isset($requestData['region']) ? $requestData['region'] : '';

        if (!empty($name) && !empty($region)) {
            $apiCall = new Apicall();
            $summonerDetails = $apiCall->getSummonersGameInformations($region, $name);  
            print_r($summonerDetails);
        } else {
            echo json_encode(array('status' => 'failed'));
        }
    } catch (Exception $e) {
        echo json_encode(array('status' => 'failed'));
    }
}
else {
    echo json_encode(array('status' => 'failed'));
}
*/