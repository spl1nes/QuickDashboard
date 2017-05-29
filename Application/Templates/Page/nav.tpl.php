<div class="floater">
    <nav>
        <ul>
            <li><i class="fa fa-home"></i> <a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/?u={?u}'); ?>">Overview</a>
            <li><i class="fa fa-line-chart"></i> Sales
                <ul>
                    <li>List
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/sales/list?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/sales/list?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li>Location
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/sales/location?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/sales/location?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li>Countries
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/sales/countries?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/sales/countries?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li>Segmentation
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/sales/segmentation?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/sales/segmentation?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li>Customers
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/sales/customers?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/sales/customers?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li>Sales Reps
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/sales/reps?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/sales/reps?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <!--<li>Products
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/sales/products?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/sales/products?{?}&i=year'); ?>">Year</a>
                        </ul>-->
                </ul>
            <li><i class="fa fa-bar-chart"></i> Reporting
                <ul>
                    <li>P&L
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/reporting/pl?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/reporting/pl?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li>Gross Profit
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/reporting/profit?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/reporting/profit?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/reporting/opex?{?}'); ?>">OPEX</a>
                    <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/reporting/ebit?{?}'); ?>">EBIT</a>
                </ul>
            <li><i class="fa fa-money"></i> MANI
                <ul>
                    <li>Balance
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/mani/package/balance?{?}'); ?>">Balance</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/mani/package/assets?{?}'); ?>">Assets</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/mani/package/cash?{?}'); ?>">Cash</a>
                        </ul>
                    <li>P&L
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/mani/package/pl/1?{?}'); ?>">P&L 1</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/mani/package/pl/2?{?}'); ?>">P&L 2</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/mani/package/pl/3?{?}'); ?>">P&L 3</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/mani/package/production?{?}'); ?>">Production</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/mani/package/rnd?{?}'); ?>">R&D</a>
                        </ul>
                    <li>Interco
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/mani/package/interco/balance?{?}'); ?>">Balance</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/mani/package/interco/pl?{?}'); ?>">P&L</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/mani/package/interco/production?{?}'); ?>">Production</a>
                        </ul>
                    <li>Customer/Vendor
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/mani/package/cv/customer?{?}'); ?>">Customer</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/mani/package/cv/vendor?{?}'); ?>">Vendor</a>
                        </ul>
                </ul>
                    <li><i class="fa fa-search"></i> KPI
                    <ul>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/kpi/finance?{?}'); ?>">Finance</a>
                        <!--
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/kpi/sales?{?}'); ?>">Sales</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/kpi/sales?{?}'); ?>">R&D</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/kpi/marketing?{?}'); ?>">Marketing</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/kpi/marketing?{?}'); ?>">Production</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/kpi/marketing?{?}'); ?>">Service</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/kpi/quality?{?}'); ?>">Quality</a>-->
                    </ul>
                <li><i class="fa fa-search"></i> Analysis
                    <ul>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/analysis/sales?{?}'); ?>">Sales Manual</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/analysis/location?{?}'); ?>">Location</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/analysis/segmentation?{?}'); ?>">Segmentation</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/analysis/reps?{?}'); ?>">Sales Reps</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/analysis/customer?{?}'); ?>">Customer</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/analysis/opex?{?}'); ?>">OPEX</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/analysis/account?{?}'); ?>">Account</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/analysis/department?{?}'); ?>">Department</a>
                        <!--<li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/analysis/product?{?}'); ?>">Product</a>-->
                    </ul>
                <!--<li>Controlling
                    <ul>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}?{?}'); ?>">Invoice Margin</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}?{?}'); ?>">Article Margin</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}?{?}'); ?>">Bonus</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}?{?}'); ?>">Accounts Receivable</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}?{?}'); ?>">Scrapping</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}?{?}'); ?>">Inventory Turnover</a>
                    </ul>-->
    </nav>
</div>