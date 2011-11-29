<html>
	<head>
		<?php
			set_include_path("/");
			require_once("models/Canopy.php");
		?>
		<title>Ghosts of the Past</title>
		<link rel="stylesheet" href="styles/main.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="styles/index.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="styles/colorBox.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<script src="scripts/jquery-1.5.1.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="scripts/jquery.colorbox.js" type="text/javascript" charset="utf-8"></script>
		
		<script type="text/javascript">
		$(function() {
			
			// Enable "Learn More"
			$learnMoreButton = $("#learnMore");
			$learnMoreButton.click(function() {
				$.colorbox({
					href:"instructions.php",
					width: 560,
					height: 600,
					iframe: true
				});
			});
			
			// Enable "Upload Canopy"
			$learnMoreButton = $("#uploadCanopy");
			$learnMoreButton.click(function() {
				$.colorbox({
					href:"uploadCanopy.php",
					width: 600,
					height: 620,
					iframe: true
				});
			});
			
			// Enable "Upload Portal"
			$addPortalButtons = $(".addPortal");
			$.each($addPortalButtons, function(index, item) {
				$item = $(item);
				var itemID = $item.attr("id");
				var canopyID = itemID.slice(9); // "addPortal" has 9 characters
				
				$item.click(function() {
					$.colorbox({
						href:"uploadPortal.php?c="+canopyID,
						width: 1000,
						height: 620,
						iframe: true
					});
				});
			});
		});
		</script>
		
	</head>
	<body id="main">
		<div id="container">
			<h1>Ghosts of the Past: Creating Historical Canopies</h1>
			<p>Ghosts of the Past helps you and your communities experience placed-based stories.</p>
			<p>It uses full circle panoramas and an iPad 2 to overlay memories on top of physical space.</p>
			<div class="step">
				<h2>1: Create a Panorama</h2>
				<p>There are several ways to make a panorama.  Some are free, some are cheap, but all are easy.</p>
				<p><input type="button" value="Learn More" id="learnMore"/></p>
			</div>
			<div class="step">
				<h2>2: Upload the Image</h2>
				<p>Once you have a panorama, upload it here so that our tool can work its magic and create a Ghost canopy.</p>
				<p><input type="button" value="Upload a Canopy" id="uploadCanopy"/></p>
			</div>
			<div class="step">
				<h2>3: Place the QR Code</h2>
				<p>Print out the QR code you got after creating your canopy and place it in the area that you just captured.  This will allow people to load in your memory.</p>
			</div>
			<div class="canopies">
				<h3>Current Canopies</h3>
				<table id="canopyList">
					<thead>
						<tr>
							<th>Canopy</th>
							<th>QR</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php
						$canopies = Canopy::getCanopies();
						foreach($canopies as $canopy) {
							?>
							<tr>
								<td><a href="<?php echo($canopy->getImgPath());?>" target="_blank"><?php echo($canopy->getName()?$canopy->getName():"Untitled Canopy");?></a></td>
								<td><a href="https://chart.googleapis.com/chart?cht=qr&chs=500x500&chl=<?php echo(urlencode("http://ghosts.slifty.com//".$canopy->getImgPath())); ?>" target="_blank"/>QR Code</a></td>
								<td><input type="button" class="addPortal" id="addPortal<?php echo($canopy->getCanopyID());?>" value="Add Portal" /></td>
							</tr>
							<?php
						}
					?>
					</tbody>
			</div>
		</div>
	</body>
</html>