<?php

class Inkl_RealDe_Helper_Config_Order extends Mage_Core_Helper_Abstract
{

	const XML_PATH_SHIPPING_CARRIER = 'inkl_realde/order/shipping_carrier';
	const XML_PATH_ONE_LINE_STREET = 'inkl_realde/order/one_line_street';
	const XML_PATH_ONE_CARRIER_CODE_MAPPINGS = 'inkl_realde/order/carrier_code_mappings';

	public function getShippingCarrier($storeId = null)
	{
		return Mage::getStoreConfig(self::XML_PATH_SHIPPING_CARRIER, $storeId);
	}

	public function isOneLineStreet($storeId = null)
	{
		return Mage::getStoreConfig(self::XML_PATH_ONE_LINE_STREET, $storeId);
	}


	public function getCarrierCodeMapping($carrierCode, $storeId = null)
	{
		$carrierCodeMappings = [];
		foreach (explode("\n", Mage::getStoreConfig(self::XML_PATH_ONE_CARRIER_CODE_MAPPINGS, $storeId)) as $line)
		{
			if (trim($line) == '') continue;

			list($oldCode, $newCode) = explode('|', trim($line));

			$carrierCodeMappings[$oldCode] = $newCode;
		}

		if (isset($carrierCodeMappings[$carrierCode]))
		{
			return $carrierCodeMappings[$carrierCode];
		}

		return $carrierCode;
	}

}
