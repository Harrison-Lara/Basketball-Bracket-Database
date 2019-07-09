<?php
//Require statements later
require ('PlayerView.php');
require ('PlayerModel.php');

//Controller class
class PlayerController{
  private $model;
  private $views;

  private $orderby = '';
  private $view = '';
  private $action = '';
  private $message = '';
  private $data = array();

  public function __construct(){
    $this->model = new PlayerModel();
    $this->views = new PlayerView();

      $this->view = $_GET['view'] ? $_GET['view'] : 'Statlist';
      $this->action = $_POST['action'];  }

  public function __destruct(){
      $this->model = null;
      $this->views = null;
  }

  public function run(){
    if ($error = $this->model->getError()) {
      print $error;
      exit;
    }

    $this->processOrderBy();

    switch($this->action) {
        case 'delete':
          $this->handleDelete();
          break;
        case 'add':
          $this->handleAddPlayer();
          break;
        case 'edit':
          $this->handleEditPlayer();
          break;
        case 'update':
          $this->handleUpdatePlayer();
          break;
      }

      switch($this->view){
        case 'playerform':
          print $this->views->playerFormView($this->data, $this->message);
          break;
        default: // playerlist
          list($orderBy, $orderDirection) = $this->model->getOrdering();
          list($players, $error) = $this->model->getPlayers();

          if ($error) {
            $this->message = $error;
          }
          print $this->views->playerListView($players, $orderBy, $orderDirection, $this->message);
      }

  }

  private function processOrderby() {
      if ($_GET['orderby']) {
        $this->model->toggleOrder($_GET['orderby']);
      }     
    }
    
    private function handleDelete() {
      if ($error = $this->model->deletePlayer($_POST['id'])) {
        $this->message = $error;
      }
      $this->view = 'playerlist';
    }
    
    private function handleAddPlayer() {
      if ($_POST['cancel']) {
        $this->view = 'playerlist';
        return;
      }
      
      $error = $this->model->addPlayer($_POST);
      if ($error) {
        $this->message = $error;
        $this->view = 'playerform';
        $this->data = $_POST;
      }
    }
    
    private function handleEditPlayer() {
      list($player, $error) = $this->model->getPlayer($_POST['id']);
      if ($error) {
        $this->message = $error;
        $this->view = 'playerlist';
        return;
      }
      $this->data = $player;
      $this->view = 'playerform';
    }
    
    private function handleUpdatePlayer() {
      if ($_POST['cancel']) {
        $this->view = 'playerlist';
        return;
      }
      
      if ($error = $this->model->updatePlayer($_POST)) {
        $this->message = $error;
        $this->view = 'playerform';
        $this->data = $_POST;
        return;
      }
      
      $this->view = 'playerlist';
    }


}
?>
