<?php
/**
 * @subpackage	Content.accordionize
 * @copyright	Copyright (C) 2012 TenTwentyFour Interactive. All rights reserved.
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class plgContentAccordionize extends JPlugin
{
	private static 
		$options = array(
			'heading_level' => 'h3',
			'wrapper_class' => 'accordionize',
			'content_class' => 'accordionContent');
	
	/**
	 * Plugin that accordionizes article contnet
	 *
	 * @param	string	The context of the content being passed to the plugin.
	 * @param	object	The article object.  Note $article->text is also available
	 * @param	object	The article params
	 * @param	int		The 'page' number
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		$this->initParams();
		
		if ($context == 'com_finder.indexer' || strpos($article->text, 'accordionize') === false) 
		{
			return true;
		}
		
		// Force Mootools.more js to be loaded.
		JHtml::_('behavior.framework', true);
		
		$article->text = preg_replace(array('/(<p>(&nbsp;)?)?\{(\/)?accordionize\}(<\/p>)?/ims'), array('{\\3accordionize}'), $article->text);
		
		$a_pattern = '/(\{accordionize\})(.*?)(\{\/accordionize\})/is';
		$accordions = preg_match_all($a_pattern, $article->text, $matches, PREG_SET_ORDER);
		preg_match_all($a_pattern, $article->text, $offset_matches, PREG_OFFSET_CAPTURE);
		
		if (count($matches) > 0)
		{
			/**
			 * Add wrappers within each accordion
			 * matches[0][0] will be the full match
			 * matches[0][1] will be the full match's position
			 * matches[1][0] will be the {accordionize}
			 * matches[1][1] will be the {accordionize}'s position
			 * matches[2][0] will be the content inside {accordionize}{/accordionize}
			 * matches[2][1] will be the content inside {accordionize}{/accordionize} position
			 * matches[3][0] will be the {/accordionize}
			 * matches[3][1] will be the {/accordionize}'s position
			 * Increment this loop by four each time to get to the next set of matches
			 */
			foreach($matches as $index => $match)
			{
				
				$start = $offset_matches[1][$index][1];
				$length = strlen($match[0]);
				$pattern = '/(<\/'.self::$options['heading_level'].'>)(.*?)(<'.self::$options['heading_level'].'>|\{\/accordionize\})/ims';
				$replacement = "\\1\n<div class=\"".self::$options['content_class']."\">\\2</div>\n\\3";
				$replaced = preg_replace($pattern, $replacement, $match[0]);
				
				$article->text = substr_replace($article->text, $replaced, $start, $length);
				
				// re-find the offset
				preg_match_all($a_pattern, $article->text, $offset_matches, PREG_OFFSET_CAPTURE);
			}
		}
		
		$article->text = str_replace(
			array(
				'{accordionize}',
				'{/accordionize}'), 
			array(
				'<div class="'.self::$options['wrapper_class'].'">', 
				'</div>'
			), $article->text);

		$document = JFactory::getDocument();
		$document->addScriptDeclaration("
			window.addEvent('load', function(){
				var myAccordion = new Fx.Accordion($$('div.accordionize h3'), $$('div.accordionContent'), {
					alwaysHide: true,
					onActive: function(toggler, element) {
						toggler.getSiblings('h3.open').removeClass('open')
						toggler.toggleClass('open');
					}
				});
			});
		");
		
	}
	
	private function initParams()
	{
		self::$options = array_merge(
			self::$options,
			$this->params->toArray()
		);
	}

	
}
