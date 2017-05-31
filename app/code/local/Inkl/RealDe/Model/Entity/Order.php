<?php

class Inkl_RealDe_Model_Entity_Order extends Mage_Core_Model_Abstract
{

	protected function _construct()
	{
		$this->_init('inkl_realde/order');
	}

	public function _beforeSave()
	{
		$this->setUpdatedAt(Varien_Date::now());

		if (!$this->getCreatedAt())
		{
			$this->setCreatedAt(Varien_Date::now());
		}

		return parent::_beforeSave();
	}

}
