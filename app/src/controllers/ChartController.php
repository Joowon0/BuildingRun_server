<?php
namespace App\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class ChartController extends BaseController {
  public function player(Request $request, Response $response, $args) {
    echo "[
  {
    \"userid\" : \"1\",
    \"facebook\" : \"100\",
    \"twitter\" : \"200\",
    \"googleplus\" : \"80\"
  },
  {
    \"userid\" : \"2\",
    \"facebook\" : \"60\",
    \"twitter\" : \"150\",
    \"googleplus\" : \"180\"
  },
  {
    \"userid\" : \"3\",
    \"facebook\" : \"50\",
    \"twitter\" : \"90\",
    \"googleplus\" : \"120\"
  }
]";
    return $response;
  }
  public function chartTEST(Request $request, Response $response, $args) {
      $this->view->render($response, 'linegraph.phtml');
      return $response;
  }

  public function makeJSON($SSN, $airType) {
    $sql = "SELECT * FROM AirQuality_Info WHERE SSN = ".$SSN." ORDER BY Timestamp";

    try {
      $stmt = $this->db->query($sql);
      //$result = $stmt->fetch();

      $data = array();
      foreach ($stmt as $result) {
        //print_r ($result);
        //echo "<br>";

        $data[] = $result;
      }
      print json_encode($data);

    } catch (PDOException $e) {
      echo "ERROR : " . $e->getMessage();
    }

    return true;
  }

  public function getJSON(Request $request, Response $response, $args) {
    $this->makeJSON(1, 'CO');
    return $response;
  }
}
