<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class DataController extends BaseController {
  // for APP
  public function app_airQualityDataTransfer(Request $request, Response $response, $args) {
    $json = file_get_contents('php://input');
    $jsonArray = json_decode($json, true);

    if (isset($jsonArray['USN']) && isset($jsonArray['timestamp']) && isset($jsonArray['SSN'])) {

      //$this->storeGPS($jsonArray);
      $this->storeAirQuality($jsonArray);

      $sendData = array("ACK"=>true);

      return $response->withStatus(200)
          ->withHeader('Content-Type', 'application/json')
          ->write(json_encode($sendData));
    }
    else {
      return $response->withStatus(204);
    }
  }

  public function storeGPS($data) {
    $sql = "INSERT INTO GPS(Out_lat, Out_log, Timestamp, USN) VALUES (".
      $data['latitude']. ", ". $data['longitude'] .", '" . $data['timestamp'] . "', " . $data['USN']. ")" ;
    try {
      $stmt = $this->db->query($sql);
      return true;
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  // TODO : need to calcul AQI
  public function storeAirQuality($data) {
    $sql = "INSERT INTO AirQuality_Info(CO, CO_AQI, SO2, SO2_AQI, NO2, NO2_AQI, O3, O3_AQI, PM2_5, PM2_5_AQI, AQI_avg, Temp, SSN, Timestamp) VALUES (".
      $data['CO']    . ", -1, ".
      $data['SO2']   . ", -1, " .
      $data['NO2']   . ", -1, ".
      $data['O3']    . ", -1, ".
      $data['PM2.5'] . ", -1, -1,".
      $data['temper']. ",".
      $data['SSN']   . ", '".
      $data['timestamp']."' )" ;
    try {
      $stmt = $this->db->query($sql);
      return true;
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  // for APP
  public function app_heartDataTransfer(Request $request, Response $response, $args) {
    $json = file_get_contents('php://input');
    $jsonArray = json_decode($json, true);

    if (isset($jsonArray['USN']) && isset($jsonArray['timestamp'])) {
      $this->storeHeartData($jsonArray);

      $sendData = array("ACK"=>true);

      return $response->withStatus(200)
          ->withHeader('Content-Type', 'application/json')
          ->write(json_encode($sendData));
    }
    else {
      return $response->withStatus(204);
    }
  }

  public function storeHeartData($data) {
    $sql = "INSERT INTO Heart_Info(Timestamp, HeartRate, HeartInterval, USN) VALUES ( '".
      $data['timestamp']."',".
      $data['heartRate'].",".
      $data['heartInterval'].",".
      $data['USN'].")" ;
    try {
      $stmt = $this->db->query($sql);
      return true;
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }
}
