<div class="floater">
    <nav>
        <ul>
            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}?u={?u}'); ?>">Overview</a>
            <li>Sales
                <ul>
                    <li>Overview
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/history?{?}'); ?>">Month</a>
                            <li><a href="">Year</a>
                        </ul>
                    <li>List
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/list?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/list?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li>Location
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/location?{?}'); ?>">Month</a>
                            <li><a href="">Year</a>
                        </ul>
                    <li>Articles
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/articles?{?}'); ?>">Month</a>
                            <li><a href="">Year</a>
                        </ul>
                    <li>Customers
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/customers?{?}'); ?>">Month</a>
                            <li><a href="">Year</a>
                        </ul>
                    <li>Sales Reps
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/reps?{?}'); ?>">Month</a>
                            <li><a href="">Year</a>
                        </ul>
                </ul>
            <li>Costs
                <ul>
                    <li>Overview
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}costs?{?}'); ?>">Accumulated</a>
                            <li><a href="">Month</a>
                        </ul>
                    <li>Department
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/reps?{?}'); ?>">Marketing</a>
                            <li><a href="">Production</a>
                            <li><a href="">R&D</a>
                            <li><a href="">Sales</a>
                            <li><a href="">Purchase</a>
                            <li><a href="">Finance</a>
                            <li><a href="">IT</a>
                            <li><a href="">Service</a>
                            <li><a href="">Warehouse</a>
                            <li><a href="">Support</a>
                        </ul>
                </ul>
            <li>Reporting
                <ul>
                    <li><a href="">KPI</a>
                    <li><a href="">Sales & EBIT</a>
                </ul>
            <li>Analysis
                <ul>
                    <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/reps?{?}'); ?>">Sales Rep</a>
                    <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/article?{?}'); ?>">Article</a>
                    <li><a href="">Customer</a>
                    <li><a href="">Account</a>
                    <li><a href="">Cost Center</a>
                    <li><a href="">Cost Object</a>
                </ul>
            <li><a href="">Risk Management</a>
    </nav>
</div>