<?php
namespace App\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SensorListController extends BaseController {

  // public function sensor_json(Request $request, Response $response, $args) {
  //   $sql = "SELECT Sensor.MAC as MAC, latitude, longitude, x.ts as Timestamp
  //           FROM Sensor JOIN (SELECT MAC, max(Timestamp) ts FROM AirQuality_Info GROUP BY MAC) x
  //           ON Sensor.MAC = x.MAC";
  //
  //     try {
  //       $sth = $this->db->query($sql);
  //       $pogo = $sth->fetchAll();
  //
  //       if ($pogo) {
  //         $sensor_array = [];
  //         foreach ($pogo as $sensor) {
  //
  //           $sensor_array[] = array(
  //             "MAC"=>$sensor['MAC'],
  //             "latitude" => $sensor['latitude'],
  //             "longitude" => $sensor['longitude'],
  //             "Timestamp" => $sensor['Timestamp'],
  //             );
  //         }
  //         return $response;
  //       }
  //     } catch(PDOException $e) {
  //       echo '{"error":{"text":'. $e->getMessage() .'}}';
  //     }
  //     $this->view->render($response, 'sensor_list.phtml', ['sensor' => $sensor_array]);
  //     return $response;
  // }

  public function sensor_list(Request $request, Response $response, $args)
  {
    $sql = "SELECT Sensor.MAC as MAC, latitude, longitude, x.ts as Timestamp
            FROM Sensor LEFT JOIN (SELECT MAC, max(Timestamp) ts FROM AirQuality_Info GROUP BY MAC) x
            ON Sensor.MAC = x.MAC
            WHERE Sensor.USN = '".$_SESSION['USN']."'";

    try {
      $sth = $this->db->query($sql);
      $data = $sth->fetchAll();

      if ($data) {
        $sensor_array = [];
        foreach ($data as $sensor) {

          $sensor_array[] = array(
            "MAC"=>$sensor['MAC'],
            "latitude" => $sensor['latitude'],
            "longitude" => $sensor['longitude'],
            "Timestamp" => $sensor['Timestamp'],
            );
        }
      }
    } catch(PDOException $e) {
      echo '{"error":{"text":'. $e->getMessage() .'}}';
    }

    $this->view->render($response, 'sensor_list.phtml', ['sensor' => $sensor_array]);
    return $response;
  }
}
