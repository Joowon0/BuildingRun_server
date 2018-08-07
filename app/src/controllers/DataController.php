<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class DataController extends BaseController {
  // for APP
  public function app_airQualityDataTransfer(Request $request, Response $response, $args) {
    $json = file_get_contents('php://input');
    $jsonArray = json_decode($json, true);

    if (isset($jsonArray['USN']) && isset($jsonArray['timestamp']) &&
    isset($jsonArray['latitude']) && isset($jsonArray['longitude']) && isset($jsonArray['SSN'])) {

      $this->storeGPS($jsonArray);
      $this->storeAirQuality($jsonArray);

      return $response->withStatus(200)
          ->withHeader('Content-Type', 'application/json');
          //->write(json_encode($sendData));
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
    $sql = "INSERT INTO AirQuality_Info(CO, CO_AQI, CO2, CO2_AQI, SO2, SO2_AQI, NO2, NO2_AQI, O3, O3_AQI, PM2_5, PM2_5_AQI, PM10, PM10_AQI, AQI_avg, Temp, Sensor_SSN, Timestamp) VALUES (".
      $data['CO']    . ", -1, ".
      $data['CO2']   . ", -1, ".
      $data['SO2']   . ", -1, " .
      $data['NO2']   . ", -1, ".
      $data['O3']    . ", -1, ".
      $data['PM2.5'] . ", -1, ".
      $data['PM10']  . ", -1, -1,".
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
}
