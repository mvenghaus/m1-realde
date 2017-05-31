<?php

use Hitmeister\Component\Api\ClientBuilder;

require_once '../../../../../../shell/abstract.php';

class ShellCommand extends Mage_Shell_Abstract
{

	public function run()
	{

		$client = ClientBuilder::create()
			->setClientKey('7bb347590054ea99b720f1218c4aa588')
			->setClientSecret('64c61a8030be52633323e0bc7b36e2274e134acbce1d3874477de1d83b2dc9a9')
			->build();

		$categories = $client->categories()->find('handy');
		foreach ($categories as $category) {
			echo "Category ID: {$category->id_category}\n";
			echo "Category Name: {$category->name}\n";
		}
	}

}

$shell = new ShellCommand();
$shell->run();
