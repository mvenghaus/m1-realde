<?php

use Inkl\AddressUtils\StreetSplitter;

class Inkl_RealDe_Helper_Street extends Mage_Core_Helper_Abstract
{

	public function buildStreetData($street, $remarks, $storeId)
	{
		$streetData = [];

		if (Mage::helper('inkl_realde/config_order')->shouldSplitStreet($storeId))
		{
			$parts = (new StreetSplitter())->split($street);
			foreach ($parts as $part)
			{
				$streetData[] = $part;
			}
		} else
		{
			$streetData[] = $street;
		}

		if ($remarks)
		{
			$streetData[] = $remarks;
		}

		return $streetData;
	}

}
