<?php
set_time_limit(0); //Allow Script to Execute for as long as it needs
ini_set('display_errors', TRUE); //enabled for debugging
error_reporting(E_ALL ^ E_NOTICE); //Omit Notices but show all other errors

require_once("TileDownloader.class.php"); //Require Main class
$td=new TileDownloader(); //Instantiate main class

//Tile Service Config
$CONFIG[0]["tileServiceName"]="Six Aerial"; //Friendly Tile Service Name
$CONFIG[0]["tileService"]="http://maps.six.nsw.gov.au/arcgis/rest/services/public/NSW_Imagery/MapServer/tile"; //Tile Service URL without trailing slash /
$CONFIG[0]["fileExtension"]="";
$CONFIG[0]["destPath"]="tiles/six-aerial"; // local storage directory Without trailing slash /
$CONFIG[0]["zoomStart"]="10"; //Start Zoom Level
$CONFIG[0]["zoomEnd"]="11"; //End Zoom Level
$CONFIG[0]["MinLng"]="151.36617122624398"; //SouthWest Lng
$CONFIG[0]["MaxLng"]="152.6313316236804"; //NorthWest Lng
$CONFIG[0]["MinLat"]="-28.249257948470415"; //NorthEast Lat
$CONFIG[0]["MaxLat"]="-29.426018708820767"; //SouthWest Lat

######################### DONT MODIFY BELOW THIS LINE ##############################
foreach($CONFIG as $key=>$val)
{
	echo("Downloading Tiles for ".$val['tileServiceName']." (Zoom Levels ".$val["zoomStart"]." - ".$val["zoomEnd"].")<br>");

	$td->setTileService($val["tileService"]); //Set Service URL
	$td->setDestPath($val["destPath"]); //Set Destination Path
	$td->setMinZoom($val["zoomStart"]); //Set Zoom Start
	$td->setMaxZoom($val["zoomEnd"]); //Set Zoom End
	$td->setMinLng($val["MinLng"]); //Set Min Longitude
	$td->setMaxLng($val["MaxLng"]); //Set Max Longitude
	$td->setMinLat($val["MinLat"]); //Set Min Latitude
	$td->setMaxLat($val["MaxLat"]); //Set Max Latitude
	$time_start = microtime(true); 
	$td->Download($val['fileExtension']); //Start Map Downloading
	$time_end = microtime(true);
	$execTime = formatSeconds($time_end - $time_start);
	echo("Downloaded ".$td->downImageCount." tile(s) in $execTime<br>###############################################<br>");
	//Reset Tile Download Counter
	$td->downImageCount=0; //Reset Tile Download Count
}

function formatSeconds($iSeconds) {
    $aNiceTime      = array('miliseconds' => 0, 'seconds' => 0, 'minutes' => 0, 'hours' => 0);
    $iDifference    = $iSeconds * 1000;
 
    $aPeriods       = array(
        'hours'         => 60*60*1000,
        'minutes'       => 60*1000,
        'seconds'       => 1000,
        'miliseconds'   => 1
    );
 
    foreach ($aPeriods as $sPeriod => $iMiliseconds) {
        $aNiceTime[$sPeriod]    = floor($iDifference / $iMiliseconds);
        $iDifference            = $iDifference % $iMiliseconds;
    }
 
    return sprintf(
        '%02d hours, %02d minutes, %02d.%03d seconds',
        $aNiceTime['hours'],
        $aNiceTime['minutes'],
        $aNiceTime['seconds'],
        $aNiceTime['miliseconds']
    );
}
?>
