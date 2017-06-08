<?php

class Inkl_RealDe_Model_System_Config_Source_Product_Attributes
{

	public function toOptionArray()
	{

		$options = [];
		$options[] = ['value' => '', 'label' => Mage::helper('adminhtml')->__('--Please Select--')];

		$attributes = Mage::getResourceModel('catalog/product_attribute_collection');
		foreach ($attributes as $code => $attribute)
		{
			$options[] = ['value' => $attribute->getId(), 'label' => $attribute->getAttributeCode()];
		}

		return $options;
	}

}
