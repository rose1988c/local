<?php

/*
* from:http://www.codepearl.com
*/
class Stego{
	/*..........*/
	//@param $i image file
	//returns jpg or png or false if it isn't one of them
	/*.........*/
	private function mimeType($i)
    {
		$mime = getimagesize($i);
		
		$return = array($mime[0],$mime[1]);
		
		switch ($mime['mime'])
		{
			case 'image/jpeg':
					$return[] = 'jpg';
					return $return;
			case 'image/png':
					$return[] = 'png';
					return $return;
			default:
					return false;
		}
    }
	
	/*..........*/
	//@param $i image file
	//returns image resource
	/*.........*/
	private function createImage($i)
    {
		$mime = $this->mimeType($i);
		
		switch($mime[2]){
			case 'jpg':
				return imagecreatefromjpeg ($i);
			case 'png':
				return imagecreatefrompng ($i);
			
		}
			
    }
	
	
	/*..........*/
	//@param $text text
	//returns array of ASCII values of text
	/*.........*/
	private function toASCII($text)
	{
		$text = str_split($text);
		
		for($i=0;$i<count($text);$i++)
		{
			$text[$i] = ord($text[$i]);
			
		}
		
		return $text;
	}
	
	/*..........*/
	//@param $arr text
	//returns text
	/*.........*/
	private function fromASCII($arr)
	{
		$text = '';
		
		foreach($arr as $a)
		{
			
			$text .= chr($a);
		}
		
		return $text;
	}
	
	/*..........*/
	//@param $text text to place in image
	//@param $i image file
	//@param $steps which pixels should be changed
	//returns image resource or false if there is not enough space in the image to do it
	/*.........*/
	public function stegoIt($text,$i,$steps)
	{
		$im				=	$this->createImage($i);
		
		$mime			=	$this->mimeType($i);
		
		$pixelCount		= $mime[0]*$mime[1];
		
		/*THIS IS THE POINT WHERE YOU CAN CHANGE THE TEXT*/
		//encrypt it maybe?  The text is in $text
		/**/
		
		$ASCII			= $this->toASCII('5TEG0'.$text.'5TEG0');
		
		$textCounter	= 0;
		
		
		
		//Is there enough space for this text?
		if($pixelCount/$steps < strlen ('5TEG0'.$text.'5TEG0')/3)return false;
		
		
		for($x = 1;$x<=$mime[0];$x++)
		{
			for($y = 1;$y<=$mime[1];$y++)
			{
				if(($x*$y)%$steps == 0){
					$r = $ASCII[$textCounter];
					$g = $ASCII[$textCounter+1];
					$b = $ASCII[$textCounter+2];
					$textCounter+=3;
					imagesetpixel($im,$x,$y,imagecolorallocate($im, $r, $g, $b));
					if($textCounter > strlen ('5TEG0'.$text.'5TEG0'))return $im;
				}
				
			}
			
		}
			
		
		
		return $im;
		
	}
	
	/*..........*/
	//@param $i image file
	//@param $steps which pixels should be coded
	//returns image resource or false if there is not enough space in the image to do it
	/*.........*/
	public function unStegoIt($i,$steps)
	{
		$im				=	$this->createImage($i);
		
		$mime			=	$this->mimeType($i);
		
		$pixelCount		= $mime[0]*$mime[1];
		
		$textCounter	= 0;
		
		$ASCII			= array();
		

		
		
		for($x = 1;$x<=$mime[0];$x++)
		{
			for($y = 1;$y<=$mime[1];$y++)
			{
				if(($x*$y)%$steps == 0){
					$rgb = imagecolorat($im, $x, $y);
					$ASCII[] = ($rgb >> 16) & 0xFF;
					$ASCII[] = ($rgb >> 8) & 0xFF;
					$ASCII[] = $rgb & 0xFF;
					
				}
				
			}
			
		}
		preg_match('#5TEG0(.+?)5TEG0#',$this->fromASCII($ASCII),$txt);
		
		/*THIS IS THE POINT WHERE YOU CAN CHANGE THE TEXT*/
		//decrypt it maybe? The text is in $txt[1]
		/**/
		
		return $txt[1];
	}
}
