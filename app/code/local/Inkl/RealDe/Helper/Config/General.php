<?php

class Inkl_RealDe_Helper_Config_General extends Mage_Core_Helper_Abstract
{

	const XML_PATH_ENABLED = 'inkl_realde/general/enabled';
	const XML_PATH_NOTIFICATION_EMAIL = 'inkl_realde/general/notification_email';

	public function isEnabled($storeId = null)
	{
		return Mage::getStoreConfigFlag(self::XML_PATH_ENABLED, $storeId);
	}

	public function getNotificationEmail()
	{
		return Mage::getStoreConfig(self::XML_PATH_NOTIFICATION_EMAIL);
	}

}
