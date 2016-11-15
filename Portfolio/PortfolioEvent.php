<?php
namespace Plugin\Portfolio;

use Eccube\Application;
use Eccube\Exception\CartException;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PortfolioEvent
{

    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }


    public function portfolio(FilterResponseEvent $event, $app)
    {

        $request = $event->getRequest();
        $response = $event->getResponse();

        $id = $request->get('id');

        $TargetWork = $this->app['eccube.plugin.repository.portfolio_data']->findOrder($id);
        $TargetWork[0]['name'];
        $addContent = $TargetWork[0]['name'];
        // ‘‚«Š·‚¦html‚Ì‰Šú‰»
        $html = $response->getContent();

        // ‘‚«Š·‚¦ˆ—‚±‚±‚©‚ç
        $crawler = new Crawler($html);
        $oldElement = $crawler->filter('.item_detail');
        $oldHtml = $oldElement->html();
        $newHtml = $addContent . $oldHtml;
        $html = $crawler->html();
        $html = str_replace($oldHtml, $newHtml, $html);
        // ‘‚«Š·‚¦ˆ—‚±‚±‚Ü‚Ä?

        $response->setContent($html);
        $event->setResponse($response);
    }


    public function afterLogin(FilterResponseEvent $event, $app)
    {
        $session = $event->getRequest()->getSession();
        if ($session->get('portfolio_redi') != null) {
            $response = $this->app->redirect($this->app->url('canvas_redirect'));
            $event->setResponse($response);
        }

    }

    public function afterOrder(FilterResponseEvent $event, $app)
    {
dump('test');
    }

}

