<div class="floater">
    <nav>
        <ul>
            <li><i class="fa fa-home"></i> <a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}?u={?u}'); ?>">Overview</a>
            <li><i class="fa fa-line-chart"></i> Sales
                <ul>
                    <li>List
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/list?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/list?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li>Location
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/location?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/location?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li>Segmentation
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/segmentation?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/segmentation?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li>Customers
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/customers?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/customers?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li>Sales Reps
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/reps?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/reps?{?}&i=year'); ?>">Year</a>
                        </ul>
                </ul>
            <li><i class="fa fa-bar-chart"></i> Reporting
                <ul>
                    <li>P&L
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}reporting/pl?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}reporting/pl?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li>Gross Profit
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}reporting/profit?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}reporting/profit?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}reporting/opex?{?}'); ?>">OPEX</a>
                    <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}reporting/ebit?{?}'); ?>">EBIT</a>
                </ul>
                <!--<li><i class="fa fa-search"></i> KPI
                    <ul>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}kpi/finance?{?}'); ?>">Finance</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}kpi/marketing?{?}'); ?>">Marketing</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}kpi/personnel?{?}'); ?>">Personnel</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}kpi/quality?{?}'); ?>">Quality</a>
                    </ul>-->
                <li><i class="fa fa-search"></i> Analysis
                    <ul>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/location?{?}'); ?>">Location</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/segmentation?{?}'); ?>">Segmentation</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/reps?{?}'); ?>">Sales Reps</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/customer?{?}'); ?>">Customer</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/opex?{?}'); ?>">OPEX</a>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/department?{?}'); ?>">Department</a>
                    </ul>
                <li><li><li>
    </nav>
</div>