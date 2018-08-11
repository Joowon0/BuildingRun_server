<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class AQIController extends BaseController {
  const AQI = array(array(0,50), array(51,100), array(101,150), array(151,200), array(201,300), array(301,400), array(401,500));

  const CO   = array(0.0, 4.5, 9.5, 12.5, 15.5, 30.5, 40.5, 50.5); // every 8 h
  const SO2  = array(0,   36,  76,  186,  305,  605,  805,  1005); // every 1 h
  const NO2  = array(0,   54,  101, 361,  650,  1250, 1650, 2050); // every 1 h
  const O3_1 = array(0,   55,  71,  86,   106,  201);              // every 8 h
  const O3_2 = array(0,   0,   125, 165,  205,  405,  505,  605);  // every 1 h



  static public function calcul_CO ($CO) {
    // $CO = 50.5;
    if ($CO < 0.0 || 50.5 <= $CO) {
      echo "NOT IN RANGE";
      return -10;
    }
    for ($category = 0; $category < count(self::CO); $category++) {
      if ($CO < self::CO[$category]){
        $category--;
        break;
      }
    }


    $indexDiff = self::AQI[$category][1] - self::AQI[$category][0];
    $rangeDiff = self::CO[$category+1] -  self::CO[$category];
    $concenDiff = $CO - self::CO[$category];

    echo $indexDiff . " = " . self::AQI[$category][1] . " - " . self::AQI[$category][0] . "<br>";
    echo $rangeDiff . " = " . self::CO[$category+1] . " - " . self::CO[$category] . "<br>";
    echo $concenDiff . " = " . $CO . " - " . self::CO[$category] . "<br>";

    $result = $indexDiff / $rangeDiff * $concenDiff + self::AQI[$category][0];
    echo $result . " = " . $indexDiff . " / " . $rangeDiff . " * " . $concenDiff . " + " . self::AQI[$category][0] . "<br>";

    return $result;
  }

  static public function calcul_SO2 ($SO2) {
    // $SO2 = 900;
    if ($SO2 < 0 || 105 <= $SO2) {
      echo "NOT IN RANGE";
      return -10;
    }
    for ($category = 0; $category < count(self::SO2); $category++) {
      if ($SO2 < self::SO2[$category]){
        $category--;
        break;
      }
    }


    $indexDiff = self::AQI[$category][1] - self::AQI[$category][0];
    $rangeDiff = self::SO2[$category+1] -  self::SO2[$category];
    $concenDiff = $SO2 - self::SO2[$category];

    echo $indexDiff . " = " . self::AQI[$category][1] . " - " . self::AQI[$category][0] . "<br>";
    echo $rangeDiff . " = " . self::SO2[$category+1] . " - " . self::SO2[$category] . "<br>";
    echo $concenDiff . " = " . $SO2 . " - " . self::SO2[$category] . "<br>";

    $result = $indexDiff / $rangeDiff * $concenDiff + self::AQI[$category][0];
    echo $result . " = " . $indexDiff . " / " . $rangeDiff . " * " . $concenDiff . " + " . self::AQI[$category][0] . "<br>";

    return $result;
  }

  static public function calcul_NO2 ($NO2) {
    // $NO2 = 2049;
    if ($NO2 < 0 || 2050 <= $NO2) {
      echo "NOT IN RANGE";
      return -10;
    }
    for ($category = 0; $category < count(self::NO2); $category++) {
      if ($NO2 < self::NO2[$category]){
        $category--;
        break;
      }
    }


    $indexDiff = self::AQI[$category][1] - self::AQI[$category][0];
    $rangeDiff = self::NO2[$category+1] -  self::NO2[$category];
    $concenDiff = $NO2 - self::NO2[$category];

    echo $indexDiff . " = " . self::AQI[$category][1] . " - " . self::AQI[$category][0] . "<br>";
    echo $rangeDiff . " = " . self::NO2[$category+1] . " - " . self::NO2[$category] . "<br>";
    echo $concenDiff . " = " . $NO2 . " - " . self::NO2[$category] . "<br>";

    $result = $indexDiff / $rangeDiff * $concenDiff + self::AQI[$category][0];
    echo $result . " = " . $indexDiff . " / " . $rangeDiff . " * " . $concenDiff . " + " . self::AQI[$category][0] . "<br>";

    return $result;
  }

  static public function calcul_O3_1 ($O3_1) {
    // $O3_1 = 250;
    if ($O3_1 < 0 || 201 <= $O3_1) {
      echo "NOT IN RANGE";
      return -10;
    }
    for ($category = 0; $category < count(self::O3_1); $category++) {
      if ($O3_1 < self::O3_1[$category]){
        $category--;
        break;
      }
    }


    $indexDiff = self::AQI[$category][1] - self::AQI[$category][0];
    $rangeDiff = self::O3_1[$category+1] -  self::O3_1[$category];
    $concenDiff = $O3_1 - self::O3_1[$category];

    echo $indexDiff . " = " . self::AQI[$category][1] . " - " . self::AQI[$category][0] . "<br>";
    echo $rangeDiff . " = " . self::O3_1[$category+1] . " - " . self::O3_1[$category] . "<br>";
    echo $concenDiff . " = " . $O3_1 . " - " . self::O3_1[$category] . "<br>";

    $result = $indexDiff / $rangeDiff * $concenDiff + self::AQI[$category][0];
    echo $result . " = " . $indexDiff . " / " . $rangeDiff . " * " . $concenDiff . " + " . self::AQI[$category][0] . "<br>";

    return $result;
  }

  static public function calcul_O3_2 ($O3_2) {
    // $O3_2 = 604;
    if ($O3_2 < 125 || 605 <= $O3_2) {
      echo "NOT IN RANGE";
      return -10;
    }
    for ($category = 0; $category < count(self::O3_2); $category++) {
      if ($O3_2 < self::O3_2[$category]){
        $category--;
        break;
      }
    }


    $indexDiff = self::AQI[$category][1] - self::AQI[$category][0];
    $rangeDiff = self::O3_2[$category+1] -  self::O3_2[$category];
    $concenDiff = $O3_2 - self::O3_2[$category];

    echo $indexDiff . " = " . self::AQI[$category][1] . " - " . self::AQI[$category][0] . "<br>";
    echo $rangeDiff . " = " . self::O3_2[$category+1] . " - " . self::O3_2[$category] . "<br>";
    echo $concenDiff . " = " . $O3_2 . " - " . self::O3_2[$category] . "<br>";

    $result = $indexDiff / $rangeDiff * $concenDiff + self::AQI[$category][0];
    echo $result . " = " . $indexDiff . " / " . $rangeDiff . " * " . $concenDiff . " + " . self::AQI[$category][0] . "<br>";

    return $result;
  }
}
