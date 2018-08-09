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

  public function hour(Request $request, Response $response, $args) {
    $sql = "SELECT avg(CO) AS CO, avg(SO2) AS SO2, avg(NO2) AS NO2, avg(O3) AS O3, avg(PM2_5) AS PM2_5, SUBSTR(Timestamp, 6, 8) AS timestamp FROM AirQuality_Info GROUP BY SUBSTR(Timestamp, 6, 8)";
    $this->makeJSON($sql);
  }

  public function minute(Request $request, Response $response, $args) {
    $sql = "SELECT avg(CO) AS CO, avg(SO2) AS SO2, avg(NO2) AS NO2, avg(O3) AS O3, avg(PM2_5) AS PM2_5, SUBSTR(Timestamp, 6, 11) AS timestamp FROM AirQuality_Info GROUP BY SUBSTR(Timestamp, 6, 11)";
    $this->makeJSON($sql);
  }

  public function getJSON(Request $request, Response $response, $args) {
    $MAC = "fdsa";
    $sql = "SELECT * FROM AirQuality_Info WHERE MAC = '".$MAC."' ORDER BY Timestamp";

    $this->makeJSON($sql);
    return $response;
  }
}
