<?php

class Inkl_RealDe_Model_Document_Order
{

	private $realDeOrder;
	private $data = [];

	public function load(Inkl_RealDe_Model_Entity_Order $realDeOrder)
	{
		$this->$realDeOrder = $realDeOrder;
	}

	public function getStoreId()
	{
		return $this->realDeOrder->getStoreId();
	}

	public function getOrderId()
	{
		return (isset($this->data['id_order']) ? $this->data['id_order'] : '');
	}

	public function getCurrencyCode()
	{
		return 'EUR';
	}

	public function getInvoiceEmail()
	{
		return $this->xpathQuery("//PARTY_ROLE[text()='invoice']/following-sibling::ADDRESS//EMAIL");
	}

	public function getInvoiceCompany()
	{
		return $this->xpathQuery("//PARTY_ROLE[text()='invoice']/following-sibling::ADDRESS//NAME");
	}

	public function getInvoiceFirstname()
	{
		return $this->xpathQuery("//PARTY_ROLE[text()='invoice']/following-sibling::ADDRESS//NAME2");
	}

	public function getInvoiceLastname()
	{
		$lastname = $this->xpathQuery("//PARTY_ROLE[text()='invoice']/following-sibling::ADDRESS//NAME3");

		$lastname = str_replace(' (nur Rechnungsadresse)', '', $lastname);

		return $lastname;
	}

	public function getInvoiceStreet()
	{
		return Mage::helper('inkl_realde/street')->buildStreetData(
			$this->xpathQuery("//PARTY_ROLE[text()='invoice']/following-sibling::ADDRESS//STREET"),
			$this->xpathQuery("//PARTY_ROLE[text()='invoice']/following-sibling::ADDRESS//ADDRESS_REMARKS"),
			$this->getStoreId()
		);
	}

	public function getInvoicePostcode()
	{
		return $this->xpathQuery("//PARTY_ROLE[text()='invoice']/following-sibling::ADDRESS//ZIP");
	}

	public function getInvoiceCity()
	{
		return $this->xpathQuery("//PARTY_ROLE[text()='invoice']/following-sibling::ADDRESS//CITY");
	}

	public function getInvoiceCountryCode()
	{
		return $this->xpathQuery("//PARTY_ROLE[text()='invoice']/following-sibling::ADDRESS//COUNTRY_CODED");
	}

	public function getInvoiceRegionId()
	{
		return '';
	}

	public function getInvoicePhone()
	{
		return $this->xpathQuery("//PARTY_ROLE[text()='invoice']/following-sibling::ADDRESS//PHONE");
	}

	public function getDeliveryCompany()
	{
		return $this->xpathQuery("//PARTY_ROLE[text()='delivery']/following-sibling::ADDRESS//NAME");
	}

	public function getDeliveryFirstname()
	{
		return $this->xpathQuery("//PARTY_ROLE[text()='delivery']/following-sibling::ADDRESS//NAME2");
	}

	public function getDeliveryLastname()
	{
		return $this->xpathQuery("//PARTY_ROLE[text()='delivery']/following-sibling::ADDRESS//NAME3");
	}

	public function getDeliveryStreet()
	{
		return Mage::helper('inkl_check24/street')->buildStreetData(
			$this->xpathQuery("//PARTY_ROLE[text()='delivery']/following-sibling::ADDRESS//STREET"),
			$this->xpathQuery("//PARTY_ROLE[text()='delivery']/following-sibling::ADDRESS//ADDRESS_REMARKS"),
			$this->getStoreId()
		);
	}

	public function getDeliveryPostcode()
	{
		return $this->xpathQuery("//PARTY_ROLE[text()='delivery']/following-sibling::ADDRESS//ZIP");
	}

	public function getDeliveryCity()
	{
		return $this->xpathQuery("//PARTY_ROLE[text()='delivery']/following-sibling::ADDRESS//CITY");
	}

	public function getDeliveryCountryCode()
	{
		return $this->xpathQuery("//PARTY_ROLE[text()='delivery']/following-sibling::ADDRESS//COUNTRY_CODED");
	}

	public function getDeliveryRegionId()
	{
		return '';
	}

	public function getDeliveryPhone()
	{
		return $this->xpathQuery("//PARTY_ROLE[text()='delivery']/following-sibling::ADDRESS//PHONE");
	}

}
