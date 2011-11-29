<?php
require_once("DBConn.php");
require_once("Canopy.php");
class CanopyFactory {
	public static function getObject($objectID) {
		$objectID = (int)$objectID;
		$mysqli = DBConn::mysqli_connect();
		$dataArray = array();
		
		// Load the need data
		$queryString = "select canopies.canopyID as canopyID,
							   canopies.lat as lat,
							   canopies.lng as lng,
							   canopies.name as name,
							   canopies.description as description,
							   canopies.imgPath as imgPath,
							   canopies.qrPath as qrPath,
							   unix_timestamp(canopies.dateBased) as dateBased,
							   unix_timestamp(canopies.dateCreated) as dateCreated
						  from canopies
						 where canopies.canopyID = ".$objectID;
		
		$result = $mysqli->query($queryString);
		if($result->num_rows == 0) {
			$result->free();
			return new Canopy();
		}
		$resultArray = $result->fetch_assoc();
		$result->free();
		
		$dataArray['canopyID'] = $resultArray['canopyID'];
		$dataArray['lat'] = $resultArray['lat'];
		$dataArray['lng'] = $resultArray['lng'];
		$dataArray['name'] = $resultArray['name'];
		$dataArray['description'] = $resultArray['description'];
		$dataArray['imgPath'] = $resultArray['imgPath'];
		$dataArray['qrPath'] = $resultArray['qrPath'];
		$dataArray['dateBased'] = $resultArray['dateBased'];
		$dataArray['dateCreated'] = $resultArray['dateCreated'];
		
		$newObject = new Canopy();
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
		$queryString = "select canopies.canopyID as canopyID,
							   canopies.lat as lat,
							   canopies.lng as lng,
							   canopies.name as name,
							   canopies.description as description,
							   canopies.imgPath as imgPath,
							   canopies.qrPath as qrPath,
							   unix_timestamp(canopies.dateBased) as dateBased,
							   unix_timestamp(canopies.dateCreated) as dateCreated
						  from canopies
						 where canopies.canopyID IN (".$objectIDString.")";
		
		$result = $mysqli->query($queryString)
			or print($mysqli->error);
		while($resultArray = $result->fetch_assoc()) {
			$dataArray = array();
			$dataArray['canopyID'] = $resultArray['canopyID'];
			$dataArray['lat'] = $resultArray['lat'];
			$dataArray['lng'] = $resultArray['lng'];
			$dataArray['name'] = $resultArray['name'];
			$dataArray['description'] = $resultArray['description'];
			$dataArray['imgPath'] = $resultArray['imgPath'];
			$dataArray['qrPath'] = $resultArray['qrPath'];
			$dataArray['dateBased'] = $resultArray['dateBased'];
			$dataArray['dateCreated'] = $resultArray['dateCreated'];
			$dataArrays[] = $dataArray;
		}
		
		// Create the objects
		$objectArray = array();
		foreach($dataArrays as $dataArray) {
			$newObject = new Canopy();
			$newObject->load($dataArray);
			$objectArray[] = $newObject;
		}
		return $objectArray;
	}
}
?>