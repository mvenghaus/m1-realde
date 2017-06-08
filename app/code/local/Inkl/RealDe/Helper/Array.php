<?php

class Inkl_RealDe_Helper_Array extends Mage_Core_Helper_Abstract
{

	public function buildPathArray($array, $path = '')
	{
		$output = [];
		foreach ($array as $key => $value)
		{
			if (is_array($value))
			{
				$output = array_merge($output, $this->buildPathArray($value, (!empty($path)) ? $path . $key . '/' : $key . '/'));
			} else $output[$path . $key] = $value;
		}
		return $output;
	}

}
