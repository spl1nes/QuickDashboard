<?php

namespace QuickDashboard\Application\Controllers;

use phpOMS\ApplicationAbstract;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Datatypes\SmartDateTime;
use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;

class DashboardController
{
    private $app = null;

    const MAX_PAST = 10;

    const ACCOUNTS = [
        8050, 8052, 8055, 8090, 8095, 8100, 8105, 8106, 8110, 8113, 8115, 8120, 8121, 8122, 8125, 8130, 8140, 8160, 8592,
        8161, 8162, 8300, 8305, 8306, 8310, 8315, 8320, 8330, 8340, 8360, 8361, 8362, 8367, 8368, 8380, 8740, 8746, 8749,
        8765, 8781, 8791, 8793, 8841, 8843, 8851, 8853, 8861, 8863, 8871, 8873, 8955, 8000, 8005, 8006, 8010, 8013, 8020,
        8021, 8022, 8030, 8040, 8060, 8062, 8064, 8065, 8070, 8075, 8400, 8405, 8406, 8410, 8413, 8415, 8420, 8425,
        8430, 8440, 8460, 8461, 8462, 8463, 8464, 8465, 8487, 8488, 8489, 8502, 8505, 8506, 8507, 8508, 8509, 8690, 8733,
        8734, 8736, 8739, 8756, 8757, 8794, 8796, 8799, 8840, 8850, 8860, 8870, 8998, 8865, 8500, 8503, 8510, 8511, 8512,
        8520, 8530, 8584, 8585, 8730, 8855, 2894, 8700, 8200, 8205, 8206, 8210, 8213, 8215, 8220, 8221, 8225, 8230, 8240,
        8260, 8261, 8262, 8263, 8264, 8287, 8289, 8290, 8741, 8753, 8754, 8761, 8782, 8792, 8795, 8842, 8852, 8862, 8872, 8910,
    ];

    const ACCOUNTS_DOMESTIC = [
        8000, 8005, 8006, 8010, 8013, 8020, 8021, 8022, 8030, 8040, 8060, 8062, 8064, 8065, 8070, 8075, 8400, 8405,
        8406, 8410, 8413, 8415, 8420, 8425, 8430, 8440, 8460, 8461, 8462, 8463, 8464, 8465, 8487, 8488, 8489, 8502, 8505,
        8506, 8507, 8508, 8509, 8690, 8733, 8734, 8736, 8739, 8756, 8757, 8794, 8796, 8799, 8840, 8850, 8860, 8870, 8998,
        8865, 8500, 8503, 8510, 8511, 8512, 8520, 8530, 8584, 8585, 8730, 8855, 2894, 8700,
    ];

