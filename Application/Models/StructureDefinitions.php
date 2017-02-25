<?php

namespace QuickDashboard\Application\Models;

class StructureDefinitions
{
    const ACCOUNTS_DOMESTIC = [
        8000, 8005, 8006, 8010, 8013, 8020, 8021, 8022, 8030, 8040, 8060, 8062, 8064, 8065, 8070, 8075, 8400, 8405,
        8406, 8410, 8413, 8415, 8420, 8425, 8430, 8440, 8460, 8461, 8462, 8463, 8464, 8465, 8487, 8488, 8489, 8502, 8505,
        8506, 8507, 8508, 8509, 8690, 8733, 8734, 8736, 8739, 8756, 8757, 8794, 8796, 8799, 8840, 8850, 8860, 8870, 8998,
        8865, 8500, 8503, 8510, 8511, 8512, 8520, 8530, 8584, 8585, 8730, 8855, 2894,
    ];

    const PL_ACCOUNTS = [
        'Sales'                    => [8050, 8052, 8055, 8090, 8095, 8100, 8105, 8106, 8110, 8113, 8115, 8120, 8121, 8122, 8125, 8130, 8140, 8160, 8592,
                                       8161, 8162, 8300, 8305, 8306, 8310, 8315, 8320, 8330, 8340, 8360, 8361, 8362, 8367, 8368, 8380, 8740, 8746, 8749,
                                       8765, 8781, 8791, 8793, 8841, 8843, 8851, 8853, 8861, 8863, 8871, 8873, 8955, 8000, 8005, 8006, 8010, 8013, 8020,
                                       8021, 8022, 8030, 8040, 8060, 8062, 8064, 8065, 8070, 8075, 8400, 8405, 8406, 8410, 8413, 8415, 8420, 8425, 8910,
                                       8430, 8440, 8460, 8461, 8462, 8463, 8464, 8465, 8487, 8488, 8489, 8502, 8505, 8506, 8507, 8508, 8509, 8690, 8733,
                                       8734, 8736, 8739, 8756, 8757, 8794, 8796, 8799, 8840, 8850, 8860, 8870, 8998, 8865, 8500, 8503, 8510, 8511, 8512,
                                       8520, 8530, 8584, 8585, 8730, 8855, 2894, 8700, 8200, 8205, 8206, 8210, 8213, 8215, 8220, 8221, 8225, 8230, 8240,
                                       8260, 8261, 8262, 8263, 8264, 8287, 8289, 8290, 8741, 8753, 8754, 8761, 8782, 8792, 8795, 8842, 8852, 8862, 8872,],
        'COGS Material'            => [3400, 3401, 3405, 3407, 3410, 3410, 3411, 3415, 3420, 3430, 3440, 3460, 3461, 3462, 3465, 3470, 3480, 3500, 3505, 3510, 3515, 3520, 3600, 3730, 3731, 3735, 3736, 3737, 3739, 3756, 3300, 3305, 3310, 3319, 3329, 3339, 3340, 3380, 3381, 3382, 3385, 3530, 3660, 3669, 3740, 3742, 3746, 3749, 3200, 3210, 3285, 3492, 3650, 3741, 3800, 4710, 2725, 3961, 3962, 3963, 3965, 4000, 3487, 3490, 8999, 3485, 3486, 8080, 8081, 8085, 8285, 8480, 8485, 8504, 3960, 3964],
        'COGS Services'            => [3552, 3553, 3554, 3555, 3557, 3100, 3101, 3105, 3110, 3119, 3840],
        'Freight'                  => [3810, 3830, 3850, 4700, 4730, 4735, 4737],
        'Provisions'               => [4760, 4765, 4767, 4768],
        'External Seminars'        => [4460, 4480, 4480, 4482, 4483, 4484, 4485, 4490],
        'Other Revenue'            => [2511, 2515, 2500, 2501, 2503, 2510, 2519, 2520, 2520, 2521, 2525, 2530, 2531, 2618, 2660, 2666, 2700, 2709, 2742, 2749, 2750, 3122, 4120, 8400, 8640, 8940, 8955, 2315, 8920, 8925, 8930, 22830, 2730, 2731, 2732, 2733, 27350, 2735, 2705, 2706, 2707, 2710, 2715, 2719, 2756],
        'Wages & Salaries'         => [2895, 4110, 4112, 4113, 4114, 4123, 4124, 4125, 4126, 4127, 4128, 4135, 4140, 4145, 4160, 4165, 4170, 4175, 4180, 4190, 4195, 4199, 8614, 8905, 8921, 8922, 8980, 8981, 8985, 8987, 8988, 8989],
        'Welfare Expenses'         => [4130, 4138, 4139, 4191, 4192],
        'Marketing'                => [4600, 4605, 4610, 4611, 4613, 4614, 4615, 4450, 4620, 4625, 4626, 4455, 4618, 4619, 4617, 4616, 4601, 4602, 4623, 4456],
        'Trade Fair'               => [4410, 4411, 4412, 4413, 4414, 4415, 4418, 4419, 4420, 4421, 4424, 4425, 4426, 4423, 4404, 4427, 4401, 4402, 4422, 4416, 4417, 4428, 4430, 4405],
        'Rental & Leasing'         => [42100, 4210, 4211, 4220, 4228, 4815, 4960, 4961],
        'Utilities'                => [4230, 4240, 4241, 4250, 4251],
        'Repair/Maintenance'       => [4260, 4261, 4262, 4800, 4805, 4806, 4809, 4810, 4985],
        'Carpool'                  => [4340, 4365, 4500, 4501, 4502, 4503, 4504, 4505, 4506, 4507, 4508, 4509, 4510, 4511, 4512, 4513, 4514, 4515, 4516, 4517, 4518, 4519, 4520, 4521, 4522, 4523, 4524, 4525, 4526, 4527, 4528, 4529, 4530, 4531, 4532, 4533, 4534, 4535, 4536, 4537, 4538, 4539, 4540, 4541, 4542, 4543, 4544, 4545, 4546, 4547, 4548, 4549, 4550, 4551, 4552, 4553, 4554, 4555, 4556, 4557, 4558, 4559, 4560, 4571, 4572, 4573, 4574, 4575, 4580],
        'Stationary Expenses'      => [4645, 4646, 49300, 4930, 4932, 4935, 4940, 4945, 4963, 4979, 4980, 4981, 4982, 4983, 4984, 4984],
        'Communication'            => [4910, 4911, 4920, 4921, 4922, 4925],
        'Travel Expenses'          => [4570, 4581, 4660, 4661, 4662, 4663, 4664, 4665, 4666, 4668, 4669, 4670, 4671, 4675, 4680, 4681, 4682, 4683, 4684, 4685, 4686, 4687, 4688, 4689, 4690, 4691, 4692, 4693, 4694, 4695, 4696, 4697, 4698],
        'Entertainment'            => [4630, 4635, 4640, 4641, 4643, 4650, 4651, 4652, 4653, 4654, 4656],
        'External Consultants'     => [4385, 4395, 4396, 4770, 4775, 4950, 4950, 4951, 4953, 4954, 4955, 4955, 4957, 4986],
        'R&D'                      => [3102, 3120, 3121, 3130, 3140, 3160, 3190, 3199],
        'Patents'                  => [3450, 3550, 3560],
        'Other Personnel Expenses' => [4100, 4101, 4105, 4106, 4107, 4155],
        'Other OPEX'               => [2001, 2002, 2020, 2025, 2150, 2155, 2165, 2300, 2380, 2385, 2600, 4150, 4396, 4398, 46550, 4738, 4780, 4790, 49000, 4900, 4905, 4908, 4909, 4947, 49490, 4949, 4970, 4975, 4360, 4380, 4390, 43960],
        'Intercompany Expenses'    => [4400, 4990, 4991, 4992, 4993, 4994, 4995, 4996, 4997],
        'Intercompany Revenue'     => [80000, 80010, 8001, 80020, 80030, 80040, 8004, 80050, 8007, 80080, 8009, 8011, 80120, 8012, 80130, 80140, 8015, 2708, 2990, 2991, 2992, 2993, 2994, 2995, 2997, 4185],
        'Doubtful Accounts'        => [2400, 2401, 2403, 2405, 2406, 2409, 2414, 2430, 2450, 2451],
        'Depreciation'             => [2893, 4822, 4830, 4832, 4835, 4855],
        'Interest Revenue'         => [26500, 2650, 2652, 2652, 2653, 2655, 26570, 2657, 26501, 2651, 2659],
        'Interest Expenses'        => [2100, 2105, 2107, 2110, 2120, 2121, 2121, 2140, 2215, 2218, 4321, 2128, 21201, 21290, 2129, 2129, 26590],
        'Taxes'                    => [2104, 21070, 2108, 2200, 2203, 2204, 2205, 2208, 2209, 2210, 2221, 22800, 2280, 22810, 2281, 22820, 2282, 2285, 2287, 43200, 4320, 4330, 43400, 4397, 4350],
        'Transfer Of Profits'      => [864, 24900, 2490, 2490, 2492, 2616, 27940, 2794],
    ];

