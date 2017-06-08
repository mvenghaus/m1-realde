<?php

class Inkl_RealDe_Model_Observer
{

	public function sales_order_shipment_save_after(Varien_Event_Observer $observer)
	{
		/* @var $shipment Mage_Sales_Model_Order_Shipment */
		$orderShipment = $observer->getEvent()->getShipment();

		Mage::getSingleton('inkl_realde/export_shipment')->markAsShipped($orderShipment);
	}
}