    const REGIONS = [
        'Europe'  => [
            'AX', 'AL', 'AD', 'AT', 'BY', 'BE', 'BA', 'BG', 'HR', 'CZ', 'DK', 'EE', 'FO', 'FI', 'FR', 'DE', 'GI', 'GR',
            'GG', 'VA', 'HU', 'IS', 'IE', 'IM', 'IT', 'JE', 'LV', 'LI', 'LT', 'LU', 'MK', 'MT', 'MD', 'MC', 'ME', 'NL',
            'NO', 'PL', 'PT', 'RO', 'RU', 'SM', 'RS', 'SK', 'SI', 'ES', 'SJ', 'SE', 'CH', 'UA', 'GB', 'XK', 'QU'
        ],
        'Asia'    => [
            'AF', 'AM', 'AZ', 'BH', 'BD', 'BT', 'BN', 'KH', 'CN', 'CY', 'GE', 'HK', 'IN', 'ID', 'IR', 'IQ', 'IL', 'JP',
            'JO', 'KZ', 'KP', 'KR', 'KW', 'KG', 'LA', 'LB', 'MO', 'MY', 'MV', 'MN', 'MM', 'NP', 'OM', 'PK', 'PS', 'PH',
            'QA', 'SA', 'SG', 'LK', 'SY', 'TW', 'TJ', 'TH', 'TL', 'TR', 'TM', 'AE', 'UZ', 'VN', 'YE',
        ],
        'America' => [
            'AI', 'AG', 'AR', 'AW', 'BS', 'BB', 'BZ', 'BM', 'BO', 'BQ', 'BR', 'CA', 'KY', 'CL', 'CO', 'CR', 'CU', 'CW',
            'DM','DO', 'EC', 'SV', 'FK', 'GF', 'GL', 'GD', 'GP', 'GT', 'GY', 'HT', 'HN', 'JM', 'MQ', 'MX', 'MS', 'NI',
            'PA', 'PY', 'PE', 'PR', 'BL', 'KN', 'LC', 'MF', 'PM', 'VC', 'SX', 'GS', 'SR', 'TT', 'TC', 'US', 'UM', 'UY',
            'VE', 'VG', 'VI',
        ],
        'Africa'  => [
            'DZ', 'AO', 'BJ', 'BW', 'BF', 'BI', 'CM', 'CV', 'CF', 'TD', 'KM', 'CG', 'CD', 'CI', 'DJ', 'EG', 'GQ', 'ER',
            'ET', 'GA', 'GM', 'GH', 'GN', 'GW', 'KE', 'LS', 'LR', 'LY', 'MG', 'MW', 'ML', 'MR', 'MU', 'YT', 'MA', 'MZ',
            'NA', 'NE', 'NG', 'RE', 'RW', 'SH', 'ST', 'SN', 'SC', 'SL', 'SO', 'ZA', 'SS', 'SD', 'SZ', 'TZ', 'TG', 'TN',
            'UG', 'EH', 'ZM', 'ZW',
        ],
        'Oceania' => [
            'AS', 'AU', 'CX', 'CC', 'CK', 'FJ', 'PF', 'TF', 'GU', 'HM', 'KI', 'MH', 'FM', 'NR', 'NC', 'NZ', 'NU', 'NF',
            'MP', 'PW', 'PG', 'PN', 'WS', 'SB', 'TK', 'TO', 'TV', 'VU', 'WF',
        ],
    ];

    const DEVELOPED = [
        'BE', 'BG', 'CZ', 'DK', 'DE', 'EE', 'GR', 'ES', 'FR', 'GR', 'IT', 'CY', 'LV', 'LT', 'LU', 'HU', 'MT', 'NL',
        'AT', 'PL', 'PT', 'RO', 'SI', 'SK', 'FI', 'SE', 'GB', 'IS', 'NO', 'CH', 'LI', 'JP', 'CA', 'US', 'AU', 'QU'
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
        1  => 'Precious Alloys',
        2  => 'Analog Consumables',
        3  => 'Digital Workflow',
        4  => 'IMPLA',
        5  => 'Misc.',
        6  => 'Mani Articles',
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
        foreach (self::GROUPING as $sKey => $segment) {
            if (in_array($id, $segment)) {
                return $sKey;
            }
        }

        return 0;
    }

    private function getGroupOfArticle(int $id) : int
    {
        foreach (self::GROUPING as $segment) {
            foreach ($segment as $gKey => $group) {
                if (in_array($id, $group)) {
                    return $gKey;
                }
            }
        }

        return 0;
    }

    private function getDevelopedUndeveloped(string $code) : string
    {
        return in_array(strtoupper(trim($code)), self::DEVELOPED) ? 'Developed' : 'Undeveloped';
    }

    private function getRegion(string $code) : string
    {
        foreach (self::REGIONS as $key => $region) {
            if (in_array(strtoupper(trim($code)), $region)) {
                return $key;
            }
        }

        return 'Other';
    }

