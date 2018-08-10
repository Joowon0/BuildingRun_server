<?php
namespace App\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class ChartController extends BaseController {
  public function chartTEST(Request $request, Response $response, $args) {
      $this->view->render($response, 'linegraph.phtml');
      return $response;
  }

  public function makeJSON($sql) {
    try {
      $stmt = $this->db->query($sql);
      $data = array();

      foreach ($stmt as $result) {
        $data[] = $result;
      }
      print json_encode($data);

    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }

    return true;
  }

  // heart related information
  public function heartRateReal(Request $request, Response $response, $args) {
    $sql = "SELECT SUBSTR(Timestamp, 12, 2) as hh, SUBSTR(Timestamp, 15, 2) as mm, SUBSTR(Timestamp, 18, 2) as ss, HeartRate, HeartInterval FROM Heart_Info WHERE USN = ".$_SESSION["USN"]." ORDER BY Timestamp DESC LIMIT 20";

    $this->makeJSON($sql);
    return $response;
  }

  public function heartRate10Min(Request $request, Response $response, $args) {
    $sql = "SELECT SUBSTR(Timestamp, 9, 7) as ts, avg(HeartRate) as HeartRate, avg(HeartInterval) as HeartInterval FROM Heart_Info WHERE USN = ".$_SESSION["USN"]." GROUP BY SUBSTR(Timestamp, 9, 7)";

    $this->makeJSON($sql);
    return $response;
  }

  public function heartRateHour(Request $request, Response $response, $args) {
    $sql = "SELECT SUBSTR(Timestamp, 9, 5) as ts, avg(HeartRate) as HeartRate, avg(HeartInterval) as HeartInterval FROM Heart_Info WHERE USN = ".$_SESSION["USN"]." GROUP BY SUBSTR(Timestamp, 9, 5)";

    $this->makeJSON($sql);
    return $response;
  }

  // air quality information
  public function getJSON(Request $request, Response $response, $args) {
    $sql = "SELECT * FROM AirQuality_Info JOIN Sensor WHERE USN = ".$_SESSION['USN']." ORDER BY Timestamp";

    $this->makeJSON($sql);
    return $response;
  }

  public function air10Min(Request $request, Response $response, $args) {
    $MAC = 'fdsa';
    $sql = "SELECT SUBSTR(Timestamp, 9, 7) as ts, avg(CO) as CO, avg(SO2) as SO2, avg(NO2) as NO2, avg(O3) as O3, avg(PM25) as PM25, avg(TEMP) as TEMP
            FROM AirQuality_Info
            WHERE MAC = '".$MAC."'
            GROUP BY SUBSTR(Timestamp, 9, 7)";
    $this->makeJSON($sql);
  }

  public function airHour(Request $request, Response $response, $args) {
    $MAC = 'fdsa';
    $sql = "SELECT SUBSTR(Timestamp, 9, 5) as ts, avg(CO) as CO, avg(SO2) as SO2, avg(NO2) as NO2, avg(O3) as O3, avg(PM25) as PM25, avg(TEMP) as TEMP
            FROM AirQuality_Info
            WHERE MAC = '".$MAC."'
            GROUP BY SUBSTR(Timestamp, 9, 5)";
    $this->makeJSON($sql);
  }


  // for APP historic data view
  public function app_airQualityHistory(Request $request, Response $response, $args) {
    $json = file_get_contents('php://input');
    $jsonArray = json_decode($json, true);

    if (isset($jsonArray['MAC']) && isset($jsonArray['period']) &&
        isset($jsonArray['startDate']) && isset($jsonArray['endDate'])) {

      if ($jsonArray['period'] == 1)
        $airData = $this->hourAirQuality($jsonArray['MAC'], $jsonArray['startDate'], $jsonArray['endDate']);
      else if ($jsonArray['period'] == 2)
        $airData = $this->dayAirQuality($jsonArray['MAC'], $jsonArray['startDate'], $jsonArray['endDate']);;

      return $response->withStatus(200)
          ->withHeader('Content-Type', 'application/json')
          ->write(json_encode($airData));
    }
    else
      return $response->withStatus(204);
  }

