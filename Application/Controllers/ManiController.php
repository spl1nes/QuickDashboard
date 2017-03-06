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
    public function showPackagePL(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/MANI/package-pl');

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

        $accountsPL = StructureDefinitions::getPLAccounts();
        $pl = [];

        if ($request->getData('u') !== 'gdf') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'sd', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);

            $plResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'sd', $accountsPL);
            $this->loobBalanceStatement($plResult, $pl);
        }

        if ($request->getData('u') !== 'sd') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'gdf', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);

            $plResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'gdf', $accountsPL);
            $this->loobBalanceStatement($plResult, $pl);
        }

        $view->setData('current', $this->getFiscalYearId($startCurrent));
        $view->setData('currentMonth', $currentMonth);
        $view->setData('balance', $balance);
        $view->setData('pl', $pl);

        return $view;
    }

    public function showPackagePL2(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/MANI/package-pl-2');

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

        $accountsPL = StructureDefinitions::getPLAccounts();
        $pl = [];

        if ($request->getData('u') !== 'gdf') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'sd', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);

            $plResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'sd', $accountsPL);
            $this->loobBalanceStatement($plResult, $pl);
        }

        if ($request->getData('u') !== 'sd') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'gdf', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);

            $plResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'gdf', $accountsPL);
            $this->loobBalanceStatement($plResult, $pl);
        }

        $view->setData('current', $this->getFiscalYearId($startCurrent));
        $view->setData('currentMonth', $currentMonth);
        $view->setData('balance', $balance);
        $view->setData('pl', $pl);

        return $view;
    }

    public function showPackagePL3(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/MANI/package-pl-3');

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

        $accountsPL = StructureDefinitions::getPLAccounts();
        $pl = [];

        if ($request->getData('u') !== 'gdf') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'sd', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);

            $plResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'sd', $accountsPL);
            $this->loobBalanceStatement($plResult, $pl);
        }

        if ($request->getData('u') !== 'sd') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'gdf', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);

            $plResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'gdf', $accountsPL);
            $this->loobBalanceStatement($plResult, $pl);
        }

        $view->setData('current', $this->getFiscalYearId($startCurrent));
        $view->setData('currentMonth', $currentMonth);
        $view->setData('balance', $balance);
        $view->setData('pl', $pl);

        return $view;
    }

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
                $sum[$result['Konto']][$result['Geschaeftsjahr']] = [
                'M1' => 0, 'M2' => 0, 'M3' => 0, 'M4' => 0, 'M5' => 0, 'M6' => 0, 'M7' => 0, 'M8' => 0, 'M9' => 0, 'M10' => 0, 'M11' => 0, 'M12' => 0, 
                'S1' => 0, 'S2' => 0, 'S3' => 0, 'S4' => 0, 'S5' => 0, 'S6' => 0, 'S7' => 0, 'S8' => 0, 'S9' => 0, 'S10' => 0, 'S11' => 0, 'S12' => 0];
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

            $sum[$result['Konto']][$result['Geschaeftsjahr']]['S1'] += $result['S1'];
            $sum[$result['Konto']][$result['Geschaeftsjahr']]['S2'] += $result['S2'];
            $sum[$result['Konto']][$result['Geschaeftsjahr']]['S3'] += $result['S3'];
            $sum[$result['Konto']][$result['Geschaeftsjahr']]['S4'] += $result['S4'];
            $sum[$result['Konto']][$result['Geschaeftsjahr']]['S5'] += $result['S5'];
            $sum[$result['Konto']][$result['Geschaeftsjahr']]['S6'] += $result['S6'];
            $sum[$result['Konto']][$result['Geschaeftsjahr']]['S7'] += $result['S7'];
            $sum[$result['Konto']][$result['Geschaeftsjahr']]['S8'] += $result['S8'];
            $sum[$result['Konto']][$result['Geschaeftsjahr']]['S9'] += $result['S9'];
            $sum[$result['Konto']][$result['Geschaeftsjahr']]['S10'] += $result['S10'];
            $sum[$result['Konto']][$result['Geschaeftsjahr']]['S11'] += $result['S11'];
            $sum[$result['Konto']][$result['Geschaeftsjahr']]['S12'] += $result['S12'];
        }

        return $sum;
    }

    public function showProduction(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/MANI/package-production');

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

        $accountsPL = StructureDefinitions::getPLAccounts();
        $pl = [];

        if ($request->getData('u') !== 'gdf') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'sd', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);

            $plResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'sd', $accountsPL);
            $this->loobBalanceStatement($plResult, $pl);
        }

        if ($request->getData('u') !== 'sd') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'gdf', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);

            $plResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'gdf', $accountsPL);
            $this->loobBalanceStatement($plResult, $pl);
        }

        $view->setData('current', $this->getFiscalYearId($startCurrent));
        $view->setData('currentMonth', $currentMonth);
        $view->setData('balance', $balance);
        $view->setData('pl', $pl);

        return $view;
    }

    public function showRnD(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/MANI/package-rnd');

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

        $accountsPL = StructureDefinitions::getPLAccounts();
        $pl = [];

        if ($request->getData('u') !== 'gdf') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'sd', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);

            $plResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'sd', $accountsPL);
            $this->loobBalanceStatement($plResult, $pl);
        }

        if ($request->getData('u') !== 'sd') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'gdf', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);

            $plResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'gdf', $accountsPL);
            $this->loobBalanceStatement($plResult, $pl);
        }

        $view->setData('current', $this->getFiscalYearId($startCurrent));
        $view->setData('currentMonth', $currentMonth);
        $view->setData('balance', $balance);
        $view->setData('pl', $pl);

        return $view;
    }

    public function showIntercoBalance(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/MANI/package-interco-balance');

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

        $accountsPL = StructureDefinitions::getPLAccounts();
        $pl = [];

        if ($request->getData('u') !== 'gdf') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'sd', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);

            $plResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'sd', $accountsPL);
            $this->loobBalanceStatement($plResult, $pl);
        }

        if ($request->getData('u') !== 'sd') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'gdf', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);

            $plResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'gdf', $accountsPL);
            $this->loobBalanceStatement($plResult, $pl);
        }

        $view->setData('current', $this->getFiscalYearId($startCurrent));
        $view->setData('currentMonth', $currentMonth);
        $view->setData('balance', $balance);
        $view->setData('pl', $pl);

        return $view;
    }

    public function showIntercoPL(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/MANI/package-interco-pl');

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

        $accountsPL = StructureDefinitions::getPLAccounts();
        $pl = [];

        if ($request->getData('u') !== 'gdf') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'sd', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);

            $plResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'sd', $accountsPL);
            $this->loobBalanceStatement($plResult, $pl);
        }

        if ($request->getData('u') !== 'sd') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'gdf', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);

            $plResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'gdf', $accountsPL);
            $this->loobBalanceStatement($plResult, $pl);
        }

        $view->setData('current', $this->getFiscalYearId($startCurrent));
        $view->setData('currentMonth', $currentMonth);
        $view->setData('balance', $balance);
        $view->setData('pl', $pl);

        return $view;
    }

    public function showIntercoProduction(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/MANI/package-interco-production');

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

        $accountsPL = StructureDefinitions::getPLAccounts();
        $pl = [];

        if ($request->getData('u') !== 'gdf') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'sd', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);

            $plResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'sd', $accountsPL);
            $this->loobBalanceStatement($plResult, $pl);
        }

        if ($request->getData('u') !== 'sd') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'gdf', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);

            $plResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'gdf', $accountsPL);
            $this->loobBalanceStatement($plResult, $pl);
        }

        $view->setData('current', $this->getFiscalYearId($startCurrent));
        $view->setData('currentMonth', $currentMonth);
        $view->setData('balance', $balance);
        $view->setData('pl', $pl);

        return $view;
    }

    public function showCVCustomer(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/MANI/package-customer');

        $current      = new SmartDateTime($request->getData('t') ?? 'now');
        $currentYear  = $current->format('m') - $this->app->config['fiscal_year'] < 0 ? $current->format('Y') - 1 : $current->format('Y');
        $mod          = (int) $current->format('m') - $this->app->config['fiscal_year'];
        $currentMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;
        $start        = $this->getFiscalYearStart($current);
        $start->modify('-2 year');

        $startCurrent = $this->getFiscalYearStart($current);
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $endCurrent->createModify(-1);

        $salesCustomer = [];
        $accounts = StructureDefinitions::PL_ACCOUNTS['Sales'];
        $accounts[] = 8591;
        $accountPositions = [];
        $consolidation = [];

        if ($request->getData('u') !== 'gdf') {
            $customersSD     = $this->select('selectCustomer', $startCurrent, $endCurrent, 'sd', $accounts);
            $customersSDLast = $this->select('selectCustomer', $startLast, $endLast, 'sd', $accounts);

            $this->loopVendor('now', $customersSD, $salesCustomer, 'SD');
            $this->loopVendor('old', $customersSDLast, $salesCustomer, 'SD');

            $accountsSD     = $this->select('selectEntries', $startCurrent, $endCurrent, 'sd', $accounts);
            $accountsSDLast = $this->select('selectEntries', $startLast, $endLast, 'sd', $accounts);

            $this->loopPL('now', $accountsSD, $accountPositions);
            $this->loopPL('old', $accountsSDLast, $accountPositions);

            $accountsSD     = $this->select('selectEntries', $startCurrent, $endCurrent, 'sd', [8591]);
            $accountsSDLast = $this->select('selectEntries', $startLast, $endLast, 'sd', [8591]);

            $this->loopPL('now', $accountsSD, $consolidation);
            $this->loopPL('old', $accountsSDLast, $consolidation);
        }

        if ($request->getData('u') !== 'sd') {
            $customersGDF     = $this->select('selectCustomer', $startCurrent, $endCurrent, 'gdf', $accounts);
            $customersGDFLast = $this->select('selectCustomer', $startLast, $endLast, 'gdf', $accounts);

            $this->loopVendor('now', $customersGDF, $salesCustomer, 'GDF');
            $this->loopVendor('old', $customersGDFLast, $salesCustomer, 'GDF');

            $accountsGDF     = $this->select('selectEntries', $startCurrent, $endCurrent, 'gdf', $accounts);
            $accountsGDFLast = $this->select('selectEntries', $startLast, $endLast, 'gdf', $accounts);

            $this->loopPL('now', $accountsGDF, $accountPositions);
            $this->loopPL('old', $accountsGDFLast, $accountPositions);

            $accountsGDF     = $this->select('selectEntries', $startCurrent, $endCurrent, 'gdf', [8591]);
            $accountsGDFLast = $this->select('selectEntries', $startLast, $endLast, 'gdf', [8591]);

            $this->loopPL('now', $accountsGDF, $consolidation);
            $this->loopPL('old', $accountsGDFLast, $consolidation);
        }

        arsort($salesCustomer['now']);
        arsort($salesCustomer['old']);

        $view->setData('customers', $salesCustomer);
        $view->setData('sales', $accountPositions);
        $view->setData('consolidation', $consolidation);
        $view->setData('date', $endCurrent);

        return $view;
    }

    public function showCVVendor(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/MANI/package-vendor');

        $current      = new SmartDateTime($request->getData('t') ?? 'now');
        $currentYear  = $current->format('m') - $this->app->config['fiscal_year'] < 0 ? $current->format('Y') - 1 : $current->format('Y');
        $mod          = (int) $current->format('m') - $this->app->config['fiscal_year'];
        $currentMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;
        $start        = $this->getFiscalYearStart($current);
        $start->modify('-2 year');

        $startCurrent = $this->getFiscalYearStart($current);
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $endCurrent->createModify(-1);

        $salesVendors = [];
        $accounts = StructureDefinitions::PL_ACCOUNTS['COGS Material'];
        $accounts[] = 3491;
        $accountPositions = [];
        $consolidation = [];

        $accounts = array_diff($accounts, [3960, 3961, 3962, 3963, 3964, 3965, 4000, 3961, 3960]);

        if ($request->getData('u') !== 'gdf') {
            $customersSD     = $this->select('selectVendor', $startCurrent, $endCurrent, 'sd', $accounts);
            $customersSDLast = $this->select('selectVendor', $startLast, $endLast, 'sd', $accounts);

            $this->loopVendor('now', $customersSD, $salesVendors, 'SD');
            $this->loopVendor('old', $customersSDLast, $salesVendors, 'SD');

            $accountsSD     = $this->select('selectEntries', $startCurrent, $endCurrent, 'sd', $accounts);
            $accountsSDLast = $this->select('selectEntries', $startLast, $endLast, 'sd', $accounts);

            $this->loopPL('now', $accountsSD, $accountPositions);
            $this->loopPL('old', $accountsSDLast, $accountPositions);

            $accountsSD     = $this->select('selectEntries', $startCurrent, $endCurrent, 'sd', [3491]);
            $accountsSDLast = $this->select('selectEntries', $startLast, $endLast, 'sd', [3491]);

            $this->loopPL('now', $accountsSD, $consolidation);
            $this->loopPL('old', $accountsSDLast, $consolidation);
        }

        if ($request->getData('u') !== 'sd') {
            $customersGDF     = $this->select('selectVendor', $startCurrent, $endCurrent, 'gdf', $accounts);
            $customersGDFLast = $this->select('selectVendor', $startLast, $endLast, 'gdf', $accounts);

            $this->loopVendor('now', $customersGDF, $salesVendors, 'GDF');
            $this->loopVendor('old', $customersGDFLast, $salesVendors, 'GDF');

            $accountsGDF     = $this->select('selectEntries', $startCurrent, $endCurrent, 'gdf', $accounts);
            $accountsGDFLast = $this->select('selectEntries', $startLast, $endLast, 'gdf', $accounts);

            $this->loopPL('now', $accountsGDF, $accountPositions);
            $this->loopPL('old', $accountsGDFLast, $accountPositions);

            $accountsGDF     = $this->select('selectEntries', $startCurrent, $endCurrent, 'gdf', [3491]);
            $accountsGDFLast = $this->select('selectEntries', $startLast, $endLast, 'gdf', [3491]);

            $this->loopPL('now', $accountsGDF, $consolidation);
            $this->loopPL('old', $accountsGDFLast, $consolidation);
        }

        arsort($salesVendors['now']);
        arsort($salesVendors['old']);

        $view->setData('vendors', $salesVendors);
        $view->setData('purchase', $accountPositions);
        $view->setData('consolidation', $consolidation);
        $view->setData('date', $endCurrent);

        return $view;
    }

    private function loopPL(string $period, array $resultset, array &$accountPositions)
    {
        foreach ($resultset as $line) {
            $position = StructureDefinitions::getAccountPLPosition($line['Konto']);
            if (!isset($accountPositions[$position][$period])) {
                $accountPositions[$position][$period] = 0.0;
            }

            $accountPositions[$position][$period] += $line['entries'];
        }
    }

    private function loopVendor(string $period, array $resultset, array &$salesCustomers, string $unit)
    {
        foreach ($resultset as $line) {
            $customer = $unit . ' ' . trim($line['id']);
            if (!isset($salesCustomers[$period][$customer])) {
                $salesCustomers[$period][$customer]['value'] = 0.0;
                $salesCustomers[$period][$customer]['name'] = trim($line['customer']);
            }

            $salesCustomers[$period][$customer]['value'] += $line['sales'];
        }
    }

    public function showCash(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/MANI/package-cash');

        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $this->getFiscalYearStart($current);
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endCurrent   = $current->getEndOfMonth();
        
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
        $view->setData('date', $endCurrent);

        return $view;
    }

    public function showAssets(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/MANI/package-assets');

        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $this->getFiscalYearStart($current);
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endCurrent   = $current->getEndOfMonth();
        
        $currentYear  = $current->format('m') - $this->app->config['fiscal_year'] < 0 ? $current->format('Y') - 1 : $current->format('Y');
        $mod          = (int) $current->format('m') - $this->app->config['fiscal_year'];
        $currentMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

        $accounts = [25, 26, 27, 200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280, 270, 320, 400, 401, 402, 403, 404, 405, 410, 411, 419, 420, 421, 422, 423, 424, 431, 440, 460, 461, 462, 464, 480, 481, 485];
        $balance = [];
        $ahkBeginning = [];
        $ahkAddition = [];
        $ahkSubtraction = [];
        $entries = [];

        if ($request->getData('u') !== 'gdf') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'sd', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);

            $ahk = $this->selectSimple('selectAHKBeginning', $startCurrent, $endCurrent, 'sd');
            $this->loopAhk($ahk, $ahkBeginning);

            $ahk = $this->selectSimple('selectAHKAdditions', $startCurrent, $endCurrent, 'sd');
            $this->loopAhk($ahk, $ahkAddition);

            $ahk = $this->selectSimple('selectAHKSubtractions', $startCurrent, $endCurrent, 'sd');
            $this->loopAhk($ahk, $ahkSubtraction);

            $accountsSD = $this->select('selectEntries2', $startCurrent, $endCurrent, 'sd', $accounts);
            $this->loopEntry('now', $accountsSD, $entries);
        }

        if ($request->getData('u') !== 'sd') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'gdf', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);

            $ahk = $this->selectSimple('selectAHKBeginning', $startCurrent, $endCurrent, 'gdf');
            $this->loopAhk($ahk, $ahkBeginning);

            $ahk = $this->selectSimple('selectAHKAdditions', $startCurrent, $endCurrent, 'gdf');
            $this->loopAhk($ahk, $ahkAddition);

            $ahk = $this->selectSimple('selectAHKSubtractions', $startCurrent, $endCurrent, 'gdf');
            $this->loopAhk($ahk, $ahkSubtraction);

            $accountsGDF = $this->select('selectEntries2', $startCurrent, $endCurrent, 'sd', $accounts);
            $this->loopEntry('now', $accountsGDF, $entries);
        }

        $view->setData('current', $this->getFiscalYearId($startCurrent));
        $view->setData('currentMonth', $currentMonth);
        $view->setData('balance', $balance);
        $view->setData('ahkBeginning', $ahkBeginning);
        $view->setData('ahkAddition', $ahkAddition);
        $view->setData('ahkSubtraction', $ahkSubtraction);
        $view->setData('entries', $entries);
        $view->setData('date', $endCurrent);

        return $view;
    }

    private function loopAhk(array $results, array &$sum)
    {
        foreach($results as $result) {
            if(!isset($sum[$result['account']])) {
                $sum[$result['account']] = 0.0;
            }

            $sum[$result['account']] += $result['ahk'];
        }

        return $sum;
    }

    private function loopEntry(string $period, array $resultset, array &$accountPositions)
    {
        foreach ($resultset as $line) {
            if (!isset($accountPositions[$line['Konto']])) {
                $accountPositions[$line['Konto']] = [];
            }

            if (!isset($accountPositions[$line['Konto']][$line['GegenKonto']])) {
                $accountPositions[$line['Konto']][$line['GegenKonto']] = 0.0;
            }

            $accountPositions[$line['Konto']][$line['GegenKonto']] += $line['entries'];
        }
    }
}