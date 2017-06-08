<?php

class Inkl_RealDe_Adminhtml_RealDeOrderController extends Mage_Adminhtml_Controller_Action
{

	public function indexAction()
	{
		$this->loadLayout();
		$this->_addContent($this->getLayout()->createBlock('inkl_realde/adminhtml_order_gridContainer'));
		$this->renderLayout();
	}

	protected function _isAllowed()
	{
		return Mage::getSingleton('admin/session')->isAllowed('admin/sales/inkl_realde');
	}

}
