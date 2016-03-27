<?
session_start();

if (isset($_SESSION['level']) && ($_SESSION['level'] >= 100) && isset($_SESSION['active']) && $_SESSION['active']) {
	if (isset($_POST['field']) && isset($_POST['projectId'])) {
		include 'inc/db.php';

		$id = $_POST['projectId'];

		$field = $_POST['field'];

		if (isset($_POST['val'])) {
			$value = $_POST['val'];
		}
		else {
			$value = "";
		}

		$query = "update project set $field = '$value' where project.id = $id;";

		mysqli_query($c, $query) or die(mysqli_error($c));

		$query2 = "select $field from project where project.id = $id;";

		$rs = mysqli_query($c, $query2) or die(mysqli_error($c));

		$row = mysqli_fetch_array($rs);

		echo json_encode(array('val' => $row[$field]));

		include 'inc/dbc.php';
	}
}
?>
