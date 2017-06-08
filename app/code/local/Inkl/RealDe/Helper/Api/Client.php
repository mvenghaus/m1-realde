<?php

use Hitmeister\Component\Api\ClientBuilder;

class Inkl_RealDe_Helper_Api_Client extends Mage_Core_Helper_Abstract
{
	public function build($storeId = null)
	{
		return ClientBuilder::create()
			->setClientKey(Mage::helper('inkl_realde/config_api')->getClientKey($storeId))
			->setClientSecret(Mage::helper('inkl_realde/config_api')->getClientSecret($storeId))
			->build();
	}

}
