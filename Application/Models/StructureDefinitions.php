<?php

namespace QuickDashboard\Application\Models;

class StructureDefinitions
{
    const ACCOUNTS = [
        8050, 8052, 8055, 8090, 8095, 8100, 8105, 8106, 8110, 8113, 8115, 8120, 8121, 8122, 8125, 8130, 8140, 8160, 8592,
        8161, 8162, 8300, 8305, 8306, 8310, 8315, 8320, 8330, 8340, 8360, 8361, 8362, 8367, 8368, 8380, 8740, 8746, 8749,
        8765, 8781, 8791, 8793, 8841, 8843, 8851, 8853, 8861, 8863, 8871, 8873, 8955, 8000, 8005, 8006, 8010, 8013, 8020,
        8021, 8022, 8030, 8040, 8060, 8062, 8064, 8065, 8070, 8075, 8400, 8405, 8406, 8410, 8413, 8415, 8420, 8425, 8910,
        8430, 8440, 8460, 8461, 8462, 8463, 8464, 8465, 8487, 8488, 8489, 8502, 8505, 8506, 8507, 8508, 8509, 8690, 8733,
        8734, 8736, 8739, 8756, 8757, 8794, 8796, 8799, 8840, 8850, 8860, 8870, 8998, 8865, 8500, 8503, 8510, 8511, 8512,
        8520, 8530, 8584, 8585, 8730, 8855, 2894, 8700, 8200, 8205, 8206, 8210, 8213, 8215, 8220, 8221, 8225, 8230, 8240,
        8260, 8261, 8262, 8263, 8264, 8287, 8289, 8290, 8741, 8753, 8754, 8761, 8782, 8792, 8795, 8842, 8852, 8862, 8872,
    ];

    const ACCOUNTS_DOMESTIC = [
        8000, 8005, 8006, 8010, 8013, 8020, 8021, 8022, 8030, 8040, 8060, 8062, 8064, 8065, 8070, 8075, 8400, 8405, 8700,
        8406, 8410, 8413, 8415, 8420, 8425, 8430, 8440, 8460, 8461, 8462, 8463, 8464, 8465, 8487, 8488, 8489, 8502, 8505,
        8506, 8507, 8508, 8509, 8690, 8733, 8734, 8736, 8739, 8756, 8757, 8794, 8796, 8799, 8840, 8850, 8860, 8870, 8998,
        8865, 8500, 8503, 8510, 8511, 8512, 8520, 8530, 8584, 8585, 8730, 8855, 2894,
    ];

    const REGIONS = [
        'Europe'  => [
            'AX', 'AL', 'AD', 'AT', 'BY', 'BE', 'BA', 'BG', 'HR', 'CZ', 'DK', 'EE', 'FO', 'FI', 'FR', 'DE', 'GI', 'GR',
            'GG', 'VA', 'HU', 'IS', 'IE', 'IM', 'IT', 'JE', 'LV', 'LI', 'LT', 'LU', 'MK', 'MT', 'MD', 'MC', 'ME', 'NL',
            'NO', 'PL', 'PT', 'RO', 'RU', 'SM', 'RS', 'SK', 'SI', 'ES', 'SJ', 'SE', 'CH', 'UA', 'GB', 'XK', 'QU',
        ],
        'Asia'    => [
            'AF', 'AM', 'AZ', 'BH', 'BD', 'BT', 'BN', 'KH', 'CN', 'CY', 'GE', 'HK', 'IN', 'ID', 'IR', 'IQ', 'IL', 'JP',
            'JO', 'KZ', 'KP', 'KR', 'KW', 'KG', 'LA', 'LB', 'MO', 'MY', 'MV', 'MN', 'MM', 'NP', 'OM', 'PK', 'PS', 'PH',
            'QA', 'SA', 'SG', 'LK', 'SY', 'TW', 'TJ', 'TH', 'TL', 'TR', 'TM', 'AE', 'UZ', 'VN', 'YE',
        ],
        'America' => [
            'AI', 'AG', 'AR', 'AW', 'BS', 'BB', 'BZ', 'BM', 'BO', 'BQ', 'BR', 'CA', 'KY', 'CL', 'CO', 'CR', 'CU', 'CW',
            'DM', 'DO', 'EC', 'SV', 'FK', 'GF', 'GL', 'GD', 'GP', 'GT', 'GY', 'HT', 'HN', 'JM', 'MQ', 'MX', 'MS', 'NI',
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
        'AT', 'PL', 'PT', 'RO', 'SI', 'SK', 'FI', 'SE', 'GB', 'IS', 'NO', 'CH', 'LI', 'JP', 'CA', 'US', 'AU', 'QU',
    ];

    const PL_MAPPING = [];

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

    public static function getSegmentOfGroup(int $id) : int
    {
        foreach (self::GROUPING as $sKey => $segment) {
            if (in_array($id, $segment)) {
                return $sKey;
            }
        }

        return 0;
    }

    public static function getGroupOfArticle(int $id) : int
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

    public static function getDevelopedUndeveloped(string $code) : string
    {
        return in_array(strtoupper(trim($code)), self::DEVELOPED) ? 'Developed' : 'Undeveloped';
    }

    public static function getRegion(string $code) : string
    {
        foreach (self::REGIONS as $key => $region) {
            if (in_array(strtoupper(trim($code)), $region)) {
                return $key;
            }
        }

        return 'Other';
    }

    public static function getSegmentOfArticle(int $id) : int
    {
        return self::getSegmentOfGroup(self::getGroupOfArticle($id));
    }
}