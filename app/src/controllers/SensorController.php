<?php
namespace App\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SensorController extends BaseController {
  //abstract class sensorMessage {
      const SSN_EXIST = 1;
      const SSN_NOT_EXIST = 2;
  //}

  // for APP
  public function app_sensorRegister(Request $request, Response $response, $args) {
    $json = file_get_contents('php://input');
    $jsonArray = json_decode($json, true);

    if (isset($jsonArray['USN']) && isset($jsonArray['MAC'])) {
      $SSN_existence = $this->checkSSNExist($jsonArray['USN'], $jsonArray['MAC']);

      if ($SSN_existence == self::SSN_NOT_EXIST) {
        $this->storeSensorInfo($jsonArray['USN'], $jsonArray['MAC']);
        $SSN = $this->getSSN($jsonArray['MAC']);
      }
      $sendData = array("Result"=>$SSN_existence,
                        "USN"=>$jsonArray['USN'],
                        "SSN"=>$SSN);

      return $response->withStatus(200)
          ->withHeader('Content-Type', 'application/json')
          ->write(json_encode($sendData));
    }
    else
      return $response->withStatus(204);
  }

  public function checkSSNExist($USN, $MAC) {
    $sql = "SELECT * FROM Sensor WHERE USN = ".$USN." AND MAC = '" . $MAC . "' " ;
    try {
    	$stmt = $this->db->query($sql);
    	$result = $stmt->fetch();

      if ($result == null) {
        return self::SSN_NOT_EXIST;
      } else {
        return self::SSN_EXIST;
      }
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }
  public function storeSensorInfo($USN, $MAC) {
    $sql = "INSERT INTO Sensor(MAC, USN, isIndoor) VALUES ('".$MAC."', ".$USN.", false)";
    try {
      $stmt = $this->db->query($sql);

      return true;
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  public function getSSN ($MAC) {
    $sql = "SELECT * FROM Sensor WHERE MAC = '" . $MAC . "'" ;
    try {
      $stmt = $this->db->query($sql);
      $result = $stmt->fetch();

      return $result['SSN'];
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  // for APP
  public function app_sensorDeregister(Request $request, Response $response, $args) {
    $json = file_get_contents('php://input');
    $jsonArray = json_decode($json, true);

    if (isset($jsonArray['SSN'])) {
      $SSN_existence = $this->checkSSNExist2($jsonArray['SSN']);

      if ($SSN_existence == self::SSN_EXIST) {
        $this->deleteSSN($jsonArray['SSN']);
      }
      $sendData = array("Result"=>$SSN_existence);

      return $response->withStatus(200)
          ->withHeader('Content-Type', 'application/json')
          ->write(json_encode($sendData));
    }
    else
      return $response->withStatus(204);
  }

  public function checkSSNExist2($SSN) {
    $sql = "SELECT * FROM Sensor WHERE SSN = ".$SSN ;
    try {
    	$stmt = $this->db->query($sql);
    	$result = $stmt->fetch();

      if ($result == null) {
        return self::SSN_NOT_EXIST;
      } else {
        return self::SSN_EXIST;
      }
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  public function deleteSSN($SSN) {
    $sql = "DELETE FROM Sensor WHERE SSN = ".$SSN ;

    try {
      $stmt = $this->db->query($sql);
      return true;
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  // for APP
  public function app_sensorListView(Request $request, Response $response, $args) {
    $json = file_get_contents('php://input');
    $jsonArray = json_decode($json, true);

    if (isset($jsonArray['USN'])) {
      $sensorData = $this->getSensorInfo($jsonArray['USN']);

      $sendData = array("num"=>count($sensorData),
                        "Sensor"=>$sensorData);

      return $response->withStatus(200)
          ->withHeader('Content-Type', 'application/json')
          ->write(json_encode($sendData));
    }
    else
      return $response->withStatus(204);
  }

  public function getSensorInfo($USN) {
    $sql = "SELECT SSN, MAC FROM Sensor WHERE USN = ".$USN ;
    try {
    	$stmt = $this->db->query($sql);
    	$result = $stmt->fetch();

      $sensorData = array();

      while ($result != null) {
        array_push($sensorData, $result);
        $result = $stmt->fetch();
      }
      //print_r($data); exit;
      return $sensorData;
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }
}
