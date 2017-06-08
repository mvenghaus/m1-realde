<?php

class Inkl_RealDe_Model_Export_Shipment
{

	public function markAsShipped(Mage_Sales_Model_Order_Shipment $orderShipment)
	{
		$order = $orderShipment->getOrder();
		$client = Mage::helper('inkl_realde/api_client')->build($order->getStoreId());

		$carrierCode = null;
		$trackingNumber = null;

		/** @var Mage_Sales_Model_Order_Shipment_Track $track */
		foreach ($orderShipment->getTracksCollection() as $track)
		{
			$carrierCode = $track->getCarrierCode();
			$trackingNumber = $track->getTrackNumber();
		}

		if ($carrierCode && $trackingNumber)
		{
			foreach ($order->getAllVisibleItems() as $orderItem)
			{
				if (!$order->getData('real_de_order_unit_shipped'))
				{
					$client->orderUnits()->send($orderItem->getData('real_de_order_unit_id'), $carrierCode, $trackingNumber);
				}
			}
		}
	}

}
