<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * CSS Link
 *
 * Generating the link tag for a css file from the assets directory
 *
 * @access	public
 * @param	string
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('link_css'))
{
	function link_css($filename,$media = NULL)
	{
		$filename = $filename.'.css';
		if(isset($media))
			$media = 'media="'.$media.'"';
		$string = '<link rel="stylesheet" type="text/css" '.$media.' href="assets/css/'.$filename.'" />';
		return $string;
	}
}

/**
 * JS Link
 *
 * Generating the link tag for a local JavaScript File
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('link_js')) {	
	function link_js($filename,$additional = NULL) {
		$filename = $filename.'.js';
		$string = '<script type="text/javascript" src="assets/js/'.$filename.'" '.$additional.'></script>';
		return $string;
	}
}