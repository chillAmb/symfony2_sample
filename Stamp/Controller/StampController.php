<?php

namespace Plugin\Stamp\Controller;

use Eccube\Application;
use Eccube\Common\Constant;
use Silex\Application as BaseApplication;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class StampController
{

    public function index(Application $app, Request $request)
    {

/*if($_SERVER['REMOTE_ADDR'] != '218.231.171.229') {
    dump('Debug中');exit;
}*/

        $options = array('1' => 'タイプ1', '2' => 'タイプ2', '3' => 'タイプ3');
        $searchForm = $app['form.factory']
            ->createBuilder('admin_search_stamp', $options)
            ->getForm();

        $searchForm->handleRequest($request);
        $searchData = array();
        if ($searchForm->isValid()) {
            $searchData = $searchForm->getData();
        }
        $pagination = array();

        // 検索ボタンクリック時の処理
        if ('POST' === $request->getMethod()) {
            $qb = $app['eccube.plugin.repository.stamp_data']
                ->getQueryBuilderBySearchData($searchData);
            $pagination = $app['paginator']()->paginate(
                $qb,
                empty($searchData['pageno']) ? 1 : $searchData['pageno'],
                empty($searchData['pagemax']) ? 10 : $searchData['pagemax']->getId()
            );
        }

        return $app->render('Stamp/View/admin/index.twig', array(
            'searchForm' => $searchForm->createView(),
            'pagination' => $pagination,
            'searchData' => $searchData,
            //'contents' => $repository,
            //'cont' => count($repository)
        ));
    }


    public function edit(Application $app, Request $request, $id)
    {


/*if($_SERVER['REMOTE_ADDR'] != '218.231.171.229') {
    dump('Debug中');exit;
}*/

        // id の存在確認
        if(is_null($id) || strlen($id) == 0) {
            return $app->redirect($app->url('admin_stamp'));
        }

        // 選択したスタンプを検索
        $PageLayout = $app['eccube.plugin.repository.stamp_data']->find($id);

        if(is_null($PageLayout)) {
            return $app->redirect($app->url('admin_stamp'));
        }
        $PageLayout->choice = array('1' => 'タイプ1', '2' => 'タイプ2', '3' => 'タイプ3');

        // formの作成
        $form = $app['form.factory']
            ->createBuilder('admin_stamp_edit', $PageLayout)
            ->getForm();

        $typeform = array();
        $cont = 0;
        foreach ($PageLayout->choice as $key => $value) {
            if ($PageLayout['type'] == $value) {
                break;
            }
            $cont++;
        }
        return $app->render('Stamp/View/admin/edit.twig', array(
                'id' => $id,
                'type' => $PageLayout['type'],
                'typenum' => $cont,
                'img' => $PageLayout['img'],
                'create_date' => $PageLayout['create_date'],
                'update_date' => $PageLayout['create_date'],
                'form' => $form->createView()
        ));

    }


    public function addImage(Application $app, Request $request)
    {

        $image = $request->files->get('admin_stamp_image');
        $mimeType = $image->getMimeType();
        if (0 !== strpos($mimeType, 'image')) {
            throw new UnsupportedMediaTypeHttpException();
        }
        $extension = $image->getClientOriginalExtension();
        $filename = date('mdH') . uniqid('_') . '.' . $extension;

        $image->move($app["config"]["admin_route"]. '/img/', $filename);
        return $app->json(array('files' => $app["config"]["admin_route"]. '/img/' .$filename), 200);
    }

/*
    public function delImage(Application $app, Request $request)
    {
        $image = $request->request->get('admin_stamp_edit');
        preg_match('{^admin/(.*?)/(.*?)$}', $image, $match);
        $dir = $match[1];
        $filename = $match[2];
//var_dump($filename);exit;
    }
*/

    public function commit(Application $app, Request $request)
    {

        $currentId = $app['eccube.plugin.repository.stamp_data']->findCurrentId();
        $currentId = $currentId[0]['id'] + 1;

        // Formを取得
        $builder = $app['form.factory']->createBuilder('admin_stamp_edit');
        $form = $builder->getForm();
        $form->handleRequest($request);
        $data = $form->getData();

        // 画像処理
        if($data['img']){
            $image = $data['img'];
            preg_match("{(.+)/(.+?)$}", $image, $match);
            $filename = $match[2];
            $typeDir = array('cate1', 'cate2', 'cate3');

            // 公開状態チェック
            if($data['publish'] != 1) {
                rename($image, $app["config"]["admin_route"]. '/stamps/' .$typeDir[($data['type'] - 1)]. '/' .$filename);
                $image = $app["config"]["admin_route"]. '/stamps/' .$typeDir[($data['type'] - 1)]. '/' .$filename;
            } else {
                rename($image, $app["config"]["admin_route"]. '/stamps/nopublish/' .$filename);
                $image = $app["config"]["admin_route"]. '/stamps/nopublish/' .$filename;
            }
        } else {
            $image = null;
        }

        if ('POST' === $request->getMethod()) {

            // 登録処理
            if(is_null($data['id'])) {
                $typearray = array('タイプ1', 'タイプ2', 'タイプ3');
                $stamp = new \Plugin\Stamp\Entity\StampData;
                $stamp->setId($currentId);
                $stamp->setName($data['name']);
                $stamp->setType($data['type']);
                $stamp->setTypeform('null');
                $stamp->setPublish($data['publish']);
                $stamp->setRank(0);
                $stamp->setImg($image);
                $status = $app['eccube.plugin.repository.stamp_data']->create($stamp);
                if (!$status) {
                    $app->addError('admin.stamp.edit.save.failure', 'admin');
                    return $app->redirect($app->url('admin_stamp'));
                }
            // 更新処理
            } else {

                // id の存在確認
                $id = $data['id'];
                if(!$id) {
                    $app->addError('admin.stamp.edit.data.illegalaccess', 'admin');
                    return $app->redirect($app->url('admin_stamp'));
                }

                // 選択したスタンプを検索
                $stamp = $app['eccube.plugin.repository.stamp_data']->find($id);
                $currentImg = $stamp['img'];
                $typearray = array('タイプ1', 'タイプ2', 'タイプ3');

                // データが存在しない場合は一覧へリダイレクト
                if(is_null($stamp)) {
                    $app->addError('admin.stamp.edit.data.notfound', 'admin');
                    return $app->redirect($app->url('admin_stamp'));
                }

                $stamp->setId($data['id']);
                $stamp->setName($data['name']);
                $stamp->setType($data['type']);
                $stamp->setTypeform('null');
                $stamp->setPublish($data['publish']);
                $stamp->setImg($image);
                $status = $app['eccube.plugin.repository.stamp_data']->update($stamp);
                if (!$status) {
                    $app->addError('admin.stamp.edit.save.failure', 'admin');
                    return $app->redirect($app->url('admin_stamp'));
                }
                unlink($currentImg);
                if($data['img']) {
                    unlink($data['img']);
                }
            }

            // 成功時のメッセージを登録する
            $app->addSuccess('スタンプ登録完了', 'admin');
        }
        return $app->redirect($app->url('admin_stamp'));
    }


    /*
     * スタンプ登録画面を表示する
     */
    public function regist(Application $app, Request $request)
    {

        $PageLayout = new \Plugin\Stamp\Entity\StampData();
        $PageLayout->choice = array('1' => 'タイプ1', '2' => 'タイプ2', '3' => 'タイプ3');

        // formの作成
        $form = $app['form.factory']
            ->createBuilder('admin_stamp_edit', $PageLayout)
            ->getForm();
        $typeform = array();
        $cont = 0;
        foreach ($PageLayout->choice as $key => $value) {
            if ($PageLayout['type'] == $value) {
                break;
            }
            $cont++;
        }
        return $app->render('Stamp/View/admin/edit.twig', array(
                'id' => null,
                'type' => 0,
                'typenum' => $cont,
                'img' => null,
                'form' => $form->createView()
        ));
    }


    public function rank(Application $app, Request $request)
    {

        $id = $request->get('thisid');
        $target = $request->get('targetid');

        $thisStamp = $app['eccube.plugin.repository.stamp_data']->find($id);
        $targetStamp = $app['eccube.plugin.repository.stamp_data']->find($target);
        $thisRank = $thisStamp['rank'];
        $targetRank = $targetStamp['rank'];

        $thisStamp->setId($id);
        $thisStamp->setRank($targetRank);

        $targetStamp->setId($target);
        $targetStamp->setRank($thisRank);

        $status = $app['eccube.plugin.repository.stamp_data']->update($thisStamp);
        $status = $app['eccube.plugin.repository.stamp_data']->update($targetStamp);

        if (!$status) {
            $app->addError('admin.stamp.edit.save.failure', 'admin');
            return "失敗しました。";
        }
        return $id;

/*

        $qb = $app['eccube.plugin.repository.stamp_data']
            ->getQueryBuilderBySearchData($searchData);
        $pagination = $app['paginator']()->paginate(
            $qb,
            empty($searchData['pageno']) ? 1 : $searchData['pageno'],
            empty($searchData['pagemax']) ? 10 : $searchData['pagemax']->getId()
        );
        return $app->render('Stamp/View/admin/index.twig', array(
            'searchForm' => $searchForm->createView(),
            'pagination' => $pagination,
            'searchData' => $searchData,
        ));
*/
    }

    public function test(Application $app)
    {
        return 'Hello, Plugin World!!';
    }

}