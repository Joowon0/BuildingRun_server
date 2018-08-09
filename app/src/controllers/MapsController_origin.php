<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class MapsController extends BaseController
{
    

    public function mapjson(Request $request, Response $response, $args) {
        $sql = "SELECT MAC, USN
                FROM Sensor";
        try {
            $sth = $this->db->query($sql);
            $pogo = $sth->fetchAll();

            if ($pogo) {
                $sensor_array = [];
                foreach ($pogo as $sensor) {
                    //$sensor_array[] = array($sensor['codename'] => array("center" => array("lat" => $sensor['lat'], "lng" => $sensor['lng']), "population" => "20000000"));
                    $sensor_array[] = array("MAC"=>$sensor['MAC'], 
                                            "USN" => $sensor['USN']
                                         
                                );
                }

                return $response->withStatus(200)
                ->withHeader('Content-Type', 'application/json')
                ->write(json_encode($sensor_array));

            } else {
                $response = $response->withStatus(404);
            }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }

   } 

    

}
