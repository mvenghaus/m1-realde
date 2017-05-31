<?php

class Inkl_RealDe_Model_Mail_Order_Error extends Inkl_RealDe_Model_Mail_Abstract
{

	public function send(array $to, Inkl_RealDe_Model_Entity_Order $realDeOrder)
	{
		return $this->sendMail($to, $this->getSubject($realDeOrder), $this->getBody($realDeOrder));
	}

	private function getSubject(Inkl_RealDe_Model_Entity_Order $realDeOrder)
	{
		return sprintf(Mage::helper('inkl_realde')->__('Error Order Import - %s'), $realDeOrder->getFilename());
	}

	private function getBody(Inkl_RealDe_Model_Entity_Order $realDeOrder)
	{
		return $realDeOrder->getErrorMessage();
	}

}
