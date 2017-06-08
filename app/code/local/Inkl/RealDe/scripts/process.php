<?php

require_once '../../../../../../shell/abstract.php';

class ShellCommand extends Mage_Shell_Abstract
{

	public function run()
	{
		Mage::getSingleton('inkl_realde/process_order')->process();


	}

}

$shell = new ShellCommand();
$shell->run();
