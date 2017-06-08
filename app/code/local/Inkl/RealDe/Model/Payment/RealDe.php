<?php

class Inkl_RealDe_Model_Payment_RealDe extends Mage_Payment_Model_Method_Abstract
{

	protected $_code = 'realde';

	protected $_isInitializeNeeded = true;
	protected $_canUseInternal = false;
	protected $_canUseForMultishipping = false;
	protected $_canUseCheckout = false;

}
