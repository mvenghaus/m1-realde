<?php

class Inkl_RealDe_Helper_Config_Product extends Mage_Core_Helper_Abstract
{
	const XML_PATH_EAN_ATTRIBUTE_ID = 'inkl_realde/product/ean_attribute_id';

	public function getEanAttribute($storeId = null)
	{
		$attributeId = Mage::getStoreConfig(self::XML_PATH_EAN_ATTRIBUTE_ID, $storeId);
		
		return Mage::getModel('eav/config')->getAttribute('catalog_product', $attributeId);
	}

}
