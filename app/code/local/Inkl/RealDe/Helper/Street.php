<?php

class Inkl_RealDe_Helper_Street extends Mage_Core_Helper_Abstract
{

	/**
	 * Checks if street should be in one line
	 *
	 * @param string $street
	 * @param string $houseNumber
	 * @param string $additional
	 * @param int $storeId
	 * @return mixed
	 */
	public function buildStreetData($street, $houseNumber, $additional, $storeId)
	{
		$streetData = [];
		if (Mage::helper('inkl_realde/config_order')->isOneLineStreet($storeId))
		{
			$streetData[] = sprintf('%s %s', $street, $houseNumber);
		} else
		{
			$streetData[] = $street;
			$streetData[] = $houseNumber;
		}

		if ($additional)
		{
			$streetData[] = $additional;
		}

		return $streetData;
	}

}
