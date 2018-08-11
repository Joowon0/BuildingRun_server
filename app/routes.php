<?php
// Routes
// user management
$app->get('/', 'App\Controller\HomeController:main')
    ->setName('main_page');
$app->post('/', 'App\Controller\HomeController:main')
    ->setName('main_page');

$app->get('/login', 'App\Controller\HomeController:login')
    ->setName('login');
$app->post('/loginHandler', 'App\Controller\LoginController:loginHandler')
    ->setName('loginHandler');

$app->get('/register', 'App\Controller\HomeController:register')
    ->setName('register');
$app->post('/registerHandler', 'App\Controller\SignupController:registerHandler')
    ->setName('registerHandler');

$app->get('/forgot_pw', 'App\Controller\HomeController:forgot_pw')
    ->setName('forgot_pw');
$app->post('/forgot_pwHandler', 'App\Controller\forgottenPWController:forgot_pwHandler')
    ->setName('forgot_pwHandler');

$app->get('/signoutHandler', 'App\Controller\HomeController:signoutHandler')
    ->setName('signoutHandler');

$app->get('/accountActivation/{id}', 'App\Controller\SignupController:accountActivation')
    ->setName('accountActivation');

$app->get('/user_change', 'App\Controller\HomeController:user_change')
    ->setName('user_change');

$app->get('/pw_check', 'App\Controller\HomeController:pw_check')
    ->setName('pw_check');
$app->post('/pw_checkHandler', 'App\Controller\SetNewPassword:pw_checkHandler')
    ->setName('pw_checkHandler');
// TODO : Pass data through session to make it only get
$app->get('/pw_checkHandler', 'App\Controller\SetNewPassword:pw_checkHandler')
    ->setName('pw_checkHandler');

$app->get('/pw_new', 'App\Controller\HomeController:pw_new')
    ->setName('pw_new');
$app->post('/pw_newHandler', 'App\Controller\SetNewPassword:pw_newHandler')
    ->setName('pw_newHandler');
// TODO : Pass data through session to make it only get
$app->get('/pw_newHandler', 'App\Controller\SetNewPassword:pw_newHandler')
    ->setName('pw_newHandler');

$app->get('/delete_id_check', 'App\Controller\HomeController:delete_id_check')
    ->setName('delete_id_check');
$app->post('/delete_id_checkHandler', 'App\Controller\IDCancellation:delete_id_checkHandler')
    ->setName('delete_id_checkHandler');
// TODO : Pass data through session to make it only get
$app->get('/delete_id_checkHandler', 'App\Controller\IDCancellation:delete_id_checkHandler')
    ->setName('delete_id_checkHandler');

// sensor management
$app->post('/app/sensorRegister', 'App\Controller\SensorController:app_sensorRegister')
    ->setName('app_sensorRegister');
$app->post('/app/sensorDeregister', 'App\Controller\SensorController:app_sensorDeregister')
    ->setName('app_sensorDeregister');
$app->post('/app/sensorListView', 'App\Controller\SensorController:app_sensorListView')
    ->setName('app_sensorListView');

$app->get('/sensorDeregister', 'App\Controller\SensorController:sensorDeregister')
    ->setName('sensorDeregister');

// chart JSON data (air)
$app->get('/getJSON/airReal', 'App\Controller\ChartController:airRealTime')
    ->setName('getJSONairReal');
$app->get('/getJSON/air10Min', 'App\Controller\ChartController:air10Min')
    ->setName('getJSONair10Min');
$app->get('/getJSON/airHour', 'App\Controller\ChartController:airHour')
    ->setName('getJSONairHour');

// chart JSON data (heart)
$app->get('/getJSON/heartReal', 'App\Controller\ChartController:heartRateReal')
    ->setName('getJSONheartRateReal');
$app->get('/getJSON/heart10Min', 'App\Controller\ChartController:heartRate10Min')
    ->setName('getJSONheartRate10Min');
$app->get('/getJSON/heartHour', 'App\Controller\ChartController:heartRateHour')
    ->setName('getJSONheartRateHour');

// chart (air)
$app->get('/air_chart', 'App\Controller\HomeController:air_chart')
    ->setName('air_chart');
$app->get('/air_chart10min', 'App\Controller\HomeController:air_chart10min')
    ->setName('airair_chart10min_chart');
$app->get('/air_chartHour', 'App\Controller\HomeController:air_chartHour')
    ->setName('air_chartHour');

// chart (heart)
$app->get('/heart', 'App\Controller\HomeController:heart')
    ->setName('heart');

// sensor list view
$app->get('/sensor_list', 'App\Controller\SensorListController:sensor_list')
    ->setName('sensor_list');

$app->get('/sensor_json', 'App\Controller\SensorListController:sensor_json')
    ->setName('sensor_json');


//map

$app->get('/mapjson', 'App\Controller\MapsController:mapjson')
    ->setName('mapjson');

$app->get('/air_map', 'App\Controller\MapsController:air_map')
    ->setName('air_map');








// for APP
$app->post('/app/register', 'App\Controller\SignupController:app_signup')
    ->setName('app_register');
$app->post('/app/login', 'App\Controller\LoginController:app_login')
    ->setName('app_login');
$app->post('/app/signout', 'App\Controller\HomeController:app_signout')
    ->setName('app_signout');
$app->post('/app/checkCurrentPW', 'App\Controller\SetNewPassword:app_checkPw')
    ->setName('app_checkCurrentPW');
$app->post('/app/setNewPW', 'App\Controller\SetNewPassword:app_setNewPW')
    ->setName('app_setNewPW');
$app->post('/app/forgotPW', 'App\Controller\forgottenPWController:app_forgotPW')
    ->setName('app_forgotPW');
$app->post('/app/accountCancel', 'App\Controller\IDCancellation:app_accountCancel')
    ->setName('app_accountCancel');
$app->post('/app/airQualityDataTransfer', 'App\Controller\DataController:app_airQualityDataTransfer')
    ->setName('app_airQualityDataTransfer');
$app->post('/app/heartDataTransfer', 'App\Controller\DataController:app_heartDataTransfer')
    ->setName('app_heartDataTransfer');

$app->post('/app/airQualityHistory', 'App\Controller\ChartController:app_airQualityHistory')
    ->setName('app_airQualityHistory');
$app->post('/app/heartHistory', 'App\Controller\ChartController:app_heartHistory')
    ->setName('app_heartHistory');

// show database for test
$app->get('/showDB/airQuality', 'App\Controller\DBTester:printAirQualityInfo')
    ->setName('showDB_airQuality');
$app->get('/showDB/building', 'App\Controller\DBTester:printBuilding')
    ->setName('showDB_building');
$app->get('/showDB/gps', 'App\Controller\DBTester:printGPS')
    ->setName('showDB_gps');
$app->get('/showDB/heartInfo', 'App\Controller\DBTester:printHeartInfo')
    ->setName('showDB_heartInfo');
$app->get('/showDB/inLoc', 'App\Controller\DBTester:printInLoc')
    ->setName('showDB_inLoc');
$app->get('/showDB/nonce', 'App\Controller\DBTester:printNonce')
    ->setName('showDB_nonce');
$app->get('/showDB/sensor', 'App\Controller\DBTester:printSensor')
    ->setName('showDB_sensor');
$app->get('/showDB/user', 'App\Controller\DBTester:printUser')
    ->setName('showDB_user');
