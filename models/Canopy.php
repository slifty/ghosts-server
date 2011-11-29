<?php
require_once("DBConn.php");
require_once("CanopyFactory.php");
require_once("PortalFactory.php");

class Canopy {
	# Instance Variables
	private $canopyID = 0;
	private $lat = 0;
	private $lng = 0;
	private $name = "";
	private $description = "";
	private $imgTempPath = "";
	private $imgPath = "";
	private $qrPath = "";
	private $dateBased = 0;
	private $dateCreated = 0;
	
	public function __construct($needID = -1) {
		$this->init($needID);
	}
	
	# Data Methods
	public function load($dataArray) {
		$this->canopyID = isset($dataArray["canopyID"])?$dataArray["canopyID"]:0;
		$this->lat = isset($dataArray["lat"])?$dataArray["lat"]:"";
		$this->lng = isset($dataArray["lng"])?$dataArray["lng"]:0;
		$this->name = isset($dataArray["name"])?$dataArray["name"]:0;
		$this->description = isset($dataArray["description"])?$dataArray["description"]:0;
		$this->imgPath = isset($dataArray["imgPath"])?$dataArray["imgPath"]:0;
		$this->qrPath = isset($dataArray["qrPath"])?$dataArray["qrPath"]:0;
		$this->dateBased = isset($dataArray["dateBased"])?$dataArray["dateBased"]:0;
		$this->dateCreated = isset($dataArray["dateCreated"])?$dataArray["dateCreated"]:0;
	}
	
	public function init($needID = -1) {
	}
	
	public function validate() {
		return true;
	}
	
	public function save() {
		if(!$this->validate())
			return false;
		$mysqli = DBConn::mysqli_connect();
		if($this->getCanopyID() == 0) {
			// Insert a new canopy entry
			$queryString = "insert into canopies
								   (canopyID,
									lat,
									lng,
									name,
									description,
									imgPath,
									qrPath,
									dateBased,
									dateCreated)
							values (NULL,
									".$this->getLat().",
									".$this->getLng().",
									'".DBConn::clean($this->getName())."',
									'".DBConn::clean($this->getDescription())."',
									'',
									'',
									".$this->getDateBased().",
									NOW())";
			$mysqli->query($queryString)
			or print($mysqli->error);
			$this->canopyID = $mysqli->insert_id;
			
			if(!$mysqli->insert_id)
				return false;
			
			// Move the canopy image
			global $CANOPY_IMG_PATH;
			$imgName = $this->getCanopyID().".jpg";
			$imgPath = $CANOPY_IMG_PATH."/".$imgName;
			if(move_uploaded_file ( $this->getImgTempPath() , $imgPath ))
				$this->imgPath = $imgPath;
			$this->setImgTempPath("");
			$this->save();
			
		} else {
			// Update an existing canopy
			$queryString = "update canopies
							   set lat = ".$this->getLat().",
								   lng = ".$this->getLng().",
								   name = '".DBConn::clean($this->getName())."',
								   description = '".DBConn::clean($this->getDescription())."',
								   imgPath = '".DBConn::clean($this->getImgPath())."',
								   qrPath = '".DBConn::clean($this->getQRPath())."',
								   dateBased = ".$this->getDateBased()."
							 where canopies.canopyID = ".$this->getCanopyID();
			$mysqli->query($queryString)
			or print($mysqli->error);
		}
		
		return true;
	}
	
	public function delete() {
	}
	
	
	#Getters
	public function getCanopyID() { return $this->canopyID; }
	
	public function getLat() { return $this->lat; }
	
	public function getLng() { return $this->lng; }
	
	public function getName() { return $this->name; }

	public function getDescription() { return $this->description; }
	
	public function getImgTempPath() { return $this->imgTempPath; }
	
	public function getImgPath() { return $this->imgPath; }
	
	public function getQRPath() { return $this->qrPath; }
	
	public function getDateBased() { return $this->dateBased; }
	
	public function getDateCreated() { return $this->dateCreated; }
	
	public function getPortals() {
		$mysqli = DBConn::mysqli_connect();
		$portalList = array();
		
		$queryString = "select portals.portalID as portalID
						  from portals
						 where portals.canopyID = ".$this->getCanopyID();
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
			
		$portalIDs = array();
		while($resultArray = $result->fetch_assoc())
			$portalIDs[] = $resultArray['portalID'];
		
		return PortalFactory::getObjects($portalIDs);
	}
	
	#Setters
	public function setLat($dec) { $this->lat = $dec; }
	
	public function setLng($dec) { $this->lng = $dec; }
	
	public function setName($str) { $this->name = $str; }
	
	public function setDescription($str) { $this->description = $str; }

	public function setImgTempPath($str) { $this->imgTempPath = $str; }
	
	public function setDateBased($timestamp) { $this->dateStart = $timestamp; }
	
	
	#Static Methods
	public static function getCanopies() {
		$mysqli = DBConn::mysqli_connect();
		$queryString = "select canopies.canopyID as canopyID
						  from canopies
					  order by canopies.dateCreated desc";
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
			
		$canopyIDs = array();
		while($resultArray = $result->fetch_assoc())
			$canopyIDs[] = $resultArray['canopyID'];
			
		return CanopyFactory::getObjects($canopyIDs);
	}
}

?>