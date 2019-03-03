# PHP-TileDownloader
PHP Map Tile Downloader supports Tile Map Services(TMS) like OpenStreetMap services. It downloads all tiles of the world for defined zoom levels.

It is a work in progress, any help to improve this would be awesome.

## Usage:

**Configuration:**
Multiple map services can be defined at the same time by simple copy and pasting the section (as below) but changing each subsequent configuration array set using ```$CONFIG[0] $CONFIG[1]``` and so on.
````
$CONFIG[0]["tileServiceName"]="OpenStreetMaps";
$CONFIG[0]["tileService"]="https://{switch:a,b,c}.tile.openstreetmap.org/{z}/{x}/{y}.png";
$CONFIG[0]["destPath"]="tiles/osm/";
$CONFIG[0]["zoomStart"]="1";
$CONFIG[0]["zoomEnd"]="12";
$CONFIG[0]["BBox"]="151.901436,-29.140467,152.131119,-28.961290"; //Format SouthWestLng,SouthWestLat,NorthEastLng,NorthEastLat (Left,Bottom,Right,Top)
````
**1. ```$CONFIG[0]["tileServiceName"]```**
 
This is the friendly name for the tile service ie. OopenStreetMaps, Six-Maps etc

**2. ```$CONFIG[0]["tileService"]```**

This is the tile service URL in the following format ```https://{switch:a,b,c}.tile.openstreetmap.org/{z}/{x}/{y}.png``` where ```{switch:a,b,c}``` is used for multiple sub-domains, ```{z}``` is the current zoom value, ```{x}``` is the TileX value and ```{y}``` is the TileY value.

**3. ```$CONFIG[0]["destPath"]```**
 
This is the absolute or relative folder path for storing the tile images. This directory must be writable and able to create sub-directories as needed.

**4. ```$CONFIG[0]["zoomStart"]```**

The Start Zoom Lavel to start capturing tiles at.

**5. ```$CONFIG[0]["zoomEnd"]```**

The End Zoom Lavel to finish capturing tiles at.

**6. ```$CONFIG[0]["BBox"]```**

This is the Bounding Box for the area in which to download tiles within. The Format is SouthWestLng,SouthWestLat,NorthEastLng,NorthEastLat (Left,Bottom,Right,Top)

An Easier way to define a bounding box is to go to <a href="http://bboxfinder.com" target="_blank">BBox Finder</a> and draw a rectangle in the area you wish to capture tiles, make sure the coordinate format is in Lng / Lat then copy the box value at the bottom. you can then paste them into the ```$CONFIG[0]["BBox"]``` value.

**Output File Naming**
Output files will be stored under the ```$CONFIG[0]["destPath"]``` you specified. Output Example from the above configuration would be:  ```tiles/osm/{z}/{x}/{y}.png```
