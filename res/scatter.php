<?php
Header("Content-type: image/png");

define('PPG', 30); // pixels per goal
define('PPP', 50); // pixels per prediction
define('WIDTH', 10 * PPG);
define('HEIGHT', 10 * PPG);
define('X0', PPG);
define('Y0', PPG);

function hToX($h) {
  return X0 + $h * PPG;
}

function aToY($a) {
  return Y0 + $a * PPG;
}

function parsePoints($pstr) {
  $pointStrs = explode(',', $pstr);
  foreach ($pointStrs as $pointStr) $points[] = explode(':', $pointStr);
  return $points;
}

function drawPoint($im, $colour, $h, $a, $size) {
  $radius = sqrt($size * PPP);
  imagefilledellipse($im, hToX($h), aToY($a), $radius, $radius, $colour);
}

function drawLine($im, $colour, $fh, $fa, $th, $ta) {
  imageline($im, hToX($fh), aToY($fa), hToX($th), aToY($ta), $colour);
}

function flipimage($im) {
  $tmp = imagecreatetruecolor(WIDTH, HEIGHT);
  imagecopyresampled($tmp, $im, 0, 0, 0, 0, WIDTH, HEIGHT, WIDTH, HEIGHT);
  imagecopyresampled($im, $tmp, 0, 0, 0, HEIGHT-1, WIDTH, HEIGHT, WIDTH, -HEIGHT);
  imagedestroy($tmp);
}

list($scoreHome, $scoreAway) = explode(':', $_GET['s']);
list($highlightHome, $highlightAway) = explode(':', $_GET['h']);
$points = parsePoints($_GET['p']);

$im = imagecreatetruecolor(WIDTH, HEIGHT); 

$backColour = imagecolorallocate($im, 0xff, 0xff, 0xff);
$pointColour = imagecolorallocate($im, 0xaa, 0xaa, 0xaa);
$lineColour = imagecolorallocate($im, 0xdd, 0xdd, 0xdd);
$scoreColour = imagecolorallocate($im, 0x00, 0xee, 0x00);
$highlightColour = imagecolorallocate($im, 0xff, 0xcc, 0x00);
$drawLineColour = imagecolorallocate($im, 0xee, 0xee, 0xee);

imagefill($im, 0, 0, $backColour);
imageline($im, hToX(0), aToY(0), hToX(9), aToY(9), $drawLineColour);

foreach ($points as $point) {
  list($h, $a, $n) = $point;
  drawLine($im, $lineColour, $scoreHome, $scoreAway, $h, $a);
}

$directHit = false;
foreach ($points as $point) {
  list($h, $a, $n) = $point;
  if ($h == $highlightHome && $a == $highlightAway) {
    $col = $highlightColour;
  } else {
    $col = $pointColour;
  }
  if ($h == $scoreHome && $a == $scoreAway) {
    $directHit = true;
    drawPoint($im, $scoreColour, $h, $a, $n+1);
  }
  drawPoint($im, $col, $h, $a, $n);
}

if (!$directHit) {
  drawPoint($im, $scoreColour, $scoreHome, $scoreAway, 1);
}
flipimage($im);
ImagePng($im);
ImageDestroy($im);
?>
