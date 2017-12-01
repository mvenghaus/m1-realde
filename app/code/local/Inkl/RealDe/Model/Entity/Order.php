<?php

class Inkl_RealDe_Model_Entity_Order extends Mage_Core_Model_Abstract
{
	private $contentPathValues;

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

	public function loadByRealDeOrderId($realDeOrderId)
	{
		return $this->load($realDeOrderId, 'real_de_order_id');
	}

	public function setContent($content)
	{
		$this->setData('content', json_encode($content));

		return $this;
	}

	public function getContent()
	{
		return json_decode($this->getData('content'), true);
	}

	private function getContentPathValue($path, $default = '')
	{
		if (!$this->contentPathValues)
		{
			$this->contentPathValues = Mage::helper('inkl_realde/array')->buildPathArray($this->getContent());
		}

		return !empty($this->contentPathValues[$path]) ? $this->contentPathValues[$path] : $default;
	}

	public function getCurrencyCode()
	{
		return 'EUR';
	}

	public function getEmail()
	{
		return $this->getContentPathValue('buyer/email');
	}

	public function getBillingAddressCompany($default = '')
	{
		return $this->getContentPathValue('billing_address/company_name', $default);
	}

	public function getBillingAddressFirstname($default = '')
	{
		return $this->getContentPathValue('billing_address/first_name', $default);
	}

	public function getBillingAddressLastname($default = '')
	{
		return $this->getContentPathValue('billing_address/last_name', $default);
	}

	public function getBillingAddressStreet($default = '')
	{
		return $this->getContentPathValue('billing_address/street', $default);
	}

	public function getBillingAddressHouseNumber($default = '')
	{
		return $this->getContentPathValue('billing_address/house_number', $default);
	}

	public function getBillingAddressAdditional($default = '')
	{
		return $this->getContentPathValue('billing_address/additional_field', $default);
	}

	public function getBillingAddressPostcode($default = '')
	{
		return $this->getContentPathValue('billing_address/postcode', $default);
	}

	public function getBillingAddressCity($default = '')
	{
		return $this->getContentPathValue('billing_address/city', $default);
	}

	public function getBillingAddressRegionId($default = '')
	{
		return $default;
	}

	public function getBillingAddressCountryId($default = '')
	{
		return $this->getContentPathValue('billing_address/country', $default);
	}

	public function getBillingAddressPhone($default = '')
	{
		return $this->getContentPathValue('billing_address/phone', $default);
	}

	public function getShippingAddressCompany($default = '')
	{
		return $this->getContentPathValue('shipping_address/company_name', $default);
	}

	public function getShippingAddressFirstname($default = '')
	{
		return $this->getContentPathValue('shipping_address/first_name', $default);
	}

	public function getShippingAddressLastname($default = '')
	{
		return $this->getContentPathValue('shipping_address/last_name', $default);
	}

	public function getShippingAddressStreet($default = '')
	{
		return $this->getContentPathValue('shipping_address/street', $default);
	}

	public function getShippingAddressHouseNumber($default = '')
	{
		return $this->getContentPathValue('shipping_address/house_number', $default);
	}

	public function getShippingAddressAdditional($default = '')
	{
		return $this->getContentPathValue('shipping_address/additional_field', $default);
	}

	public function getShippingAddressPostcode($default = '')
	{
		return $this->getContentPathValue('shipping_address/postcode', $default);
	}

	public function getShippingAddressCity($default = '')
	{
		return $this->getContentPathValue('shipping_address/city', $default);
	}

	public function getShippingAddressRegionId($default = '')
	{
		return $default;
	}

	public function getShippingAddressCountryId($default = '')
	{
		return $this->getContentPathValue('shipping_address/country', $default);
	}

	public function getShippingAddressPhone($default = '')
	{
		return $this->getContentPathValue('shipping_address/phone', $default);
	}

	public function getOrderItems()
	{
		$contentData = $this->getContent();
		if (!isset($contentData['items']))
		{
			throw new Exception('no order items found');
		}

		$eanAttribute = Mage::helper('inkl_realde/config_product')->getEanAttribute($this->getStoreId());
		if (!($eanAttribute->getId() > 0))
		{
			throw new Exception('no ean attribute is set in config');
		}

		$orderItems = [];
		foreach ($contentData['items'] as $item)
		{
			$itemEan = $item['ean'];
			if (strlen($itemEan) === 14)
			{
				$itemEan = substr($itemEan, 0, 13);
			}

			$productCollection = Mage::getResourceModel('catalog/product_collection')
				->addAttributeToFilter('type_id', ['eq' => Mage_Catalog_Model_Product_Type::TYPE_SIMPLE])
				->addAttributeToFilter($eanAttribute->getAttributeCode(), ['eq' => $itemEan]);

			$baseProduct = null;
			foreach ($productCollection as $product)
			{
				$baseProduct = $product;
			}

			if (!$baseProduct)
			{
				throw new Exception(sprintf('product with ean "%s" not found'), $itemEan);
			}

			$product = Mage::getModel('catalog/product')->load($baseProduct->getId())
				->setName($item['title'])
				->setPrice($item['price'])
				->setFinalPrice($item['price'])
				->setSpecialPrice($item['price']);

			$orderItems[] = [
				'order_unit_ids' => $item['order_unit_ids'],
				'product' => $product,
				'qty' => $item['qty']
			];
		}

		return $orderItems;
	}

	public function getInvoiceNumber($default = '')
	{
		return $this->getContentPathValue('invoice/number', $default);
	}

	public function getInvoiceUrl($default = '')
	{
		return $this->getContentPathValue('invoice/url', $default);
	}

}
