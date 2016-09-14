<?php

namespace QuickDashboard\Application\Controllers;

use phpOMS\ApplicationAbstract;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Datatypes\SmartDateTime;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;

class DashboardController
{
    private $app = null;

    const ACCOUNTS = [
        8050, 8052, 8592, 8055, 8090, 8095, 8100, 8105, 8106, 8110, 8113, 8115, 8120, 8121, 8122, 8125, 8130, 8140, 8160, 
        8161, 8162, 8300, 8305, 8306, 8310, 8315, 8320, 8330, 8340, 8360, 8361, 8362, 8367, 8368, 8380, 8740, 8746, 8749, 
        8765, 8781, 8791, 8793, 8841, 8843, 8851, 8853, 8861, 8863, 8871, 8873, 8955, 8000, 8005, 8006, 8010, 8013, 8020, 
        8021, 8022, 8030, 8040, 8060, 8062, 8064, 8591, 8065, 8070, 8075, 8400, 8405, 8406, 8410, 8413, 8415, 8420, 8425, 
        8430, 8440, 8460, 8461, 8462, 8463, 8464, 8465, 8487, 8488, 8489, 8502, 8505, 8506, 8507, 8508, 8509, 8690, 8733, 
        8734, 8736, 8739, 8756, 8757, 8794, 8796, 8799, 8840, 8850, 8860, 8870, 8998, 8865, 8500, 8503, 8510, 8511, 8512, 
        8520, 8530, 8584, 8585, 8730, 8855, 2894, 8700, 8200, 8205, 8206, 8210, 8213, 8215, 8220, 8221, 8225, 8230, 8240, 
        8260, 8261, 8262, 8263, 8264, 8287, 8289, 8290, 8741, 8753, 8754, 8761, 8782, 8792, 8795, 8842, 8852, 8862, 8872, 8910
    ];

    const ACCOUNTS_DOMESTIC = [
        8000, 8005, 8006, 8010, 8013, 8020, 8021, 8022, 8030, 8040, 8060, 8062, 8064, 8591, 8065, 8070, 8075, 8400, 8405, 
        8406, 8410, 8413, 8415, 8420, 8425, 8430, 8440, 8460, 8461, 8462, 8463, 8464, 8465, 8487, 8488, 8489, 8502, 8505, 
        8506, 8507, 8508, 8509, 8690, 8733, 8734, 8736, 8739, 8756, 8757, 8794, 8796, 8799, 8840, 8850, 8860, 8870, 8998, 
        8865, 8500, 8503, 8510, 8511, 8512, 8520, 8530, 8584, 8585, 8730, 8855, 2894, 8700,
    ];

    const SELECT_SALES_BY = [
        'country' => 'KUNDENADRESSE.LAENDERKUERZEL',
        'rep' => 'KUNDENADRESSE.VERKAEUFER',
        'area' => 'KUNDENADRESSE.GEBIET',
        'customergroup' => 'KUNDENADRESSE._KUNDENGRUPPE',
        'costcenter' => 'FiBuchungen.KST'
    ];

    const GROUPING = [
        1 => [10 => [101, 102, 103, 104, 105, 106]],
        2 => [
            12 => [121, 122, 123],
            14 => [141],
            19 => [191],
            33 => [331],
            34 => [341],
            40 => [401, 402],
            41 => [411, 412, 413, 414, 415],
            42 => [421, 422, 423],
            43 => [431],
            44 => [441, 442, 443, 444, 4130],
            50 => [501],
            51 => [514],
        ],
        3 => [
            16 => [162],
            23 => [231, 232, 233, 234, 235, 236],
            24 => [241, 242],
        ],
        4 => [16 => [161]],
        5 => [
            11 => [111, 112],
            13 => [131],
            15 => [151],
            17 => [171, 172],
            18 => [181],
            20 => [201],
            21 => [211, 212, 213, 214],
            22 => [221, 222, 223, 224, 225, 8000],
            30 => [301],
            31 => [311],
            32 => [321, 322, 323],
            35 => [351, 352, 353, 354],
        ],
        6 => [
            61 => [611, 612],
            62 => [621, 622],
            63 => [631, 632, 633, 634],
        ],
    ];

    const NAMING = [
        1 => 'Precious Alloys',
        2 => 'Analog Consumables',
        3 => 'Digital Workflow',
        4 => 'IMPLA',
        5 => 'Misc.',
        6 => 'Mani Articles',
        10 => 'Precious Alloys',
        11 => 'Non-precious Alloys',
        12 => 'Acrylics',
        13 => 'Ceramics',
        14 => 'Composites Lab',
        15 => 'Titan',
        16 => 'IMPLA',
        17 => 'Welding Systems',
        18 => 'Attachments',
        19 => 'Investment Material',
        20 => 'Misc. Machines',
        21 => 'Casting Materials',
        22 => 'Misc. (Service, Courses)',
        23 => 'Zirconia (CAD/CAM)',
        24 => 'Zebris',
        30 => 'Laser',
        31 => 'Dental Machines',
        32 => 'Weil (Impression Mat.)',
        33 => 'Composites',
        34 => 'Bleaching',
        35 => 'Misc.',
        40 => 'GDF PMMA',
        41 => 'GDF Composites',
        42 => 'GDF Acrylics',
        43 => 'GDF Bleaching',
        44 => 'GDF Misc.',
        50 => 'GDF Investment',
        51 => 'GDF ADC Misc.',
    ];

    private function getSegmentOfGroup(int $id) : int
    {
        foreach(self::GROUPING as $sKey => $segment) {
            if(in_array($id, segment)) {
                return $sKey;
            }
        }

        return 0;
    }

