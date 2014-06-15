<?php
Header("Content-type: image/png");
$b = $_GET['b'];
$l = $_GET['l'];
$sd = $_GET['sd'];

$dx = 101; // bar width (without border)
$dy = 9; // bar height (without border)

$barPos = round($b * ($dx - 1) + 1, 0);
$linePos = round($l * ($dx - 1) + 1, 0);
$sdPx = round($sd * $dx, 0);
$lhPos = $linePos - $sdPx;
$rhPos = $linePos + $sdPx;
if ($lhPos < 1) $lhPos = 1;
if ($rhPos > $dx) $rhPos = $dx;

$im = imagecreatetruecolor($dx+2,$dy+2); 

$white = ImageColorAllocate($im, 255,255,255);
$black = ImageColorAllocate($im, 0,0,0);

if ($l == '') {
  $barCol = ImageColorAllocate($im, 0xaa,0xaa,0xaa);
} elseif ($b > $l) {
  $barCol = ImageColorAllocate($im, 0xff - (255 * ($b - $l)), 0xff,0);
} else {
  $barCol = ImageColorAllocate($im, 0xff,0xff - (255 * ($l - $b)),0);
}

ImageFill($im, 0, 0, $white);
ImageRectangle($im,0,0,$dx+1,$dy+1,$black);

if ($b != '')
  ImageFilledRectangle($im, 1, 1, $barPos, $dy, $barCol);
if ($sd != '') {
  $hlCol = ImageColorAllocateAlpha($im, 0xaa, 0xaa, 0xaa, 0x60);
  ImageFilledRectangle($im, $lhPos, 1, $rhPos, $dy, $hlCol);
}
if ($l != '') {
  $lineCol = ImageColorAllocate($im, 0xaa,0xaa,0xaa);
  ImageLine($im, $linePos, 1, $linePos, $dy, $lineCol);
}

ImagePng($im);
ImageDestroy($im);
?>