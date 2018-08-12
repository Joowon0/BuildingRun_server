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
      $this->calculNstoreAQI($jsonArray);


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

  // for APP
  public function app_RecentUpdateTime(Request $request, Response $response, $args) {
    $json = file_get_contents('php://input');
    $jsonArray = json_decode($json, true);

    if (isset($jsonArray['MAC'])) {
      $sql = "SELECT max(Timestamp) as TIME FROM AirQuality_Info WHERE MAC = '" .$jsonArray['MAC']. "'";

      try {
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();

        if ($result["TIME"] == null) {
          $result = array("TIME" => "2000-01-01 00:00:00");
        }

        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($result));

      } catch (PDOException $e) {
        echo "ERROR : " . $e->getMessage();
      }
    }
    else {
      return $response->withStatus(204);
    }
  }




  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  // const AQI = array(array(0,50), array(51,100), array(101,150), array(151,200), array(201,300), array(301,400), array(401,500));
  //
  // const CO   = array(0.0, 4.5,  9.5,  12.5, 15.5,  30.5,  40.5,  50.5); // every 8 h
  // const SO2  = array(0,   36,   76,   186,  305,   605,   805,   1005); // every 1 h
  // const NO2  = array(0,   54,   101,  361,  650,   1250,  1650,  2050); // every 1 h
  // const O3_1 = array(0,   55,   71,   86,   106,   201);                // every 8 h
  // const O3_2 = array(0,   0,    125,  165,  205,   405,   505,   605);  // every 1 h
  // const PM25 = array(0.0, 12.1, 35.5, 55.5, 150.5, 250.5, 350.5, 500.5);// every 24 h

  public function calculNstoreAQI($inData) {
    echo "GOT into calculNstoreAQI" . "<br>";

    $avg1h  = $this->get1hAvg($inData['MAC']);
    $avg8h  = $this->get8hAvg($inData['MAC']);
    $avg24h = $this->get24hAvg($inData['MAC']);

    print_r($avg1h); echo "<br>";
    print_r($avg8h); echo "<br>";
    print_r($avg24h); echo "<br>";

    echo "<br>";

    $aqi_CO   = AQIController::calcul_CO($avg8h['CO']);
    $aqi_SO2  = AQIController::calcul_SO2($avg1h['SO2']);
    $aqi_NO2  = AQIController::calcul_NO2($avg1h['NO2']);
    $aqi_O3_1 = AQIController::calcul_O3_1($avg8h['O3_1']);
    $aqi_O3_2 = AQIController::calcul_O3_2($avg1h['O3_2']);
    $aqi_PM25 = AQIController::calcul_PM25($avg24h['PM25']);

    echo $aqi_CO;echo "<br>";
    echo $aqi_SO2;echo "<br>";
    echo $aqi_NO2;echo "<br>";
    echo $aqi_O3_1;echo "<br>";
    echo $aqi_O3_2;echo "<br>";
    echo $aqi_PM25;echo "<br>";

    $this->storeAQI($inData['MAC'], $inData['TIME'], $aqi_CO, $aqi_SO2, $aqi_NO2, $aqi_O3_1, $aqi_O3_2, $aqi_PM25);

    // exit;

    return true;
  }

