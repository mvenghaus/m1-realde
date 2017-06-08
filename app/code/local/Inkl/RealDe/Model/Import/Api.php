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

			$this->importOrders($store);
		}
	}

	private function importOrders(Mage_Core_Model_Store $store)
	{
		try
		{
			$client = ClientBuilder::create()
				->setClientKey($this->apiConfigHelper->getClientKey($store->getId()))
				->setClientSecret($this->apiConfigHelper->getClientSecret($store->getId()))
				->build();

			$orderUnits = $client->orderUnits()->find('need_to_be_sent');
			foreach ($orderUnits as $orderUnit)
			{
				$realDeOrder = Mage::getModel('inkl_realde/entity_order')->loadByRealDeOrderId($orderUnit->id_order);

				$realDeOrder
					->setStoreId($store->getId())
					->setRealDeOrderId($orderUnit->id_order)
					->setContent($this->combineOrderUnits($realDeOrder->getContent(), $orderUnit->jsonSerialize()))
					->save();
			}
		} catch (Exception $e)
		{
			print_r($e->getMessage());
		}

		return [];
	}

	private function combineOrderUnits($oldData, $newData)
	{
		$orderUnitId = $newData['id_order_unit'];
		if (isset($oldData['order_unit_ids'][$orderUnitId]))
		{
			return $oldData;
		}

		unset($newData['id_order_unit']);

		$orderUnitPrice = ($newData['price'] / 100);
		unset($newData['price']);
		unset($newData['revenue_gross']);
		unset($newData['revenue_net']);

		$orderUnitItem = $newData['item'];
		unset($newData['item']);

		if (!$oldData)
		{
			$oldData = $newData;
			$oldData['items'] = [];
			$oldData['order_unit_ids'] = [];
		}

		$orderUnitItemId = $orderUnitItem['id_item'];
		if (!isset($oldData['items'][$orderUnitItemId]))
		{
			$oldData['items'][$orderUnitItemId] = [
				'order_unit_item_id' => $orderUnitItemId,
				'order_unit_id' => $orderUnitId,
				'title' => $orderUnitItem['title'],
				'ean' => current($orderUnitItem['eans']),
				'price' => $orderUnitPrice,
				'qty' => 0,
			];
		}

		$oldData['items'][$orderUnitItemId]['qty'] += 1;
		$oldData['order_unit_ids'][$orderUnitId] = $orderUnitId;

		return $oldData;
	}

	private function hasValidSettings(Mage_Core_Model_Store $store)
	{
		return ($this->generalConfigHelper->isEnabled($store->getId()) &&
			$this->apiConfigHelper->getClientKey($store->getId()) &&
			$this->apiConfigHelper->getClientSecret($store->getId()));
	}

}
