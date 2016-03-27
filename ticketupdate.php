<?
session_start();

$selectFields = array("project", "importance", "type", "status");

if (isset($_POST['field']) && isset($_POST['ticketId']) && isset($_SESSION['active']) && $_SESSION['active']) {
	$id = $_POST['ticketId'];

	$field = $_POST['field'];

	if (isset($_POST['val'])) {
		$value = $_POST['val'];
	}
	else {
		$value = "";
	}

	if ($_SESSION['canUpdate']) {
		include 'inc/db.php';

		$id = $_POST['ticketId'];

		$field = $_POST['field'];

		if (isset($_POST['val'])) {
			$value = $_POST['val'];
		}
		else {
			$value = "";
		}

		$fieldId = "";
		if (in_array($field, $selectFields)) {
			$fieldId = "Id";
		}
		else {
			//$value = htmlentities($value, ENT_QUOTES);
		}

		$query = "update ticket set " . $field . $fieldId . " = '$value' where ticket.id = $id;";

		mysqli_query($c, $query) or die(mysqli_error($c));
	}
	if (in_array($field, $selectFields)) {
		$query2 =	"select " .
					$field . ".name as " . $field . "name, " .
					$field . ".id as " . $field . "id " .
				"from ticket inner join $field on " .
					$field . ".id = ticket." . $field . "Id " .
				"where ticket.id = $id;";
	}
	else {
		$query2 = "select $field from ticket where ticket.id = $id;";
	}

	$rs = mysqli_query($c, $query2) or die(mysqli_error($c));

	$row = mysqli_fetch_array($rs);

	if (in_array($field, $selectFields)) {
		echo json_encode(array("name" => $row[$field . "name"], "val" => $row[$field . "id"]));
	}
	else {
		$match = "/https?:\/\/.+?(?=(&#x3C;|<|\n| ))/";
		$replace = "&#x3C;a auto=&#x22;true&#x22; href=&#x22;$0&#x22; onclick=&#x22;window.open(&#x27;$0&#x27;);return false;&#x22;&#x3E;$0&#x3C;/a&#x3E;";

		$name = preg_replace($match, $replace, $row[$field]);

		echo json_encode(array("name" => $name));
	}

	include 'inc/dbc.php';
}
?>
