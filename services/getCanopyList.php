<?PHP 
	header ("Content-Type:text/xml");
	echo("<?xml version=\"1.0\"?>");
	set_include_path("../");
	require_once("models/CanopyFactory.php");
?>
<canopies>
<?PHP
	
	$canopies = Canopy::getCanopies();
	foreach($canopies as $canopy) {
		?>
<canopy>
	<id><?PHP echo($canopy->getCanopyID()); ?></id>
	<lat><?PHP echo($canopy->getLat()); ?></lat>
	<lng><?PHP echo($canopy->getLng()); ?></lng>
	<name><?PHP echo(htmlentities($canopy->getName())); ?></name>
	<description><?PHP echo(htmlentities($canopy->getDescription())); ?></description>
	<dateBased><?PHP echo($canopy->getCanopyID()); ?></dateBased>
	<dateCreated><?PHP echo($canopy->getCanopyID()); ?></dateCreated>
</canopy>
<?PHP
}
?>
</canopies>
