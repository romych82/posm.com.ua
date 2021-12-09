<?php

$path = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'];
$nameImage = end(explode('/', $_SERVER['REQUEST_URI'])); //Имя изображения

newImage($path);

function newImage(){
	// Загружаем оригинальное изображение
	$image = new Imagick();
	$image->readImage($path);
    //   $image->readImage('logo2.jpg');
	$w = $image->getImageWidth(); 
	$h = $image->getImageHeight();
	
	$imageWatermark = new Imagick();
	$imageWatermark->readImage('watermark.png');
	$ww = $imageWatermark->getImageWidth(); 
	$wh = $imageWatermark->getImageHeight();
	
	//Отступ снизу
	$paddingBottom = 10;
	
	//Отступ справа
	$paddingRight = 10;
	
	//Это позволяет поставить изображение в нижний правый угол (учитывая отступы)
	$x = ($w - $ww) - $paddingRight;
	$y = ($h - $wh) - $paddingBottom;
	
	$image->compositeImage($imageWatermark, imagick::COMPOSITE_OVER, $x, $y);

	header('Content-type: image/jpeg');
	echo $image->getImageBlob();
}

