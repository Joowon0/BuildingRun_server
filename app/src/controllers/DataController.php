<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class DataController extends BaseController {
  //abstract class loginMessage {
      const MAC_EXIST = 1;
      const MAC_NOT_EXIST = 2;
  //}

  // for APP
  public function app_airQualityDataTransfer(Request $request, Response $response, $args) {
    $json = file_get_contents('php://input');
    $jsonArray = json_decode($json, true);

    if (isset($jsonArray['USN']) && isset($jsonArray['TIME']) && isset($jsonArray['MAC'])) {

      $this->storeGPS($jsonArray);

      $MAC_existence = $this->checkMAC($jsonArray['MAC']);
      if ($MAC_existence == self::MAC_EXIST)
        $this->storeAirQuality($jsonArray);

      $sendData = array("Result"=>$MAC_existence);

      return $response->withStatus(200)
          ->withHeader('Content-Type', 'application/json')
          ->write(json_encode($sendData));
    }
    else {
      return $response->withStatus(204);
    }
  }

  public function storeGPS($data) {
    $sql = "UPDATE Sensor SET latitude = ".$data['latitude'].", longitude=".$data['longitude']." WHERE USN = ". $data['USN'] ;
    try {
      $stmt = $this->db->query($sql);
      return true;
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  public function checkMAC($MAC) {
    $sql = "SELECT * FROM Sensor WHERE MAC = '" .$MAC. "'" ;
    try {
      $stmt = $this->db->query($sql);
      $result = $stmt->fetch();

      if ($result == null) {
        return self::MAC_NOT_EXIST;
      } else {
        return self::MAC_EXIST;
      }
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  // TODO : need to calcul AQI
  public function storeAirQuality($data) {
    $sql = "INSERT INTO AirQuality_Info(MAC, Timestamp, CO, SO2, NO2, O3, PM25, TEMP) VALUES ('".
      $data['MAC']."', '".
      $data['TIME']."', ".
      $data['CO'].", ".
      $data['SO2'].", ".
      $data['NO2'].", ".
      $data['O3'].", ".
      $data['PM25'].", ".
      $data['TEMP']." ) " ;
      // echo $sql; exit;
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

    if (isset($jsonArray['USN']) && isset($jsonArray['TIME'])) {
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
      $data['TIME']."',".
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
