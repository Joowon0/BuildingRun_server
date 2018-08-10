<?php
namespace App\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SensorListController extends BaseController {

  public function sensor_json(Request $request, Response $response, $args) {
    $sql = "SELECT Sensor.MAC as MAC, latitude, longitude, x.ts as Timestamp
            FROM Sensor JOIN (SELECT MAC, max(Timestamp) ts FROM AirQuality_Info GROUP BY MAC) x
            ON Sensor.MAC = x.MAC";
    

   try {
            $sth = $this->db->query($sql);
            $pogo = $sth->fetchAll();

            if ($pogo) {
                $sensor_array = [];
                foreach ($pogo as $sensor) {

                    $sensor_array[] = array(

                        "MAC"=>$sensor['MAC'],
                        "latitude" => $sensor['latitude'],
                        "longitude" => $sensor['longitude'],
                        "Timestamp" => $sensor['Timestamp'],
                        
                    );

                }

             
                 return $response->withHeader('Content-Type', 'application/json')
               ->write(json_encode($sensor_array, JSON_NUMERIC_CHECK))->withStatus(200);


            } else {
                $response = $response->withStatus(404);
            }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }






  }

  public function sensor_list(Request $request, Response $response, $args)
  {
      $this->view->render($response, 'sensor_list.phtml');
      return $response;
  }
}
