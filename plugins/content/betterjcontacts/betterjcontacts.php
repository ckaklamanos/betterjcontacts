<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.Contact
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Contact Plugin
 *
 * @since  3.2
 */
class PlgContentBetterjcontacts extends JPlugin
{
	/**
	 * Database object
	 *
	 * @var    JDatabaseDriver
	 * @since  3.3
	 */
	protected $db;

	/**
	 * Load the language file on instantiation.
	 * Note this is only available in Joomla 3.1 and higher.
	 * If you want to support 3.0 series you must override the constructor
	 *
	 * @var boolean
	 * @since 3.1
	 */
	protected $autoloadLanguage = true;
	
	public function onContentPrepareForm($form, $data) {
		
		$app = JFactory::getApplication();
		$option = $app->input->get('option');
		$extension = $app->input->get('extension');
		
		switch($option) {
			
			case 'com_categories':
				if ( $app->isAdmin() && $extension=='com_contact' ) {
					
					JForm::addFormPath(__DIR__ . '/forms');
					$form->loadFile('category', false);
					
					$document = JFactory::getDocument();
					
					JHtml::_('script', Juri::root() . 'plugins/content/betterjcontacts/assets/js/js.js');
					
				}
				return true;
			
			case 'com_contact':
				if ($app->isAdmin()) {
					
					
					JHtml::_('script', Juri::root() . 'plugins/content/betterjcontacts/assets/js/js.js');
					
					JForm::addFormPath(__DIR__ . '/forms');
					$form->loadFile('contact', false);
				}else{
					if( $form -> getName() == 'com_contact.contact' ){
						

						jimport('joomla.application.component.model');
						

						$extraFields = null;
						JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_contact/models');
						$contactModel = JModelLegacy::getInstance( 'Contact', 'ContactModel' );
						$contact = $contactModel ->getItem($app->input->get('id'));
						$extraFieldsSource = $contact->params->get('extrafieldssource');
						
						switch($extraFieldsSource) {
							case 'category':
								$catid = $contact ->catid;
								
								JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_contact/models');
								$categoryModel = JModelLegacy::getInstance('Category', 'ContactModel', array('ignore_request' => true));
								$categoryModel->setState('category.id', $catid);
								$category = $categoryModel ->getItems( $catid );
								$extraFields = $category[0]->params->get('contact_extra_fields');
								
							case 'own':
								
								$extraFields = $contact->params->get('contact_extra_fields');
						}
						
						//var_dump($extraFields);
						


						
						if($extraFields){
							//var_dump($this->createExtraFieldsXMLString( $extraFields ));
							$element = new SimpleXMLElement($this->createExtraFieldsXMLString( $extraFields ));
							
							
							$form->setFieldAttribute ('contact_name','required','false');
							$form->setField($element);
						
						}

					}

					}
				return true;
		}
		return true;
	}

	
	private function createExtraFieldsXMLString( $extraFields ){
		
		$extraFields = json_decode ( $extraFields );
		
		if(!$extraFields)
			return false;
		
		$extraFieldsArray = array();
		
		foreach(  $extraFields  as $key => $value) {
		
			foreach( $value as $k => $v ) {
				
				$extraFieldsArray[$k][$key] = $v;
			}
			
			
		}
		
		$xml = '<fieldset name="extra_fields">';
		
		foreach( $extraFieldsArray as $key => $value) {
									var_dump($value);
			//Field must be enabled
			if( !$value['enabled'] )
			{
				continue;
			}
			
			//Field name attribute must not be empty
			if( !trim($value['name']) )
			{
				continue;
			}
			
			
			$xml.= '<field ';
			
			foreach( $value as $k => $v)
			{
				$xml.= $k.'="'.trim($v).'" ';
			}
			
			$xml.= '/>';

		}
		
		
		$xml.= '</fieldset>';
		
		return $xml;
		
	}
	
}
