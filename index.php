<?php
set_time_limit(0);
ini_set('display_errors', TRUE);
error_reporting(E_ALL ^ E_NOTICE);

require_once("TileDownloader.class.php");
$td=new TileDownloader();

//Tile Service Config
$CONFIG[0]["tileServiceName"]="Six Aerial";
$CONFIG[0]["tileService"]="http://maps.six.nsw.gov.au/arcgis/rest/services/public/NSW_Imagery/MapServer/tile/{z}/{y}/{x}";
$CONFIG[0]["destPath"]="tiles/six-aerial/"; //Include Trailing slash /
$CONFIG[0]["zoomStart"]="1";
$CONFIG[0]["zoomEnd"]="10";
$CONFIG[0]["BBox"]="151.901436,-29.140467,152.131119,-28.961290"; //Format SouthWestLng,SouthWestLat,NorthEastLng,NorthEastLat (Left,Bottom,Right,Top)

$CONFIG[1]["tileServiceName"]="OSM";
$CONFIG[1]["tileService"]="https://{switch:a,b,c}.tile.openstreetmap.org/{z}/{x}/{y}.png";
$CONFIG[1]["destPath"]="tiles/osm/"; //Include Trailing slash /
$CONFIG[1]["zoomStart"]="1";
$CONFIG[1]["zoomEnd"]="12";
$CONFIG[1]["BBox"]="151.901436,-29.140467,152.131119,-28.961290"; //Format SouthWestLng,SouthWestLat,NorthEastLng,NorthEastLat (Left,Bottom,Right,Top)


######################### DONT MODIFY BELOW THIS LINE ##############################
foreach($CONFIG as $key=>$val)
{
	echo("Downloading Tiles for ".$val['tileServiceName']." (Zoom Levels ".$val["zoomStart"]." - ".$val["zoomEnd"].")<br>");

	$td->setTileService($val["tileService"]);
	$td->setDestPath($val["destPath"]);
	$td->setMinZoom($val["zoomStart"]);
	$td->setMaxZoom($val["zoomEnd"]);
	$td->setBBox($val["BBox"]);
	$time_start = microtime(true); 
	$td->Download();
	$time_end = microtime(true);
	$execTime = formatSeconds($time_end - $time_start);
	echo("Downloaded ".$td->downImageCount." tile(s) in $execTime<br>###############################################<br>");
	//Reset Tile Download Counter
	$td->downImageCount=0;
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