    const BALANCE = [
        'Intangible Assets'                  => [25, 26, 27, 39],
        'Tangible Assets'                    => [165, 200, 201, 205, 210, 210, 211, 215, 220, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280, 270, 320, 400, 401, 402, 403, 404, 405, 410, 411, 419, 420, 421, 421, 422, 423, 424, 431, 440, 460, 461, 462, 464, 480, 481, 485, 490, 992, 299],
        'Financial Assets'                   => [500, 502, 519, 505, 506, 507, 550, 551, 555, 556, 595],
        'Stocks'                             => [3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984, 1519],
        'Doubtful Accounts'                  => [1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407, 1436, 15950, 1595, 1596, 505, 5060, 1401, 14700, 1550, 1594, 1403, 1539, 1540, 1549, 1548, 1567, 1570, 1575, 15760, 1580, 1587, 17910, 1410, 1440, 1450, 1500, 1520, 1525, 1527, 15450, 1545, 1590, 1591, 17920],
        'Cash Assets'                        => [1000, 1020, 1050, 1100, 1120, 1200, 12100, 1210, 1215, 1220, 1240, 1242, 1250, 1260, 1270, 1280, 1320, 1330, 1340, 1360, 1361, 1362, 1363, 1364, 1365, 1366, 1415],
        'Prepayments and Accured Income'     => [980, 981, 982],
        'Subscribed Capital'                 => [800, 900, 901, 902, 903, 904, 9010, 9020, 9030, 9040],
        'Capital Reserve'                    => [840, 910, 911, 912, 913, 914, 9100, 9110, 9120, 9130, 9140, 9842, 9843],
        'Profit Brought Forward'             => [860, 868, 890, 905, 906, 907, 908, 909, 915, 916, 917, 918, 919, 8600, 9150, 9160, 9170, 9180, 9190, 9790],
        'Net Income'                         => [9000, 9008, 9009, 9009],
        'Adjustment item for own shares'     => [850, 8500, 9880],
        'Reserve'                            => [950, 955, 956, 963, 964, 9570, 9500, 965, 969, 970, 971, 971, 972, 973, 974, 975, 977, 978, 979, 9770],
        'Liabilities to Credit Institutions' => [631, 6501, 650, 6900, 690, 731, 1255, 6500],
        'Advanced received'                  => [1722],
        'Accounts payable'                   => [1600, 1602, 1605, 1605, 1607],
        'Liabilitie to Affiliates'           => [17050, 7000, 7053, 7051, 7051, 7050, 1601, 1721, 705, 701, 704],
        'Liabilities to Shareholders'        => [700, 751, 920, 923, 924, 925, 1636, 17010, 1630, 1603, 702, 1701, 1435, 1635],
        'Other Liabilities'                  => [752, 1610, 1700, 1730, 1741, 1556, 1561, 1566, 1571, 1576, 1577, 1586, 1588, 1756, 1761, 1762, 1764, 1766, 1767, 1772, 1773, 1774, 1775, 1776, 1777, 1779, 1780, 1781, 1786, 1787, 17890, 1789, 1790, 1795, 1740, 1742, 1744, 1745, 1746, 1755, 990, 991],
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

    const DEPARTMENTS_SD = [
        'Finance'                    => [5600],
        'Personnel'                  => [5700],
        'Sales Domestic Back Office' => [5100, 5200, 5400],
        'Sales Domestic Reps'        => [4500],
        'Sales Export Back Office'   => [3500],
        'Sales Export Reps'          => [3300],
        'Sales Dentist Reps'         => [4700],
        'Sales Management'           => [6100],
        'Service'                    => [1199],
        'Support'                    => [600, 6050],
        'Production'                 => [],
        'Purchase'                   => [5450],
        'Warehouse'                  => [5300, 6300],
        'R&D'                        => [1000],
        'Management'                 => [5000],
        'Trainees'                   => [6400],
        'Marketing'                  => [5800],
        'IT'                         => [5500],
        'Secretariat'                => [5900],
        'QA'                         => [6200],
        'MANI'                       => [4800],
        'General'                    => [4900, 2900, 3400],
    ];

    const DEPARTMENTS_GDF = [
        'Finance'                    => [5600],
        'Personnel'                  => [5700],
        'Sales Domestic Back Office' => [4500, 5400],
        'Sales Domestic Reps'        => [],
        'Sales Export Back Office'   => [],
        'Sales Export Reps'          => [3300],
        'Sales Dentist Reps'         => [],
        'Sales Management'           => [],
        'Service'                    => [],
        'Support'                    => [],
        'Production'                 => [4300, 4400, 4450, 4600],
        'Purchase'                   => [],
        'Warehouse'                  => [6300],
        'R&D'                        => [4000],
        'Management'                 => [5000],
        'Trainees'                   => [6400],
        'Marketing'                  => [],
        'IT'                         => [5500],
        'Secretariat'                => [5900],
        'QA'                         => [6000],
        'MANI'                       => [4800],
        'General'                    => [4900, 4009, 4120, 5900],
    ];

    const DEVELOPED = [
        'BE', 'BG', 'CZ', 'DK', 'DE', 'EE', 'GR', 'ES', 'FR', 'IT', 'CY', 'LV', 'LT', 'LU', 'HU', 'MT', 'NL', 'HR',
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
        2  => 'Standard Consumables',
        3  => 'Digital Workflow',
        4  => 'IMPLA',
        5  => 'Misc.',
        6  => 'MANI Articles',
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
        61 => 'Surgical Needles',
        62 => 'Burs & Instruments',
        63 => 'Endodontics',
        101 => 'Precious Alloys',
        102 => 'Solder',
        103 => 'Galvano',
        104 => 'Insurance',
        105 => 'Scheidgut',
        106 => 'Milling',
        111 => 'Non-precious Alloys',
        121 => 'Futura',
        122 => 'KFO',
        123 => 'Misc. Acrylics',
        131 => 'Ceramics',
        141 => 'Composites',
        151 => 'Titanium',
        161 => 'Impla',
        162 => 'Impla 3D',
        171 => 'Laser',
        172 => 'Welder',
        181 => 'Attachements',
        201 => 'Misc. Machines',
        211 => 'Casting',
        212 => 'Misc Consumables',
        213 => 'Plaster Cast',
        214 => 'Rotating Instruments',
        221 => 'Courses',
        222 => 'Furniture',
        223 => 'Service',
        224 => 'Misc',
        225 => 'Shipping & Packaging',
        8000 => 'Misc',
        231 => 'Digital Consumables',
        232 => 'Zirconia',
        233 => 'CAD/CAM',
        234 => 'Misc',
        235 => 'Insurance',
        236 => 'Maintenance',
        241 => 'Zebris',
        301 => 'Laser and Parts',
        311 => 'Dental Machines',
        321 => 'Misc Consumables',
        322 => 'Temp. Material',
        323 => 'Investment Mat',
        331 => 'Composites Dentist',
        341 => 'Bleaching',
        351 => 'Service Work Time',
        354 => 'Packaging',
        611 => 'Sutures',
        612 => 'Eyed Needles',
        613 => 'Sutures Micro Use',
        621 => 'Burs & Instr. Lab',
        622 => 'Burs & Instr. Dentist',
        631 => 'Endodontic Instruments',
        632 => 'Endodontic Rotary Instruments',
        633 => 'Endodontic Micro Accessoires',
        634 => 'Endodontic Accessoires',
    ];

    const CUSTOMER_GROUP = [
        'sd'  => [
            0    => 'Other Customer',
            1000 => 'Dental Lab',
            1300 => 'Export',
            2000 => 'Dentist Lab',
            3000 => 'Dentist',
            4000 => 'Dental Depot',
            4300 => 'Export',
            5000 => 'Educational Facility',
            6000 => 'Dental Trading',
        ],
        'gdf' => [
            0    => 'Other Customer',
            4000 => 'Dental Trading',
            4300 => 'Micerium',
            6000 => 'Anax Dent',
            6010 => 'Resound China',
            6020 => 'Dental Trading',
        ],
    ];

    public static function getDomesticExportAccount(int $account) : string
    {
        return $account === 8591 || in_array($account, self::ACCOUNTS_DOMESTIC) ? 'Domestic' : 'Export';
    }

    public static function getSegmentOfGroup(int $id) : int
    {
        foreach (self::GROUPING as $sKey => $segment) {
            if (isset($segment[$id])) {
                return $sKey;
            }
        }

        return 0;
    }

    public static function getSalesGroupsAll() : array
    {
        $groups = [];
        foreach (self::GROUPING as $segment) {
            foreach ($segment as $gKey => $group) {
                $groups = array_merge($groups, $group);
            }
        }

        return $groups;
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
        foreach (self::GROUPING as $sKey => $segment) {
            foreach ($segment as $gKey => $group) {
                if (in_array($id, $group)) {
                    return $sKey;
                }
            }
        }

        return 0;
    }

    public static function getSalesGroups(int $id) : array
    {
        $groups = [];

        if ($id === 16) {
            return [161];
        }

        foreach (self::GROUPING as $segmentId => $group) {
            foreach ($group as $groupId => $salesGroup) {
                foreach ($salesGroup as $salesGroupId) {
                    if ($segmentId === $id || $groupId === $id || $salesGroupId === $id) {
                        $groups[] = $salesGroupId;
                    }

                    if ($salesGroupId === $id) {
                        break 3;
                    }
                }

                if ($groupId === $id) {
                    break 2;
                }
            }

            if ($segmentId === $id) {
                break;
            }
        }

        return array_unique($groups);
    }

    public static function getAccountPLPosition(int $id) : string
    {
        foreach (self::PL_ACCOUNTS as $key => $account) {
            if (in_array($id, $account)) {
                return $key;
            }
        }

        if ($id === 8591) {
            return 'Sales';
        } elseif ($id === 3491) {
            return 'COGS Material';
        }

        return '';
    }

    public static function getAccountBalancePosition(int $id) : string
    {
        foreach (self::BALANCE as $key => $account) {
            if (in_array($id, $account)) {
                return $key;
            }
        }

        return '';
    }

    public static function getCOGSAccounts() : array
    {
        return array_merge(self::PL_ACCOUNTS['COGS Material'], self::PL_ACCOUNTS['COGS Services']);
    }

    public static function getOPEXPositions() : array
    {
        return [
            'Freight', 'Provisions', 'External Seminars', 'Wages & Salaries', 'Welfare Expenses',
            'Marketing', 'Trade Fair', 'Rental & Leasing', 'Utilities', 'Carpool', 'Repair/Maintenance',
            'Stationary Expenses', 'Communication', 'Travel Expenses', 'Entertainment', 'External Consultants', 'R&D',
            'Patents', 'Other Personnel Expenses', 'Other OPEX', 'Intercompany Expenses', 'Intercompany Revenue',
            'Doubtful Accounts', 'Depreciation'];
    }

    public static function getBalanceAccounts() : array
    {
        $balanceAccounts = [];

        foreach(self::BALANCE as $accounts) {
            $balanceAccounts = array_merge($balanceAccounts, $accounts);
        }

        return $balanceAccounts;
    }

    public static function getPLAccounts() : array
    {
        $plAccounts = [];

        foreach(self::PL_ACCOUNTS as $accounts) {
            $plAccounts = array_merge($plAccounts, $accounts);
        }

        return $plAccounts;
    }

    public static function getOPEXAccounts() : array
    {
        return array_merge(
            self::PL_ACCOUNTS['Freight'],
            self::PL_ACCOUNTS['Provisions'],
            self::PL_ACCOUNTS['External Seminars'],
            self::PL_ACCOUNTS['Other Revenue'],
            self::PL_ACCOUNTS['Wages & Salaries'],
            self::PL_ACCOUNTS['Welfare Expenses'],
            self::PL_ACCOUNTS['Marketing'],
            self::PL_ACCOUNTS['Trade Fair'],
            self::PL_ACCOUNTS['Rental & Leasing'],
            self::PL_ACCOUNTS['Utilities'],
            self::PL_ACCOUNTS['Carpool'],
            self::PL_ACCOUNTS['Repair/Maintenance'],
            self::PL_ACCOUNTS['Stationary Expenses'],
            self::PL_ACCOUNTS['Communication'],
            self::PL_ACCOUNTS['Travel Expenses'],
            self::PL_ACCOUNTS['Entertainment'],
            self::PL_ACCOUNTS['External Consultants'],
            self::PL_ACCOUNTS['R&D'],
            self::PL_ACCOUNTS['Patents'],
            self::PL_ACCOUNTS['Other Personnel Expenses'],
            self::PL_ACCOUNTS['Other OPEX'],
            self::PL_ACCOUNTS['Intercompany Expenses'],
            self::PL_ACCOUNTS['Intercompany Revenue'],
            self::PL_ACCOUNTS['Doubtful Accounts'],
            self::PL_ACCOUNTS['Depreciation']
        );
    }

    public static function getEBITAccounts() : array
    {
        return array_merge(
            self::PL_ACCOUNTS['Sales'],
            self::PL_ACCOUNTS['COGS Material'],
            self::PL_ACCOUNTS['COGS Services'],
            self::PL_ACCOUNTS['Freight'],
            self::PL_ACCOUNTS['Provisions'],
            self::PL_ACCOUNTS['External Seminars'],
            self::PL_ACCOUNTS['Other Revenue'],
            self::PL_ACCOUNTS['Wages & Salaries'],
            self::PL_ACCOUNTS['Welfare Expenses'],
            self::PL_ACCOUNTS['Marketing'],
            self::PL_ACCOUNTS['Trade Fair'],
            self::PL_ACCOUNTS['Rental & Leasing'],
            self::PL_ACCOUNTS['Utilities'],
            self::PL_ACCOUNTS['Carpool'],
            self::PL_ACCOUNTS['Repair/Maintenance'],
            self::PL_ACCOUNTS['Stationary Expenses'],
            self::PL_ACCOUNTS['Communication'],
            self::PL_ACCOUNTS['Travel Expenses'],
            self::PL_ACCOUNTS['Entertainment'],
            self::PL_ACCOUNTS['External Consultants'],
            self::PL_ACCOUNTS['R&D'],
            self::PL_ACCOUNTS['Patents'],
            self::PL_ACCOUNTS['Other Personnel Expenses'],
            self::PL_ACCOUNTS['Other OPEX'],
            self::PL_ACCOUNTS['Intercompany Expenses'],
            self::PL_ACCOUNTS['Intercompany Revenue'],
            self::PL_ACCOUNTS['Doubtful Accounts'],
            self::PL_ACCOUNTS['Depreciation']
        );
    }

    public static function getCountries() : array
    {
        $countries = [];

        foreach (self::REGIONS as $region) {
            $countries = array_merge($countries, $region);
        }

        return $countries;
    }

    public static function getLocations(string $location) : array
    {
        $locations = [];
        $countries = self::getCountries();

        if ($location === 'Export') {
            $locations = array_diff($countries, ['DE']);
        } elseif ($location === 'Domestic' || $location === 'DE') {
            $locations = ['DE'];
        } elseif ($location === 'Developed') {
            $locations = self::DEVELOPED;
        } elseif ($location === 'Undeveloped') {
            $locations = array_diff($countries, self::DEVELOPED);
        } elseif (isset(self::REGIONS[$location])) {
            $locations = self::REGIONS[$location];
        } elseif(in_array($location, $countries)) {
            $locations = [$location];
        } else {
            throw new \Exception('Unknown location ' . $location);
        }

        return $locations;
    }

    public static function getDepartmentByCostCenter(int $costcenter, string $company) : string
    {
        $departments = $company !== 'gdf' ? self::DEPARTMENTS_SD : self::DEPARTMENTS_GDF;

        foreach($departments as $name => $costcenters) {
            if(in_array($costcenter, $costcenters)) {
                return $name;
            }
        }

        return 'Misc.';
    }
}