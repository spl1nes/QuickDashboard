<?php
namespace QuickDashboard\Application;

use phpOMS\ApplicationAbstract;
use phpOMS\Asset\AssetType;
use phpOMS\DataStorage\Database\DatabaseStatus;
use phpOMS\DataStorage\Database\Pool;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Localization\Localization;
use phpOMS\Message\Http\Request;
use phpOMS\Message\Http\RequestStatus;
use phpOMS\Message\Http\Response;
use phpOMS\Model\Html\Head;
use phpOMS\Router\Router;
use phpOMS\Uri\Http;
use phpOMS\Views\View;

class WebApplication extends ApplicationAbstract
{
    public $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->run();
    }

    private function run()
    {
        set_exception_handler(['\phpOMS\UnhandledHandler', 'exceptionHandler']);
        set_error_handler(['\phpOMS\UnhandledHandler', 'errorHandler']);
        register_shutdown_function(['\phpOMS\UnhandledHandler', 'shutdownHandler']);
        mb_internal_encoding('UTF-8');

        $uri = new Http(Http::getCurrent());
        $uri->setRootPath($this->config['page']['root']);

        $request  = new Request(new Localization(), $uri);
        $response = new Response(new Localization());

        $response->getHeader()->set('x-xss-protection', '1; mode=block');
        $response->getHeader()->set('x-content-type-options', 'nosniff');
        $response->getHeader()->set('x-frame-options', 'SAMEORIGIN');
        $response->getHeader()->set('content-security-policy', 'script-src \'self\' \'unsafe-inline\' https://cdnjs.cloudflare.com; frame-src \'self\'', true);

        if ($this->config['page']['https']) {
            $response->getHeader()->set('strict-transport-security', 'max-age=31536000');
        }

        $response->getL11n()->setLanguage('en');
        $request->getL11n()->setLanguage('en');
        $request->init();

        $this->dbPool = new Pool();
        $this->dbPool->create('sd', $this->config['db']['SD']);
        $this->dbPool->create('gdf', $this->config['db']['GDF']);

        if ($this->dbPool->get('sd')->getStatus() !== DatabaseStatus::OK || $this->dbPool->get('gdf')->getStatus() !== DatabaseStatus::OK) {
            $dispatched   = [];
            $dispatched[] = new View($this, $request, $response);
            $dispatched[0]->setTemplate('/QuickDashboard/Application/Templates/Page/error');
            $response->setStatusCode(RequestStatus::R_503);
        } else {
            $this->router = new Router();
            $this->router->importFromFile(__DIR__ . '/Routes.php');

            $this->dispatcher = new Dispatcher($this);
            $dispatched       = $this->dispatcher->dispatch($this->router->route($request), $request, $response);
        }

        if (empty($dispatched)) {
            $dispatched[] = new View($this, $request, $response);
            $dispatched[0]->setTemplate('/QuickDashboard/Application/Templates/Page/error');
            $response->setStatusCode(RequestStatus::R_404);
        }

        $pageView = new View($this, $request, $response);
        $head     = new Head();
        $baseUri  = $request->getUri()->getBase();

        $head->addAsset(AssetType::JS, $baseUri . 'Model/Message/DomAction.js');
        $pageView->setData('head', $head);
        $pageView->setData('dispatch', $dispatched);
        $pageView->setTemplate('/QuickDashboard/Application/Templates/index');

        $response->set('Content', $pageView);
        $response->getHeader()->push();

        echo $response->getBody();
    }
}
