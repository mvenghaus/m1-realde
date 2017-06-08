<?php

class Inkl_RealDe_Model_Cron_Order
{

	public function run()
	{
		Mage::getSingleton('inkl_realde/import_api')->import();
		Mage::getSingleton('inkl_realde/process_order')->process();
	}

}
