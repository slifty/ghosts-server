<?php
require_once("DBConn.php");

class Portal {
	# Instance Variables
	private $portalID = 0;
	private $canopyID = 0;
	private $offX = 0;
	private $offY = 0;
	private $name = "";
	private $description = "";
	private $caption = "";
	private $imgTempPath = "";
	private $imgPath = "";
	private $dateBased = 0;
	private $dateCreated = 0;
	
	public function __construct($needID = -1) {
		$this->init($needID);
	}
	
	# Data Methods
	public function load($dataArray) {
		$this->portalID = isset($dataArray["portalID"])?$dataArray["portalID"]:0;
		$this->canopyID = isset($dataArray["canopyID"])?$dataArray["canopyID"]:0;
		$this->offX = isset($dataArray["offX"])?$dataArray["offX"]:"";
		$this->offY = isset($dataArray["offY"])?$dataArray["offY"]:0;
		$this->name = isset($dataArray["name"])?$dataArray["name"]:0;
		$this->description = isset($dataArray["description"])?$dataArray["description"]:"";
		$this->caption = isset($dataArray["caption"])?$dataArray["caption"]:"";
		$this->imgPath = isset($dataArray["imgPath"])?$dataArray["imgPath"]:0;
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
		if($this->getPortalID() == 0) {
			// Insert a new portal entry
			$queryString = "insert into portals
								   (portalID,
									canopyID,
									offX,
									offY,
									name,
									description,
									caption,
									imgPath,
									dateBased,
									dateCreated)
							values (NULL,
									".$this->getCanopyID().",
									".$this->getOffX().",
									".$this->getOffY().",
									'".DBConn::clean($this->getName())."',
									'".DBConn::clean($this->getDescription())."',
									'".DBConn::clean($this->getCaption())."',
									'',
									".$this->getDateBased().",
									NOW())";
			$mysqli->query($queryString)
			or print($mysqli->error);
			
			if(!$mysqli->insert_id)
				return false;
				
			$this->portalID = $mysqli->insert_id;			
		} else {
			// Update an existing portal
			$queryString = "update portals
							   set offX = ".$this->getOffX().",
								   offY = ".$this->getOffY().",
								   name = '".DBConn::clean($this->getName())."',
								   description = '".DBConn::clean($this->getDescription())."',
								   caption = '".DBConn::clean($this->getCaption())."',
								   imgPath = '".DBConn::clean($this->getImgPath())."',
								   dateBased = ".$this->getDateBased()."
							 where portals.portalID = ".$this->getPortalID();
			$mysqli->query($queryString)
			or print($mysqli->error);
		}
		
		if( $this->getImgTempPath() != "" ) {
			// Move the portal image
			global $PORTAL_IMG_PATH;
			$imgName = $this->getPortalID().".jpg";
			$imgPath = $PORTAL_IMG_PATH."/".$imgName;
			
			if(move_uploaded_file ( $this->getImgTempPath() , $imgPath ))
				$this->imgPath = $imgPath;
			$this->setImgTempPath("");
			$this->save();
		}
		
		return true;
	}
	
	public function delete() {
	}
	
	
	#Getters
	public function getPortalID() { return $this->portalID; }
	
	public function getCanopyID() { return $this->canopyID; }
	
	public function getOffX() { return $this->offX; }
	
	public function getOffY() { return $this->offY; }
	
	public function getName() { return $this->name; }

	public function getDescription() { return $this->description; }
	
	public function getCaption() { return $this->caption; }
	
	public function getImgTempPath() { return $this->imgTempPath; }
	
	public function getImgPath() { return $this->imgPath; }
	
	public function getDateBased() { return $this->dateBased; }
	
	public function getDateCreated() { return $this->dateCreated; }
	
	
	#Setters
	public function setCanopyID($int) { $this->canopyID = $int; }

	public function setOffX($int) { $this->offX = $int; }
	
	public function setOffY($int) { $this->offY = $int; }
	
	public function setName($str) { $this->name = $str; }
	
	public function setDescription($str) { $this->description = $str; }
	
	public function setCaption($str) { $this->caption = $str; }

	public function setImgTempPath($str) { $this->imgTempPath = $str; }
	
	public function setDateBased($timestamp) { $this->dateStart = $timestamp; }
	
}

?>