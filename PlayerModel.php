<?php

class PlayerModel{
  private $error = '';
  private $mysqli;
  private $orderBy = 'playerFirstName';
  private $orderDirection = 'asc';

  public function __construct(){
    session_start();
    $this->initDatabaseConnection();
    $this->restoreOrdering();
  }

  public function __destruct(){
    if($this->mysqli){
      $this->mysqli->close();
    }
  }

  public function getError(){
    return $this->error;
  }

  private function initDatabaseConnection(){
    require('db_credentials.php');
    $this->mysqli = new mysqli($servername, $username, $password, $dbname);
    if($this->mysqli->connect_error){
      $this->error = $mysqli->connect_error;
    }
  }

  private function restoreOrdering(){
    $this->orderBy = $_SESSION['orderby'] ? $_SESSION['orderby'] : $this->orderBy;
    $this->orderDirection = $_SESSION['orderdirection'] ? $_SESSION['orderdirection'] : $this->orderDirection;

    $_SESSION['orderby'] = $this->orderBy;
    $_SESSION['orderdirection'] = $this->orderDirection;
  }

  public function toggleOrder($orderBy) {
    if ($this->orderBy == $orderBy)	{
      if ($this->orderDirection == 'asc') {
        $this->orderDirection = 'desc';
      } else {
        $this->orderDirection = 'asc';
      }
    } else {
      $this->orderDirection = 'asc';
    }
    $this->orderBy = $orderBy;

    $_SESSION['orderby'] = $this->orderBy;
    $_SESSION['orderdirection'] = $this->orderDirection;
  }

public function getOrdering() {
      return array($this->orderBy, $this->orderDirection);
    }


public function getPlayers() {
      $this->error = '';
      $players = array();
    
      if (! $this->mysqli) {
        $this->error = "No connection to database.";
        return array($player, $this->error);
      }
    
      $orderByEscaped = $this->mysqli->real_escape_string($this->orderBy);
      $orderDirectionEscaped = $this->mysqli->real_escape_string($this->orderDirection);

      $sql = "SELECT * 
            FROM players, teams
            WHERE players.playerTeamID = teams.team_id
            ORDER BY $orderByEscaped $orderDirectionEscaped";

      if ($result = $this->mysqli->query($sql)) {
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            array_push($players, $row);
          }
        }
        $result->close();
      } else {
        $this->error = $mysqli->error;
      }

      
      return array($players, $this->error);
    }



    public function getPlayer($id) {
      $this->error = '';
      $player = null;
    
      if (! $this->mysqli) {
        $this->error = "No connection to database.";
        return array($player, $this->error);
      }
      
      if (! $id) {
        $this->error = "No id specified for player to retrieve.";
        return array($player, $this->error);
      }
      
      $idEscaped = $this->mysqli->real_escape_string($id);
    
      $sql = "SELECT * FROM players WHERE player_id = '$idEscaped'";
      if ($result = $this->mysqli->query($sql)) {
        if ($result->num_rows > 0) {
          $player = $result->fetch_assoc();
        }
        $result->close();
      } else {
        $this->error = $this->mysqli->error;
      }
      
      return array($player, $this->error);    
    }
      



    public function addPlayer($data) {
      $this->error = '';
      
      
      $firstName = $data['playerFirstName'];
      $lastName = $data['playerLastName'];
      $number = $data['playerNumber'];
      $position = $data['playerPosition'];
      $playerTeamID =$data['playerTeamID'];
      
      if (! $firstName) {
        $this->error = "No first name found for player to add. A first name is required.";
        return $this->error;      
      }
      if (! $lastName) {
        $this->error = "No last name found for player to add. A last name is required.";
        return $this->error;      
      }
      if (! $number) {
        $this->error = "No number found for player to add. A number is required.";
        return $this->error;      
      }
      if (! $position) {
        $this->error = "No position found for player to add. A position is required.";
        return $this->error;      
      }
      if  (! $playerTeamID){
        $this->error = "No team found for player to add. A team is required.";
        return $this->error;
      }

      
      $firstNameEscaped = $this->mysqli->real_escape_string($firstName);    
      $lastNameEscaped = $this->mysqli->real_escape_string($lastName);
      $numberEscaped = $this->mysqli->real_escape_string($number);
      $positionEscaped = $this->mysqli->real_escape_string($position);
      $playerTeamIDEscaped = $this->mysqli->real_escape_string($playerTeamID);

      // print($firstNameEscaped);
      // print($lastNameEscaped);
      // print($numberEscaped);
      //print($positionEscaped);
      // print($playerTeamIDEscaped);

      $sql = "INSERT INTO players (playerFirstName, playerLastName, playerNumber, playerPosition, playerTeamID) VALUES ('$firstNameEscaped', '$lastNameEscaped', '$numberEscaped', '$positionEscaped', '$playerTeamIDEscaped')";
  
      if (! $result = $this->mysqli->query($sql)) {
        $this->error = $this->mysqli->error;
      }
      
      return $this->error;
    }



    public function updatePlayer($data) {
      $this->error = '';

      $firstName = $data['playerFirstName'];
      $lastName = $data['playerLastName'];
      $number = $data['playerNumber'];
      $position = $data['playerPosition'];
      $playerTeamID =$data['playerTeamID'];
      if (! $firstName) {
        $this->error = "No first name found for player to add. A first name is required.";
        return $this->error;      
      }
      if (! $lastName) {
        $this->error = "No last name found for player to add. A last name is required.";
        return $this->error;      
      }
      if (! $number) {
        $this->error = "No number found for player to add. A number is required.";
        return $this->error;      
      }
      if (! $position) {
        $this->error = "No position found for player to add. A position is required.";
        return $this->error;      
      }
      if  (! $playerTeamID){
        $this->error = "No team found for player to add. A team is required.";
        return $this->error;
      }

      $id = $data['player_id'];
      if (! $id) {
        $this->error = "No id specified for player to update.";
        return $this->error;      
      }

      
      $firstNameEscaped = $this->mysqli->real_escape_string($firstName);    
      $lastNameEscaped = $this->mysqli->real_escape_string($lastName);
      $numberEscaped = $this->mysqli->real_escape_string($number);
      $positionEscaped = $this->mysqli->real_escape_string($position);
      $playerTeamIDEscpaed = $this->mysqli->real_escape_string($playerTeamID);
      $idEscaped = $this->mysqli->real_escape_string($id);


      $sql = "UPDATE players SET playerfirstName='$firstNameEscaped', playerlastName='$lastNameEscaped', playerNumber='$numberEscaped', playerPosition='$positionEscaped', playerTeamID='$playerTeamIDEscpaed'  WHERE player_id = $idEscaped";
      if (! $result = $this->mysqli->query($sql) ) {
        $this->error = $this->mysqli->error;
      } 
      
      return $this->error;
    }
    
    public function deletePlayer($id) {
      $this->error = '';
      
      if (! $this->mysqli) {
        $this->error = "No connection to database.";
        return $this->error;
      }
      
      if (! $id) {
        $this->error = "No id specified for Player to delete.";
        return $this->error;      
      }     
    
      $idEscaped = $this->mysqli->real_escape_string($id);
      $sql = "DELETE FROM players WHERE player_id = $idEscaped";
      if (! $result = $this->mysqli->query($sql) ) {
        $this->error = $this->mysqli->error;
      }
      
      return $this->error;
    }



}
?>
