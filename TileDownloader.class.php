<?php

class TileDownloader {

	private $tileService;
	private $destPath;
	
	private $minZoom = 0;
	private $maxZoom = 0;
	
	private $BBox = array();
	
	private $minLng = 0;
	private $maxLng = 0;
	
	private $minLat = 0;
	private $maxLat = 0;
	
	public $downImageCount = 0;

	public function Download() {
		$destImgPath = "";
		$destImgName = "";
		$tileServiceImg = "";
		
		//Foreach Zoom Level
		foreach (range($this->minZoom, $this->maxZoom) as $z)
		{
			//Convert Min Lat and Max Lat to tile TileY Values
			$minY = $this->latToTileY($this->minLat, $z);
			$maxY = $this->latToTileY($this->maxLat, $z);
			
			//Foreach Tile Column
			foreach (range($minY, $maxY) as $y)
			{
				//Convert Min Lng and Max Lng to tile TileX Values
				$minX=$this->lonToTileX($this->minLng, $z);
				$maxX=$this->lonToTileX($this->maxLng, $z);
				
				//Foreach Tile Row
				foreach (range($minX, $maxX) as $x)
				{
					$destImgPath = $this->getDestPath() . $z . "/" . $x . "/";
					$destImgName = $destImgPath . $y . ".png";
					$tileServiceImg = $this->getTileService($z,$x,$y);
					
					//Check if image exist.
					if($checkFile=file_exists($destImgName))
					{
						continue;
					}
					
					if($this->get_http_response_code($tileServiceImg) == "200")
					{
						if(!is_dir($destImgPath))
						{
							 mkdir($destImgPath,0777,true);
						}
						
						if($imgData = file_get_contents($tileServiceImg))
						{
							if(!file_put_contents($destImgName,$imgData))
							{
								return;
							}
						}
						
						$this->downImageCount++;
					}
				}
			}
		}
	}
	
	private function get_http_response_code($url) {
		$headers = get_headers($url);
		return substr($headers[0], 9, 3);
	}
	
	public function getTileService($z,$x,$y) {
		$url=$this->tileService;
		$has_matches = preg_match( '/{switch:(.*?)}/', $url, $domains );
		if ( ! $has_matches ) {
			$domains = [ '', '' ];
		}
		$domains = explode( ',', $domains[1] );
		$url     = preg_replace( '/{switch:(.*?)}/', '{s}', $url );
		$url     = preg_replace( '/{s}/', $domains[ array_rand( $domains ) ], $url );
		$url     = preg_replace( '/{z}/', $z, $url );
		$url     = preg_replace( '/{x}/', $x, $url );
		$url     = preg_replace( '/{y}/', $y, $url );
		return $url;
	}

	public function setTileService($tileService) {
		
		$this->tileService = $tileService;
	}

	public function getDestPath() {
		return $this->destPath;
	}

	public function setDestPath($destPath) {
		$this->destPath = $destPath;
	}		

	public function getMinZoom() {
		return $this->minZoom;
	}

	public function setMinZoom($minZoom) {
		$this->minZoom = $minZoom;
	}

	public function getMaxZoom() {
		return $this->maxZoom;
	}

	public function setMaxZoom($maxZoom) {
		$this->maxZoom = $maxZoom;
	}

	public function getBBox()
	{
		return $this->BBox;
	}
	
	public function setBBox($bbox)
	{
		$TMP        = explode( ',', $bbox );
		$BBOX['left']=$TMP[0];
		$BBOX['right']=$TMP[2];
		$BBOX['top']=$TMP[3];
		$BBOX['bottom']=$TMP[1];
		
		$this->setMinLat($BBOX['top']);
		$this->setMaxLat($BBOX['bottom']);
		$this->setMinLng($BBOX['left']);
		$this->setMaxLng($BBOX['right']);
		
		$this->BBox=$BBOX;
	}
	
	public function getMinLng() {
		return $this->minLng;
	}

	public function setMinLng($minLng) {
		$this->minLng = $minLng;
	}

	public function getMaxLng() {
		return $this->maxLng;
	}

	public function setMaxLng($maxLng) {
		$this->maxLng = $maxLng;
	}
	
	public function getMinLat() {
		return $this->minLat;
	}

	public function setMinLat($minLat) {
		$this->minLat = $minLat;
	}

	public function getMaxLat() {
		return $this->maxLat;
	}

	public function setMaxLat($maxLat) {
		$this->maxLat = $maxLat;
	}
	
	private function degTorad($deg)
	{
		return $deg * M_PI / 180;
	}
	
    private function lonToTileX($lon, $zoom)
	{
		return floor((($lon + 180) / 360) * pow(2, $zoom));
	}
	
    private function latToTileY($lat, $zoom)
	{
		return floor((1 - log(tan($this->degTorad($lat)) + 1 / cos($this->degTorad($lat))) / M_PI) / 2 * pow(2, $zoom));
	}
}

?>