// $sql = "SELECT MAC, avg(SO2) as SO2, avg(NO2) as NO2, avg(O3) as O3_2
//         FROM AirQuality_Info
//         WHERE Timestamp >= NOW() - INTERVAL 1 Hour
//         GROUP BY MAC;"
//
// $sql2 = "SELECT MAC, avg(CO) as CO, avg(O3) as O3_1
//          FROM AirQuality_Info
//          WHERE Timestamp >= NOW() - INTERVAL 8 Hour
//          GROUP BY MAC;"
// $sql3 = "SELECT MAC, avg(PM25) as PM25
//          FROM AirQuality_Info
//          WHERE Timestamp >= NOW() - INTERVAL 24 Hour
//          GROUP BY MAC;"
  public function get1hAvg($MAC) {
    $sql = "SELECT MAC, avg(SO2) as SO2, avg(NO2) as NO2, avg(O3) as O3_2
            FROM AirQuality_Info
            WHERE MAC = '".$MAC."' AND
            Timestamp >= NOW() - INTERVAL 1 Hour";
    try {
    	$stmt = $this->db->query($sql);
    	$result = $stmt->fetch();

      return $result;
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  public function get8hAvg($MAC) {
    $sql = "SELECT MAC, avg(CO) as CO, avg(O3) as O3_1
            FROM AirQuality_Info
            WHERE MAC = '".$MAC."' AND
            Timestamp >= NOW() - INTERVAL 8 Hour";

    try {
    	$stmt = $this->db->query($sql);
    	$result = $stmt->fetch();

      return $result;
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  public function get24hAvg($MAC) {
    $sql = "SELECT MAC, avg(PM25) as PM25
            FROM AirQuality_Info
            WHERE  MAC = '".$MAC."' AND
            Timestamp >= NOW() - INTERVAL 24 Hour";

    try {
    	$stmt = $this->db->query($sql);
    	$result = $stmt->fetch();

      return $result;
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }

  // static public function calcul_CO($CO) {
  //   // $CO = 50.5;
  //   if ($CO < 0.0) {
  //     echo "NOT IN RANGE";
  //     return 0;
  //   } else if (50.5 <= $CO) {
  //     echo "NOT IN RANGE";
  //     return 500;
  //   }
  //
  //
  //   for ($category = 0; $category < count(self::CO); $category++) {
  //     if ($CO < self::CO[$category]){
  //       $category--;
  //       break;
  //     }
  //   }
  //
  //
  //   $indexDiff = self::AQI[$category][1] - self::AQI[$category][0];
  //   $rangeDiff = self::CO[$category+1] -  self::CO[$category];
  //   $concenDiff = $CO - self::CO[$category];
  //
  //   echo $indexDiff . " = " . self::AQI[$category][1] . " - " . self::AQI[$category][0] . "<br>";
  //   echo $rangeDiff . " = " . self::CO[$category+1] . " - " . self::CO[$category] . "<br>";
  //   echo $concenDiff . " = " . $CO . " - " . self::CO[$category] . "<br>";
  //
  //   $result = $indexDiff / $rangeDiff * $concenDiff + self::AQI[$category][0];
  //   echo $result . " = " . $indexDiff . " / " . $rangeDiff . " * " . $concenDiff . " + " . self::AQI[$category][0] . "<br>";
  //
  //   return $result;
  // }
  //
  // static public function calcul_SO2 ($SO2) {
  //   // $SO2 = 900;
  //   if ($SO2 < 0) {
  //     echo "NOT IN RANGE";
  //     return 0;
  //   } else if (105 <= $SO2) {
  //     echo "NOT IN RANGE";
  //     return 500;
  //   }
  //
  //
  //   for ($category = 0; $category < count(self::SO2); $category++) {
  //     if ($SO2 < self::SO2[$category]){
  //       $category--;
  //       break;
  //     }
  //   }
  //
  //
  //   $indexDiff = self::AQI[$category][1] - self::AQI[$category][0];
  //   $rangeDiff = self::SO2[$category+1] -  self::SO2[$category];
  //   $concenDiff = $SO2 - self::SO2[$category];
  //
  //   echo $indexDiff . " = " . self::AQI[$category][1] . " - " . self::AQI[$category][0] . "<br>";
  //   echo $rangeDiff . " = " . self::SO2[$category+1] . " - " . self::SO2[$category] . "<br>";
  //   echo $concenDiff . " = " . $SO2 . " - " . self::SO2[$category] . "<br>";
  //
  //   $result = $indexDiff / $rangeDiff * $concenDiff + self::AQI[$category][0];
  //   echo $result . " = " . $indexDiff . " / " . $rangeDiff . " * " . $concenDiff . " + " . self::AQI[$category][0] . "<br>";
  //
  //   return $result;
  // }
  //
  // static public function calcul_NO2 ($NO2) {
  //   // $NO2 = 2049;
  //   if ($NO2 < 0) {
  //     echo "NOT IN RANGE";
  //     return 0;
  //   } else if (2050 <= $NO2) {
  //     echo "NOT IN RANGE";
  //     return 500;
  //   }
  //
  //
  //   for ($category = 0; $category < count(self::NO2); $category++) {
  //     if ($NO2 < self::NO2[$category]){
  //       $category--;
  //       break;
  //     }
  //   }
  //
  //
  //   $indexDiff = self::AQI[$category][1] - self::AQI[$category][0];
  //   $rangeDiff = self::NO2[$category+1] -  self::NO2[$category];
  //   $concenDiff = $NO2 - self::NO2[$category];
  //
  //   echo $indexDiff . " = " . self::AQI[$category][1] . " - " . self::AQI[$category][0] . "<br>";
  //   echo $rangeDiff . " = " . self::NO2[$category+1] . " - " . self::NO2[$category] . "<br>";
  //   echo $concenDiff . " = " . $NO2 . " - " . self::NO2[$category] . "<br>";
  //
  //   $result = $indexDiff / $rangeDiff * $concenDiff + self::AQI[$category][0];
  //   echo $result . " = " . $indexDiff . " / " . $rangeDiff . " * " . $concenDiff . " + " . self::AQI[$category][0] . "<br>";
  //
  //   return $result;
  // }
  //
  // static public function calcul_O3_1 ($O3_1) {
  //   // $O3_1 = 250;
  //   if ($O3_1 < 0) {
  //     echo "NOT IN RANGE";
  //     return 0;
  //   } else if (201 <= $O3_1) {
  //     echo "NOT IN RANGE";
  //     return 500;
  //   }
  //   for ($category = 0; $category < count(self::O3_1); $category++) {
  //     if ($O3_1 < self::O3_1[$category]){
  //       $category--;
  //       break;
  //     }
  //   }
  //
  //
  //   $indexDiff = self::AQI[$category][1] - self::AQI[$category][0];
  //   $rangeDiff = self::O3_1[$category+1] -  self::O3_1[$category];
  //   $concenDiff = $O3_1 - self::O3_1[$category];
  //
  //   echo $indexDiff . " = " . self::AQI[$category][1] . " - " . self::AQI[$category][0] . "<br>";
  //   echo $rangeDiff . " = " . self::O3_1[$category+1] . " - " . self::O3_1[$category] . "<br>";
  //   echo $concenDiff . " = " . $O3_1 . " - " . self::O3_1[$category] . "<br>";
  //
  //   $result = $indexDiff / $rangeDiff * $concenDiff + self::AQI[$category][0];
  //   echo $result . " = " . $indexDiff . " / " . $rangeDiff . " * " . $concenDiff . " + " . self::AQI[$category][0] . "<br>";
  //
  //   return $result;
  // }
  //
  // static public function calcul_O3_2 ($O3_2) {
  //   // $O3_2 = 604;
  //   if ($O3_2 < 125) {
  //     echo "NOT IN RANGE";
  //     return 0;
  //   } else if (605 <= $O3_2) {
  //     echo "NOT IN RANGE";
  //     return 500;
  //   }
  //   for ($category = 0; $category < count(self::O3_2); $category++) {
  //     if ($O3_2 < self::O3_2[$category]){
  //       $category--;
  //       break;
  //     }
  //   }
  //
  //
  //   $indexDiff = self::AQI[$category][1] - self::AQI[$category][0];
  //   $rangeDiff = self::O3_2[$category+1] -  self::O3_2[$category];
  //   $concenDiff = $O3_2 - self::O3_2[$category];
  //
  //   echo $indexDiff . " = " . self::AQI[$category][1] . " - " . self::AQI[$category][0] . "<br>";
  //   echo $rangeDiff . " = " . self::O3_2[$category+1] . " - " . self::O3_2[$category] . "<br>";
  //   echo $concenDiff . " = " . $O3_2 . " - " . self::O3_2[$category] . "<br>";
  //
  //   $result = $indexDiff / $rangeDiff * $concenDiff + self::AQI[$category][0];
  //   echo $result . " = " . $indexDiff . " / " . $rangeDiff . " * " . $concenDiff . " + " . self::AQI[$category][0] . "<br>";
  //
  //   return $result;
  // }
  // static public function calcul_PM25 ($PM25) {
  //   // $PM25 = 500.0;
  //   if ($PM25 < 0.0) {
  //     echo "NOT IN RANGE";
  //     return 0;
  //   } else if (500.5 <= $PM25) {
  //     echo "NOT IN RANGE";
  //     return 500;
  //   }
  //   for ($category = 0; $category < count(self::PM25); $category++) {
  //     if ($PM25 < self::PM25[$category]){
  //       $category--;
  //       break;
  //     }
  //   }
  //
  //
  //   $indexDiff = self::AQI[$category][1] - self::AQI[$category][0];
  //   $rangeDiff = self::PM25[$category+1] -  self::PM25[$category];
  //   $concenDiff = $PM25 - self::PM25[$category];
  //
  //   echo $indexDiff . " = " . self::AQI[$category][1] . " - " . self::AQI[$category][0] . "<br>";
  //   echo $rangeDiff . " = " . self::PM25[$category+1] . " - " . self::PM25[$category] . "<br>";
  //   echo $concenDiff . " = " . $PM25 . " - " . self::PM25[$category] . "<br>";
  //
  //   $result = $indexDiff / $rangeDiff * $concenDiff + self::AQI[$category][0];
  //   echo $result . " = " . $indexDiff . " / " . $rangeDiff . " * " . $concenDiff . " + " . self::AQI[$category][0] . "<br>";
  //
  //   return $result;
  // }

  public function storeAQI($MAC, $ts, $CO, $SO2, $NO2, $O3_1, $O3_2, $PM25) {
    $sql = "INSERT INTO AQI_Info(MAC, Timestamp, CO, SO2, NO2, O3_1, O3_2, PM25)
            VALUES ('".
              $MAC ."', '".
              $ts  ."', ".
              $CO  .", ".
              $SO2 .", ".
              $NO2 .", ".
              $O3_1.", ".
              $O3_2.", ".
              $PM25.");";
      // echo $sql . "<br>";
      // exit;
    try {
    	$stmt = $this->db->query($sql);

      return true;
    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }
}
