<?PHP 
	header ("Content-Type:text/xml");
	echo("<?xml version=\"1.0\"?>");
	set_include_path("../");
	require_once("models/CanopyFactory.php");
	if(isset($_GET['c'])) {
		$canopy = CanopyFactory::getObject($_GET['c']);
		$canopyImage = imagecreatefromjpeg("../".$canopy->getImgPath());
		?>

<canopy>
	<id><?PHP echo($canopy->getCanopyID()); ?></id>
	<lat><?PHP echo($canopy->getLat()); ?></lat>
	<lng><?PHP echo($canopy->getLng()); ?></lng>
	<name><?PHP echo(htmlentities($canopy->getName())); ?></name>
	<description><?PHP echo(htmlentities($canopy->getDescription())); ?></description>
	<dateBased><?PHP echo($canopy->getCanopyID()); ?></dateBased>
	<dateCreated><?PHP echo($canopy->getCanopyID()); ?></dateCreated>
	<height><?PHP echo(imagesy($canopyImage)); ?></height>
	<width><?PHP echo(imagesx($canopyImage)); ?></width>
	<projections><?PHP
		foreach($canopy->getPortals() as $portal) {
			$hasImage = false;
			if($portal->getImgPath()) {
				$hasImage = true;
				$portalImage = imagecreatefromjpeg("../".$portal->getImgPath());
				echo("||".$portal->getImgPath()."||");
			}
			?>
			<projection>
				<id><?PHP echo($portal->getPortalID()); ?></id>
				<height><?PHP echo($hasImage?imagesy($portalImage):0); ?></height>
				<width><?PHP echo($hasImage?imagesx($portalImage):0); ?></width>
				<offX><?PHP echo($portal->getOffX()); ?></offX>
				<offY><?PHP echo($portal->getOffY()); ?></offY>
				<description><![CDATA[<?PHP echo(DBConn::unclean($portal->getDescription())); ?>]]></description>
				<caption><![CDATA[<?PHP echo(DBConn::unclean($portal->getCaption())); ?>]]></caption>
			</projection>
			<?PHP
		}
	?></projections>
</canopy><?PHP
	} else {
		?>
		<error>No canopy ID provided</error>
		<?PHP
	}
?>