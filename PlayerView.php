<?php

	class PlayerView{
		private $stylesheet = 'PlayerCSS.css';
		private $pageTitle = 'Players';
		
		public function __construct() {

		}
		
		public function __destruct() {

		}
		
		public function playerListView($players, $orderBy = 'playerFirstName', $orderDirection = 'asc', $message = '') {
			$body = "<h3>Player Manager</h3>\n";
		
			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}
		
			$body .= "<p style = text-align:center><a class='playerButton' href='index.php?view=playerform'>+ Add player</a></p>\n";
	
			if (count($players) < 1) {
				$body .= "<p>No players to display!</p>\n";
				return $body;
			}
	
			$body .= "<table align=center>\n";
			//$body .= "<tr><th>delete</th><th>edit</th><th>completed</th>";
			$body .= "<tr><th>delete</th><th>edit</th>";
		
			$columns = array(array('name' => 'playerFirstName', 'label' => 'First Name'), 
							 array('name' => 'PlayerLastName', 'label' => 'Last Name'), 
							 array('name' => 'playerPosition', 'label' => 'Position'), 
							 array('name' => 'playerNumber', 'label' => 'Number'), 
							 array('name' => 'teamSchool', 'label' => 'School'));
		
			// geometric shapes in unicode
			// http://jrgraphix.net/r/Unicode/25A0-25FF
			foreach ($columns as $column) {
				$name = $column['name'];
				$label = $column['label'];
				if ($name == $orderBy) {
					if ($orderDirection == 'asc') {
						$label .= " &#x25BC;";  // ▼
					} else {
						$label .= " &#x25B2;";  // ▲
					}
				}
				$body .= "<th><a class='order' href='index.php?orderby=$name'>$label</a></th>";
			}
	
			foreach ($players as $player) {
				// $id = $task['id'];
				// $addDate = $task['addDate'];
				// $completedDate = ($task['completedDate']) ? $task['completedDate'] : '';
				// $title = $task['title'];
				// $description = ($task['description']) ? $task['description'] : '';
				// $category = $task['category'];

				$id = $player['player_id'];
				$firstName = $player['playerFirstName'];
				$lastName = $player['playerLastName'];
				$position = $player['playerPosition'];
				$number = $player['playerNumber'];
				$school = $player['teamSchool'];

				// $completedAction = 'set_completed';
				// $completedLabel = 'not completed';
				// if ($completedDate) {
				// 	$completedAction = 'set_not_completed';
				// 	$completedLabel = 'completed';
				// }
			
				$body .= "<tr>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='delete' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Delete'></form></td>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='edit' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Edit'></form></td>";
				// $body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='$completedAction' /><input type='hidden' name='id' value='$id' /><input type='submit' value='$completedLabel'></form></td>";
				$body .= "<td>$firstName</td><td>$lastName</td><td>$position</td><td>$number</td><td>$school</td>";
				$body .= "</tr>\n";
			}
			$body .= "</table>\n";
	
			return $this->page($body);
		}
		
		public function playerFormView($data = null, $message = '') {
			// $playerTeamID = '';
			// $firstName = '';
			// $lastName = '';
			// $position = '';
			// $number = '';
			// $selected = array('1' => '', '2' => '', '3' => '');
			$playerTeamID = '';
			$playerFirstName = '';
			$playerLastName = '';
			$playerPosition = '';
			$playerNumber = '';
			$selected = array('1' => '', '2' => '', '3' => '');
			$selected2 = array('Guard' => '', 'Forward' => '', 'Center' => '');

			if ($data) {
      $playerFirstName = $data['playerFirstName'];
      $playerLastName = $data['playerLastName'];
      $playerNumber = $data['playerNumber'];
      $playerPosition = $data['playerPosition'];
      $playerTeamID =$data['playerTeamID'];
      $selected[$playerTeamID] = 'selected';
      $selected2[$playerPosition] = 'selected';


			} else {
				// $selected['uncategorized'] = 'selected';
			}
	
			$html = <<<EOT1
<!DOCTYPE html>
<html>
<head>
<title>Task Manager</title>
<link rel="stylesheet" type="text/css" href="taskmanager.css">
</head>
<body>
<h1>Players</h1>
EOT1;

			if ($message) {
				$html .= "<p class='message'>$message</p>\n";
			}
		
			$html .= "<form action='index.php' method='post'>";
		
			if ($data['player_id']) {
				$html .= "<input type='hidden' name='action' value='update' />";
				$html .= "<input type='hidden' name='player_id' value='{$data['player_id']}' />";
				//mabye change name='id'
			} else {
				$html .= "<input type='hidden' name='action' value='add' />";
			}
			//LOOK HERE
		
			$html .= <<<EOT2
  <p>Team<br />
  <select name="playerTeamID">
	  <option value="1" {$selected['1']}>Texas A&M</option>
	  <option value="2" {$selected['2']}>Missouri Tigers</option>
	  <option value="3" {$selected['3']}>Utah Utes</option>
  </select>
  </p>

  <p>Team<br />
  <select name="playerPosition">
	  <option value="Guard" {$selected2['Guard']}>Guard</option>
	  <option value="Forward" {$selected2['Forward']}>Forward</option>
	  <option value="Center" {$selected2['Center']}>Center</option>
  </select>
  </p>

  <p>Player First Name<br />
  <input type="text" name="playerFirstName" value="$playerFirstName" placeholder="First Name" maxlength="255" size="80"></p>

  <p>Player Last Name<br />
  <input type="text" name="playerLastName" value="$playerLastName" placeholder="Last Name" maxlength="255" size="80"></p>

  <p>Number<br />
  <input type="text" name="playerNumber" value="$playerNumber" placeholder="Number" maxlength="255" size="80"></p>

  <input type="submit" name='submit' value="Submit"> <input type="submit" name='cancel' value="Cancel">
</form>
</body>
</html>
EOT2;

			print $html;
		}
		
		public function errorView($message) {
			$body = "<h1>Players</h1>\n";
			$body .= "<p>$message</p>\n";
			
			return $this->page($body);
		}
		
		private function page($body) {
			$html = <<<EOT
<!DOCTYPE html>
<html>
<head>
<title>{$this->pageTitle}</title>
<link rel="stylesheet" type="text/css" href="{$this->stylesheet}">
</head>
<body>
$body
<p2>CS3380 Final Project</p2>
</body>
</html>
EOT;
			return $html;
		}

}