<?php

class Inkl_RealDe_Helper_Config_Api extends Mage_Core_Helper_Abstract
{

	const XML_PATH_CLIENT_KEY = 'inkl_realde/api/client_key';
	const XML_PATH_CLIENT_SECRET = 'inkl_realde/api/client_secret';

	public function getClientKey($storeId = null)
	{
		return Mage::getStoreConfig(self::XML_PATH_CLIENT_KEY, $storeId);
	}

	public function getClientSecret($storeId = null)
	{
		return Mage::getStoreConfig(self::XML_PATH_CLIENT_SECRET, $storeId);
	}


}
