<?php

class Inkl_RealDe_Block_Adminhtml_Order_GridContainer extends Mage_Adminhtml_Block_Widget_Grid_Container
{

	public function __construct()
	{
		$this->_controller = 'adminhtml_order_gridContainer';
		$this->_blockGroup = 'inkl_realde';
		$this->_headerText = Mage::helper('inkl_realde')->__('Real.de Orders');

        parent::__construct();

        $this->_removeButton('add');
    }

}
