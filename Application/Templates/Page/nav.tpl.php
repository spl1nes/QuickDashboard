<div class="floater">
    <nav>
        <ul>
            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}?u={?u}'); ?>">Overview</a>
            <li>Sales
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
            <li>Reporting
                <ul>
                    <li><a>P&L</a>
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}reporting/pl?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}reporting/pl?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li><a>Gross Profit</a>
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}reporting/profit?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}reporting/profit?{?}&i=year'); ?>">Year</a>
                        </ul>
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