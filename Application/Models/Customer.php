<?php

namespace QuickDashboard\Application\Models;

use phpOMS\Datatypes\Location;

class Customer
{
	private $id = 0;
	private $name = '';
	private $location = null;
	private $salesRep = '';
	private $customerGroup = '';
	private $createdAt = null;

	public function __construct(int $id, string $name, Location $location, string $salesRep, \DateTime $createdAt, string $customerGroup) {
		$this->id = $id;
		$this->name = $name;
		$this->location = $location;
		$this->salesRep = $salesRep;
		$this->createdAt = $createdAt;
		$this->customerGroup = $customerGroup;
	}

	public function getId() : int 
	{
		return $this->id;
	}

	public function getName() : string 
	{
		return $this->name;
	}

	public function getLocation() : Location
	{
		return $this->location;
	}

	public function getSalesRep() : string
	{
		return $this->salesRep;
	}

	public function getCustomerGroup() : string
	{
		return $this->customerGroup;
	}

	public function getCreatedAt() : \DateTime
	{
		return $this->createdAt;
	}
}