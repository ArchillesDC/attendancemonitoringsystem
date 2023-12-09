<?php
function addDesignToQRCode($qrpath, $employeeName)
{
    $qrCode = imagecreatefrompng($qrpath);
    $fontSize = 20; // Adjust the font size as needed
    $fontColor = imagecolorallocate($qrCode, 0, 0, 0); // Black color
    $fontFile = 'ch.ttf'; // Replace with the path to your TTF font file

    // Calculate position to center the text at the top
    $textBoundingBox = imagettfbbox($fontSize, 0, $fontFile, $employeeName);
    $textWidth = $textBoundingBox[2] - $textBoundingBox[0];
    $textX = (imagesx($qrCode) - $textWidth) / 2;
    $textY = $fontSize;

    // Add employee name at the top of the QR code
    imagettftext($qrCode, $fontSize, 0, $textX, $textY, $fontColor, $fontFile, $employeeName);

    // Calculate position to add space at the bottom
    $bottomSpacing = 2 * $fontSize; // 2rem spacing at the bottom
    $textYBottom = imagesy($qrCode) - $bottomSpacing;

    // Add employee name with bottom spacing
    imagettftext($qrCode, $fontSize, 0, $textX, $textYBottom, $fontColor, $fontFile, $employeeName);

    // Save the final image
    imagepng($qrCode, $qrpath);

    // Free up memory
    imagedestroy($qrCode);

    return $qrpath;
}
?>