    private function getExportDomestic(string $code) : string
    {
        return strtoupper(trim($code)) === 'DE' || strtoupper(trim($code)) === 'QU' ? 'Domestic' : 'Export';
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

        $totalSales    = [];
        $accTotalSales = [];

        if($request->getData('u') !== 'gdf') {
            $salesSD       = $this->selectSalesYearMonth($start, $current, 'sd', self::ACCOUNTS);
            foreach ($salesSD as $line) {
                $fiscalYear  = $line['months'] - $this->app->config['fiscal_year'] < 0 ? $line['years'] - 1 : $line['years'];
                $mod         = $line['months'] - $this->app->config['fiscal_year'];
                $fiscalMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

                $totalSales[$fiscalYear][$fiscalMonth] = $line['sales'];
            }
        }

        if($request->getData('u') !== 'sd') {
            $salesGDF      = $this->selectSalesYearMonth($start, $current, 'gdf', self::ACCOUNTS);
            foreach ($salesGDF as $line) {
                $fiscalYear  = $line['months'] - $this->app->config['fiscal_year'] < 0 ? $line['years'] - 1 : $line['years'];
                $mod         = ($line['months'] - $this->app->config['fiscal_year']);
                $fiscalMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

                if (!isset($totalSales[$fiscalYear][$fiscalMonth])) {
                    $totalSales[$fiscalYear][$fiscalMonth] = 0.0;
                }

                $totalSales[$fiscalYear][$fiscalMonth] += $line['sales'];
            }
        }

        foreach ($totalSales as $year => $months) {
            ksort($totalSales[$year]);

            foreach ($totalSales[$year] as $month => $value) {
                $prev                         = $accTotalSales[$year][$month - 1] ?? 0.0;
                $accTotalSales[$year][$month] = $prev + $value;
            }
        }

        $currentYear  = $current->format('m') - $this->app->config['fiscal_year'] < 0 ? $current->format('Y') - 1 : $current->format('Y');
        $mod          = (int) $current->format('m') - $this->app->config['fiscal_year'];
        $currentMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

        unset($totalSales[$currentYear][$currentMonth]);
        unset($accTotalSales[$currentYear][$currentMonth]);

        $view->setData('currentFiscalYear', $currentYear);
        $view->setData('currentMonth', $currentMonth);
        $view->setData('sales', $totalSales);
        $view->setData('salesAcc', $accTotalSales);

        return $view;
    }

    public function showSalesOverview(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-history');

        return $view;
    }

