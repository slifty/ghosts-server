<?PHP
	set_include_path("../");
	require_once("models/PortalFactory.php");
	if(isset($_GET['p'])) {
		$portal = PortalFactory::getObject($_GET['p']);
		$portalImage = imagecreatefromjpeg("../".$portal->getImgPath());
		$x = isset($_GET['x'])?$_GET['x']:0;
		$y = isset($_GET['y'])?$_GET['y']:0;
		$h = isset($_GET['h'])?$_GET['h']:imagesy($portalImage);
		$w = isset($_GET['w'])?$_GET['w']:imagesx($portalImage);
		$newW = min($w, 400);
		$newH = $h * ($newW / $w);
		
		$resizedImage = imagecreatetruecolor($newW, $newH);
		
		imagecopyresized( $resizedImage, $portalImage , 0 , 0 , 0 , 0 , $newW , $newH , $w , $h );
		
		header('Content-Type: image/jpeg');
		imagejpeg($resizedImage);
	} else {
	}
?>