<?
include "inc/db.php";
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
	<title>TKT</title>
	<style type="text/css">
		@import url('inc/theme.css');
	</style>
</head>

<script type="text/javascript" src="inc/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="inc/jquery.autosize.min.js"></script>
<script type="text/javascript" src="inc/he.js"></script>

<body>
	<table>
		<tr>
			<td style="width: 33%; text-align: left;"></td>
			<td style="width: 33%; text-align: center;">
				<a class="nostyle" href="/"><img src="/i/hdr.png" /></a>
			</td>
			<td style="width: 33%; text-align:right;">
				<? if (!isset($_SESSION['active']) || !$_SESSION['active']) { ?>
					<a href="login.php?rdr=<?= $_SERVER['REQUEST_URI'] ?>">Login</a>
				<? } else { ?>
					<a href="newticket.php">New Ticket</a> &bull;
					<a href="login.php?out=true">Logout</a>
				<? } ?>
			</td>
		</tr>
	</table>
