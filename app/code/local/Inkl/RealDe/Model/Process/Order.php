<?php

class Inkl_RealDe_Model_Process_Order
{

	public function process()
	{
		foreach ($this->getOpenRealDeOrders() as $realDeOrder)
		{
			try
			{
				$magentoOrder = $this->createMagentoOrder($realDeOrder);

				$realDeOrder
					->setProcessed(true)
					->setMagentoOrderId($magentoOrder->getId())
					->save();
			} catch (Exception $e)
			{
				$realDeOrder
					->setError(true)
					->setErrorMessage($e->getMessage())
					->save();

				Mage::getSingleton('inkl_realde/mail_order_error')->send(
					[Mage::helper('inkl_realde/config_general')->getNotificationEmail()],
					$realDeOrder
				);
			}
		}
	}

	private function getOpenRealDeOrders()
	{
		$collection = Mage::getResourceModel('inkl_realde/order_collection')
			->addFieldToFilter('processed', ['eq' => 0])
			->addFieldToFilter('error', ['eq' => 0]);

		return $collection;
	}

	private function createMagentoOrder(Inkl_RealDe_Model_Entity_Order $realDeOrder)
	{
		/** @var Mage_Sales_Model_Quote $quote */
		$quote = Mage::getModel('sales/quote');

		$this->setBaseData($quote, $realDeOrder);
		$this->setBillingAddress($quote, $realDeOrder);
		$this->setShippingAddress($quote, $realDeOrder);
		$this->setProducts($quote, $realDeOrder);
		$this->setShippingMethod($quote, $realDeOrder);
		$this->setPaymentMethod($quote, $realDeOrder);

		$quote->collectTotals()->save();

		$service = Mage::getModel('sales/service_quote', $quote);
		$service->submitAll();
		$order = $service->getOrder();

		// $order->sendNewOrderEmail();

		return $order;
	}

	/**
	 * @param Mage_Sales_Model_Quote $quote
	 * @param Inkl_RealDe_Model_Entity_Order $realDeOrder
	 */
	private function setBaseData(Mage_Sales_Model_Quote $quote, Inkl_RealDe_Model_Entity_Order $realDeOrder)
	{
		$customerNote = sprintf('Bestellnummer: %s', $realDeOrder->getRealDeOrderId());
		$customerNote .= sprintf('<br>Rechnung: <a href="%s" target="_blank">%s</a>', $realDeOrder->getInvoiceUrl(), $realDeOrder->getInvoiceNumber());

		$quote
			->setRealDeOrderId($realDeOrder->getRealDeOrderId())
			->setStoreId($realDeOrder->getStoreId())
			->setBaseCurrencyCode($realDeOrder->getCurrencyCode())
			->setCustomerNote($customerNote)
			->setCustomerIsGuest(true)
			->setCustomerEmail($realDeOrder->getEmail())
			->setCustomerFirstname($realDeOrder->getBillingAddressFirstname())
			->setCustomerLastname($realDeOrder->getBillingAddressLastname());
	}

	/**
	 * @param Mage_Sales_Model_Quote $quote
	 * @param Inkl_RealDe_Model_Entity_Order $realDeOrder
	 */
	private function setBillingAddress(Mage_Sales_Model_Quote $quote, Inkl_RealDe_Model_Entity_Order $realDeOrder)
	{
		$quote->getBillingAddress()
			->setCompany()
			->setFirstname('Real')
			->setLastname('Sb-Warenhaus GmbH')
			->setStreet(Mage::helper('inkl_realde/street')->buildStreetData('Metro-Straße', '1', '', $realDeOrder->getStoreId()))
			->setPostcode('40235')
			->setCity('Düsseldorf')
			->setCountryId('DE')
			->setTelephone('02161/403-0');
	}

	/**
	 * @param Mage_Sales_Model_Quote $quote
	 * @param Inkl_RealDe_Model_Entity_Order $realDeOrder
	 */
	private function setShippingAddress(Mage_Sales_Model_Quote $quote, Inkl_RealDe_Model_Entity_Order $realDeOrder)
	{
		$quote->getShippingAddress()
			->setCompany($realDeOrder->getShippingAddressCompany())
			->setFirstname($realDeOrder->getShippingAddressFirstname())
			->setLastname($realDeOrder->getShippingAddressLastname())
			->setStreet(Mage::helper('inkl_realde/street')->buildStreetData($realDeOrder->getShippingAddressStreet(), $realDeOrder->getShippingAddressHouseNumber(), $realDeOrder->getShippingAddressAdditional(), $realDeOrder->getStoreId()))
			->setPostcode($realDeOrder->getShippingAddressPostcode())
			->setCity($realDeOrder->getShippingAddressCity())
			->setCountryId($realDeOrder->getShippingAddressCountryId())
			->setTelephone($realDeOrder->getShippingAddressPhone('-'));
	}

	/**
	 * @param Mage_Sales_Model_Quote $quote
	 * @param Inkl_RealDe_Model_Entity_Order $realDeOrder
	 */
	private function setProducts(Mage_Sales_Model_Quote $quote, Inkl_RealDe_Model_Entity_Order $realDeOrder)
	{
		foreach ($realDeOrder->getOrderItems() as $orderItem)
		{
			$quoteItem = $quote->addProduct($orderItem['product'], $orderItem['qty']);
			$quoteItem->setData('real_de_order_unit_ids', implode(',', $orderItem['order_unit_ids']));
		}

		$quote
			->collectTotals()
			->setTotalsCollectedFlag(false);
	}

	/**
	 * @param Mage_Sales_Model_Quote $quote
	 * @param Inkl_RealDe_Model_Entity_Order $realDeOrder
	 */
	private function setShippingMethod(Mage_Sales_Model_Quote $quote, Inkl_RealDe_Model_Entity_Order $realDeOrder)
	{
		$quote->getShippingAddress()
			->setCollectShippingRates(true)
			->collectShippingRates();

		$shippingMethod = '';
		$shippingCarrier = Mage::helper('inkl_realde/config_order')->getShippingCarrier($realDeOrder->getStoreId());

		foreach ($quote->getShippingAddress()->getShippingRatesCollection() as $rate)
		{
			if ($rate->getCarrier() == $shippingCarrier)
			{
				$shippingMethod = $rate->getCode();
				break;
			}
		}

		$quote->getShippingAddress()
			->setShippingMethod($shippingMethod);
	}

	/**
	 * @param Mage_Sales_Model_Quote $quote
	 * @param Inkl_RealDe_Model_Entity_Order $realDeOrder
	 */
	private function setPaymentMethod(Mage_Sales_Model_Quote $quote, Inkl_RealDe_Model_Entity_Order $realDeOrder)
	{
		$quote->getPayment()->importData(['method' => 'realde']);
	}

}
