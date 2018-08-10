<?php
namespace App\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SensorListController extends BaseController {
  public function getSensorList() {
    $sql = "SELECT Sensor.MAC as MAC, latitude, longitude, x.ts as lastUpdate
            FROM Sensor JOIN (SELECT MAC, max(Timestamp) ts FROM AirQuality_Info GROUP BY MAC) x
            ON Sensor.MAC = x.MAC"
    try {
    	$stmt = $this->db->query($sql);

    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }
  }
}
