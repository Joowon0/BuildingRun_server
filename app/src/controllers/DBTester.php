<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class DBTester extends BaseController
{
    public function printDB($sql) {
	try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch();

            while ($result != null) {
		print_r($result);
                echo "<br />";
		$result = $stmt->fetch();
            }

        } catch (PDOException $e) {
            echo "ERROR : " . $e->getMessage();
        }
    }

    public function printAirQualityInfo(Request $request, Response $response, $args) {
        $sql = "SELECT * FROM AirQuality_Info";
        $this->printDB($sql);
    }
    public function printBuilding(Request $request, Response $response, $args) {
        $sql = "SELECT * FROM Building";
        $this->printDB($sql);
    }
    public function printGPS(Request $request, Response $response, $args) {
        $sql = "SELECT * FROM GPS";
        $this->printDB($sql);
    }
    public function printHeartInfo(Request $request, Response $response, $args) {
        $sql = "SELECT * FROM Heart_Info";
        $this->printDB($sql);
    }
    public function printInLoc(Request $request, Response $response, $args) {
        $sql = "SELECT * FROM In_Loc";
        $this->printDB($sql);
    }
    public function printNonce(Request $request, Response $response, $args) {
        $sql = "SELECT * FROM Nonce";
        $this->printDB($sql);
    }
    public function printSensor(Request $request, Response $response, $args) {
        $sql = "SELECT * FROM Sensor";
        $this->printDB($sql);
    }
    public function printUser(Request $request, Response $response, $args) {
        $sql = "SELECT * FROM User";
        $this->printDB($sql);
    }


}