  public function hourAirQuality($MAC, $startDate, $endDate) {
    $sql = "SELECT SUBSTR(Timestamp, 1, 13) as TIME, avg(CO) AS CO, avg(SO2) AS SO2, avg(NO2) AS NO2, avg(O3) AS O3, avg(PM25) AS PM25, avg(TEMP) as TEMP FROM AirQuality_Info WHERE '".$startDate."' <= Timestamp  AND Timestamp < '".$endDate."' GROUP BY SUBSTR(Timestamp, 1, 13)";
    try {
    	$stmt = $this->db->query($sql);
    	$result = $stmt->fetch();

      $airData = array();

      while ($result != null) {
        array_push($airData, $result);
        $result = $stmt->fetch();
      }
      //print_r($data); exit;
      return $airData;
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }
  public function dayAirQuality($MAC, $startDate, $endDate) {
    $sql = "SELECT SUBSTR(Timestamp, 1, 10) as TIME, avg(CO) AS CO, avg(SO2) AS SO2, avg(NO2) AS NO2, avg(O3) AS O3, avg(PM25) AS PM25, avg(TEMP) as TEMP FROM AirQuality_Info WHERE '".$startDate."' <= Timestamp  AND Timestamp < '".$endDate."' GROUP BY SUBSTR(Timestamp, 1, 10)";
    try {
    	$stmt = $this->db->query($sql);
    	$result = $stmt->fetch();

      $airData = array();

      while ($result != null) {
        array_push($airData, $result);
        $result = $stmt->fetch();
      }
      //print_r($data); exit;
      return $airData;
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  // for APP historic data view
  public function app_heartHistory(Request $request, Response $response, $args) {
    $json = file_get_contents('php://input');
    $jsonArray = json_decode($json, true);

    if (isset($jsonArray['USN']) && isset($jsonArray['period']) &&
        isset($jsonArray['startDate']) && isset($jsonArray['endDate'])) {

      if ($jsonArray['period'] == 1)
        $airData = $this->hourHeart($jsonArray['USN'], $jsonArray['startDate'], $jsonArray['endDate']);
      else if ($jsonArray['period'] == 2)
        $airData = $this->dayHeart($jsonArray['USN'], $jsonArray['startDate'], $jsonArray['endDate']);;

      return $response->withStatus(200)
          ->withHeader('Content-Type', 'application/json')
          ->write(json_encode($airData));
    }
    else
      return $response->withStatus(204);
  }

  public function hourHeart($USN, $startDate, $endDate) {
    $sql = "SELECT SUBSTR(Timestamp, 1, 13) as TIME, avg(HeartRate) as HeartRate, avg(HeartInterval) as HeartInterval
            FROM Heart_Info
            WHERE USN = ".$USN." AND
            '".$startDate."' <= Timestamp  AND Timestamp < '".$endDate."' GROUP BY SUBSTR(Timestamp, 1, 13)";
    try {
    	$stmt = $this->db->query($sql);
    	$result = $stmt->fetch();

      $airData = array();

      while ($result != null) {
        array_push($airData, $result);
        $result = $stmt->fetch();
      }
      //print_r($data); exit;
      return $airData;
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  public function dayHeart($USN, $startDate, $endDate) {
    $sql = "SELECT SUBSTR(Timestamp, 1, 10) as TIME, avg(HeartRate) as HeartRate, avg(HeartInterval) as HeartInterval
            FROM Heart_Info
            WHERE USN = ".$USN." AND
            '".$startDate."' <= Timestamp  AND Timestamp < '".$endDate."' GROUP BY SUBSTR(Timestamp, 1, 10)";

    try {
    	$stmt = $this->db->query($sql);
    	$result = $stmt->fetch();

      $airData = array();

      while ($result != null) {
        array_push($airData, $result);
        $result = $stmt->fetch();
      }
      //print_r($data); exit;
      return $airData;
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }
}
