<?php

namespace QuickDashboard\Application\Controllers;

use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Datatypes\SmartDateTime;
use QuickDashboard\Application\Models\Queries;
use QuickDashboard\Application\WebApplication;

class DashboardController
{
    protected $app = null;

    const MAX_PAST = 10;

    public function __construct(WebApplication $app)
    {
        $this->app = $app;
    }

    protected function calcCurrentMonth(\DateTime $date) : int
    {
        $mod = ((int) $date->format('m') - $this->app->config['fiscal_year'] - 1);

        return abs(($mod < 0 ? 12 + $mod : $mod) % 12 + 1);
    }

    protected function getFiscalYearStart(SmartDateTime $date) : SmartDateTime
    {
        $newDate = new SmartDateTime($date->format('Y') . '-' . $date->format('m') . '-01');
        $newDate->smartModify(0, -$this->calcCurrentMonth($date));

        return $newDate;
    }

    protected function select(string $selectQuery, \DateTime $start, \DateTime $end, string $company, array $accounts) : array
    {
        $query = new Builder($this->app->dbPool->get($company));
        $query->raw(Queries::{$selectQuery}($start, $end, $accounts));
        $result = $query->execute()->fetchAll();
        $result = empty($result) ? [] : $result;

        return $result;
    }

    protected function selectAddon(string $selectQuery, \DateTime $start, \DateTime $end, string $company, array $accounts, $addon) : array
    {
        $query = new Builder($this->app->dbPool->get($company));
        $query->raw(Queries::{$selectQuery}($start, $end, $accounts, $addon));
        $result = $query->execute()->fetchAll();
        $result = empty($result) ? [] : $result;

        return $result;
    }

    protected function selectCustomerInformation(string $company, int $customer)
    {
        $query = new Builder($this->app->dbPool->get($company));
        $query->raw(Queries::selectCustomerInformation($customer));
        $result = $query->execute()->fetch();

        return $result;
    }
}