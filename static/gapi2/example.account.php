<?php

/*

	Client ID					1022779640668-8rj5emnnrfkpg9ogvmujo3ms6a41m9l2.apps.googleusercontent.com
	Email address				1022779640668-8rj5emnnrfkpg9ogvmujo3ms6a41m9l2@developer.gserviceaccount.com
	Certificate fingerprints	7c646e53f332d3154f405a34e69a15a0521812c3

*/

require 'gapi.class.php';

$ga = new gapi("1022779640668-8rj5emnnrfkpg9ogvmujo3ms6a41m9l2@developer.gserviceaccount.com", "key.p12");

$ga->requestAccountData();

foreach($ga->getAccounts() as $result)
{
  echo $result . ' ' . $result->getId() . ' (' . $result->getProfileId() . ")<br />";
}
