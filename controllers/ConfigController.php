<?php

namespace app\controllers;

use Yii;
use app\models\Food;
use app\models\FoodSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FoodController implements the CRUD actions for Food model.
 */
class ConfigController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Food models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FoodSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->isPost)
        {
            $orderRequireApproval =  Yii::$app->request->post('orderRequireApproval');
            $approvalEmailSubject =  Yii::$app->request->post('approvalEmailSubject');
            $approvalEmailFrom = Yii::$app->request->post('approvalEmailFrom');
            $orderRequireVerification = Yii::$app->request->post('orderRequireVerification');
            $orderVerificationEmailSubject = Yii::$app->request->post('orderVerificationEmailSubject');
            $invoiceGrouping =  Yii::$app->request->post('invoiceGrouping');
            $dayOrderGetVerified = Yii::$app->request->post('dayOrderGetVerified');
            $invoiceNumberFormat = Yii::$app->request->post('invoiceNumberFormat');
            $invoiceNoPrefix = Yii::$app->request->post('invoiceNoPrefix');
            $invoiceNoDigitSize = Yii::$app->request->post('invoiceNoDigitSize');

            Yii::$app->config->set('orderRequireApproval',$orderRequireApproval);
            Yii::$app->config->set('approvalEmailFrom',$approvalEmailFrom);
            Yii::$app->config->set('orderRequireVerification',$orderRequireVerification);
            Yii::$app->config->set('orderVerificationEmailSubject',$orderVerificationEmailSubject);
            Yii::$app->config->set('invoiceGrouping',$invoiceGrouping);
            Yii::$app->config->set('dayOrderGetVerified',$dayOrderGetVerified);
            Yii::$app->config->set('approvalEmailSubject',$approvalEmailSubject);
            Yii::$app->config->set('invoiceNumberFormat',$invoiceNumberFormat);
            Yii::$app->config->set('invoiceNoPrefix',$invoiceNoPrefix);
            Yii::$app->config->set('invoiceNoDigitSize',$invoiceNoDigitSize);

        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Food model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Food model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Food();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->foodId]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Food model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->foodId]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Food model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Food model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Food the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Food::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
