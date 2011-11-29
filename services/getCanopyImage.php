<?PHP
	set_include_path("../");
	require_once("models/CanopyFactory.php");
	if(isset($_GET['c'])) {
		$canopy = CanopyFactory::getObject($_GET['c']);
		$canopyImage = imagecreatefromjpeg("../".$canopy->getImgPath());
		$x = isset($_GET['x'])?$_GET['x']:0;
		$y = isset($_GET['y'])?$_GET['y']:0;
		$h = isset($_GET['h'])?$_GET['h']:imagesy($canopyImage);
		$w = isset($_GET['w'])?$_GET['w']:imagesx($canopyImage);
		$resizedImage = imagecreatetruecolor($w, $h);
		imagecopyresized( $resizedImage, $canopyImage , 0 , 0 , $x , $y , $w , $h , $w , $h );
		
		header('Content-Type: image/jpeg');
		imagejpeg($resizedImage);
		
	} else {
	}
?>