<?php

namespace QuickDashboard\Application\Controllers;

use phpOMS\Datatypes\Location;
use phpOMS\Datatypes\SmartDateTime;
use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Math\Finance\Lorenzkurve;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;
use QuickDashboard\Application\Models\Customer;
use QuickDashboard\Application\Models\StructureDefinitions;
use phpOMS\DataStorage\Database\Query\Builder;
use QuickDashboard\Application\Models\Queries;

class ManiController extends DashboardController
{
	public function showPackageBalance(RequestAbstract $request, ResponseAbstract $response)
    {
    	$view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/MANI/package-balance');

        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $this->getFiscalYearStart($current);
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        
        $currentYear  = $current->format('m') - $this->app->config['fiscal_year'] < 0 ? $current->format('Y') - 1 : $current->format('Y');
        $mod          = (int) $current->format('m') - $this->app->config['fiscal_year'];
        $currentMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

        $accounts = StructureDefinitions::getBalanceAccounts();
        $balance = [];

        if ($request->getData('u') !== 'gdf') {
	        $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'sd', $accounts);
	        $this->loobBalanceStatement($balanceResult, $balance);
    	}

    	if ($request->getData('u') !== 'sd') {
	        $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'gdf', $accounts);
	        $this->loobBalanceStatement($balanceResult, $balance);
    	}

        $view->setData('current', $this->getFiscalYearId($startCurrent));
        $view->setData('currentMonth', $currentMonth);
        $view->setData('balance', $balance);

        return $view;
    }

    private function selectBalanceAccounts(int $start, int $end, string $company, array $accounts)
    {
    	$query = new Builder($this->app->dbPool->get($company));
        $query->raw(Queries::selectBalanceAccounts($start, $end, $accounts));
        $result = $query->execute()->fetchAll();
        $result = empty($result) ? [] : $result;

        return $result;
    }

    private function loobBalanceStatement(array $results, array &$sum)
    {
    	foreach($results as $result) {
    		if(!isset($sum[$result['Konto']])) {
    			$sum[$result['Konto']] = [];
    		}

    		if(!isset($sum[$result['Konto']][$result['Geschaeftsjahr']])) {
    			$sum[$result['Konto']][$result['Geschaeftsjahr']] = ['M1' => 0, 'M2' => 0, 'M3' => 0, 'M4' => 0, 'M5' => 0, 'M6' => 0, 'M7' => 0, 'M8' => 0, 'M9' => 0, 'M10' => 0, 'M11' => 0, 'M12' => 0];
    		}

    		$sum[$result['Konto']][$result['Geschaeftsjahr']]['M1'] += $result['M1'];
    		$sum[$result['Konto']][$result['Geschaeftsjahr']]['M2'] += $result['M2'];
    		$sum[$result['Konto']][$result['Geschaeftsjahr']]['M3'] += $result['M3'];
    		$sum[$result['Konto']][$result['Geschaeftsjahr']]['M4'] += $result['M4'];
    		$sum[$result['Konto']][$result['Geschaeftsjahr']]['M5'] += $result['M5'];
    		$sum[$result['Konto']][$result['Geschaeftsjahr']]['M6'] += $result['M6'];
    		$sum[$result['Konto']][$result['Geschaeftsjahr']]['M7'] += $result['M7'];
    		$sum[$result['Konto']][$result['Geschaeftsjahr']]['M8'] += $result['M8'];
    		$sum[$result['Konto']][$result['Geschaeftsjahr']]['M9'] += $result['M9'];
    		$sum[$result['Konto']][$result['Geschaeftsjahr']]['M10'] += $result['M10'];
    		$sum[$result['Konto']][$result['Geschaeftsjahr']]['M11'] += $result['M11'];
    		$sum[$result['Konto']][$result['Geschaeftsjahr']]['M12'] += $result['M12'];
    	}

    	return $sum;
    }
}