<?php

if(!isset($_REQUEST['cmd'])){
  echo '{"result": 0, "message": "Command unknown"}';
  return;
}

$cmd = $_REQUEST['cmd'];

switch ($cmd) {
  case 1:
    signUp();
    break;

  case 2:
    loginUser();
    break;

  case 3:
    signOut();
    break;

  case 4:
    loginUserSession();
    break;

  case 5:
    getUserDetailsById();
    break;

  case 6:
    getArtisanBySkill();
    break;

  case 7:
    getArtisansByCommunity();
    break;

  case 8:
    becomeArtisan();
    break;

  default:
    echo '{"result": 0, "message": "Command unknown"}';
    return;
    break;
}

function signUp(){}

function loginUser(){}

function signOut(){}

function loginUserSession(){}

function getUserDetailsById(){}

function getArtisanBySkill(){}

function getArtisansByCommunity(){}

function becomeArtisan(){}


 ?>
