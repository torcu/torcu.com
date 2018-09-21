<?php

/*

	Client ID			1022779640668-8rj5emnnrfkpg9ogvmujo3ms6a41m9l2.apps.googleusercontent.com
	Email address			1022779640668-8rj5emnnrfkpg9ogvmujo3ms6a41m9l2@developer.gserviceaccount.com
	Certificate fingerprints	7c646e53f332d3154f405a34e69a15a0521812c3

*/

require 'gapi.class.php';
define('ga_profile_id','77425084');

$ga = new gapi("1022779640668-8rj5emnnrfkpg9ogvmujo3ms6a41m9l2@developer.gserviceaccount.com", "key.p12");

$ga->requestReportData(ga_profile_id,array('browser','browserVersion'),array('pageviews','visits'));
?>
<table>
<tr>
  <th>Browser &amp; Browser Version</th>
  <th>Pageviews</th>
  <th>Visits</th>
</tr>
<?php
foreach($ga->getResults() as $result):
?>
<tr>
  <td><?php echo $result ?></td>
  <td><?php echo $result->getPageviews() ?></td>
  <td><?php echo $result->getVisits() ?></td>
</tr>
<?php
endforeach
?>
</table>

<table>
<tr>
  <th>Total Results</th>
  <td><?php echo $ga->getTotalResults() ?></td>
</tr>
<tr>
  <th>Total Pageviews</th>
  <td><?php echo $ga->getPageviews() ?>
</tr>
<tr>
  <th>Total Visits</th>
  <td><?php echo $ga->getVisits() ?></td>
</tr>
</table>
