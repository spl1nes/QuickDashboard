<?php

namespace QuickDashboard\Application\Models;

class Queries
{
    public static function selectSalesYearMonth(\DateTime $start, \DateTime $end, array $accounts) : string
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

    public static function selectSalesDaily(\DateTime $start, \DateTime $end, array $accounts) : string
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

    public static function selectSalesByCountry(\DateTime $start, \DateTime $end, array $accounts) : string
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

    public static function selectAccounts(\DateTime $start, \DateTime $end, array $accounts) : string
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

    public static function selectEntries(\DateTime $start, \DateTime $end, array $accounts) : string
    {
        return 'SELECT DISTINCT
                t.Konto, SUM(t.entries) AS entries
            FROM (
                    SELECT 
                        FiBuchungsArchiv.Konto, SUM(-FiBuchungsArchiv.Betrag) AS entries
                    FROM FiBuchungsArchiv
                    WHERE 
                        CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungsArchiv.Konto
                UNION ALL
                    SELECT 
                        FiBuchungen.Konto, SUM(-FiBuchungen.Betrag) AS entries
                    FROM FiBuchungen
                    WHERE 
                        CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto
                ) t
                    GROUP BY t.Konto;';
    }

    public static function selectSalesArticleGroups(\DateTime $start, \DateTime $end, array $accounts) : string
    {
        return 'SELECT DISTINCT
                t.costcenter, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        FiBuchungsArchiv.KST AS costcenter,
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv
                    WHERE 
                        FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungsArchiv.KST
                UNION ALL
                    SELECT 
                        FiBuchungen.KST AS costcenter,
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen
                    WHERE 
                        FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.KST
                ) t
            GROUP BY t.costcenter;';
    }

    public static function selectCustomerGroup(\DateTime $start, \DateTime $end, array $accounts) : string
    {
        return 'SELECT DISTINCT
                t.cgroup, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        KUNDENADRESSE._KUNDENGRUPPE AS cgroup,
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE._KUNDENGRUPPE
                UNION ALL
                    SELECT 
                        KUNDENADRESSE._KUNDENGRUPPE AS cgroup,
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE._KUNDENGRUPPE
                ) t
            GROUP BY t.cgroup;';
    }

    public static function selectSalesRep(\DateTime $start, \DateTime $end, array $accounts) : string
    {
        return 'SELECT DISTINCT
                t.rep, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        KUNDENADRESSE.VERKAEUFER AS rep,
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE.VERKAEUFER
                UNION ALL
                    SELECT 
                        KUNDENADRESSE.VERKAEUFER AS rep,
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE.VERKAEUFER
                ) t
            GROUP BY t.rep;';
    }
}