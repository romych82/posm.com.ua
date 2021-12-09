<?php


//Путь до файла с оригинальным изображением
$path = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'];
$nameImage = end(explode('/', $_SERVER['REQUEST_URI'])); //Имя изображения
$nameImageId = md5($path) . '_' . $nameImage; //Имя изображения в кеше


newImage();

//Если нет в кеше или есть но более старая версия
function newImage(){
    $path = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'];
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

    header('Content-type: image/jpeg');
    echo $image->getImageBlob();
}