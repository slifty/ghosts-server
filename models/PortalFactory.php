<?php
require_once("DBConn.php");
require_once("Portal.php");
class PortalFactory {
	public static function getObject($objectID) {
		$objectID = (int)$objectID;
		$mysqli = DBConn::mysqli_connect();
		$dataArray = array();
		
		// Load the data
		$queryString = "select portals.portalID as portalID,
							   portals.canopyID as canopyID,
							   portals.offX as offX,
							   portals.offY as offY,
							   portals.name as name,
							   portals.description as description,
							   portals.caption as caption,
							   portals.imgPath as imgPath,
							   unix_timestamp(portals.dateBased) as dateBased,
							   unix_timestamp(portals.dateCreated) as dateCreated
						  from portals
						 where portals.portalID = ".$objectID;
		
		$result = $mysqli->query($queryString);
		if($result->num_rows == 0) {
			$result->free();
			return new Portal();
		}
		$resultArray = $result->fetch_assoc();
		$result->free();
		
		$dataArray['portalID'] = $resultArray['portalID'];
		$dataArray['canopyID'] = $resultArray['canopyID'];
		$dataArray['offX'] = $resultArray['offX'];
		$dataArray['offY'] = $resultArray['offY'];
		$dataArray['name'] = $resultArray['name'];
		$dataArray['description'] = $resultArray['description'];
		$dataArray['caption'] = $resultArray['caption'];
		$dataArray['imgPath'] = $resultArray['imgPath'];
		$dataArray['dateBased'] = $resultArray['dateBased'];
		
		$newObject = new Portal();
		$newObject->load($dataArray);
		return $newObject;
	}
	
	public static function getObjects($objects) {
		$objectIDs = array();
		foreach($objects as $object)
			$objectIDs[] = (int)$object;
		
		// If there are no objects being request, return an empty array
		if(sizeof($objectIDs) == 0)
			return array();
		
		$objectIDString = implode(",",$objectIDs);
		$mysqli = DBConn::mysqli_connect();
		$dataArrays = array();
		
		// Load the data
		$queryString = "select portals.portalID as portalID,
							   portals.canopyID as canopyID,
							   portals.offX as offX,
							   portals.offY as offY,
							   portals.name as name,
							   portals.description as description,
							   portals.caption as caption,
							   portals.imgPath as imgPath,
							   unix_timestamp(portals.dateBased) as dateBased,
							   unix_timestamp(portals.dateCreated) as dateCreated
						  from portals
						 where portals.portalID IN (".$objectIDString.")";
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		while($resultArray = $result->fetch_assoc()) {
			$dataArray = array();
			$dataArray['portalID'] = $resultArray['portalID'];
			$dataArray['canopyID'] = $resultArray['canopyID'];
			$dataArray['offX'] = $resultArray['offX'];
			$dataArray['offY'] = $resultArray['offY'];
			$dataArray['name'] = $resultArray['name'];
			$dataArray['description'] = $resultArray['description'];
			$dataArray['caption'] = $resultArray['caption'];
			$dataArray['imgPath'] = $resultArray['imgPath'];
			$dataArray['dateBased'] = $resultArray['dateBased'];
			$dataArrays[] = $dataArray;
		}
		
		// Create the objects
		$objectArray = array();
		foreach($dataArrays as $dataArray) {
			$newObject = new Portal();
			$newObject->load($dataArray);
			$objectArray[] = $newObject;
		}
		return $objectArray;
	}
}
?>