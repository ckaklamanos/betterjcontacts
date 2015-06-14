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
		switch($option) {
			case 'com_contact':
				if ($app->isAdmin()) {
					JForm::addFormPath(__DIR__ . '/forms');
					$form->loadFile('betterjcontacts', false);
				}else{
					if( $form -> getName() == 'com_contact.contact' ){
						
						var_dump( $form);
						var_dump( $app->input->get('id'));
						
						jimport('joomla.application.component.model');
						JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_contact/models');
						$contactModel = JModelLegacy::getInstance( 'Contact', 'ContactModel' );
						$contact = $contactModel ->getItem($app->input->get('id'));
						
						var_dump(json_decode ( $contact->params->get('contact_extra_fields')) );
						
						
						$element = new SimpleXMLElement('<fieldset name="any_name">
                                    <field name="onfly"
                                          type="text"
                                          label="onfly"
                                          description="onfly desc"
                                          class="inputbox"
                                          size="30"
                                          required="true" />
                                  </fieldset>');
$form->setField($element);

					}

					}
				return true;
		}
		return true;
	}

	

	
}
