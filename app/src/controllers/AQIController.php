<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class AQIController extends BaseController {
  const AQI = array(array(0,50), array(51,100), array(101,150), array(151,200), array(201,300), array(301,400), array(401,500));

  const CO  = array(array(0.0,4.4), array(4.5,9.4), array(9.5,12.4), array(12.5,15.4), array(15.5,30.4), array(30.5,40.4), array(40.5,50.4));
  const SO2 = array(array(0,35), array(36,75), array(76,185), array(186,304), array(305,604), array(605,804), array(805,1004));
  const NO2 = array(array(0,53), array(54,100), array(101,360), array(361,649), array(650,1249), array(1250,1649), array(1650,2049));
  const O3  = array(array());
}
