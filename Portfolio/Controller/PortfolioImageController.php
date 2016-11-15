<?php

namespace Plugin\Portfolio\Controller;

use Eccube\Application;
use Eccube\Common\Constant;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Silex\Application as BaseApplication;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Doctrine\ORM\EntityRepository;

class PortfolioImageController
{

    /** Rank */
    public function rank(Application $app, Request $request)
    {
        $id = $request->get('thisid');
        $target = $request->get('targetid');

        $thisPortfolio = $app['eccube.plugin.repository.portfolio_data']->find($id);
        $targetPortfolio = $app['eccube.plugin.repository.portfolio_data']->find($target);
        $thisRank = $thisPortfolio['rank'];
        $targetRank = $targetPortfolio['rank'];

        $thisPortfolio->setId($id);
        $thisPortfolio->setRank($targetRank);
        $targetPortfolio->setId($target);
        $targetPortfolio->setRank($thisRank);

        $status = $app['eccube.plugin.repository.portfolio_data']->update($thisPortfolio);
        $status = $app['eccube.plugin.repository.portfolio_data']->update($targetPortfolio);

        if (!$status) {
            $app->addError('失敗しました', 'admin');
            return "失敗しました。";
        }
        return $id;
    }


    /** 画像追加処理 */
    public function addImage(Application $app, Request $request)
    {
//unlink('upload/portfolio_image/4/1110065819_58239bfb7df9a.jpg');exit;
/*
        if (is_dir('upload/portfolio_image/' .$request->get('id'). '')) {
            $files = array('upload/portfolio_image/' .$request->get('id'). '/*.png', 'upload/portfolio_image/' .$request->get('id'). '/*.jpeg', 'upload/portfolio_image/' .$request->get('id'). '/*.png', 'upload/portfolio_image/' .$request->get('id'). '/*.jpg', 'upload/portfolio_image/' .$request->get('id'). '/*.gif');
            foreach ($files as $file) {
                foreach (glob($file) as $val) {
                    unlink($val);
                }
            }
        }
exit;
*/
        $currentId = $app['eccube.plugin.repository.portfolio_imagedata']->findCurrentId();
        $currentId = $currentId[0]['image_id'] + 1;

        $image = $request->files->get('admin_portfolio_image2');
        $mimeType = $image->getMimeType();
        if (0 !== strpos($mimeType, 'image')) {
            throw new UnsupportedMediaTypeHttpException();
        }
        $extension = $image->getClientOriginalExtension();
        $filename = date('mdHis') . uniqid('_') . '.' . $extension;
        $image->move('upload/portfolio_image/' .$request->get('id'). '/', $filename);

        $portfolioImage = new \Plugin\Portfolio\Entity\PortfolioImageData;
        $portfolioImage->setImageid((int)$currentId);
        $portfolioImage->setPortfolioid($request->get('id'));
        $portfolioImage->setFilename($filename);
        $portfolioImage->setRank(1);
        $status = $app['eccube.plugin.repository.portfolio_imagedata']->create($portfolioImage);

        return $app->json(array('files' => $filename), 200);
    }


    /** 画像削除処理 */
    public function delImage(Application $app, Request $request)
    {
        $portfolioImage = $app['eccube.plugin.repository.portfolio_imagedata']->findByName($request->get('filename'));
        $status = $app['eccube.plugin.repository.portfolio_imagedata']->delete($portfolioImage[0]);
        unlink('upload/portfolio_image/' .$request->get('id'). '/' .$request->get('filename'). '');
        return $app->json(array('files' => 'upload/portfolio_image/' .$request->get('id'). '/' .$request->get('filename')), 200);
    }


    /** 編集確定処理 */
    public function commit(Application $app, Request $request)
    {
        $currentId = $app['eccube.plugin.repository.portfolio_data']->findCurrentId();
        $currentId = $currentId[0]['id'] + 1;

        // Formを取得
        $builder = $app['form.factory']->createBuilder('admin_portfolio_edit');
        $form = $builder->getForm();
        $form->handleRequest($request);
        $data = $form->getData();
        if ('POST' === $request->getMethod()) {

            // 登録処理
            if(is_null($data['id'])) {
                $portfolio = new \Plugin\Portfolio\Entity\PortfolioData;
                $portfolio->setId((int)$currentId);
                $portfolio->setName($data['name']);
                $portfolio->setType($data['type']);
                $portfolio->setImg($data['img']);
                $portfolio->setPublish($data['publish']);
                $portfolio->setComment($data['comment']);
                $status = $app['eccube.plugin.repository.portfolio_data']->create($portfolio);
                if (!$status) {
                    $app->addError('登録に失敗しました', 'admin');
	            return $app->redirect($app->url('admin_portfolio'));
                }

            // 更新処理
            } else {
                $id = $data['id'];

                // id の存在確認　nullであれば一覧に戻る
                if(!$id) {
                    $app->addError('admin.portfolio.edit.data.illegalaccess', 'admin');
	            return $app->redirect($app->url('admin_portfolio'));
                }

	        // 選択した作品を検索
	        $portfolio = $app['eccube.plugin.repository.portfolio_data']->find($id);

                // データが存在しない場合は一覧へリダイレクト
                if(is_null($portfolio)) {
                    $app->addError('該当データが見つかりません', 'admin');
                    return $app->redirect($app->url('admin_portfolio'));
                }

                // 更新処理
                $portfolio->setId((int)$data['id']);
                $portfolio->setName($data['name']);
                $portfolio->setType($data['type']);
                $portfolio->setImg($data['img']);
                $portfolio->setPublish($data['publish']);
                $portfolio->setComment($data['comment']);

                $status = $app['eccube.plugin.repository.portfolio_data']->update($portfolio);
                if (!$status) {
                    $app->addError('更新に失敗しました', 'admin');
                    return $app->redirect($app->url('admin_portfolio'));
                }
            }

            // 成功時のメッセージを登録する
            $app->addSuccess('更新完了', 'admin');
        }
        return $app->redirect($app->url('admin_portfolio'));
    }



}