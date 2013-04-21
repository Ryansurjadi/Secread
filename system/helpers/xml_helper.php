<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter XML Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/xml_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Convert Reserved XML characters to Entities
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('xml_convert'))
{
	function xml_convert($str, $protect_all = FALSE)
	{
		$temp = '__TEMP_AMPERSANDS__';

		// Replace entities to temporary markers so that
		// ampersands won't get messed up
		$str = preg_replace("/&#(\d+);/", "$temp\\1;", $str);

		if ($protect_all === TRUE)
		{
			$str = preg_replace("/&(\w+);/",  "$temp\\1;", $str);
		}

		$str = str_replace(array("&","<",">","\"", "'", "-"),
							array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;", "&#45;"),
							$str);

		// Decode the temp markers back to entities
		$str = preg_replace("/$temp(\d+);/","&#\\1;",$str);

		if ($protect_all === TRUE)
		{
			$str = preg_replace("/$temp(\w+);/","&\\1;", $str);
		}

		return $str;
	}
}

/* 
	**********************************************************
	Usage
	
	$this->load->helper('xml');

	$dom = xml_dom();
	$book = xml_add_child($dom, 'book');
	
	xml_add_child($book, 'title', 'Hyperion');
	$author = xml_add_child($book, 'author', 'Dan Simmons');		
	xml_add_attribute($author, 'birthdate', '1948-04-04');

	xml_print($dom);
	
	**********************************************************
	Result

	<?xml version="1.0"?>
	<book>
	  <title>Hyperion</title>
	  <author birthdate="1948-04-04">Dan Simmons</author>
	</book>

 */


if ( ! function_exists('xml_dom'))
{
	function xml_dom()
	{
		return new DOMDocument('1.0');
	}
}


if ( ! function_exists('xml_add_child'))
{
	function xml_add_child($parent, $name, $value = NULL, $cdata = FALSE)
	{
		if($parent->ownerDocument != "")
		{
			$dom = $parent->ownerDocument;			
		}
		else
		{
			$dom = $parent;
		}
		
		$child = $dom->createElement($name);		
		$parent->appendChild($child);
		
		if($value != NULL)
		{
			if ($cdata)
			{
				$child->appendChild($dom->createCdataSection($value));
			}
			else
			{
				$child->appendChild($dom->createTextNode($value));
			}
		}
		
		return $child;		
	}
}


if ( ! function_exists('xml_add_attribute'))
{
	function xml_add_attribute($node, $name, $value = NULL)
	{
		$dom = $node->ownerDocument;			
		
		$attribute = $dom->createAttribute($name);
		$node->appendChild($attribute);
		
		if($value != NULL)
		{
			$attribute_value = $dom->createTextNode($value);
			$attribute->appendChild($attribute_value);
		}
		
		return $node;
	}
}


if ( ! function_exists('xml_print'))
{
	function xml_print($dom, $return = FALSE)
	{
		$dom->formatOutput = TRUE;
		$xml = $dom->saveXML();
		if ($return)
		{
			return $xml;
		}
		else
		{
			echo $xml;
		}
	}
}
// ------------------------------------------------------------------------

/* End of file xml_helper.php */
/* Location: ./system/helpers/xml_helper.php */