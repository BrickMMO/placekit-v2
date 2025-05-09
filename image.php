<?php

require __DIR__ . '/vendor/autoload.php';

function hex2rgb( $colour ) {

    $colour = str_replace('#', '', $colour);
    list($r, $g, $b) = array_map("hexdec", str_split($colour, (strlen( $colour ) / 3)));
    return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}

$font = __DIR__.'/ARIALBD.TTF';

// Get image information based on URL parameters
$image_width = isset($_GET['width']) ? $_GET['width'] : 800;
$image_height = isset($_GET['height']) ? $_GET['height'] : 500;

$text_content = isset($_GET['text']) ? $_GET['text'] : $image_width.' x '.$image_height;
$text_content = strtoupper($text_content);
$text_size = $image_height / 12;

$bg_colour = isset($_GET['bg']) ? $_GET['bg'] : 'ffffff';
$bg_colour = hex2rgb($bg_colour);

$text_colour = isset($_GET['colour']) ? $_GET['colour'] : '000000';
$text_colour = hex2rgb($text_colour);

// Make a draft image for placing content and measuring sizing
$draft = imagecreate($image_width, $image_height);
$colour = imagecolorallocate($draft, $text_colour['red'], $text_colour['green'], $text_colour['blue']);
$text_box = imagettftext($draft, $text_size, 0, 0, 0, $colour, $font, $text_content);
$text_width = abs($text_box[4] - $text_box[0]);
$text_height = abs($text_box[5] - $text_box[1]);

// Make actual iange
$image = imagecreate($image_width, $image_height);
$colour = imagecolorallocate($image, $text_colour['red'], $text_colour['green'], $text_colour['blue']);
$bg = imagecolorallocate($image, $bg_colour['red'], $bg_colour['green'], $bg_colour['blue']);
imagefill($image, 0, 0, $bg);

imagettftext($image, $text_size, 0,
    round(($image_width - $text_width) / 2), 
    round(($image_height /*- $text_height*/) / 2), 
    $colour, $font, $text_content);

header("Content-Type: image/jpeg");
echo imagejpeg($image);
