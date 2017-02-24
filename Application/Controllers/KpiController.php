<?php

namespace QuickDashboard\Application\Controllers;

use phpOMS\Datatypes\SmartDateTime;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Utils\ArrayUtils;
use phpOMS\Views\View;
use QuickDashboard\Application\Models\StructureDefinitions;
use phpOMS\DataStorage\Database\Query\Builder;
use QuickDashboard\Application\Models\Queries;

class KpiController extends DashboardController
{
    public function showFinance(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Kpi/kpi-finance');

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

        $balance = $this->getBalance($request->getData('u'), $startLast, $startCurrent);
        $pl = $this->getPL($request->getData('u'), $startLast, $startCurrent);

        $view->setData('current', $this->getFiscalYearId($startCurrent));
        $view->setData('currentMonth', $currentMonth);
        $view->setData('balance', $balance);
        $view->setData('pl', $pl);

        return $view;
    }

    private function getBalance(string $unit, \DateTime $startLast, \DateTime $startCurrent)
    {
        $accounts = StructureDefinitions::getBalanceAccounts();
        $balance = [];

        if ($unit !== 'gdf') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'sd', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);
        }

        if ($unit !== 'sd') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'gdf', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);
        }

        return $balance;
    }

    private function getPL(string $unit, \DateTime $startLast, \DateTime $startCurrent)
    {
        $accounts = StructureDefinitions::getPLAccounts();
        $balance = [];

        if ($unit === 'sd' || $unit === 'gdf') {
            $accounts[] = 8591;
            $accounts[] = 3491;
        }

        if ($unit !== 'gdf') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'sd', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);
        }

        if ($unit !== 'sd') {
            $balanceResult = $this->selectBalanceAccounts($this->getFiscalYearId($startLast), $this->getFiscalYearId($startCurrent), 'gdf', $accounts);
            $this->loobBalanceStatement($balanceResult, $balance);
        }

        return $balance;
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
                $sum[$result['Konto']][$result['Geschaeftsjahr']] = ['M1' => 0, 'M2' => 0, 'M3' => 0, 'M4' => 0, 'M5' => 0, 'M6' => 0, 'M7' => 0, 'M8' => 0, 'M9' => 0, 'M10' => 0, 'M11' => 0, 'M12' => 0, 'S1' => 0, 'S2' => 0, 'S3' => 0, 'S4' => 0, 'S5' => 0, 'S6' => 0, 'S7' => 0, 'S8' => 0, 'S9' => 0, 'S10' => 0, 'S11' => 0, 'S12' => 0];
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

    public function showMarketing(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Kpi/kpi-marketing');

        // roi on p

        // roi on k

        // marketing+fairs

        return $view;
    }

    public function showPersonnel(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Kpi/kpi-personnel');

        return $view;
    }

    public function showQuality(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Kpi/kpi-quality');

        // credit notes

        return $view;
    }
}