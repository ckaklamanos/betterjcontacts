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
class PlgContactBetterjcontacts extends JPlugin
{
	/**
	 * Database object
	 *
	 * @var    JDatabaseDriver
	 * @since  3.3
	 */

    
	/**
	 * Plugin that retrieves contact information for contact
	 *
	 * @param   string   $context  The context of the content being passed to the plugin.
	 * @param   mixed    &$row     An object with a "text" property
	 * @param   mixed    $params   Additional parameters. See {@see PlgContentContent()}.
	 * @param   integer  $page     Optional page number. Unused. Defaults to zero.
	 *
	 * @return  boolean	True on success.
	 */
	public function onSubmitContact(&$contact, &$data)
	{
        foreach ($data as $key => $value) {
            if( $key!="contact_message" && $key!="contact_subject" && $key!="contact_email" && $key!="contact_name" ){
                $data["contact_message"].="\r\n\r\n".$key.":".$value;
            }
        }

	}


    
}
