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
	//-- onBeforeRender -------------------------------------------------------
	function onBeforeRender ()
	{
		$app = JFactory::getApplication();
		if($app->isAdmin() === true)
		{
			return;
		}

		$document = JFactory::getDocument();
		
		if ($this->params->get('customfiles')){
	//$document->addStyleSheet($url, $type = 'text/css', $media = null, $attribs = array());
	//$document->addScript($url, $type = "text/javascript", $defer = false, $async = false);
	//$document->addStyleDeclaration($content, $type = 'text/css');
	//$document->addScriptDeclaration($content, $type = 'text/javascript');
			$lCustomFiles = $this->params->get('customfiles');
			foreach ($lCustomFiles as $lCustomObj)
			{
				if (is_object($lCustomObj) and property_exists($lCustomObj, 'typ'))
				{
					if ($lCustomObj->typ == 'css_url')
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
							$document->addStyleSheet($lCustomObj->css_url, $lCustomObj->css_code_type);
						} else {
							$document->addStyleDeclaration($lCustomObj->css_code);
						}
					} 
					elseif ($lCustomObj->typ == 'js_url')
					{
						if ($lCustomObj->js_url_type != '')
						{
							$document->addStyleSheet($lCustomObj->css_url, $lCustomObj->js_url_type);
						} else {
							$document->addScript($lCustomObj->js_url);
						}
					} 
					elseif (($lCustomObj->typ == 'js_code') and ($lCustomObj->js_code != ''))
					{
						if ($lCustomObj->js_code_type != '')
						{
							$document->addStyleSheet($lCustomObj->css_url, $lCustomObj->js_code_type);
						} else {
							$document->addScriptDeclaration($lCustomObj->js_code);
						}
					}
				}
			}
		}
		return true;
	}
}
?>
