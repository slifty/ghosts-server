<html>
	<head>
		<title></title>
		<link rel="stylesheet" href="styles/main.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="styles/upload.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<script src="scripts/jquery-1.5.1.min.js" type="text/javascript" charset="utf-8"></script>
		
		<?php
			set_include_path("/");
			require_once("models/Portal.php");
			require_once("models/CanopyFactory.php");
			
			$canopyID = isset($_GET['c'])?$_GET['c']:0;
			$canopy = CanopyFactory::getObject($canopyID);
			
			// Processes the upload canopy form and creates a new canopy
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$newPortal = new Portal();
				
				$canopyImage = imagecreatefromjpeg($canopy->getImgPath());
				$imageHeight = (int)imagesy($canopyImage);
				$imageWidth = (int)imagesx($canopyImage);
				
				$newPortal->setName(isset($_POST['portalName'])?$_POST['portalName']:"");
				$newPortal->setCaption(isset($_POST['portalCaption'])?$_POST['portalCaption']:"");
				$newPortal->setDescription(isset($_POST['portalDescription'])?$_POST['portalDescription']:"");
				$newPortal->setOffX(isset($_POST['offXPct'])?$_POST['offXPct'] * $imageWidth:0);
				$newPortal->setOffY(isset($_POST['offYPct'])?$_POST['offYPct'] * $imageHeight:0);
				$newPortal->setCanopyID($canopyID);
				
				// Deal with the file
				if($_FILES["imageFile"]["name"] != "") { // Did they try to upload an image?
					if ($_FILES["imageFile"]["error"] > 0)
						$error = "The image didn't upload.  It may have been an invalid file type.";
					else {
						// Make sure the file is an image
						$validTypes = array("image/jpeg","image/jpg");
						if(array_search($_FILES["imageFile"]["type"], $validTypes) === false)
							$error = "File type must be jpg or jpeg";
						else {
							$tempName = $_FILES["imageFile"]["tmp_name"];
							$newPortal->setImgTempPath($tempName);
						}
					}
				}
				
				if($_FILES["imageFile"]["name"] == "" && $_POST['portalDescription'] == "")
					$error = "You can't have an empty portal";

				if($_FILES["imageFile"]["name"] == "" && $_POST['portalCaption'] != "")
					$error = "You can't have a caption if you don't have an image.";
					
				if(!isset($error) || !$error)
					$newPortal->save();
			}
		?>
		
		<script type="text/javascript">
			$(function(){
				// Enable the image placement
				$image = $("#canopyImage");
				$marker = $("<div />");
				$marker.hide();
				$marker.attr("id","placementMarker");
				$marker.appendTo($("body"));
				$image.click(function(e) {
					var offXPct = e.offsetX / $image.width();
					var offYPct = e.offsetY / $image.height();
					$("#offXPct").val(offXPct);
					$("#offYPct").val(offYPct);
					$marker.css("top",e.clientY - 5);
					$marker.css("left",e.clientX - 5);
					$marker.show();
				});
			});
		</script>
	</head>
	<body>
		<?php if(isset($newPortal) && (!isset($error) || !$error)) { ?>
			<p>You portal has been created.  Go to its canopy and you'll see it there!</p>
		<?php } else {?>
			<h1>Create a Portal</h1>
			<?php if(isset($error) && $error) { ?>
				<p>Something went wrong (<?php echo($error); ?>)</p>
			<?php } ?>
			<form enctype="multipart/form-data" method="POST">
				<p>Upload your image to include it on this canopy.</p>
				<ul>
					<li><label for="imageFile">Image:</label><input type="file" name="imageFile" id="imageFile"/></li>
					<li><label for="portalCaption">Caption:</label><textarea name="portalCaption" id="portalCaption"></textarea></li>
					<li><label for="portalDescription">Description:</label><textarea name="portalDescription" id="portalDescription"></textarea></li>
					<li>
						<div>
							<h2>Click the location where you want this portal to appear</h2>
							<img src="<?php echo($canopy->getImgPath()); ?>" id="canopyImage"/>
						</div>
						<input type="hidden" name="offXPct" id="offXPct"/>
						<input type="hidden" name="offYPct" id="offYPct"/>
					</li>
					<li>
						<input type="hidden" name="canopyID" value="<?php echo($canopyID);?>" />
						<input type="submit" value="Upload" />
					</li>
				</ul>
			</form>
		<?php } ?>
	</body>
</html>