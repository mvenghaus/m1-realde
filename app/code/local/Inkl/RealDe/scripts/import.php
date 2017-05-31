<?php

require_once '../../../../../../shell/abstract.php';

class ShellCommand extends Mage_Shell_Abstract
{

	public function run()
	{
		Mage::getSingleton('inkl_realde/import_api')->import();
	}

}

$shell = new ShellCommand();
$shell->run();
