<?php

namespace Plugin\Portfolio\ServiceProvider;

use Eccube\Application;
use Silex\Application as BaseApplication;
use Silex\ServiceProviderInterface;

class PortfolioServiceProvider implements ServiceProviderInterface
{
    public function register(BaseApplication $app)
    {
//        $cd = 'portfolio';

	$app->match('/' .$app["config"]["admin_route"]. '/portfolio', '\\Plugin\\Portfolio\\Controller\PortfolioController::index')
            ->bind("admin_portfolio");

	$app->match('/' .$app["config"]["admin_route"]. '/portfolio/edit/{id}', '\\Plugin\\Portfolio\\Controller\PortfolioController::edit')
            ->value('id', null)->assert('id', '\d+|')
	    ->bind("admin_portfolio_edit");

	$app->match('/' .$app["config"]["admin_route"]. '/portfolio/regist', '\\Plugin\\Portfolio\\Controller\PortfolioController::regist2')
	    ->bind("admin_portfolio_regist");

        $app->post('/' .$app["config"]["admin_route"]. '/portfolio/edit/addimg', '\\Plugin\\Portfolio\\Controller\PortfolioController::addImage')
            ->bind('admin_portfolio_addimg');

        $app->post('/' .$app["config"]["admin_route"]. '/portfolio/edit/addpimg', '\\Plugin\\Portfolio\\Controller\PortfolioImageController::addImage')
            ->bind('admin_portfolio_addpimg');

        $app->post('/' .$app["config"]["admin_route"]. '/portfolio/edit/delpimg', '\\Plugin\\Portfolio\\Controller\PortfolioImageController::delImage')
            ->bind('admin_portfolio_delpimg');

	$app->match('/' .$app["config"]["admin_route"]. '/portfolio/commit', '\\Plugin\\Portfolio\\Controller\PortfolioController::commit')
	    ->bind("admin_portfolio_commit");

	$app->match('/' .$app["config"]["admin_route"]. '/portfolio/delete/{id}', '\\Plugin\\Portfolio\\Controller\PortfolioController::delete')
	    ->bind("admin_portfolio_delete");

	$app->match('/' .$app["config"]["admin_route"]. '/portfolio/rank', '\\Plugin\\Portfolio\\Controller\PortfolioController::rank')
	    ->bind("admin_portfolio_rank");

	$app->match('/portfolio/regist', '\\Plugin\\Portfolio\\Controller\PortfolioController::regist')
	    ->bind("portfolio_regist");

	$app->match('/portfolio/publish/{id}', '\\Plugin\\Portfolio\\Controller\PortfolioController::publish')
	    ->bind("portfolio_publish");

	$app->match('/canvas/post/{id}', '\\Plugin\\Portfolio\\Controller\PortfolioController::canvas')
	    ->bind("canvas_post")->assert('id', '\d+');

	$app->match('/canvas/redirect', '\\Plugin\\Portfolio\\Controller\PortfolioController::redirect')
	    ->bind("canvas_redirect");

        // -- Repositoy --
        $app['eccube.plugin.repository.portfolio_data'] = function () use ($app) {
            return $app['orm.em']->getRepository('\Plugin\Portfolio\Entity\PortfolioData');
        };

        $app['eccube.plugin.repository.portfolio_imagedata'] = function () use ($app) {
            return $app['orm.em']->getRepository('\Plugin\Portfolio\Entity\PortfolioImageData');
        };

        // FormType
        $app['form.types'] = $app->share($app->extend('form.types', function ($types) use ($app) {
            $types[] = new \Plugin\Portfolio\Form\Type\PortfolioEditType($app);
            return $types;
        }));

        $app['form.types'] = $app->share($app->extend('form.types', function ($types) use ($app) {
            $types[] = new \Plugin\Portfolio\Form\Type\SearchPortfolioType($app);
            return $types;
        }));

        // メニュー登録
/*
        $app['config'] = $app->share($app->extend('config', function ($config) {
            $addNavi = array(
                'id' => 'Portfolio',
                'name' => "スタンプ管理",
                'has_child' => true,
                'icon' => 'cb-comment',
                'child' => array(
                    array(
                        'id' => "Portfolio",
                        'name' => "スタンプ設定",
                        'url' => "admin_Portfolio",
                    ),
                ),
            );
            $nav = $config['nav'];
            foreach ($nav as $key => $val) {
                if ("setting" == $val['id']) {
                    array_splice($nav, $key, 0, array($addNavi));
                    break;
                }
            }
            $config['nav'] = $nav;
            return $config;
        }));
*/
    }

    public function boot(BaseApplication $app)
    {
    }
}