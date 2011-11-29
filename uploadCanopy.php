<html>
	<head>
		<title></title>
		<link rel="stylesheet" href="styles/main.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="styles/upload.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<script src="scripts/jquery-1.5.1.min.js" type="text/javascript" charset="utf-8"></script>
		
		<?php
			set_include_path("/");
			require_once("models/Canopy.php");
			// Processes the upload canopy form and creates a new canopy
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$newCanopy = new Canopy();
		
				$newCanopy->setName(isset($_POST['canopyName'])?$_POST['canopyName']:"");
				$newCanopy->setDescription(isset($_POST['canopyDescription'])?$_POST['canopyDescription']:"");
				
				// Deal with the file
				if ($_FILES["imageFile"]["error"] > 0)
					$error = "The image didn't upload.  It may have been an invalid file type.";
				else {
					// Make sure the file is an image
					
					$validTypes = array("image/jpeg","image/jpg");
					if(array_search($_FILES["imageFile"]["type"], $validTypes) === false)
						$error = "File type must be jpg or jpeg";
					else {
						$tempName = $_FILES["imageFile"]["tmp_name"];
						$newCanopy->setImgTempPath($tempName);
					}
				}
		
				if(!isset($error) || !$error)
					$newCanopy->save();
			}
		?>
	</head>
	<body>
		<?php if(isset($newCanopy) && (!isset($error) || !$error)) { ?>
			<p>You canopy has been created.  Just print out this QR code and place it in the location where you want to be able to load it!</p>
			<p><img src="https://chart.googleapis.com/chart?cht=qr&chs=500x500&chl=<?php echo(urlencode("http://ghosts.slifty.com/canopy_files/".$newCanopy->getCanopyID().".jpg"))?>" /></p>
		<?php } else {?>
			<h1>Create a Canopy</h1>
			<?php if(isset($error) && $error) { ?>
				<p>Something went wrong (<?php echo($error); ?>)</p>
			<?php } ?>
			<form enctype="multipart/form-data" method="POST">
				<p>Upload your panorama to create a ghost canopy.</p>
				<ul>
					<li><label for="imageFile">Image: </label><input type="file" name="imageFile" id="imageFile"/></li>
					<li><label for="canopyName">Name: </label><input type="text" name="canopyName" id="canopyName"/></li>
					<li><label for="canopyDescription">Description: </label><textarea name="canopyDescription" id="canopyDescription" rows="10" cols="50"></textarea></li>
					<li><input type="submit" value="Upload" /></li>
				</ul>
			</form>
		<?php } ?>
	</body>
</html>