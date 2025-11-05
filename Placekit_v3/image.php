<?php
declare(strict_types=1);

ini_set('display_errors', '1');
error_reporting(E_ALL);


function fail(int $status, string $msg): void {
    http_response_code($status);
    header('Content-Type: text/plain; charset=utf-8');
    echo $msg;
    exit;
}
function get_int(string $key, int $default): int {
    return isset($_GET[$key]) ? max(1, (int)$_GET[$key]) : $default;
}
function get_str(string $key, ?string $default=null): ?string {
    return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
}
function load_image(string $path) {
    $info = @getimagesize($path);
    if (!$info || empty($info['mime'])) return null;
    return match ($info['mime']) {
        'image/jpeg' => @imagecreatefromjpeg($path),
        'image/png'  => @imagecreatefrompng($path),
        'image/gif'  => @imagecreatefromgif($path),
        default      => null,
    };
}
function cover_resize($src, int $w, int $h) {
    $sw = imagesx($src); $sh = imagesy($src);
    $dst = imagecreatetruecolor($w, $h);
    $white = imagecolorallocate($dst, 255, 255, 255);
    imagefilledrectangle($dst, 0, 0, $w, $h, $white);

    $scale = max($w / $sw, $h / $sh);
    $nw = (int)ceil($sw * $scale);
    $nh = (int)ceil($sh * $scale);
    $dx = (int)floor(($w - $nw) / 2);
    $dy = (int)floor(($h - $nh) / 2);

    imagecopyresampled($dst, $src, $dx, $dy, 0, 0, $nw, $nh, $sw, $sh);
    return $dst;
}
function draw_overlay_text($img, string $text): void {
    $font = __DIR__ . '/ARIALBD.TTF'; 
    $h = imagesy($img); $w = imagesx($img);
    $bar = imagecolorallocatealpha($img, 0, 0, 0, 70);
    imagefilledrectangle($img, 0, $h - 48, $w, $h, $bar);

    $white = imagecolorallocate($img, 255, 255, 255);
    if (is_file($font)) {
        $size = max(12, (int)round($h / 24));
        $bbox = imagettfbbox($size, 0, $font, $text);
        $textW = $bbox[2] - $bbox[0];
        $textH = $bbox[1] - $bbox[7];
        $x = (int)round(($w - $textW) / 2);
        $y = (int)round($h - 48/2 - $textH/2 + $textH);
        imagettftext($img, $size, 0, $x, $y, $white, $font, $text);
    } else {
        $fontId = 5;
        $fontW = imagefontwidth($fontId);
        $fontH = imagefontheight($fontId);
        $textW = strlen($text) * $fontW;
        $x = (int)round(($w - $textW) / 2);
        $y = (int)round($h - 48 + (48 - $fontH) / 2);
        imagestring($img, $fontId, $x, $y, $text, $white);
    }
}

$w        = get_int('width', 800);
$h        = get_int('height', 500);
$imageKey = get_str('image', 'random');   
$overlay  = (int) (get_str('overlay', '0') ?? '0');
$text     = strtoupper(get_str('text', "{$w}x{$h}") ?? "{$w}x{$h}");


$imagesDir = __DIR__ . '/images';
if (!is_dir($imagesDir)) {
    fail(404, 'Images folder not found. Create /images and add JPG/PNG/GIF files.');
}
$files = glob($imagesDir . '/*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE);
if (!$files) fail(404, 'No images found in /images. Add JPG/PNG/GIF files.');

natsort($files);
$files = array_values($files); 


if ($imageKey === 'random') {
    $path = $files[array_rand($files)];
} else {
    $idx = max(1, (int)$imageKey);           
    $i = $idx - 1;                           
    if (!isset($files[$i])) {
        fail(404, "Image index {$idx} not found. Available: 1.." . count($files));
    }
    $path = $files[$i];
}


$src = load_image($path);
if (!$src) fail(415, 'Unsupported or unreadable image format');

$dst = cover_resize($src, $w, $h);
if ($overlay) draw_overlay_text($dst, $text);

header('Content-Type: image/jpeg');
imagejpeg($dst, null, 90);

imagedestroy($src);
imagedestroy($dst);
