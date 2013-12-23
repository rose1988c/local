<?php
$maxTiles = 7;                    //The number of tiles at the center (widest part) of the board
$minTiles = 4;                    //The number of tiles at the edges of the board
$side = 25;                        //The length of the sides of the tiles in pixels
$bgColor = array(0, 0, 0);        //The background color in RGB format
$fgColor = array(255, 255, 255);//The foreground color in RGB format

//Calculated values
$widthInTiles = array(1);            //In our example: 7, 6, 5, 4
$rowsInTiles = count($widthInTiles)*2-1;                //the total number of rows on our board
$xSide = $side*sin(deg2rad(60));                        //the length of the x-part of the angled sides
$ySide = $side*sin(deg2rad(30));                        //the length of the y-part of the angled sides
$boardWidth = $xSide*$widthInTiles[0]*2;                //The entire width of the board
$boardHeight = $rowsInTiles*($side + $ySide) + $ySide;    //The entire height of the board

// create a blank image and allocate the foreground, background colors
$image = imagecreate($boardWidth, $boardHeight);
$bg = imagecolorallocate($image, $bgColor[0], $bgColor[1], $bgColor[2]);
$fg = imagecolorallocate($image, $fgColor[0], $fgColor[1], $fgColor[2]);

// draw the board
$row = 0;
foreach($widthInTiles as $tiles)
{
    for ($i = 0; $i < $tiles+1; $i++)
    {
        $x1 = $row*$xSide + $i*$xSide*2;
        $y1 = $boardHeight/2;
        $y1Dif = ($side/2) + $row*($side+$ySide);

        $x2 = $x1 + $xSide;
        $y2 = $y1;
        $y2Dif = $ySide;

        $x3 = $x2 + $xSide;

        if ($i < $tiles)
        {
            imageline($image, $x1, $y1 - $y1Dif, $x2, $y2 - $y1Dif - $y2Dif, $fg);
            imageline($image,  $x1, $y1 + $y1Dif, $x2, $y2 + $y1Dif + $y2Dif, $fg);
            imageline($image, $x2, $y2 - $y1Dif - $y2Dif, $x3, $y1 - $y1Dif, $fg);
            imageline($image, $x2, $y2 + $y1Dif + $y2Dif, $x3, $y1 + $y1Dif, $fg);
        }

        imageline($image, $x1, $y1 - $y1Dif, $x1, $y1 - $y1Dif + $side, $fg);
        imageline($image, $x1, $y1 + $y1Dif, $x1, $y1 + $y1Dif - $side, $fg);
    }
    $row++;
}

// output the picture
header("Content-type: image/png");
imagepng($image);
imagedestroy($image);