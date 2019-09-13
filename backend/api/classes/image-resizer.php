<?php

/**
 * Resizes an image
 * 
 * @param string $srcImgPath_s - The path to the source file (jpg).
 * @param string $destImgPath_s - The path to where to save the thumbnail file.
 * @param integer $destImgWidth_i - The width of the thumbnail file.
 */
function imageResizer($srcImgPath_s, $destImgPath_s, $destImgWidth_i) {

    /* read the source image */
    $srcImg_r = imagecreatefromjpeg($srcImgPath_s);
    $srcImgWidth_i = imagesx($srcImg_r);
    $srcImgHeight_i = imagesy($srcImg_r);

    /* find the "desired height" of this thumbnail, relative to the desired width  */
    $destImgHeight_i = floor($srcImgHeight_i * ($destImgWidth_i / $srcImgWidth_i));

    /* create a new, "virtual" image */
    $destImg_r = imagecreatetruecolor($destImgWidth_i, $destImgHeight_i);

    /* copy source image at a resized size */
    imagecopyresampled($destImg_r, $srcImg_r, 0, 0, 0, 0, $destImgWidth_i, $destImgHeight_i, $srcImgWidth_i, $srcImgHeight_i);

    /* create the physical thumbnail image to its destination */
    imagejpeg($destImg_r, $destImgPath_s);
}

?>