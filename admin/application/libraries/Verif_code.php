<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Verif_code
{
	function hexrgb($hexstr)
	{
		$int = hexdec ( $hexstr );
		return array (
				"red" => 0xFF & ($int >> 0x10),
				"green" => 0xFF & ($int >> 0x8),
				"blue" => 0xFF & $int 
		);
	}
	
	/**
	 * 刷新验证码
	 *
	 * @param $code_name 验证码名称        	
	 */
	public function code($code_name = 'code', $code = '')
	{
		$image_width = 65;
		$image_height = 40;
		
		
		$font = dirname ( __FILE__ ) . "/monofont.ttf"; // 字体路径
		
		$random_dots = 50;
		$random_lines = 20;
		//$captcha_text_color = "0x142864";
		//$captcha_noice_color = "0x102864";

		$captcha_text_color_arr = array('0xeebf6e','0x7abe95','0xbe7a87','0xbe7aac','0xad84c9','0x84a1c9','0x84c0c9','0x91c984','0xa9c281','0xd3bc94');
		$captcha_noice_color_arr = array('0xeebf6e','0x7abe95','0xbe7a87','0xbe7aac','0xad84c9','0x84a1c9','0x84c0c9','0x91c984','0xa9c281','0xd3bc94');
		$background_color_arr = array('0xa9d3d5','0xcacbf3','0xf3cae1','0xf3cbca','0xdbd3d2','0xf6e3d0','0xdfdbd6','0xeef5d7','0xdef7cf','0xcff7df');

		$color_idx = rand(0,9);
		$captcha_text_color = $captcha_text_color_arr[$color_idx];
		$captcha_noice_color = $captcha_noice_color_arr[$color_idx];
		$background_color_tmp = $background_color_arr[$color_idx];

		
		header ( 'Content-Type: image/jpeg' ); // defining the image type to be shown in browser widow
		$font_size = $image_height * 0.75;
		
		$image = @imagecreate ( $image_width, $image_height );
		$background_color_tmp = $this->hexrgb($background_color_tmp);
		
		$background_color = imagecolorallocate ( $image, $background_color_tmp ['red'], $background_color_tmp ['green'], $background_color_tmp ['blue'] );
		
		$arr_text_color = $this->hexrgb ( $captcha_text_color );
		$text_color = imagecolorallocate ( $image, $arr_text_color ['red'], $arr_text_color ['green'], $arr_text_color ['blue'] );
		
		$arr_noice_color = $this->hexrgb ( $captcha_noice_color );
		$image_noise_color = imagecolorallocate ( $image, $arr_noice_color ['red'], $arr_noice_color ['green'], $arr_noice_color ['blue'] );
		
		/* generating the dots randomly in background */
		for($i = 0; $i < $random_dots; $i ++)
		{
			imagefilledellipse ( $image, mt_rand ( 0, $image_width ), mt_rand ( 0, $image_height ), 2, 3, $image_noise_color );
		}
		
		/* generating lines randomly in background of image */
		for($i = 0; $i < $random_lines; $i ++)
		{
			imageline ( $image, mt_rand ( 0, $image_width ), mt_rand ( 0, $image_height ), mt_rand ( 0, $image_width ), mt_rand ( 0, $image_height ), $image_noise_color );
		}
		
		/* create a text box and add 6 letters code in it */
		$textbox = imagettfbbox ( $font_size, 0, $font, $code );
		
		$x = ($image_width - $textbox [4]) / 2;
		$y = ($image_height - $textbox [5]) / 2;
		imagettftext ( $image, $font_size, 0, $x, $y, $text_color, $font, $code );
		
		/* Show captcha image in the page html page */
		imagejpeg ( $image ); // showing the image
		imagedestroy ( $image ); // destroying the image instance
	}

}


// END Sms class

/* End of file Verif_code.php */
/* Location: ./application/libraries/Verif_code.php */