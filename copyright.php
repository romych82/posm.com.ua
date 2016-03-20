<?php
// налаштування скрипта
define( 'NAME_WATER_IMAGE', 'watermark.png'); // ім'я файлу "копірат", краще у фарматах png або gif (через прозорий фон)
define( 'SCALE_WATER_IMAGE', 100); //найбільше відношення розмірів водяного знаку по відношенню до оригіналу, значення в діапазоні від 0 до 100
define( 'ALPHA_WATER_IMAGE', 50); // коефіцієнт прозорості копірайту (0 - 100)
define( 'VALIGN_WATER_IMAGE', 'center'); // Вертикальне розташування водяного знаку: top (за замовчуванням), center, bottom
define( 'HALIGN_WATER_IMAGE', 'center'); // Горизонтальне розташування водяного знаку: left (за замовчуванням), center, right
define( 'FORMAT_OUT_IMAGE', 'jpeg'); // формат виводу оброблених картинок jpeg або png
define( 'GIF_TYPE',  '1');
define( 'JPG_TYPE',  '2');
define( 'PNG_TYPE',  '3');
define( 'WBMP_TYPE', '15');
define( 'XBM_TYPE',  '16');
 
function open_and_create_image( $name_image ){
 
  $image_identifier = 0;
  $array_name_image = parse_url($name_image);
  $name_image = (substr($array_name_image['path'], 0, 1)== '/') ? substr($array_name_image['path'], 1) : $array_name_image['path'];
  list($width, $height, $type, $attr) = getimagesize( $name_image );
  switch($type){
    case GIF_TYPE:
      $image_identifier = imagecreatefromgif ( $name_image );
      break;
    case JPG_TYPE:
      $image_identifier = ImageCreateFromJPEG ( $name_image );
      break;
    case PNG_TYPE:
      $image_identifier = imagecreatefrompng ( $name_image );
      break;
    case WBMP_TYPE:
      $image_identifier = imagecreatefromwbmp ( $name_image );
      break;
    case XBM_TYPE:
      $image_identifier = imagecreatefromxbm ( $name_image );
  }
  if ( !$image_identifier ) {
        $image_identifier = imagecreate (1000, 30);
        $bgc = imagecolorallocate ($image_identifier, 255, 255, 255);
        $tc = imagecolorallocate ($image_identifier, 0, 0, 0);
        imagefilledrectangle ($image_identifier, 0, 0, 1000, 30, $bgc);
        imagestring ($image_identifier, 1, 5, 5, 'Помилка завантаження ' .  $name_image . ' або формат не підтримується', $tc);
        $width = 1000;
        $height = 30;
  }
  return array( 'im'          => $image_identifier,
                'width'  => $width,
                'height' => $height
                                );
}
$name_water_image   = ( isset($_GET['nwi'])? $_GET['nwi'] : NAME_WATER_IMAGE );
$scale_water_image  = ( isset($_GET['swi'])? $_GET['swi'] : SCALE_WATER_IMAGE );
$alpha_water_image  = ( isset($_GET['awi'])? $_GET['awi'] : ALPHA_WATER_IMAGE );
$valign_water_image = ( isset($_GET['vwi'])? $_GET['vwi'] : VALIGN_WATER_IMAGE );
$halign_water_image = ( isset($_GET['hwi'])? $_GET['hwi'] : HALIGN_WATER_IMAGE );
$format_out_image   = ( isset($_GET['foi'])? $_GET['foi'] : FORMAT_OUT_IMAGE );
 
$water_img_param = open_and_create_image( $name_water_image );
$src_img_param   = open_and_create_image($_GET['image']);
 
// Перевірка і масштабування
$hscale = $water_img_param['height'] / $src_img_param['height'];
$wscale = $water_img_param['width'] / $src_img_param['width'];
if (($hscale > $scale_water_image/100) || ($wscale > $scale_water_image/100)) {
  $scale = ($scale_water_image / 100) / (($hscale > $wscale)?$hscale:$wscale);
  $newheight= floor( $water_img_param['height'] * $scale);
  $newwidth = floor( $water_img_param['width']  * $scale);
} else {
  $newheight= $water_img_param['height'];
  $newwidth = $water_img_param['width'];
}
 
$tmp_img = imagecreatetruecolor( $newwidth, $newheight);
$white_color = imagecolorallocate ($tmp_img, 255, 255, 255);
imagefilledrectangle ($tmp_img, 0, 0, $newwidth, $newheight, $white_color);
imagecolortransparent ($tmp_img, $white_color);
imagecopyresized($tmp_img, $water_img_param['im'], 0, 0, 0, 0, $newwidth, $newheight, $water_img_param['width'], $water_img_param['height']);
imagedestroy($water_img_param['im']);
$water_img_param = array ( 'im'     => $tmp_img,
                                              'width'  => $newwidth,
                                       'height' => $newheight
                                                         );
$tmp_img = imagecreatetruecolor($src_img_param['width'], $src_img_param['height']);
$white_color = imagecolorallocate ($tmp_img, 255, 255, 255);
imagefilledrectangle ($tmp_img, 0, 0, $src_img_param['width'], $src_img_param['height'], $white_color);
imagecolortransparent ($tmp_img, $white_color); 
imagecopy($tmp_img, $src_img_param['im'], 0,0,0,0, $src_img_param['width'], $src_img_param['height']); 
$x_ins = $y_ins = 0;
switch ( $halign_water_image ){
  case 'center' :
    $x_ins = floor( ( $src_img_param['width'] - $water_img_param['width']) / 2 );
    break;
  case 'right' :
    $x_ins = $src_img_param['width'] - $water_img_param['width'];
}
switch ( $valign_water_image ){
  case 'center' :
    $y_ins = floor( ( $src_img_param['height'] - $water_img_param['height']) / 2 );
    break;
  case 'bottom' :
    $y_ins = $src_img_param['height'] - $water_img_param['height'];
}
imagecopymerge($tmp_img, $water_img_param['im'], $x_ins, $y_ins, 0, 0, $water_img_param['width'], $water_img_param['height'], $alpha_water_image);
switch ( $format_out_image ){
  case 'jpeg' :
  case 'jpg' :
  case 'JPEG' :
  case 'JPG' :
        header('content-type: image/jpeg');
        ImageJPEG($tmp_img,'',100);
    break;
  case 'png' :
  case 'PNG' :
        header('content-type: image/png');
        ImagePNG($tmp_img);
}
imagedestroy($src_img_param['im']);
imagedestroy($water_img_param['im']);
imagedestroy($tmp_img);
?>