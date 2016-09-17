<?php

namespace QuickDashboard\Application\Models;

class Queries
{
    public static function selectSalesYearMonth(\DateTime $start, \DateTime $end, string $company, array $accounts) : string
    {
        return 'SELECT 
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
            GROUP BY t.years, t.months;';
    }

    public static function selectSalesDaily(\DateTime $start, \DateTime $end, string $company, array $accounts) : string
    {
        return 'SELECT 
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
            GROUP BY t.days;';
    }

    public static function selectSalesByCountry(\DateTime $start, \DateTime $end, string $company, array $accounts) : string
    {
        return 'SELECT DISTINCT
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
            GROUP BY t.countryChar;';
    }

    public static function selectSales(\DateTime $start, \DateTime $end, string $company, array $accounts) : string
    {
        return 'SELECT DISTINCT
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
                ) t;';
    }
}