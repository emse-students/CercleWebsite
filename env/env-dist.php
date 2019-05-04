<?php
/**
 * Created by PhpStorm.
 * User: douec
 * Date: 09/12/2018
 * Time: 17:21
 */
$_ENV['env_name'] = "dev";

//Parameter of the BDD
$_ENV['bdd'] = [];
//host is most of the time localhost
$_ENV['bdd']['host'] = 'localhost';
$_ENV['bdd']['bdd_name'] = 'bdd_name';
$_ENV['bdd']['login'] = 'login';
$_ENV['bdd']['pwd'] = 'password';

$_ENV['api']['secure'] = true;
$_ENV['api']['login'] = 'login';
$_ENV['api']['pwd'] = 'pwd';

//Cassification od the site
//You can use the CAS on your local machine by creating a virtual host with a name which is finished by "emse.fr"
//For exemple "my-cercle.emse.fr"
$_ENV['CAS'] = false;
//$_ENV['CAS'] = true;