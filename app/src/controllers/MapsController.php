<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class MapsController extends BaseController
{
   
	 public function mapjson(Request $request, Response $response, $args) {
        $sql = "SELECT USN, latitude, longitude
                FROM Sensor";
        try {
            $sth = $this->db->query($sql);
            $pogo = $sth->fetchAll();

            if ($pogo) {
                $sensor_array = [];
                foreach ($pogo as $sensor) {
                  
                    $sensor_array[] = array("USN"=>$sensor['USN'], 
                        "latitude" => $sensor['latitude'], 
                        "longitude" => $sensor['longitude']);   
                        
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
