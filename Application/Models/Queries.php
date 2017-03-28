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

    public static function selectEntries2(\DateTime $start, \DateTime $end, array $accounts) : string
    {
        return 'SELECT DISTINCT 
                t.Konto, t.GegenKonto, SUM(t.entries) AS entries
            FROM (
                SELECT FiBuchungsArchiv.Konto, FiBuchungsArchiv.GegenKonto, SUM(-FiBuchungsArchiv.Betrag) as entries
                FROM FiBuchungsArchiv
                WHERE 
                    FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                    AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                    AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                GROUP BY FiBuchungsArchiv.Konto, FiBuchungsArchiv.GegenKonto
            UNION ALL
                SELECT FiBuchungen.Konto, FiBuchungen.GegenKonto, SUM(-FiBuchungen.Betrag) as entries
                FROM FiBuchungen
                WHERE 
                    FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                    AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                    AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                GROUP BY FiBuchungen.Konto, FiBuchungen.GegenKonto
            ) t 
            GROUP BY t.Konto, t.GegenKonto;';
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
                t.customer, t.id, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        FiBuchungsArchiv.GegenKonto AS id,
                        KUNDENADRESSE.NAME1 AS customer,
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungsArchiv.GegenKonto,
                        KUNDENADRESSE.NAME1
                UNION ALL
                    SELECT 
                        FiBuchungen.GegenKonto AS id,
                        KUNDENADRESSE.NAME1 AS customer,
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.GegenKonto,
                        KUNDENADRESSE.NAME1
                ) t
            GROUP BY t.id, t.customer;';
    }


    public static function selectVendor(\DateTime $start, \DateTime $end, array $accounts) : string
    {
        return 'SELECT DISTINCT
                t.customer, t.id, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        FiBuchungsArchiv.GegenKonto AS id,
                        LieferantenAdresse.NAME1 AS customer,
                        SUM(FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv, LieferantenAdresse
                    WHERE 
                        LieferantenAdresse.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungsArchiv.GegenKonto,
                        LieferantenAdresse.NAME1
                UNION ALL
                    SELECT 
                        FiBuchungen.GegenKonto AS id,
                        LieferantenAdresse.NAME1 AS customer,
                        SUM(FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen, LieferantenAdresse
                    WHERE 
                        LieferantenAdresse.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.GegenKonto,
                        LieferantenAdresse.NAME1
                ) t
            GROUP BY t.id, t.customer;';
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
        return 'SELECT
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
        return 'SELECT
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
            GROUP BY t.years, t.months, t.costcenter;';
    }

    public static function selectAccountsByCostCenter(\DateTime $start, \DateTime $end, array $accounts, array $costcenters) : string
    {
        return 'SELECT
                t.years, t.months, t.account, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS months, 
                        FiBuchungsArchiv.Konto as account,
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv
                    WHERE 
                        FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.KST IN (\'' . rtrim(implode(' \',\'', $costcenters), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)),
                        FiBuchungsArchiv.Konto
                UNION ALL
                    SELECT 
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS months, 
                        FiBuchungen.Konto as account,
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen
                    WHERE 
                        FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.KST IN (\'' . rtrim(implode(' \',\'', $costcenters), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)),
                        FiBuchungen.Konto
                ) t
            GROUP BY t.years, t.months, t.account;';
    }

    public static function selectOPByAccountDebit(\DateTime $end, int $account) : string
    {
        return self::selectOPByAccount($end, $account, 'S');
    }

    public static function selectOPByAccountCredit(\DateTime $end, int $account) : string
    {
        return self::selectOPByAccount($end, $account, 'H');
    }

    private static function selectOPByAccount(\DateTime $end, int $account, string $type) : string
    {
        return 'SELECT
                    SUM(FiOffenePosten.Betrag) 
                FROM FiOffenePosten
                WHERE 
                    FiOffenePosten.Konto = ' . $account . '
                    AND FiOffenePosten.OPKennzeichen = \'' . $type . '\'
                    AND CONVERT(VARCHAR(30), FiOffenePosten.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    AND (
                        CONVERT(VARCHAR(30), FiOffenePosten.Ausgleichsdatum, 104) >= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
                        OR FiOffenePosten.Ausgleichsdatum IS NULL
                    )';
    }

    public static function selectOPByAccountDebitDue(\DateTime $end, int $account) : string
    {
        return self::selectOPByAccountDue($end, $account, 'S');
    }

    public static function selectOPByAccountCreditDue(\DateTime $end, int $account) : string
    {
        return self::selectOPByAccountDue($end, $account, 'H');
    }

    private static function selectOPByAccountDue(\DateTime $end, int $account, string $type) : string
    {
        return 'SELECT
                    SUM(FiOffenePosten.Betrag) 
                FROM FiOffenePosten
                WHERE 
                    FiOffenePosten.Konto = ' . $account . '
                    AND FiOffenePosten.OPKennzeichen = \'' . $type . '\'
                    AND CONVERT(VARCHAR(30), FiOffenePosten.faellig, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    AND CONVERT(VARCHAR(30), FiOffenePosten.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    AND (
                        CONVERT(VARCHAR(30), FiOffenePosten.Ausgleichsdatum, 104) >= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
                        OR FiOffenePosten.Ausgleichsdatum IS NULL
                    )';
    }

    public static function selectSalesRepNames() : string
    {
        return 'SELECT DISTINCT Personalstamm.Personalnummer AS id, Personalstamm.Name AS name FROM Personalstamm;';
    }

    public static function selectRepSalesYearMonth(\DateTime $start, \DateTime $end, array $accounts, array $reps) : string
    {
        return 'SELECT 
                t.account, t.years, t.months, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        FiBuchungsArchiv.Konto as account,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS months, 
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv, KUNDENADRESSE, Personalstamm
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND Personalstamm.Personalnummer = KUNDENADRESSE.VERKAEUFER
                        AND Personalstamm.Name = (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
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
                    FROM FiBuchungen, KUNDENADRESSE, Personalstamm
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND Personalstamm.Personalnummer = KUNDENADRESSE.VERKAEUFER
                        AND Personalstamm.Name = (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104))
                ) t
            GROUP BY t.account, t.years, t.months;';
    }

    public static function selectRepGroupSales(\DateTime $start, \DateTime $end, array $accounts, array $groups) : string
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
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $groups) . ')
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
                        AND FiBuchungen.KST IN (' . implode(',', $groups) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        Personalstamm.Name
                ) t
            GROUP BY t.rep;';
    }

    public static function selectRepCustomer(\DateTime $start, \DateTime $end, array $accounts, array $reps) : string
    {
        return 'SELECT DISTINCT
                t.customer, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        KUNDENADRESSE.NAME1 AS customer,
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv, KUNDENADRESSE, Personalstamm
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND Personalstamm.Personalnummer = KUNDENADRESSE.VERKAEUFER
                        AND Personalstamm.Name = (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE.NAME1
                UNION ALL
                    SELECT 
                        KUNDENADRESSE.NAME1 AS customer,
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen, KUNDENADRESSE, Personalstamm
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND Personalstamm.Personalnummer = KUNDENADRESSE.VERKAEUFER
                        AND Personalstamm.Name = (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE.NAME1
                ) t
            GROUP BY t.customer;';
    }

    public static function selectRepCustomerCount(\DateTime $start, \DateTime $end, array $accounts, array $reps) : string
    {
        return 'SELECT
                t.years, t.months, COUNT(t.customer) AS customers
            FROM (
                    SELECT
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS years, 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS months,
                        KUNDENADRESSE.KONTO AS customer
                    FROM FiBuchungsArchiv, KUNDENADRESSE, Personalstamm
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND Personalstamm.Personalnummer = KUNDENADRESSE.VERKAEUFER
                        AND Personalstamm.Name = (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
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
                    FROM FiBuchungen, KUNDENADRESSE, Personalstamm
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND Personalstamm.Personalnummer = KUNDENADRESSE.VERKAEUFER
                        AND Personalstamm.Name = (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)),
                        KUNDENADRESSE.KONTO
                ) t
            GROUP BY t.years, t.months;';
    }

    public static function selectRepSalesArticleGroups(\DateTime $start, \DateTime $end, array $accounts, array $reps) : string
    {
        return 'SELECT
                t.account, t.costcenter, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        FiBuchungsArchiv.Konto as account,
                        FiBuchungsArchiv.KST AS costcenter,
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv, KUNDENADRESSE, Personalstamm
                    WHERE 
                        FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND Personalstamm.Personalnummer = KUNDENADRESSE.VERKAEUFER
                        AND Personalstamm.Name = (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungsArchiv.Konto, FiBuchungsArchiv.KST
                UNION ALL
                    SELECT 
                        FiBuchungen.Konto as account,
                        FiBuchungen.KST AS costcenter,
                        SUM(-FiBuchungen.Betrag) AS sales
                    FROM FiBuchungen, KUNDENADRESSE, Personalstamm
                    WHERE 
                        FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND Personalstamm.Personalnummer = KUNDENADRESSE.VERKAEUFER
                        AND Personalstamm.Name = (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto, FiBuchungen.KST
                ) t
            GROUP BY t.account, t.costcenter;';
    }

    public static function selectBalanceAccounts(int $start, int $end, array $accounts) : string
    {
        return 'SELECT Konto, Geschaeftsjahr, 
            SollMonat01 - HabenMonat01 as M1, 
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 as M2,
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 + SollMonat03 - HabenMonat03 as M3,
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 + SollMonat03 - HabenMonat03 + SollMonat04 - HabenMonat04 as M4,
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 + SollMonat03 - HabenMonat03 + SollMonat04 - HabenMonat04 + SollMonat05 - HabenMonat05 as M5,
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 + SollMonat03 - HabenMonat03 + SollMonat04 - HabenMonat04 + SollMonat05 - HabenMonat05 + SollMonat06 - HabenMonat06 as M6,
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 + SollMonat03 - HabenMonat03 + SollMonat04 - HabenMonat04 + SollMonat05 - HabenMonat05 + SollMonat06 - HabenMonat06 + SollMonat07 - HabenMonat07 as M7,
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 + SollMonat03 - HabenMonat03 + SollMonat04 - HabenMonat04 + SollMonat05 - HabenMonat05 + SollMonat06 - HabenMonat06 + SollMonat07 - HabenMonat07 + SollMonat08 - HabenMonat08 as M8,
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 + SollMonat03 - HabenMonat03 + SollMonat04 - HabenMonat04 + SollMonat05 - HabenMonat05 + SollMonat06 - HabenMonat06 + SollMonat07 - HabenMonat07 + SollMonat08 - HabenMonat08 + SollMonat09 - HabenMonat09 as M9,
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 + SollMonat03 - HabenMonat03 + SollMonat04 - HabenMonat04 + SollMonat05 - HabenMonat05 + SollMonat06 - HabenMonat06 + SollMonat07 - HabenMonat07 + SollMonat08 - HabenMonat08 + SollMonat09 - HabenMonat09 + SollMonat10 - HabenMonat10 as M10,
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 + SollMonat03 - HabenMonat03 + SollMonat04 - HabenMonat04 + SollMonat05 - HabenMonat05 + SollMonat06 - HabenMonat06 + SollMonat07 - HabenMonat07 + SollMonat08 - HabenMonat08 + SollMonat09 - HabenMonat09 + SollMonat10 - HabenMonat10 + SollMonat11 - HabenMonat11 as M11,
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 + SollMonat03 - HabenMonat03 + SollMonat04 - HabenMonat04 + SollMonat05 - HabenMonat05 + SollMonat06 - HabenMonat06 + SollMonat07 - HabenMonat07 + SollMonat08 - HabenMonat08 + SollMonat09 - HabenMonat09 + SollMonat10 - HabenMonat10 + SollMonat11 - HabenMonat11 + SollMonat12 - HabenMonat12 as M12,
            SollMonat01 - HabenMonat01 as S1, 
            SollMonat02 - HabenMonat02 as S2,
            SollMonat03 - HabenMonat03 as S3,
            SollMonat04 - HabenMonat04 as S4,
            SollMonat05 - HabenMonat05 as S5,
            SollMonat06 - HabenMonat06 as S6,
            SollMonat07 - HabenMonat07 as S7,
            SollMonat08 - HabenMonat08 as S8,
            SollMonat09 - HabenMonat09 as S9,
            SollMonat10 - HabenMonat10 as S10,
            SollMonat11 - HabenMonat11 as S11,
            SollMonat12 - HabenMonat12 as S12
            FROM FiKontensalden 
            WHERE Konto IN (' . implode(',', $accounts) . ') 
            AND Geschaeftsjahr >= ' . $start . ' AND Geschaeftsjahr <= ' . $end;
    }

    public static function selectBalanceAccountsRange(int $start, int $end, array $accounts) : string
    {
        return 'SELECT Konto, Geschaeftsjahr, 
            SollMonat01 - HabenMonat01 as M1, 
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 as M2,
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 + SollMonat03 - HabenMonat03 as M3,
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 + SollMonat03 - HabenMonat03 + SollMonat04 - HabenMonat04 as M4,
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 + SollMonat03 - HabenMonat03 + SollMonat04 - HabenMonat04 + SollMonat05 - HabenMonat05 as M5,
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 + SollMonat03 - HabenMonat03 + SollMonat04 - HabenMonat04 + SollMonat05 - HabenMonat05 + SollMonat06 - HabenMonat06 as M6,
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 + SollMonat03 - HabenMonat03 + SollMonat04 - HabenMonat04 + SollMonat05 - HabenMonat05 + SollMonat06 - HabenMonat06 + SollMonat07 - HabenMonat07 as M7,
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 + SollMonat03 - HabenMonat03 + SollMonat04 - HabenMonat04 + SollMonat05 - HabenMonat05 + SollMonat06 - HabenMonat06 + SollMonat07 - HabenMonat07 + SollMonat08 - HabenMonat08 as M8,
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 + SollMonat03 - HabenMonat03 + SollMonat04 - HabenMonat04 + SollMonat05 - HabenMonat05 + SollMonat06 - HabenMonat06 + SollMonat07 - HabenMonat07 + SollMonat08 - HabenMonat08 + SollMonat09 - HabenMonat09 as M9,
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 + SollMonat03 - HabenMonat03 + SollMonat04 - HabenMonat04 + SollMonat05 - HabenMonat05 + SollMonat06 - HabenMonat06 + SollMonat07 - HabenMonat07 + SollMonat08 - HabenMonat08 + SollMonat09 - HabenMonat09 + SollMonat10 - HabenMonat10 as M10,
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 + SollMonat03 - HabenMonat03 + SollMonat04 - HabenMonat04 + SollMonat05 - HabenMonat05 + SollMonat06 - HabenMonat06 + SollMonat07 - HabenMonat07 + SollMonat08 - HabenMonat08 + SollMonat09 - HabenMonat09 + SollMonat10 - HabenMonat10 + SollMonat11 - HabenMonat11 as M11,
            SollMonat01 - HabenMonat01 + SollMonat02 - HabenMonat02 + SollMonat03 - HabenMonat03 + SollMonat04 - HabenMonat04 + SollMonat05 - HabenMonat05 + SollMonat06 - HabenMonat06 + SollMonat07 - HabenMonat07 + SollMonat08 - HabenMonat08 + SollMonat09 - HabenMonat09 + SollMonat10 - HabenMonat10 + SollMonat11 - HabenMonat11 + SollMonat12 - HabenMonat12 as M12,
            SollMonat01 - HabenMonat01 as S1, 
            SollMonat02 - HabenMonat02 as S2,
            SollMonat03 - HabenMonat03 as S3,
            SollMonat04 - HabenMonat04 as S4,
            SollMonat05 - HabenMonat05 as S5,
            SollMonat06 - HabenMonat06 as S6,
            SollMonat07 - HabenMonat07 as S7,
            SollMonat08 - HabenMonat08 as S8,
            SollMonat09 - HabenMonat09 as S9,
            SollMonat10 - HabenMonat10 as S10,
            SollMonat11 - HabenMonat11 as S11,
            SollMonat12 - HabenMonat12 as S12
            FROM FiKontensalden 
            WHERE Konto >= ' . ((int) $accounts[0]) . '
            AND Konto <= ' . ((int) $accounts[1]) . '
            AND Geschaeftsjahr >= ' . $start . ' AND Geschaeftsjahr <= ' . $end;
    }

    public static function selectAHKBeginning(\DateTime $start, \DateTime $end) : string
    {
            return 'SELECT Konto AS account, SUM(AKHK) AS ahk 
                FROM AnBuchungen 
                WHERE 
                    CONVERT(VARCHAR(30), AnBuchungen.BuchDatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                    AND CONVERT(VARCHAR(30), AnBuchungen.BuchDatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
                    AND BuchArt = 90 
                GROUP BY Konto';
    }

    public static function selectAHKAdditions(\DateTime $start, \DateTime $end) : string
    {
            return 'SELECT HRBILANZKTO AS account, SUM(HRAKHK) AS ahk
                FROM AnObjektLLAkten 
                WHERE Objekt IN (
                    SELECT ObjektNr 
                    FROM AnBuchungen 
                    WHERE 
                        CONVERT(VARCHAR(30), AnBuchungen.BuchDatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), AnBuchungen.BuchDatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
                        AND BuchArt = 20
                ) 
                GROUP BY HRBILANZKTO';
    }

    public static function selectAHKSubtractions(\DateTime $start, \DateTime $end) : string
    {
            return 'SELECT HRBILANZKTO AS account, SUM(HRAKHK) AS ahk
                FROM AnObjektLLAkten 
                WHERE Objekt IN (
                    SELECT ObjektNr 
                    FROM AnBuchungen 
                    WHERE 
                        CONVERT(VARCHAR(30), AnBuchungen.BuchDatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), AnBuchungen.BuchDatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
                        AND BuchArt = 30
                ) 
                GROUP BY HRBILANZKTO';
    }

    public static function selectCustomNewCustomerAnalysis(\DateTime $start, \DateTime $end, array $accounts, array $countries = null, array $costcenters = null, array $reps = null) : string
    {
        if(!isset($countries) && !isset($costcenters) && !isset($reps)) {
            return 'SELECT DISTINCT
                t.account, t.first
            FROM (
                    SELECT 
                        FiBuchungsArchiv.GegenKonto AS account,
                        MIN(CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS first
                    FROM FiBuchungsArchiv
                    WHERE 
                        FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.Betrag < 0
                    GROUP BY
                        FiBuchungsArchiv.GegenKonto
                UNION ALL
                    SELECT 
                        FiBuchungen.GegenKonto AS account,
                        MIN(CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS first
                    FROM FiBuchungen
                    WHERE 
                        FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.Betrag < 0
                    GROUP BY
                        FiBuchungen.GegenKonto
                ) t
            WHERE 
                    CONVERT(VARCHAR(30), t.first, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                    AND CONVERT(VARCHAR(30), t.first, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
            GROUP BY t.account, t.first;';
        } elseif(!isset($countries) && !isset($costcenters) && isset($reps)) {
            return 'SELECT DISTINCT
                t.account, t.first
            FROM (
                    SELECT 
                        FiBuchungsArchiv.GegenKonto AS account,
                        MIN(CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS first
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.Betrag < 0
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungsArchiv.GegenKonto
                UNION ALL
                    SELECT 
                        FiBuchungen.GegenKonto AS account,
                        MIN(CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS first
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.Betrag < 0
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungen.GegenKonto
                ) t
            WHERE 
                    CONVERT(VARCHAR(30), t.first, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                    AND CONVERT(VARCHAR(30), t.first, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
            GROUP BY t.account, t.first;';
        } elseif(!isset($countries) && isset($costcenters) && !isset($reps)) {
            return 'SELECT DISTINCT
                t.account, t.first
            FROM (
                    SELECT 
                        FiBuchungsArchiv.GegenKonto AS account,
                        MIN(CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS first
                    FROM FiBuchungsArchiv
                    WHERE 
                        FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.Betrag < 0
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
                    GROUP BY
                        FiBuchungsArchiv.GegenKonto
                UNION ALL
                    SELECT 
                        FiBuchungen.GegenKonto AS account,
                        MIN(CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS first
                    FROM FiBuchungen
                    WHERE 
                        FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.Betrag < 0
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                    GROUP BY
                        FiBuchungen.GegenKonto
                ) t
            WHERE 
                    CONVERT(VARCHAR(30), t.first, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                    AND CONVERT(VARCHAR(30), t.first, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
            GROUP BY t.account, t.first;';
        } elseif(isset($countries) && !isset($costcenters) && !isset($reps)) {
            return 'SELECT DISTINCT
                t.account, t.first
            FROM (
                    SELECT 
                        FiBuchungsArchiv.GegenKonto AS account,
                        MIN(CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS first
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.Betrag < 0
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungsArchiv.GegenKonto
                UNION ALL
                    SELECT 
                        FiBuchungen.GegenKonto AS account,
                        MIN(CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS first
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.Betrag < 0
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungen.GegenKonto
                ) t
            WHERE 
                    CONVERT(VARCHAR(30), t.first, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                    AND CONVERT(VARCHAR(30), t.first, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
            GROUP BY t.account, t.first;';
        } elseif(isset($countries) && !isset($costcenters) && isset($reps)) {
            return 'SELECT DISTINCT
                t.account, t.first
            FROM (
                    SELECT 
                        FiBuchungsArchiv.GegenKonto AS account,
                        MIN(CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS first
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.Betrag < 0
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungsArchiv.GegenKonto
                UNION ALL
                    SELECT 
                        FiBuchungen.GegenKonto AS account,
                        MIN(CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS first
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.Betrag < 0
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungen.GegenKonto
                ) t
            WHERE 
                    CONVERT(VARCHAR(30), t.first, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                    AND CONVERT(VARCHAR(30), t.first, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
            GROUP BY t.account, t.first;';
        } elseif(!isset($countries) && isset($costcenters) && isset($reps)) {
            return 'SELECT DISTINCT
                t.account, t.first
            FROM (
                    SELECT 
                        FiBuchungsArchiv.GegenKonto AS account,
                        MIN(CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS first
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.Betrag < 0
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungsArchiv.GegenKonto
                UNION ALL
                    SELECT 
                        FiBuchungen.GegenKonto AS account,
                        MIN(CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS first
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.Betrag < 0
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungen.GegenKonto
                ) t
            WHERE 
                    CONVERT(VARCHAR(30), t.first, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                    AND CONVERT(VARCHAR(30), t.first, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
            GROUP BY t.account, t.first;';
        } elseif(isset($countries) && isset($costcenters) && !isset($reps)) {
            return 'SELECT DISTINCT
                t.account, t.first
            FROM (
                    SELECT 
                        FiBuchungsArchiv.GegenKonto AS account,
                        MIN(CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS first
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.Betrag < 0
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungsArchiv.GegenKonto
                UNION ALL
                    SELECT 
                        FiBuchungen.GegenKonto AS account,
                        MIN(CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS first
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.Betrag < 0
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungen.GegenKonto
                ) t
            WHERE 
                    CONVERT(VARCHAR(30), t.first, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                    AND CONVERT(VARCHAR(30), t.first, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
            GROUP BY t.account, t.first;';
        } elseif(isset($countries) && isset($costcenters) && isset($reps)) {
            return 'SELECT DISTINCT
                t.account, t.first
            FROM (
                    SELECT 
                        FiBuchungsArchiv.GegenKonto AS account,
                        MIN(CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS first
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.Betrag < 0
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungsArchiv.GegenKonto
                UNION ALL
                    SELECT 
                        FiBuchungen.GegenKonto AS account,
                        MIN(CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS first
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.Betrag < 0
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungen.GegenKonto
                ) t
            WHERE 
                    CONVERT(VARCHAR(30), t.first, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                    AND CONVERT(VARCHAR(30), t.first, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
            GROUP BY t.account, t.first;';
        }

        return '';
    }

    public static function selectCustomLostCustomerAnalysis(\DateTime $start, \DateTime $end, array $accounts, array $countries = null, array $costcenters = null, array $reps = null) : string
    {
        if(!isset($countries) && !isset($costcenters) && !isset($reps)) {
            return 'SELECT DISTINCT
                t.account, t.last
            FROM (
                    SELECT 
                        FiBuchungsArchiv.GegenKonto AS account,
                        MAX(CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS last
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.Betrag < 0
                    GROUP BY
                        FiBuchungsArchiv.GegenKonto
                UNION ALL
                    SELECT 
                        FiBuchungen.GegenKonto AS account,
                        MAX(CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS last
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.Betrag < 0
                    GROUP BY
                        FiBuchungen.GegenKonto
                ) t
            WHERE 
                    CONVERT(VARCHAR(30), t.last, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                    AND CONVERT(VARCHAR(30), t.last, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
            GROUP BY t.account, t.last;';
        } elseif(!isset($countries) && !isset($costcenters) && isset($reps)) {
            return 'SELECT DISTINCT
                t.account, t.last
            FROM (
                    SELECT 
                        FiBuchungsArchiv.GegenKonto AS account,
                        MAX(CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS last
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.Betrag < 0
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungsArchiv.GegenKonto
                UNION ALL
                    SELECT 
                        FiBuchungen.GegenKonto AS account,
                        MAX(CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS last
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.Betrag < 0
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungen.GegenKonto
                ) t
            WHERE 
                    CONVERT(VARCHAR(30), t.last, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                    AND CONVERT(VARCHAR(30), t.last, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
            GROUP BY t.account, t.last;';
        } elseif(!isset($countries) && isset($costcenters) && !isset($reps)) {
            return 'SELECT DISTINCT
                t.account, t.last
            FROM (
                    SELECT 
                        FiBuchungsArchiv.GegenKonto AS account,
                        MAX(CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS last
                    FROM FiBuchungsArchiv
                    WHERE 
                        FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.Betrag < 0
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
                    GROUP BY
                        FiBuchungsArchiv.GegenKonto
                UNION ALL
                    SELECT 
                        FiBuchungen.GegenKonto AS account,
                        MAX(CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS last
                    FROM FiBuchungen
                    WHERE 
                        FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.Betrag < 0
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                    GROUP BY
                        FiBuchungen.GegenKonto
                ) t
            WHERE 
                    CONVERT(VARCHAR(30), t.last, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                    AND CONVERT(VARCHAR(30), t.last, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
            GROUP BY t.account, t.last;';
        } elseif(isset($countries) && !isset($costcenters) && !isset($reps)) {
            return 'SELECT DISTINCT
                t.account, t.last
            FROM (
                    SELECT 
                        FiBuchungsArchiv.GegenKonto AS account,
                        MAX(CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS last
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.Betrag < 0
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungsArchiv.GegenKonto
                UNION ALL
                    SELECT 
                        FiBuchungen.GegenKonto AS account,
                        MAX(CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS last
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.Betrag < 0
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungen.GegenKonto
                ) t
            WHERE 
                    CONVERT(VARCHAR(30), t.last, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                    AND CONVERT(VARCHAR(30), t.last, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
            GROUP BY t.account, t.last;';
        } elseif(isset($countries) && !isset($costcenters) && isset($reps)) {
            return 'SELECT DISTINCT
                t.account, t.last
            FROM (
                    SELECT 
                        FiBuchungsArchiv.GegenKonto AS account,
                        MAX(CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS last
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.Betrag < 0
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungsArchiv.GegenKonto
                UNION ALL
                    SELECT 
                        FiBuchungen.GegenKonto AS account,
                        MAX(CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS last
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.Betrag < 0
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungen.GegenKonto
                ) t
            WHERE 
                    CONVERT(VARCHAR(30), t.last, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                    AND CONVERT(VARCHAR(30), t.last, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
            GROUP BY t.account, t.last;';
        } elseif(!isset($countries) && isset($costcenters) && isset($reps)) {
            return 'SELECT DISTINCT
                t.account, t.last
            FROM (
                    SELECT 
                        FiBuchungsArchiv.GegenKonto AS account,
                        MAX(CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS last
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.Betrag < 0
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungsArchiv.GegenKonto
                UNION ALL
                    SELECT 
                        FiBuchungen.GegenKonto AS account,
                        MAX(CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS last
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.Betrag < 0
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungen.GegenKonto
                ) t
            WHERE 
                    CONVERT(VARCHAR(30), t.last, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                    AND CONVERT(VARCHAR(30), t.last, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
            GROUP BY t.account, t.last;';
        } elseif(isset($countries) && isset($costcenters) && !isset($reps)) {
            return 'SELECT DISTINCT
                t.account, t.last
            FROM (
                    SELECT 
                        FiBuchungsArchiv.GegenKonto AS account,
                        MAX(CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS last
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.Betrag < 0
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungsArchiv.GegenKonto
                UNION ALL
                    SELECT 
                        FiBuchungen.GegenKonto AS account,
                        MAX(CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS last
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.Betrag < 0
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungen.GegenKonto
                ) t
            WHERE 
                    CONVERT(VARCHAR(30), t.last, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                    AND CONVERT(VARCHAR(30), t.last, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
            GROUP BY t.account, t.last;';
        } elseif(isset($countries) && isset($costcenters) && isset($reps)) {
            return 'SELECT DISTINCT
                t.account, t.last
            FROM (
                    SELECT 
                        FiBuchungsArchiv.GegenKonto AS account,
                        MAX(CONVERT(VARCHAR(30), FiBuchungsArchiv.Buchungsdatum, 104)) AS last
                    FROM FiBuchungsArchiv, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungsArchiv.GegenKonto
                        AND FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.Betrag < 0
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungsArchiv.GegenKonto
                UNION ALL
                    SELECT 
                        FiBuchungen.GegenKonto AS account,
                        MAX(CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)) AS last
                    FROM FiBuchungen, KUNDENADRESSE
                    WHERE 
                        KUNDENADRESSE.KONTO = FiBuchungen.GegenKonto
                        AND FiBuchungen.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungen.Betrag < 0
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                    GROUP BY
                        FiBuchungen.GegenKonto
                ) t
            WHERE 
                    CONVERT(VARCHAR(30), t.last, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                    AND CONVERT(VARCHAR(30), t.last, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102) 
            GROUP BY t.account, t.last;';
        }

        return '';
    }

    public static function showCustomOverviewAnalysis(\DateTime $start, \DateTime $end, array $accounts, array $countries = null, array $costcenters = null, array $reps = null) : string
    {
        if(!isset($countries) && !isset($costcenters) && !isset($reps)) {
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
        } elseif(!isset($countries) && !isset($costcenters) && isset($reps)) {
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
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
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
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104))
                ) t
            GROUP BY t.account, t.years, t.months;';
        } elseif(!isset($countries) && isset($costcenters) && !isset($reps)) {
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
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
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
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104))
                ) t
            GROUP BY t.account, t.years, t.months;';
        } elseif(isset($countries) && !isset($costcenters) && !isset($reps)) {
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
        } elseif(isset($countries) && !isset($costcenters) && isset($reps)) {
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
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
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
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104))
                ) t
            GROUP BY t.account, t.years, t.months;';
        } elseif(!isset($countries) && isset($costcenters) && isset($reps)) {
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
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
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
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104))
                ) t
            GROUP BY t.account, t.years, t.months;';
        } elseif(isset($countries) && isset($costcenters) && !isset($reps)) {
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
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
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
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104))
                ) t
            GROUP BY t.account, t.years, t.months;';
        } elseif(isset($countries) && isset($costcenters) && isset($reps)) {
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
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
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
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto,
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104))
                ) t
            GROUP BY t.account, t.years, t.months;';
        }

        return '';
    }

    public static function showCustomCustomerAnalysis(\DateTime $start, \DateTime $end, array $accounts, array $countries = null, array $costcenters = null, array $reps = null) : string
    {
        if(!isset($countries) && !isset($costcenters) && !isset($reps)) {
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
        } elseif(!isset($countries) && !isset($costcenters) && isset($reps)) {
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
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
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
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE.NAME1
                ) t
            GROUP BY t.customer;';
        } elseif(!isset($countries) && isset($costcenters) && !isset($reps)) {
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
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
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
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE.NAME1
                ) t
            GROUP BY t.customer;';
        } elseif(isset($countries) && !isset($costcenters) && !isset($reps)) {
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
        } elseif(isset($countries) && !isset($costcenters) && isset($reps)) {
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
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
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
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE.NAME1
                ) t
            GROUP BY t.customer;';
        } elseif(!isset($countries) && isset($costcenters) && isset($reps)) {
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
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
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
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE.NAME1
                ) t
            GROUP BY t.customer;';
        } elseif(isset($countries) && isset($costcenters) && !isset($reps)) {
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
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
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
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE.NAME1
                ) t
            GROUP BY t.customer;';
        } elseif(isset($countries) && isset($costcenters) && isset($reps)) {
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
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
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
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        KUNDENADRESSE.NAME1
                ) t
            GROUP BY t.customer;';
        }

        return '';
    }

    public static function showCustomCustomerCountAnalysis(\DateTime $start, \DateTime $end, array $accounts, array $countries = null, array $costcenters = null, array $reps = null) : string
    {
        if(!isset($countries) && !isset($costcenters) && !isset($reps)) {
            return 'SELECT
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
        } elseif(!isset($countries) && !isset($costcenters) && isset($reps)) {
            return 'SELECT
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
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
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
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)),
                        KUNDENADRESSE.KONTO
                ) t
            GROUP BY t.years, t.months;';
        } elseif(!isset($countries) && isset($costcenters) && !isset($reps)) {
            return 'SELECT
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
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
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
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)),
                        KUNDENADRESSE.KONTO
                ) t
            GROUP BY t.years, t.months;';
        } elseif(isset($countries) && !isset($costcenters) && !isset($reps)) {
            return 'SELECT
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
        } elseif(isset($countries) && !isset($costcenters) && isset($reps)) {
            return 'SELECT
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
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
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
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)),
                        KUNDENADRESSE.KONTO
                ) t
            GROUP BY t.years, t.months;';
        } elseif(!isset($countries) && isset($costcenters) && isset($reps)) {
            return 'SELECT
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
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
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
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)),
                        KUNDENADRESSE.KONTO
                ) t
            GROUP BY t.years, t.months;';
        } elseif(isset($countries) && isset($costcenters) && !isset($reps)) {
            return 'SELECT
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
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
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
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)),
                        KUNDENADRESSE.KONTO
                ) t
            GROUP BY t.years, t.months;';
        } elseif(isset($countries) && isset($costcenters) && isset($reps)) {
            return 'SELECT
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
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
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
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        datepart(yyyy, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)), 
                        datepart(m, CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104)),
                        KUNDENADRESSE.KONTO
                ) t
            GROUP BY t.years, t.months;';
        }

        return '';
    }

    public static function showCustomGroupsAnalysis(\DateTime $start, \DateTime $end, array $accounts, array $countries = null, array $costcenters = null, array $reps = null) : string
    {
        if(!isset($countries) && !isset($costcenters) && !isset($reps)) {
            return 'SELECT
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
        } elseif(!isset($countries) && !isset($costcenters) && isset($reps)) {
            return 'SELECT
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
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
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
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto, FiBuchungen.KST
                ) t
            GROUP BY t.account, t.costcenter;';
        } elseif(!isset($countries) && isset($costcenters) && !isset($reps)) {
            return 'SELECT
                t.account, t.costcenter, SUM(t.sales) AS sales
            FROM (
                    SELECT 
                        FiBuchungsArchiv.Konto as account,
                        FiBuchungsArchiv.KST AS costcenter,
                        SUM(-FiBuchungsArchiv.Betrag) AS sales
                    FROM FiBuchungsArchiv
                    WHERE 
                        FiBuchungsArchiv.Konto IN (' . implode(',', $accounts) . ')
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
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
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto, FiBuchungen.KST
                ) t
            GROUP BY t.account, t.costcenter;';
        } elseif(isset($countries) && !isset($costcenters) && !isset($reps)) {
            return 'SELECT
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
        } elseif(isset($countries) && !isset($costcenters) && isset($reps)) {
            return 'SELECT
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
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
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
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto, FiBuchungen.KST
                ) t
            GROUP BY t.account, t.costcenter;';
        } elseif(!isset($countries) && isset($costcenters) && isset($reps)) {
            return 'SELECT
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
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
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
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto, FiBuchungen.KST
                ) t
            GROUP BY t.account, t.costcenter;';
        } elseif(isset($countries) && isset($costcenters) && !isset($reps)) {
            return 'SELECT
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
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
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
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto, FiBuchungen.KST
                ) t
            GROUP BY t.account, t.costcenter;';
        } elseif(isset($countries) && isset($costcenters) && isset($reps)) {
            return 'SELECT
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
                        AND FiBuchungsArchiv.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
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
                        AND FiBuchungen.KST IN (' . implode(',', $costcenters) . ')
                        AND KUNDENADRESSE.LAENDERKUERZEL IN (\'' . rtrim(implode(' \',\'', $countries), ',\'')  . ' \')
                        AND KUNDENADRESSE.VERKAEUFER IN (\'' . rtrim(implode(' \',\'', $reps), ',\'')  . ' \')
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                        AND CONVERT(VARCHAR(30), FiBuchungen.Buchungsdatum, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102)
                    GROUP BY
                        FiBuchungen.Konto, FiBuchungen.KST
                ) t
            GROUP BY t.account, t.costcenter;';
        }

        return '';
    }
}