    public function showListMonth(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-list-month');

        $current = new SmartDateTime('now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $current->getStartOfMonth();
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $startLast->getEndOfMonth();

        $totalSales        = [];
        $totalSalesLast    = [];
        $accTotalSales     = [];
        $accTotalSalesLast = [];

        if($request->getData('u') !== 'gdf') {
            $salesSD      = $this->selectSalesDaily($startCurrent, $endCurrent, 'sd', self::ACCOUNTS);
            $salesSDLast  = $this->selectSalesDaily($startLast, $endLast, 'sd', self::ACCOUNTS);

            foreach ($salesSD as $line) {
                $totalSales[$line['days']] = $line['sales'];
            }

            foreach ($salesSDLast as $line) {
                $totalSalesLast[$line['days']] = $line['sales'];
            }
        }

        if($request->getData('u') !== 'sd') {
            $salesGDFLast = $this->selectSalesDaily($startLast, $endLast, 'gdf', self::ACCOUNTS);
            $salesGDF     = $this->selectSalesDaily($startCurrent, $endCurrent, 'gdf', self::ACCOUNTS);

            foreach ($salesGDF as $line) {
                if (!isset($totalSales[$line['days']])) {
                    $totalSales[$line['days']] = 0.0;
                }

                $totalSales[$line['days']] += $line['sales'];
            }

            foreach ($salesGDFLast as $line) {
                if (!isset($totalSalesLast[$line['days']])) {
                    $totalSalesLast[$line['days']] = 0.0;
                }

                $totalSalesLast[$line['days']] += $line['sales'];
            }
        }

        ksort($totalSales);
        ksort($totalSalesLast);

        $days = $endCurrent->format('d');
        for ($i = 1; $i <= $days; $i++) {
            $prev              = $accTotalSales[$i - 1] ?? 0;
            $accTotalSales[$i] = $prev + ($totalSales[$i] ?? 0);
        }

        $days = $endLast->format('d');
        for ($i = 1; $i <= $days; $i++) {
            $prev                  = $accTotalSalesLast[$i - 1] ?? 0;
            $accTotalSalesLast[$i] = $prev + ($totalSalesLast[$i] ?? 0);
        }

        $view->setData('sales', $totalSales);
        $view->setData('salesAcc', $accTotalSales);
        $view->setData('salesLast', $totalSalesLast);
        $view->setData('salesAccLast', $accTotalSalesLast);
        $view->setData('maxDays', max($endCurrent->format('d'), $endLast->format('d')));
        $view->setData('today', $current->format('d') - 1);

        return $view;
    }

    public function showListYear(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-list-year');

        $current = new SmartDateTime('now');
        $start   = $this->getFiscalYearStart($current);
        $start->modify('-1 year');

        $totalSales    = [];
        $accTotalSales = [];

        if($request->getData('u') !== 'gdf') {
            $salesSD       = $this->selectSalesYearMonth($start, $current, 'sd', self::ACCOUNTS);
            foreach ($salesSD as $line) {
                $fiscalYear  = $line['months'] - $this->app->config['fiscal_year'] < 0 ? $line['years'] - 1 : $line['years'];
                $mod         = $line['months'] - $this->app->config['fiscal_year'];
                $fiscalMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

                $totalSales[$fiscalYear][$fiscalMonth] = $line['sales'];
            }
        }

        if($request->getData('u') !== 'sd') {
            $salesGDF      = $this->selectSalesYearMonth($start, $current, 'gdf', self::ACCOUNTS);
            foreach ($salesGDF as $line) {
                $fiscalYear  = $line['months'] - $this->app->config['fiscal_year'] < 0 ? $line['years'] - 1 : $line['years'];
                $mod         = ($line['months'] - $this->app->config['fiscal_year']);
                $fiscalMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

                if (!isset($totalSales[$fiscalYear][$fiscalMonth])) {
                    $totalSales[$fiscalYear][$fiscalMonth] = 0.0;
                }

                $totalSales[$fiscalYear][$fiscalMonth] += $line['sales'];
            }
        }

        foreach ($totalSales as $year => $months) {
            ksort($totalSales[$year]);

            for ($i = 1; $i <= 12; $i++) {
                $prev                         = $accTotalSales[$year][$i - 1] ?? 0.0;
                $accTotalSales[$year][$i] = $prev + ($totalSales[$year][$i] ?? 0);
            }
        }

        $currentYear  = $current->format('m') - $this->app->config['fiscal_year'] < 0 ? $current->format('Y') - 1 : $current->format('Y');
        $mod          = (int) $current->format('m') - $this->app->config['fiscal_year'];
        $currentMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

        $view->setData('sales', $totalSales[$currentYear]);
        $view->setData('salesAcc', $accTotalSales[$currentYear]);
        $view->setData('salesLast', $totalSales[$currentYear-1]);
        $view->setData('salesAccLast', $accTotalSales[$currentYear-1]);
        $view->setData('currentFiscalYear', $currentYear);
        $view->setData('currentMonth', $currentMonth);

        return $view;
    }

    public function showLocationMonth(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime('now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-'.self::MAX_PAST .' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $current->getStartOfMonth();
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $startLast->getEndOfMonth();

        return $this->showLocation($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showLocationYear(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime('now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-'.self::MAX_PAST .' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $this->getFiscalYearStart($current);
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $endCurrent->createModify(-1);

        return $this->showLocation($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showLocation(RequestAbstract $request, ResponseAbstract $response, \DateTime $startCurrent, \DateTime $endCurrent, \DateTime $startLast, \DateTime $endLast)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-location');

        $salesRegion         = [];
        $salesDevUndev       = [];
        $salesExportDomestic = [];
        $salesCountry = [];

        if($request->getData('u') !== 'gdf') {
            $countrySD      = $this->selectSalesByCountry($startCurrent, $endCurrent, 'sd', self::ACCOUNTS);
            $countrySDLast  = $this->selectSalesByCountry($startLast, $endLast, 'sd', self::ACCOUNTS);

            foreach ($countrySD as $line) {
                $region = $this->getRegion($line['countryChar']);
                if (!isset($salesRegion['now'][$region])) {
                    $salesRegion['now'][$region] = 0.0;
                }

                $salesRegion['now'][$region] += $line['sales'];

                $devundev = $this->getDevelopedUndeveloped($line['countryChar']);
                if (!isset($salesDevUndev['now'][$devundev])) {
                    $salesDevUndev['now'][$devundev] = 0.0;
                }

                $salesDevUndev['now'][$devundev] += $line['sales'];

                $iso3166Char3 = ltrim(ISO3166TwoEnum::getName(trim(strtoupper($line['countryChar']))), '_');
                if (!isset($salesCountry['now'][$iso3166Char3])) {
                    $salesCountry['now'][$iso3166Char3] = 0.0;
                }

                $salesCountry['now'][$iso3166Char3] += $line['sales'];
            }

            foreach ($countrySDLast as $line) {
                $region = $this->getRegion($line['countryChar']);
                if (!isset($salesRegion['old'][$region])) {
                    $salesRegion['old'][$region] = 0.0;
                }

                $salesRegion['old'][$region] += $line['sales'];

                $devundev = $this->getDevelopedUndeveloped($line['countryChar']);
                if (!isset($salesDevUndev['old'][$devundev])) {
                    $salesDevUndev['old'][$devundev] = 0.0;
                }

                $salesDevUndev['old'][$devundev] += $line['sales'];

                $iso3166Char3 = ltrim(ISO3166TwoEnum::getName(trim(strtoupper($line['countryChar']))), '_');
                if (!isset($salesCountry['old'][$iso3166Char3])) {
                    $salesCountry['old'][$iso3166Char3] = 0.0;
                }

                $salesCountry['old'][$iso3166Char3] += $line['sales'];
            }

            $domesticSDLast = $this->selectSales($startLast, $endLast, 'sd', self::ACCOUNTS_DOMESTIC);
            $domesticSD = $this->selectSales($startCurrent, $endCurrent, 'sd', self::ACCOUNTS_DOMESTIC);

            $allSD = $this->selectSales($startCurrent, $endCurrent, 'sd', self::ACCOUNTS);
            $allSDLast = $this->selectSales($startLast, $endLast, 'sd', self::ACCOUNTS);
        }

        if($request->getData('u') !== 'sd') {
            $countryGDF     = $this->selectSalesByCountry($startCurrent, $endCurrent, 'gdf', self::ACCOUNTS);
            $countryGDFLast = $this->selectSalesByCountry($startLast, $endLast, 'gdf', self::ACCOUNTS);

            foreach ($countryGDF as $line) {
                $region = $this->getRegion($line['countryChar']);
                if (!isset($salesRegion['now'][$region])) {
                    $salesRegion['now'][$region] = 0.0;
                }

                $salesRegion['now'][$region] += $line['sales'];

                $devundev = $this->getDevelopedUndeveloped($line['countryChar']);
                if (!isset($salesDevUndev['now'][$devundev])) {
                    $salesDevUndev['now'][$devundev] = 0.0;
                }

                $salesDevUndev['now'][$devundev] += $line['sales'];

                $iso3166Char3 = ltrim(ISO3166TwoEnum::getName(trim(strtoupper($line['countryChar']))), '_');
                if (!isset($salesCountry['now'][$iso3166Char3])) {
                    $salesCountry['now'][$iso3166Char3] = 0.0;
                }

                $salesCountry['now'][$iso3166Char3] += $line['sales'];
            }

            foreach ($countryGDFLast as $line) {
                $region = $this->getRegion($line['countryChar']);
                if (!isset($salesRegion['old'][$region])) {
                    $salesRegion['old'][$region] = 0.0;
                }

                $salesRegion['old'][$region] += $line['sales'];

                $devundev = $this->getDevelopedUndeveloped($line['countryChar']);
                if (!isset($salesDevUndev['old'][$devundev])) {
                    $salesDevUndev['old'][$devundev] = 0.0;
                }

                $salesDevUndev['old'][$devundev] += $line['sales'];

                $iso3166Char3 = ltrim(ISO3166TwoEnum::getName(trim(strtoupper($line['countryChar']))), '_');
                if (!isset($salesCountry['old'][$iso3166Char3])) {
                    $salesCountry['old'][$iso3166Char3] = 0.0;
                }

                $salesCountry['old'][$iso3166Char3] += $line['sales'];
            }

            $domesticGDFLast = $this->selectSales($startLast, $endLast, 'gdf', self::ACCOUNTS_DOMESTIC);
            $domesticGDF = $this->selectSales($startCurrent, $endCurrent, 'gdf', self::ACCOUNTS_DOMESTIC);

            $allGDF = $this->selectSales($startCurrent, $endCurrent, 'gdf', self::ACCOUNTS);
            $allGDFLast = $this->selectSales($startLast, $endLast, 'gdf', self::ACCOUNTS);
        }

        $salesExportDomestic['now']['Domestic'] = ($domesticSD[0]['sales'] ?? 0) + ($domesticGDF[0]['sales'] ?? 0);
        $salesExportDomestic['old']['Domestic'] = ($domesticSDLast[0]['sales'] ?? 0) + ($domesticGDFLast[0]['sales'] ?? 0);
        $salesExportDomestic['now']['Export'] = ($allGDF[0]['sales'] ?? 0) + ($allSD[0]['sales'] ?? 0) - $salesExportDomestic['now']['Domestic'];
        $salesExportDomestic['old']['Export'] = ($allGDFLast[0]['sales'] ?? 0) + ($allSDLast[0]['sales'] ?? 0) - $salesExportDomestic['old']['Domestic'];
        $salesCountry['now']['DEU'] = $salesExportDomestic['now']['Domestic'];
        $salesCountry['old']['DEU'] = $salesExportDomestic['old']['Domestic'];

        arsort($salesCountry['now']);

        $salesDevUndev['now']['Developed'] += array_sum($salesExportDomestic['now']) - array_sum($salesDevUndev['now']);
        $salesDevUndev['old']['Developed'] += array_sum($salesExportDomestic['old']) - array_sum($salesDevUndev['old']);

        $salesRegion['now']['Europe'] += array_sum($salesExportDomestic['now']) - array_sum($salesRegion['now']);
        $salesRegion['old']['Europe'] += array_sum($salesExportDomestic['old']) - array_sum($salesRegion['old']);

        $view->setData('salesCountry', $salesCountry);
        $view->setData('salesRegion', $salesRegion);
        $view->setData('salesDevUndev', $salesDevUndev);
        $view->setData('salesExportDomestic', $salesExportDomestic);

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

        return $newDate->modify('-' . $this->calcCurrentMonth($date) . ' month');
    }

    private function selectSalesYearMonth(\DateTime $start, \DateTime $end, string $company, array $accounts) : array
    {
        $query = new Builder($this->app->dbPool->get($company));
        $query->raw(
            'SELECT 
                t.years, t.months, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS months, 
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv
                    WHERE 
                        FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104))
                UNION ALL
                    SELECT 
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS months, 
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen
                    WHERE 
                        FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104))
                ) t
            GROUP BY t.years, t.months;');
        $result = $query->execute()->fetchAll();
        $result = empty($result) ? [] : $result;

        return $result;
    }

    private function selectSalesDaily(\DateTime $start, \DateTime $end, string $company, array $accounts) : array
    {
        $query = new Builder($this->app->dbPool->get($company));
        $query->raw(
            'SELECT 
                t.days, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        datepart(d, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS days, 
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv
                    WHERE 
                        FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(d, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104))
                UNION ALL
                    SELECT 
                        datepart(d, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS days, 
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen
                    WHERE 
                        FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(d, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104))
                ) t
            GROUP BY t.days;');
        $result = $query->execute()->fetchAll();
        $result = empty($result) ? [] : $result;

        return $result;
    }

    private function selectSalesByCountry(\DateTime $start, \DateTime $end, string $company, array $accounts) : array
    {
        $query = new Builder($this->app->dbPool->get($company));
        $query->raw(
            'SELECT DISTINCT
                t.countryChar, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        KUNDENADRESSE.LAENDERKUERZEL AS countryChar,
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE.LAENDERKUERZEL
                UNION ALL
                    SELECT 
                        KUNDENADRESSE.LAENDERKUERZEL AS countryChar,
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE.LAENDERKUERZEL
                ) t
            GROUP BY t.countryChar;');
        $result = $query->execute()->fetchAll();
        $result = empty($result) ? [] : $result;

        return $result;
    }

    private function selectSales(\DateTime $start, \DateTime $end, string $company, array $accounts) : array
    {
        $query = new Builder($this->app->dbPool->get($company));
        $query->raw(
            'SELECT DISTINCT
                SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv
                    WHERE 
                        FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                UNION ALL
                    SELECT 
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen
                    WHERE 
                        FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                ) t;');
        $result = $query->execute()->fetchAll();
        $result = empty($result) ? [] : $result;

        return $result;
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