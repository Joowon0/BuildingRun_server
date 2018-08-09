<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class MapsController extends BaseController
{
    public function default(Request $request, Response $response, $args) {
        $response = $this->view->render($response, 'hotelmarkers.phtml');
        return $response;
        
   } 

    public function circles(Request $request, Response $response, $args) {
        $response = $this->view->render($response, 'example-map.phtml');
        return $response;
        
   } 

    public function pokemonjson(Request $request, Response $response, $args) {
        $sql = "SELECT id, attributes as att, name, lat, lng, tth, pokedex
                FROM pokemon
                LIMIT 20";
        try {
            $sth = $this->db->query($sql);
            $pogo = $sth->fetchAll();

            if ($pogo) {
                $person_array = [];
                foreach ($pogo as $person) {
                    //$person_array[] = array($person['codename'] => array("center" => array("lat" => $person['lat'], "lng" => $person['lng']), "population" => "20000000"));
                    $person_array[] = array("name"=>$person['name'], "lat" => round($person['lat'], 5), 
                                "lng" => round($person['lng'], 5),
                                "dex"=>$person['pokedex'] . "",
                                "att" => ""
                                );
                }

                return $response->withStatus(200)
                ->withHeader('Content-Type', 'application/json')
                ->write(json_encode($person_array));

            } else {
                $response = $response->withStatus(404);
            }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }

   } 

    public function pokemon(Request $request, Response $response, $args) {
        $response = $this->view->render($response, 'pokemap.phtml');
        return $response;
    }    

}
