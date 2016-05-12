<?php

define('ACCOUNT_SHORTNAME', "<account shortname right here>");
define('WIDGET_ACCESS_KEY', "<widget access key right here>");
define('WIDGET_SECRET_KEY', "<widget secret key right here>");
define('WIDGET_TYPE', "addtickets");
define('URL_BASE', "https://apps.ticketmatic.com/widgets/");

date_default_timezone_set("Europe/Amsterdam");

$eventid = 0;
$saleschannelid = 0;
$skinid = 1;
$returnurl = "http://www.ticketmatic.nl";

$parameters = [
	"event" => $eventid,
	"flow" => "basketwithcheckout",
	"saleschannelid" => $saleschannelid,
	"l" => "nl",
	"returnurl" => $returnurl,
	"skinid" => $skinid
	];

// sort parameters in alphabetical order
ksort($parameters);

// prepare signature payload
$payload_parameters = "";
$skip_in_payload = ["l"];
foreach($parameters as $key => $value) {
    if(in_array($key, $skip_in_payload)) {
        continue;
    }
    
    $payload_parameters .= $key . $parameters[$key];
}

$payload  = WIDGET_ACCESS_KEY . ACCOUNT_SHORTNAME . $payload_parameters;

// generate signature
$signature = hash_hmac("sha256", $payload, WIDGET_SECRET_KEY);

// add signature parameters
$parameters['accesskey'] = WIDGET_ACCESS_KEY;
$parameters['signature'] = $signature;	

// prepare widget url querystring 
$querystring = "";
foreach($parameters as $key => $value) {
	$querystring .= ($querystring === "") ? "?" : "&";
	$querystring .= $key . "=" . urlencode($value);
}

// create the full widget url
$url = URL_BASE . ACCOUNT_SHORTNAME . "/" . WIDGET_TYPE . $querystring;

echo "<a href=\"" . $url . "\" target=\"blank\">" . $url . "</a>";