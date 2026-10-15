<?php

$sourcePath = 'C:/Users/ASUS/.gemini/antigravity-ide/brain/764696bb-7343-4505-8393-eeb9984fab9a/.user_uploaded/media_1792048635782.png';
$info = getimagesize($sourcePath);
$width = $info[0];
$height = $info[1];

$src = imagecreatefrompng($sourcePath);

// Crop out the black device border (2% from sides, 3.5% from top/bottom)
$cropX = (int) ($width * 0.02);
$cropY = (int) ($height * 0.035);
$cropW = $width - ($cropX * 2);
$cropH = $height - ($cropY * 2);

$img = imagecrop($src, ['x' => $cropX, 'y' => $cropY, 'width' => $cropW, 'height' => $cropH]);

// Icon area starts at around 78% of cropW and goes up to 13% of cropH
$iconStartX = (int) ($cropW * 0.77);
$iconStartY = 0;
$iconW = $cropW - $iconStartX;
$iconH = (int) ($cropH * 0.14);

// To blend smoothly without hard edges, sample from x: (iconStartX - iconW - 20)
$sampleX = $iconStartX - $iconW - 20;

// Copy clean background over the icons with alpha/gradient feathering:
for ($x = 0; $x < $iconW; $x++) {
    $tX = $iconStartX + $x;
    $sX = $sampleX + $x;
    // feather weight from 0 at edge to 1 inside
    $alpha = min(1.0, $x / 25.0);

    for ($y = 0; $y < $iconH; $y++) {
        $rgbSample = imagecolorat($img, $sX, $y);
        $sR = ($rgbSample >> 16) & 0xFF;
        $sG = ($rgbSample >> 8) & 0xFF;
        $sB = $rgbSample & 0xFF;

        $rgbOrig = imagecolorat($img, $tX, $y);
        $oR = ($rgbOrig >> 16) & 0xFF;
        $oG = ($rgbOrig >> 8) & 0xFF;
        $oB = $rgbOrig & 0xFF;

        $finalR = (int) (($sR * $alpha) + ($oR * (1.0 - $alpha)));
        $finalG = (int) (($sG * $alpha) + ($oG * (1.0 - $alpha)));
        $finalB = (int) (($sB * $alpha) + ($oB * (1.0 - $alpha)));

        $col = imagecolorallocate($img, $finalR, $finalG, $finalB);
        imagesetpixel($img, $tX, $y, $col);
    }
}

imagepng($img, 'public/images/bg-login.png');
echo "CRISP_SUCCESS\n";
imagedestroy($img);
imagedestroy($src);
