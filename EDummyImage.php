<?php

/**
 * Dynamic Dummy Image Generator - as seen on DummyImage.com
 *
 * You can create dummy images with this script kinda easily. Just provide at least the size parameter, that's it.
 * Examples:
 *
 * image.php?size=250x850&type=jpg&bg=ff8800&color=000000
 * - will create a 250px width, 800px height jpg image with orange background and black text
 *
 * image.php?size=250
 * - will create a 250px width, 250px height png image with black background and white text
 *
 * Original idea and script by Russel Heimlich (see http://DummyImage.com).
 * Rewritten by Fabian Beiner. https://github.com/FabianBeiner/PHP-Dummy-Image-Generator
 * Rewritten for YII framework by Zivorad Antonijevic (zivoradantonijevic@gmail.com)
 *
 * Date: 30.12.14.
 */
class EDummyImage
{
    public function image($params = array())
    {
        if (empty($params)) {
            $params = $_GET;
        }
// Handle the parameters.
        $strSize = (isset($params['size']) ? strtolower($params['size']) : null);
        $strType = (isset($params['type']) ? strtolower($params['type']) : 'png');
        $strBg = (isset($params['bg']) ? strtolower($params['bg']) : '000000');
        $strColor = (isset($params['color']) ? strtolower($params['color']) : 'ffffff');

// Now let's check the parameters.
        if ($strSize == null) {
            $this->error('You have to provide the size of the image. Example: 250x320.');
        }
        if ($strType != 'png' and $strType != 'gif' and $strType != 'jpg') {
            $this->error('The selected type is wrong. You can chose between PNG, GIF or JPG.');
        }
        if (strlen($strBg) != 6 and strlen($strBg) != 3) {
            $this->error('You have to provide the background color as hex. Example: 000000 (for black).');
        }
        if (strlen($strColor) != 6 and strlen($strColor) != 3) {
            $this->error('You have to provide the font color as hex. Example: ffffff (for white).');
        }

// Get width and height from current size.
        $parts = explode('x', $strSize);
        $strWidth = $parts[0];
        $strHeight = isset($parts[1]) ? $parts[1] : $strWidth;

// Check if size and height are digits, otherwise stop the script.
        if (ctype_digit($strWidth) and ctype_digit($strHeight)) {
            // Check if the image dimensions are over 9999 pixel.
            if (($strWidth > 9999) or ($strHeight > 9999)) {
                $this->error('The maximum picture size can be 9999x9999px.');
            }

            // Let's define the font (size. And NEVER go above 9).
            $intFontSize = $strWidth / 16;
            if ($intFontSize < 9) {
                $intFontSize = 9;
            }
            putenv('GDFONTPATH=' . dirname(__FILE__) . '/fonts');
            $strFont = "DroidSansMono.ttf";

            $strText = isset($params['text']) ? $params['text'] : $strWidth . 'x' . $strHeight;

            $this->drawImage($strWidth, $strHeight, $strBg, $strColor, $intFontSize, $strFont, $strText, $strType);

        } else {
            $this->error('You have to provide the size of the image. Example: 250x320.');
        }
    }


    /**
     * @param $text
     */
    protected function error($text)
    {
        $this->drawImage(200, 200, array(255, 255, 255), array(0, 0, 0), 10, 'DroidSansMono.ttf', $text);
    }

    /**
     * Output image to browser
     *
     * @param int    $strWidth
     * @param int    $strHeight
     * @param array  $strBg
     * @param array  $strColor
     * @param int    $intFontSize
     * @param string $strFont
     * @param string $strText
     * @param string $strType
     */
    protected function drawImage(
        $strWidth,
        $strHeight,
        $strBg = array(255, 255, 255),
        $strColor = array(0, 0, 0),
        $intFontSize = 10,
        $strFont = 'DroidSansMono.ttf',
        $strText = '',
        $strType = 'png'
    ) {
        // Create the picture.
        if (!($objImg = @imagecreatetruecolor($strWidth, $strHeight))) {
            die('Sorry, there is a problem with the GD lib.');
        }
        $strBgRgb = $this->html2rgb($strBg);
        $strColorRgb = $this->html2rgb($strColor);
        $strBg = imagecolorallocate($objImg, $strBgRgb[0], $strBgRgb[1], $strBgRgb[2]);
        $strColor = imagecolorallocate($objImg, $strColorRgb[0], $strColorRgb[1], $strColorRgb[2]);

        // Create the actual image.
        imagefilledrectangle($objImg, 0, 0, $strWidth, $strHeight, $strBg);

        // Insert the text.
        $arrTextBox = imagettfbbox($intFontSize, 0, $strFont, $strText);
        $strTextWidth = $arrTextBox[4] - $arrTextBox[1];
        $strTextHeight = abs($arrTextBox[7]) + abs($arrTextBox[1]);
        $strTextX = ($strWidth - $strTextWidth) / 2;
        $strTextY = ($strHeight - $strTextHeight) / 2 + $strTextHeight;
        imagettftext($objImg, $intFontSize, 0, $strTextX, $strTextY, $strColor, $strFont, $strText);

        // Give out the requested type.
        switch ($strType) {
            case 'png':
                header('Content-Type: image/png');
                imagepng($objImg);
                break;
            case 'gif':
                header('Content-Type: image/gif');
                imagegif($objImg);
                break;
            case 'jpg':
                header('Content-Type: image/jpeg');
                imagejpeg($objImg);
                break;
        }

        // Free some memory.
        imagedestroy($objImg);
    }

    /**
     * // Color stuff.
     *
     * @param $strColor
     *
     * @return array
     */
    protected function html2rgb($strColor)
    {
        if (strlen($strColor) == 6) {
            list($strRed, $strGreen, $strBlue) = array($strColor[0] . $strColor[1], $strColor[2] . $strColor[3], $strColor[4] . $strColor[5]);
        } elseif (strlen($strColor) == 3) {
            list($strRed, $strGreen, $strBlue) = array($strColor[0] . $strColor[0], $strColor[1] . $strColor[1], $strColor[2] . $strColor[2]);
        } else {
            $this->error('Valid colors are with length 3 or 6');
        }

        $strRed = hexdec($strRed);
        $strGreen = hexdec($strGreen);
        $strBlue = hexdec($strBlue);

        return array($strRed, $strGreen, $strBlue);
    }
} 