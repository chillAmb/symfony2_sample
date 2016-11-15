<?php

namespace Plugin\Stamp\ServiceProvider;

use Eccube\Application;
use Silex\Application as BaseApplication;
use Silex\ServiceProviderInterface;

class StampServiceProvider implements ServiceProviderInterface
{
    public function register(BaseApplication $app)
    {
        $cd = 'stamp';

	$app->match('/' .$app["config"]["admin_route"]. '/stamp', '\\Plugin\\Stamp\\Controller\StampController::index')
            ->bind("admin_{$cd}");

	$app->match('/' .$app["config"]["admin_route"]. '/stamp/edit/{id}', '\\Plugin\\Stamp\\Controller\StampController::edit')
            ->value('id', null)->assert('id', '\d+|')
	    ->bind("admin_{$cd}_edit");

	$app->match('/' .$app["config"]["admin_route"]. '/stamp/regist', '\\Plugin\\Stamp\\Controller\StampController::regist')
	    ->bind("admin_{$cd}_regist");

        $app->post('/' .$app["config"]["admin_route"]. '/stamp/edit/addimg', '\\Plugin\\Stamp\\Controller\StampController::addImage')
            ->bind('admin_stamp_image_add');

        $app->post('/' .$app["config"]["admin_route"]. '/stamp/edit/delimg', '\\Plugin\\Stamp\\Controller\StampController::delImage')
            ->bind('admin_stamp_image_del');

	$app->match('/' .$app["config"]["admin_route"]. '/stamp/commit', '\\Plugin\\Stamp\\Controller\StampController::commit')
	    ->bind("admin_{$cd}_commit");

	$app->match('/' .$app["config"]["admin_route"]. '/stamp/rank', '\\Plugin\\Stamp\\Controller\StampController::rank')
	    ->bind("admin_{$cd}_rank");

        // -- Repositoy --
        $app['eccube.plugin.repository.stamp_data'] = function () use ($app) {
            return $app['orm.em']->getRepository('\Plugin\Stamp\Entity\StampData');
        };

        // FormType
        $app['form.types'] = $app->share($app->extend('form.types', function ($types) use ($app) {
            $types[] = new \Plugin\Stamp\Form\Type\StampEditType($app);
            return $types;
        }));

        $app['form.types'] = $app->share($app->extend('form.types', function ($types) use ($app) {
            $types[] = new \Plugin\Stamp\Form\Type\SearchStampType($app);
            return $types;
        }));

        // メニュー登録
/*
        $app['config'] = $app->share($app->extend('config', function ($config) {
            $addNavi = array(
                'id' => 'stamp',
                'name' => "スタンプ管理",
                'has_child' => true,
                'icon' => 'cb-comment',
                'child' => array(
                    array(
                        'id' => "stamp",
                        'name' => "スタンプ設定",
                        'url' => "admin_stamp",
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