<?php

require dirname(__FILE__).'/php-sdk/src/facebook.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$googleBaseURL="http://maps.googleapis.com/maps/api/staticmap?size=640x640&sensor=true";

$facebook = new Facebook(array(
  'appId'  => '267947846583709',
  'secret' => '2bf606b5cd75c2a2be44f19a9ef2fb04',
));


$access_token="AAADzsng2xZA0BAMUSqX4cabnlYmEJPpBrO5lUbU7MawCNIAigXFtJ5fdILvTuw4oMWVFStbCuZBvFfygnp1rK3YjS2YgTeQuALtOso7QZDZD";
 
$fql = "SELECT current_location from user where uid IN (SELECT uid2 FROM friend WHERE uid1=me())";

$response = $facebook->api(array(
'access_token'=>$access_token,
'method' => 'fql.query',
'query' =>$fql,
));
 
$locationIDArray = array();

foreach ($response as $cur)
{
    if($cur[current_location][id])
    {
        if(array_search($cur[current_location][id], $locationIDArray)===FALSE)
        {
            $locationIDArray[]=$cur[current_location][id];
        }
        
    }
}

$location_list=implode(",", $locationIDArray);


$fql = "SELECT latitude,longitude from place where page_id IN (".$location_list.")";


$response = $facebook->api(array(
'access_token'=>$access_token,
'method' => 'fql.query',
'query' =>$fql,
));


var_export($response);
 

foreach ($response as $key => $value) {
    
    if($value[latitude]&&$value[longitude])
    {
       
        $googleBaseURL.="&markers=".round($value[latitude],2).",".round($value[longitude],2);
    }
    
}


echo strlen($googleBaseURL);
echo $googleBaseURL;






?>
