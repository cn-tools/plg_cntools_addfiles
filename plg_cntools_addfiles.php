<?php
/**
 * @Copyright
 * @package     Content - WhatsApp Sharing - Plug In
 * @author      Clemens Neubauer {@link https://github.com/cn-tools/}
 * @date        Created on 27-July-2017
 * @link        Project Site {@link https://github.com/cn-tools/plg_cntools_addfiles}
 *
 * @license GNU/GPL
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
defined('_JEXEC') or die('Restricted access');

class PlgSystemplg_cntools_addfiles extends JPlugin
{
	//-- UseObject ------------------------------------------------------------
	protected function UseObject($aObj)
	{
		$lResult = false;
		
		if (is_object($aObj) and property_exists($aObj, 'typ') and property_exists($aObj, 'status'))
		{
			if (($aObj->status == '1') or ($aObj->status == '3'))
			{
				$lResult = true;
			}
			else
			{
				$app = JFactory::getApplication();
				if (($aObj->status == '2') and ($app->isAdmin() === true))
				{
					$lResult = true;
				}
			}
		}

		return $lResult;
	}
	//-- onBeforeRender -------------------------------------------------------
	function onBeforeRender ()
	{
		if ($this->params->get('customfiles'))
		{
//$document->addStyleSheet($url, $type = 'text/css', $media = null, $attribs = array());
//$document->addScript($url, $type = "text/javascript", $defer = false, $async = false);
//$document->addStyleDeclaration($content, $type = 'text/css');
//$document->addScriptDeclaration($content, $type = 'text/javascript');
			$document = JFactory::getDocument();
			$lCustomFiles = $this->params->get('customfiles');
			foreach ($lCustomFiles as $lCustomObj)
			{
				if ($this->UseObject($lCustomObj) === true)
				{
					if (($lCustomObj->typ == 'css_url') and ($lCustomObj->css_url != ''))
					{
						if ($lCustomObj->css_url_type != '')
						{
							$document->addStyleSheet($lCustomObj->css_url, $lCustomObj->css_url_type);
						} else {
							$document->addStyleSheet($lCustomObj->css_url);
						}
					} 
					elseif (($lCustomObj->typ == 'css_code') and ($lCustomObj->css_code != ''))
					{
						if ($lCustomObj->css_code_type != '')
						{
							$document->addStyleDeclaration($lCustomObj->css_code, $lCustomObj->css_code_type);
						} else {
							$document->addStyleDeclaration($lCustomObj->css_code);
						}
					} 
					elseif (($lCustomObj->typ == 'js_url') and ($lCustomObj->js_url != ''))
					{
						if ($lCustomObj->js_url_type != '')
						{
							$document->addScript($lCustomObj->js_url, $lCustomObj->js_url_type);
						} else {
							$document->addScript($lCustomObj->js_url);
						}
					} 
					elseif (($lCustomObj->typ == 'js_code') and ($lCustomObj->js_code != ''))
					{
						if ($lCustomObj->js_code_type != '')
						{
							$document->addScriptDeclaration($lCustomObj->js_code, $lCustomObj->js_code_type);
						} else {
							$document->addScriptDeclaration($lCustomObj->js_code);
						}
					}
				}
			}
		}
		return true;
	}
	
	function onAfterRender()
	{
		if ($this->params->get('customfiles'))
		{
			$app = JFactory::getApplication();
			$lDoSet = false;
			$fullPage = JResponse::getBody();
			$lCustomFiles = $this->params->get('customfiles');
			foreach ($lCustomFiles as $lCustomObj)
			{
				if ($this->UseObject($lCustomObj) === true)
				{
					if (($lCustomObj->typ == 'tag_code') and ($lCustomObj->tag_code != ''))
					{
						//stripos - Find the position of the first occurrence of "php" inside the string
						if ($lCustomObj->tag_code_find == '0')
						{
							if (stripos($fullPage, $lCustomObj->tag_code_type))
							{
								$lPos = stripos($fullPage, $lCustomObj->tag_code_type);
								if ($lCustomObj->tag_code_pos == '0')
								{
									//before
									$fullPage = substr($fullPage, 0, $lPos) . $lCustomObj->tag_code . substr($fullPage, $lPos);
									$lDoSet = true;
								}
								elseif ($lCustomObj->tag_code_pos == '1')
								{
									//after
									$lTagCodeLen = strlen($lCustomObj->tag_code_type);
									$fullPage = substr($fullPage, 0, $lPos + $lTagCodeLen) . $lCustomObj->tag_code . substr($fullPage, $lPos + $lTagCodeLen);
									$lDoSet = true;
								}
							}
						}
						//strripos - Find the position of the last occurrence of "php" inside the string
						elseif ($lCustomObj->tag_code_find == '1')
						{
							if (strripos($fullPage, $lCustomObj->tag_code_type))
							{
								$lPos = strripos($fullPage, $lCustomObj->tag_code_type);
								if ($lCustomObj->tag_code_pos == '0')
								{
									//before
									$fullPage = substr($fullPage, 0, $lPos) . $lCustomObj->tag_code . substr($fullPage, $lPos);
									$lDoSet = true;
								}
								elseif ($lCustomObj->tag_code_pos == '1')
								{
									//after
									$lTagCodeLen = strlen($lCustomObj->tag_code_type);
									$fullPage = substr($fullPage, 0, $lPos + $lTagCodeLen) . $lCustomObj->tag_code . substr($fullPage, $lPos + $lTagCodeLen);
									$lDoSet = true;
								}
							}
						}
					} 
				}
			}
			
			if ($lDoSet == true)
			{
				JResponse::setBody($fullPage);
			}
		}
		
		return true;
	}
}
?>
