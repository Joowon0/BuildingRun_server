<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class DataController extends BaseController {
  //abstract class loginMessage {
      const SUCCESS = 1;
      const MAC_NOT_EXIST = 2;
      const WRONG_USN_MAC_PAIR = 3;
      const TIMESTAMP_EXIST = 4;
  //}

  // for APP
  public function app_airQualityDataTransfer(Request $request, Response $response, $args) {
    $json = file_get_contents('php://input');
    $jsonArray = json_decode($json, true);

    if (isset($jsonArray['USN']) && isset($jsonArray['TIME']) && isset($jsonArray['MAC'])) {

      $this->storeGPS($jsonArray);

      // 1. check if MAC exist
      $MAC_existence = $this->checkMAC($jsonArray['MAC']);

      if ($MAC_existence == self::MAC_NOT_EXIST) {
        $sendData = array("Result"=>self::MAC_NOT_EXIST);

        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($sendData));
      }

      // 2. check if USN and MAC is in correct pair
      $correct_pair = $this->checkUSN_MAC_Pair($jsonArray['USN'], $jsonArray['MAC']);

      if ($correct_pair == self::WRONG_USN_MAC_PAIR) {
        $sendData = array("Result"=>self::WRONG_USN_MAC_PAIR);

        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($sendData));
      }

      // 3. check if the timestamp alreay exist
      $ts_existence = $this->checkTSexist($jsonArray['MAC'], $jsonArray['TIME']);

      if ($ts_existence == self::TIMESTAMP_EXIST) {
        $sendData = array("Result"=>self::TIMESTAMP_EXIST);

        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($sendData));
      }

      // 4. sucess
      $this->storeAirQuality($jsonArray);
      $sendData = array("Result"=>self::SUCCESS);

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
        return self::SUCCESS;
      }
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  public function checkUSN_MAC_Pair($USN, $MAC) {
    $sql = "SELECT * FROM Sensor WHERE MAC = '".$MAC."' AND USN = ".$USN;
    try {
      $stmt = $this->db->query($sql);
      $result = $stmt->fetch();

      if ($result == null) {
        return self::WRONG_USN_MAC_PAIR;
      } else {
        return self::SUCCESS;
      }
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  public function checkTSexist($MAC, $Timestamp) {
    $sql = "SELECT * FROM `AirQuality_Info` WHERE Timestamp = '".$Timestamp."' AND MAC = '".$MAC."'";
    try {
      $stmt = $this->db->query($sql);
      $result = $stmt->fetch();

      if ($result == null) {
        return self::SUCCESS;
      } else {
        return self::TIMESTAMP_EXIST;
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
