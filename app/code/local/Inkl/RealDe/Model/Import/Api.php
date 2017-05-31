<?php

use Hitmeister\Component\Api\ClientBuilder;

class Inkl_RealDe_Model_Import_Api
{
	private $apiConfigHelper;
	private $generalConfigHelper;

	public function __construct()
	{
		$this->generalConfigHelper = Mage::helper('inkl_realde/config_general');
		$this->apiConfigHelper = Mage::helper('inkl_realde/config_api');
	}

	public function import()
	{
		foreach (Mage::app()->getStores() as $store)
		{
			if (!$this->hasValidSettings($store))
			{
				continue;
			}

			$orders = $this->findOrders($store);
			foreach ($orders as $order)
			{
				Mage::getModel('inkl_realde/entity_order')
					->setStoreId($store->getId())
					->setContent($order)
					->save();
			}
		}
	}

	private function findOrders(Mage_Core_Model_Store $store)
	{
		try
		{
			$client = ClientBuilder::create()
				->setClientKey($this->apiConfigHelper->getClientKey($store->getId()))
				->setClientSecret($this->apiConfigHelper->getClientSecret($store->getId()))
				->build();



		} catch (Exception $e)
		{
			print_r($e->getMessage());
		}

		return [];
	}

	private function hasValidSettings(Mage_Core_Model_Store $store)
	{
		return ($this->generalConfigHelper->isEnabled($store->getId()) &&
			$this->apiConfigHelper->getClientKey($store->getId()) &&
			$this->apiConfigHelper->getClientSecret($store->getId()));
	}

}