    private function getGroupOfArticle(int $id) : int
    {
        foreach(self::GROUPING as $segment) {
            foreach($segment as $gKey => $group) {
                if(in_array($id, group)) {
                    return $gKey;
                }
            }
        }

        return 0;
    }

    private function getSegmentOfArticle(int $id) : int
    {
        return $this->getSegmentOfGroup($this->getGroupOfArticle($id));
    }

    public function __construct(ApplicationAbstract $app)
    {
        $this->app = $app;
    }

    public function showOverview(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/overview');

        $current = new SmartDateTime('now');
        $start   = $this->getFiscalYearStart($current);
        $start->modify('-2 year');
        $iterator = clone $start;
        $year     = $iterator->format('Y');

        $currentMonth = (int) $start->format('m');
        $sales        = [];

        while ($iterator->getTimestamp() < $current->getTimestamp()) {
            $endOfMonth = $iterator->getEndOfMonth();
            $month      = ($currentMonth - $this->app->config['fiscal_year'] - 1) % 12 + 1;

            $sales[$year][$month] = $this->selectSales($iterator, $endOfMonth, 'sd', self::ACCOUNTS)
            $sales[$year][$month] += $this->selectSales($iterator, $endOfMonth, 'gdf', self::ACCOUNTS);

            if ($currentMonth % 12 === 0) {
                $year++;
            }

            $currentMonth++;
            $iterator->modify('+1 month');
        }

        $view->setData('sales', $sales);

        return $view;
    }

    public function showSalesOverview(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-history');

        return $view;
    }

    public function showMonth(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-month');

        return $view;
    }

    public function showLocation(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-location');

        return $view;
    }

    public function showArticles(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-article');

        return $view;
    }

    public function showCustomers(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-customer');

        return $view;
    }

    public function showReps(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-reps');

        return $view;
    }

    public function showCosts(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Costs/costs-positions');

        return $view;
    }

    public function showAnalysisReps(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Analysis/analysis-reps');

        return $view;
    }

    private function calcCurrentMonth(\DateTime $date) : int
    {
        return ((int) $date->format('m') - $this->app->config['fiscal_year'] - 1) % 12 + 1;
    }

    private function getFiscalYearStart(SmartDateTime $date) : SmartDateTime
    {
        $newDate = new SmartDateTime($date->format('Y') . '-' . $date->format('m') . '-01');

        return $newDate->modify('-' . ($this->calcCurrentMonth($date) - 1) . ' month');
    }

    private function selectSales(\DateTime $start, \DateTime $end, string $company, array $accounts) : float
    {
        $query = new Builder($this->app->dbPool->get($company));
        $query->raw(
            'SELECT 
                SUM(FiBuchungsArchiv.Betrag) AS Sales
            FROM FiBuchungsArchiv
            WHERE 
                FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102);');
        $result1 = $query->execute()->fetchAll();
        $result1 = empty($result1) ? 0 : $result1[0][0];

        $query->raw(
            'SELECT 
                SUM(FiBuchungen.Betrag) AS Sales
            FROM FiBuchungen
            WHERE 
                FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102);');
        $result2 = $query->execute()->fetchAll();
        $result2 = empty($result2) ? 0 : $result2[0][0];

        return $result1+$result2;
    }

    private function selectSalesByX(\DateTime $start, \DateTime $end, string $company, string $groupBy = 'KUNDENADRESSE.LAENDERKUERZEL') : float
    {
        $query = new Builder($this->app->dbPool->get($company));
        $query->raw(
            'SELECT DISTINCT
                ' . $groupBy . ', SUM(FiBuchungsArchiv.Betrag) AS Sales
            FROM FiBuchungsArchiv, KUNDENADRESSE
            WHERE 
                KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                AND FiBuchungsArchiv.Konto IN (' . implode(',', self::ACCOUNTS) . ')
                AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
            GROUP BY '. $groupBy .';');
        $result1 = $query->execute()->fetchAll();

        $query->raw(
            'SELECT DISTINCT
                ' . $groupBy . ', SUM(FiBuchungen.Betrag) AS Sales
            FROM FiBuchungen, KUNDENADRESSE
            WHERE 
                KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                AND FiBuchungen.Konto IN (' . implode(',', self::ACCOUNTS) . ')
                AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
            GROUP BY '. $groupBy .';');
        $result2 = $query->execute()->fetchAll();

        return $result1 + $result2;
    }
}

/*
SELECT Kunde_Belegzeilen_Archiv.BELEGNUMMER, Kunde_Belegzeilen_Archiv.STATUS, Kunde_Belegzeilen_Archiv.BELEGDATUM, SUM(Kunde_Belegzeilen_Archiv.STATUMSATZ) AS Sales
            FROM Kunde_Belegzeilen_Archiv
            WHERE 
                Kunde_Belegzeilen_Archiv.BELEGART IN ('VR0', 'VR1', 'VRS', 'VRT', 'VW0', 'VG0') 
                AND Kunde_Belegzeilen_Archiv.STATUS & 1 = 0
                AND CONVERT(VARCHAR(30), Kunde_Belegzeilen_Archiv.BELEGDATUM, 104) >= CONVERT(datetime, '2015.09.04', 102) 
                AND CONVERT(VARCHAR(30), Kunde_Belegzeilen_Archiv.BELEGDATUM, 104) <= CONVERT(datetime, '2015.09.04', 102) group by Kunde_Belegzeilen_Archiv.BELEGNUMMER, Kunde_Belegzeilen_Archiv.STATUS, Kunde_Belegzeilen_Archiv.BELEGDATUM;
                */