<?php
header("Content-Type: image/png");

$width  = $_GET['width'];
$height = $_GET['height'];

$color  = $_GET['color'];
if (isValidNamedColor($color)) {
    $color  = $_GET['color'];
} else {
    $color  = 'lightgray';
}

$image = new Imagick();

$image->newImage($width, $height, new ImagickPixel($color));

$image->setImageFormat('png');

$draw = new ImagickDraw();
$draw->setFillColor('black');
$draw->setFontSize(20);

$text = "{$width}x{$height}";

$metrics = $image->queryFontMetrics($draw, $text);
$textWidth = $metrics['textWidth'];
$textHeight = $metrics['textHeight'];

$x = ($width - $textWidth) / 2;
$y = ($height + $textHeight) / 2;

$image->annotateImage($draw, $x, $y, 0, $text);

echo $image->getImageBlob();

$image->destroy();

function isValidNamedColor($color) {
    $validColors = [
        "black", "white", "red", "green", "blue", "yellow", "gray", "lightgray", "darkgray", "silver", "beige", "orange", "purple", "pink", "turquoise", "teal", "brown", "limegreen", "navy", "darkblue", "gold", "indigo", "lightblue", "ivory", "magenta"
    ];

    return in_array(strtolower($color), $validColors);
}

?>