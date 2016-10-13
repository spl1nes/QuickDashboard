<?php

namespace QuickDashboard\Application\Models;

class Queries
{
    public static function selectSalesYearMonth(\DateTime $start, \DateTime $end, array $accounts) : string
    {
        return 'SELECT 
                t.account, t.years, t.months, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        FiBuchungsArchiv.Konto as account,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS months, 
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv
                    WHERE 
                        FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungsArchiv.Konto,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104))
                UNION ALL
                    SELECT 
                        FiBuchungen.Konto as account,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS months, 
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen
                    WHERE 
                        FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104))
                ) t
            GROUP BY t.account, t.years, t.months;';
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
                        FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungsArchiv.Konto
                UNION ALL
                    SELECT 
                        FiBuchungen.Konto, SUM(-FiBuchungen.Betrag) AS entries
                    FROM FiBuchungen
                    WHERE 
                        FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto
                ) t
                    GROUP BY t.Konto;';
    }

    public static function selectSalesArticleGroups(\DateTime $start, \DateTime $end, array $accounts) : string
    {
        return 'SELECT DISTINCT
                t.account, t.costcenter, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        FiBuchungsArchiv.Konto as account,
                        FiBuchungsArchiv.KST AS costcenter,
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv
                    WHERE 
                        FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungsArchiv.Konto, FiBuchungsArchiv.KST
                UNION ALL
                    SELECT 
                        FiBuchungen.Konto as account,
                        FiBuchungen.KST AS costcenter,
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen
                    WHERE 
                        FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto, FiBuchungen.KST
                ) t
            GROUP BY t.account, t.costcenter;';
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

    public static function selectCustomer(\DateTime $start, \DateTime $end, array $accounts) : string
    {
        return 'SELECT DISTINCT
                t.customer, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        KUNDENADRESSE.NAME1 AS customer,
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE.NAME1
                UNION ALL
                    SELECT 
                        KUNDENADRESSE.NAME1 AS customer,
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE.NAME1
                ) t
            GROUP BY t.customer;';
    }

    public static function selectCustomerCount(\DateTime $start, \DateTime $end, array $accounts) : string
    {
        return 'SELECT DISTINCT
                t.years, t.months, COUNT(t.customer) AS customers
            FROM (
                    SELECT
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS months,
                        KUNDENADRESSE.KONTO AS customer
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)),
                        KUNDENADRESSE.KONTO
                UNION ALL
                    SELECT 
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS months,
                        KUNDENADRESSE.KONTO AS customer
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)),
                        KUNDENADRESSE.KONTO
                ) t
            GROUP BY t.years, t.months;';
    }

    public static function selectSalesRep(\DateTime $start, \DateTime $end, array $accounts) : string
    {
        return 'SELECT DISTINCT
                t.rep, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        Personalstamm.Name AS rep,
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv, KUNDENADRESSE, Personalstamm
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND Personalstamm.Personalnummer = KUNDENADRESSE.VERKAEUFER
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        Personalstamm.Name
                UNION ALL
                    SELECT 
                        Personalstamm.Name AS rep,
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen, KUNDENADRESSE, Personalstamm
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND Personalstamm.Personalnummer = KUNDENADRESSE.VERKAEUFER
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        Personalstamm.Name
                ) t
            GROUP BY t.rep;';
    }

    public static function selectGroupsByCustomer(\DateTime $start, \DateTime $end, array $accounts, int $customer) : string
    {
        return 'SELECT DISTINCT
                t.entry, t.years, t.months, t.costcenter, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        FiBuchungsArchiv.BelegNr AS entry,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS months, 
                        FiBuchungsArchiv.KST AS costcenter,
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv
                    WHERE 
                        FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.GegenKonto = ' . $customer . '
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungsArchiv.BelegNr, 
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)),
                        FiBuchungsArchiv.KST
                UNION ALL
                    SELECT 
                        FiBuchungen.BelegNr AS entry,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS months, 
                        FiBuchungen.KST AS costcenter,
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen
                    WHERE 
                        FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.GegenKonto = ' . $customer . '
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.BelegNr,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)),
                        FiBuchungen.KST
                ) t
            GROUP BY t.entry, t.years, t.months, t.costcenter;';
    }

    public static function selectCustomerInformation(int $customer) : string
    {
        return 'SELECT KUNDENADRESSE.NAME1, KUNDENADRESSE.ORT, KUNDENADRESSE.PLZ, KUNDENADRESSE.STRASSE, KUNDENADRESSE.LAENDERKUERZEL, KUNDENADRESSE._KUNDENGRUPPE,KUNDENADRESSE.ROW_CREATE_TIME, Personalstamm.Name
            FROM KUNDENADRESSE, Personalstamm
            WHERE 
                KUNDENADRESSE.KONTO = ' . $customer . '
                AND Personalstamm.Personalnummer = KUNDENADRESSE.VERKAEUFER';
    }

    public static function selectSalesGroupYearMonth(\DateTime $start, \DateTime $end, array $accounts, array $groups) : string
    {
        return 'SELECT 
                t.account, t.years, t.months, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        FiBuchungsArchiv.Konto as account,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS months, 
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv
                    WHERE 
                        FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $groups) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungsArchiv.Konto,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104))
                UNION ALL
                    SELECT 
                        FiBuchungen.Konto as account,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS months, 
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen
                    WHERE 
                        FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.KST IN (' . implode(',', $groups) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104))
                ) t
            GROUP BY t.account, t.years, t.months;';
    }

    public static function selectGroupCustomer(\DateTime $start, \DateTime $end, array $accounts, array $groups) : string
    {
        return 'SELECT DISTINCT
                t.customer, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        KUNDENADRESSE.NAME1 AS customer,
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $groups) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE.NAME1
                UNION ALL
                    SELECT 
                        KUNDENADRESSE.NAME1 AS customer,
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.KST IN (' . implode(',', $groups) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE.NAME1
                ) t
            GROUP BY t.customer;';
    }

    public static function selectGroupCustomerCount(\DateTime $start, \DateTime $end, array $accounts, array $groups) : string
    {
        return 'SELECT DISTINCT
                t.years, t.months, COUNT(t.customer) AS customers
            FROM (
                    SELECT
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS months,
                        KUNDENADRESSE.KONTO AS customer
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $groups) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)),
                        KUNDENADRESSE.KONTO
                UNION ALL
                    SELECT 
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS months,
                        KUNDENADRESSE.KONTO AS customer
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.KST IN (' . implode(',', $groups) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)),
                        KUNDENADRESSE.KONTO
                ) t
            GROUP BY t.years, t.months;';
    }

    public static function selectGroupAccounts(\DateTime $start, \DateTime $end, array $accounts, array $groups) : string
    {
        return 'SELECT DISTINCT
                SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv
                    WHERE 
                        FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $groups) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                UNION ALL
                    SELECT 
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen
                    WHERE 
                        FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.KST IN (' . implode(',', $groups) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                ) t;';
    }

    public static function selectGroupSalesByCountry(\DateTime $start, \DateTime $end, array $accounts, array $groups) : string
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
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $groups) . ')
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
                        AND FiBuchungen.KST IN (' . implode(',', $groups) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE.LAENDERKUERZEL
                ) t
            GROUP BY t.countryChar;';
    }

    public static function selectCountrySalesYearMonth(\DateTime $start, \DateTime $end, array $accounts, array $countries) : string
    {
        return 'SELECT 
                t.account, t.years, t.months, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        FiBuchungsArchiv.Konto as account,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS months, 
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungsArchiv.Konto,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104))
                UNION ALL
                    SELECT 
                        FiBuchungen.Konto as account,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS months, 
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104))
                ) t
            GROUP BY t.account, t.years, t.months;';
    }

    public static function selectCountryCustomer(\DateTime $start, \DateTime $end, array $accounts, array $countries) : string
    {
        return 'SELECT DISTINCT
                t.customer, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        KUNDENADRESSE.NAME1 AS customer,
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE.NAME1
                UNION ALL
                    SELECT 
                        KUNDENADRESSE.NAME1 AS customer,
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE.NAME1
                ) t
            GROUP BY t.customer;';
    }

    public static function selectCountryCustomerCount(\DateTime $start, \DateTime $end, array $accounts, array $countries) : string
    {
        return 'SELECT DISTINCT
                t.years, t.months, COUNT(t.customer) AS customers
            FROM (
                    SELECT
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS months,
                        KUNDENADRESSE.KONTO AS customer
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)),
                        KUNDENADRESSE.KONTO
                UNION ALL
                    SELECT 
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS months,
                        KUNDENADRESSE.KONTO AS customer
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)),
                        KUNDENADRESSE.KONTO
                ) t
            GROUP BY t.years, t.months;';
    }

    public static function selectCountrySalesArticleGroups(\DateTime $start, \DateTime $end, array $accounts, array $countries) : string
    {
        return 'SELECT DISTINCT
                t.account, t.costcenter, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        FiBuchungsArchiv.Konto as account,
                        FiBuchungsArchiv.KST AS costcenter,
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungsArchiv.Konto, FiBuchungsArchiv.KST
                UNION ALL
                    SELECT 
                        FiBuchungen.Konto as account,
                        FiBuchungen.KST AS costcenter,
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto, FiBuchungen.KST
                ) t
            GROUP BY t.account, t.costcenter;';
    }

    public static function selectGroupsByDay(\DateTime $start, \DateTime $end, array $accounts) : string
    {
        return 'SELECT
                t.years, t.months, t.costcenter, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS months, 
                        FiBuchungsArchiv.KST AS costcenter,
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv
                    WHERE 
                        FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)),
                        FiBuchungsArchiv.KST
                UNION ALL
                    SELECT 
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS months, 
                        FiBuchungen.KST AS costcenter,
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen
                    WHERE 
                        FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)),
                        FiBuchungen.KST
                ) t
            GROUP BY t.entry, t.years, t.months, t.costcenter;';
    }
}