<?php
// Routes

//$app->get('/', 'App\Controller\HomeController:dispatch')
//    ->setName('homepage');

$app->get('/post/{id}', 'App\Controller\HomeController:viewPost')
    ->setName('view_post');

$app->get('/', 'App\Controller\HomeController:main')
    ->setName('main_page');
$app->post('/', 'App\Controller\HomeController:main')
    ->setName('main_page');
// $app->post('/main_after', 'App\Controller\HomeController:main_after')
//     ->setName('main_after');
// $app->get('/main_after', 'App\Controller\HomeController:main_after')
//     ->setName('main_after');


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

$app->get('/pw_new', 'App\Controller\HomeController:pw_new')
    ->setName('pw_new');
$app->post('/pw_newHandler', 'App\Controller\SetNewPassword:pw_newHandler')
    ->setName('pw_newHandler');

$app->get('/delete_id_check', 'App\Controller\HomeController:delete_id_check')
    ->setName('delete_id_check');
$app->post('/delete_id_checkHandler', 'App\Controller\IDCancellation:delete_id_checkHandler')
    ->setName('delete_id_checkHandler');

$app->get('/intro_teama', 'App\Controller\HomeController:intro_teama')
    ->setName('intro_teama');

$app->get('/air_map', 'App\Controller\HomeController:air_map')
    ->setName('air_map');

$app->get('/air_chart', 'App\Controller\HomeController:air_chart')
    ->setName('air_chart');




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
