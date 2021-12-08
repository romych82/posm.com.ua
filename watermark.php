<?php
/* 
	PHP Watermark
	Author Alfyorov D. ADDA
	https://github.com/wdda/watermark
*/

//На всякий случай запретим выполнять без пароля (его нужно поменять в .htaccess)
if(!empty($_GET['pass'])){
	
	//Поменяйте в htaccess и тут
	if($_GET['pass'] != '123123') die;
	
}else{die;}

$dir = 'cache';
if(!is_dir($dir)) mkdir($dir);

//Путь до файла с оригинальным изображением
$path = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'];
$array = explode('/', $_SERVER['REQUEST_URI']);
$nameImage = end($array); //Имя изображения
$nameImageId = md5($path) . '_' . $nameImage; //Имя изображения в кеше

//Проверяем дату для рефреша кеша
if(file_exists('cache/' . $nameImageId)){
	
	//Время создания файла в кеше
	$dateImageCache = filemtime('cache/' . $nameImageId);
	
	//Время создания оригинального файла
	$dateImage = filemtime($path);
	
	//Если оригинал старше чем в кеше
	if($dateImage < $dateImageCache){
		
		$image = new Imagick();
        try {
            $image->readImage('cache/' . $nameImageId);
        } catch (ImagickException $e) {
        }
        header('Content-type: image/jpeg');
        try {
            echo $image->getImageBlob();
        } catch (ImagickException $e) {
        }

    }else{
        try {
            newImage($path, $nameImageId);
        } catch (ImagickException $e) {
        }
    }
	
}else{
    try {
        newImage($path, $nameImageId);
    } catch (ImagickException $e) {
    }
}

//Если нет в кеше или есть но более старая версия
/**
 * @throws ImagickException
 */
function newImage($path, $nameImageId){
	// Загружаем оригинальное изображение 
	$image = new Imagick();
	$image->readImage($path);
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
	$image->writeImage('cache/' . $nameImageId);
	header('Content-type: image/jpeg');
	echo $image->getImageBlob();
}