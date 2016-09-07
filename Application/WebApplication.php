<?php
namespace QuickDashboard\Application;

use phpOMS\Account\AccountManager;
use phpOMS\ApplicationAbstract;
use phpOMS\Asset\AssetType;
use phpOMS\DataStorage\Cache\Pool as CachePool;
use phpOMS\DataStorage\Database\DataMapperAbstract;
use phpOMS\DataStorage\Database\Pool;
use phpOMS\DataStorage\Session\HttpSession;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Event\EventManager;
use phpOMS\Localization\Localization;
use phpOMS\Message\Http\Request;
use phpOMS\Message\Http\Response;
use phpOMS\Model\Html\Head;
use phpOMS\Module\ModuleManager;
use phpOMS\Router\Router;
use phpOMS\Uri\Http;
use phpOMS\Autoloader;
use Web\Views\Page\GenericView;

class WebApplication extends ApplicationAbstract
{
    public function __construct(array $config)
    {
        set_exception_handler(['\phpOMS\UnhandledHandler', 'exceptionHandler']);
        set_error_handler(['\phpOMS\UnhandledHandler', 'errorHandler']);
        register_shutdown_function(['\phpOMS\UnhandledHandler', 'shutdownHandler']);
        mb_internal_encoding('UTF-8');

        $uri          = new Http(Http::getCurrent());
        $uri->setRootPath($config['page']['root']);

        $request  = new Request(new Localization(), $uri);
        $response = new Response(new Localization());

        $response->getHeader()->set('x-xss-protection', '1; mode=block');
        $response->getHeader()->set('x-content-type-options', 'nosniff');
        $response->getHeader()->set('x-frame-options', 'SAMEORIGIN');

        if($config['page']['https']) {
            $response->getHeader()->set('strict-transport-security', 'max-age=31536000');
        }

        $response->getL11n()->setLanguage('en');
        $request->getL11n()->setLanguage('en');
        $request->init();

        $this->dbPool = new Pool();
        $this->dbPool->create('sd', $config['db']['SD']);
        $this->dbPool->create('gdf', $config['db']['GDF']);

        $this->router = new Router();
        $this->router->importFromFile(__DIR__ . '/Routes.php');

        $this->dispatcher = new Dispatcher($this);
        $route = $this->router->route($request);
        $dispatched = $this->dispatcher->dispatch($this->router->route($request), $request, $response);

        $head    = new Head();
        $baseUri = $request->getUri()->getBase();

        $head->addAsset(AssetType::JS, $baseUri . 'Model/Message/DomAction.js');

        $pageView = new GenericView($this, $request, $response);
        $pageView->setData('head', $head);
        $pageView->setData('dispatch', $dispatched);
        $pageView->setTemplate('/QuickDashboard/Application/Templates/index');

        $response->set('Content', $pageView);
        $response->getHeader()->push();

        echo $response->getBody();
    }
}
