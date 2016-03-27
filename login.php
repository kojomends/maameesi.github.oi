<html>
<head>
<style>
	@import url('inc/theme.css');
</style>
</head>
<body>

<?
session_start();

$rdr = "index.php";

if (isset($_GET['rdr'])) {
	$rdr = $_GET['rdr'];
}

if (isset($_GET['out'])) {
	$_SESSION['active'] = false;
	$_SESSION['usn'] = "";
	$_SESSION['role'] = "nobody";
	$_SESSION['canUpdate'] = false;
	$_SESSION['level'] = -1;

	session_destroy();
}

if (isset($_SESSION['active'])) {
	header("Location: $rdr");
}

if (isset($_POST['username'])) {
	include "inc/db.php";

	$usn = $_POST['username'];
	$psw = $_POST['password'];

	$psw = md5($psw);

	var_dump($psw);

	$query = "select user_role.roleId as roleId, role.level as roleLevel from user inner join user_role on user.id = user_role.userId inner join role on user_role.roleId = role.id where username = '$usn' and password ='$psw';";

	$rs = mysqli_query($c, $query) or die(mysqli_error($c));

	if (mysqli_num_rows($rs) == 1) {
		$row = mysqli_fetch_array($rs);

		if ($row['roleId'] == 1) {
			$_SESSION['canUpdate'] = true;
		}
		else {
			$_SESSION['canUpdate'] = false;
		}

		$_SESSION['active'] = true;
		$_SESSION['usn'] = $usn;
		$_SESSION['role'] = $row['roleId'];
		$_SESSION['level'] = intval($row['roleLevel']);

		header("Location: $rdr");
	}
	else {
		die("No such user!<script type='text/javascript'>setTimeout('window.location=\'/\';', 5000);</script>");
	}

}
else {
?>

<form method="post" action="login.php?rdr=<?= htmlspecialchars($rdr) ?>">
	<input type="text" name="username" placeholder="username" /><br />
	<input type="password" name="password" placeholder="password" /><br />
	<input type="submit" value="Log In" />
</form>

<?
}
?>

</body>
</html>
