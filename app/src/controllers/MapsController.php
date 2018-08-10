<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class MapsController extends BaseController
{

	 public function mapjson(Request $request, Response $response, $args) {
        $sql = "SELECT *
                FROM Sensor RIGHT JOIN (
                    SELECT AirQuality_Info.MAC as MAC, AirQuality_Info.Timestamp as Timestamp, AirQuality_Info.CO as CO, AirQuality_Info.SO2 as SO2, AirQuality_Info.NO2 as NO2, AirQuality_Info.O3 as O3, AirQuality_Info.PM25 as PM25, AirQuality_Info.TEMP as TEMP
                    FROM AirQuality_Info RIGHT JOIN (SELECT max(Timestamp) as ts, MAC FROM AirQuality_Info GROUP BY MAC) x
                    ON AirQuality_Info.Timestamp = x.ts AND AirQuality_Info.MAC = x.MAC) y
                ON Sensor.MAC = y.MAC";
        try {
            $sth = $this->db->query($sql);
            $pogo = $sth->fetchAll();

            if ($pogo) {
                $sensor_array = [];
                foreach ($pogo as $sensor) {

                    $sensor_array[] = array(

                        "MAC"=>$sensor['MAC'],
                        "CO" => $sensor['CO'],
                        "SO2" => $sensor['SO2'],
                        "NO2" => $sensor['NO2'],
                        "O3" => $sensor['O3'],
                        "PM25" => $sensor['PM25'],
                        "TEMP" => $sensor['TEMP'],
                        "latitude" => $sensor['latitude'],
                        "longitude" => $sensor['longitude'],
                    );

                }

               //return $response->withHeader(200)
               // ->withHeader('Content-Type', 'application/json')
                //->write(json_encode($sensor_array, JSON_NUMERIC_CHECK));

                 return $response->withHeader('Content-Type', 'application/json')
               ->write(json_encode($sensor_array, JSON_NUMERIC_CHECK))->withStatus(200);


            } else {
                $response = $response->withStatus(404);
            }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }

   }


    public function air_map(Request $request, Response $response, $args) {
        $response = $this->view->render($response, 'air_map.phtml');
        return $response;
    }

}
