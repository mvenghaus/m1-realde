<?php

class Inkl_RealDe_Model_Process_Order
{

	public function process()
	{
		foreach ($this->getOpenRealDeOrders() as $realDeOrder)
		{
			try
			{
				$documentOrder = Mage::getModel('inkl_realde/document_order')->load($realDeOrder);

				$magentoOrder = $this->createMagentoOrder($documentOrder);

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

	private function createMagentoOrder(Inkl_RealDe_Model_Document_Order $documentOrder)
	{
		/** @var Mage_Sales_Model_Quote $quote */
		$quote = Mage::getModel('sales/quote');

		$this->setBaseData($quote, $documentOrder);

		print_r($quote->debug());

		/*
		$this->setBillingAddress($quote, $documentOrder);
		$this->setShippingAddress($quote, $documentOrder);
		$this->setProducts($quote, $documentOrder);
		$this->setShippingMethod($quote, $documentOrder);
		$this->setPaymentMethod($quote, $documentOrder);

		$quote->collectTotals()->save();

		$service = Mage::getModel('sales/service_quote', $quote);
		$service->submitAll();
		$order = $service->getOrder();

		$order->sendNewOrderEmail();
		*/

		return $order;
	}

	/**
	 * @param Mage_Sales_Model_Quote $quote
	 * @param Inkl_Check24_Model_OpenTrans_Order $openTransOrder
	 */
	private function setBillingAddress(Mage_Sales_Model_Quote $quote, Inkl_Check24_Model_OpenTrans_Order $openTransOrder)
	{
		$quote->getBillingAddress()
			->setCompany($openTransOrder->getInvoiceCompany())
			->setFirstname($openTransOrder->getInvoiceFirstname())
			->setLastname($openTransOrder->getInvoiceLastname())
			->setStreet($openTransOrder->getInvoiceStreet())
			->setPostcode($openTransOrder->getInvoicePostcode())
			->setCity($openTransOrder->getInvoiceCity())
			->setCountryId($openTransOrder->getInvoiceCountryCode())
			->setRegionId($openTransOrder->getInvoiceRegionId())
			->setTelephone($openTransOrder->getInvoicePhone());
	}

	/**
	 * @param Mage_Sales_Model_Quote $quote
	 * @param Inkl_Check24_Model_OpenTrans_Order $openTransOrder
	 */
	private function setBaseData(Mage_Sales_Model_Quote $quote, Inkl_RealDe_Model_Document_Order $documentOrder)
	{
		Mage::log(sprintf('%s - setBaseData | store_id: %s', $documentOrder->getOrderId(), $openTransOrder->getStoreId()), null, 'check24--orders.log');

		$quote
			->setStoreId($openTransOrder->getStoreId())
			->setBaseCurrencyCode($openTransOrder->getCurrencyCode())
			->setCustomerNote($openTransOrder->getOrderId())
			->setCustomerIsGuest(true)
			->setCustomerEmail($openTransOrder->getInvoiceEmail())
			->setCustomerFirstname($openTransOrder->getInvoiceFirstname())
			->setCustomerLastname($openTransOrder->getInvoiceLastname());
	}

	/**
	 * @param Mage_Sales_Model_Quote $quote
	 * @param Inkl_Check24_Model_OpenTrans_Order $openTransOrder
	 */
	private function setShippingAddress(Mage_Sales_Model_Quote $quote, Inkl_Check24_Model_OpenTrans_Order $openTransOrder)
	{
		$quote->getShippingAddress()
			->setCompany($openTransOrder->getDeliveryCompany())
			->setFirstname($openTransOrder->getDeliveryFirstname())
			->setLastname($openTransOrder->getDeliveryLastname())
			->setStreet($openTransOrder->getDeliveryStreet())
			->setPostcode($openTransOrder->getDeliveryPostcode())
			->setCity($openTransOrder->getDeliveryCity())
			->setCountryId($openTransOrder->getDeliveryCountryCode())
			->setRegionId($openTransOrder->getDeliveryRegionId())
			->setTelephone($openTransOrder->getDeliveryPhone());
	}

	/**
	 * @param Mage_Sales_Model_Quote $quote
	 * @param Inkl_Check24_Model_OpenTrans_Order $openTransOrder
	 */
	private function setProducts(Mage_Sales_Model_Quote $quote, Inkl_Check24_Model_OpenTrans_Order $openTransOrder)
	{
		foreach ($openTransOrder->getOrderItems() as $orderItem)
		{
			$quote->addProduct($orderItem['product'], $orderItem['qty']);
		}

		$quote
			->collectTotals()
			->setTotalsCollectedFlag(false);
	}

	/**
	 * @param Mage_Sales_Model_Quote $quote
	 * @param Inkl_Check24_Model_OpenTrans_Order $openTransOrder
	 */
	private function setShippingMethod(Mage_Sales_Model_Quote $quote, Inkl_Check24_Model_OpenTrans_Order $openTransOrder)
	{
		$quote->getShippingAddress()
			->setCollectShippingRates(true)
			->collectShippingRates();

		$shippingMethod = '';
		$shippingCarrier = Mage::helper('inkl_check24/config_order')->getShippingCarrier($openTransOrder->getStoreId());

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
	 * @param Inkl_Check24_Model_OpenTrans_Order $openTransOrder
	 */
	private function setPaymentMethod(Mage_Sales_Model_Quote $quote, Inkl_Check24_Model_OpenTrans_Order $openTransOrder)
	{
		$quote->getPayment()->importData(['method' => 'check24']);
	}

}
