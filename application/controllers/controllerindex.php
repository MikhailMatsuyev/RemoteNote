<?php
class ControllerIndex extends Controller 
{   
    private $model;
    function __construct() 
    {
        parent::__construct();
        if(file_exists(Q_PATH.'/application/models/'.'modelindex'.'.php')){
            include_once Q_PATH.'/application/models/'.'modelindex'.'.php';	
        }
		$this->model = new ModelIndex();
    }

    public function actionIndex()
    {
        // грузим index при начальной загрузке страницы
        $this->view->generate('index');
        exit();
    }

    public function actionNew()
    {
        $this->view->generate('new');
        exit();
    }

    public function actionList()
    {
        $this->view->generate('list');
        exit();
    }

    public function actionEdit($id)
    {
        $model_array = $this->model->findNoteById($id[1]);
        $this->view->generate('update', $model_array);
        exit();
    }

    public function actionSave($id)
    {
        $model_array = $this->model->updateExistNote($_POST);
        $this->view->generate('new', $model_array);
        exit();
    }

}