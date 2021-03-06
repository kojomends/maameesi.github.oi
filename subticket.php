<html>
<head>
<style type="text/css">
	@import url('inc/theme.css');
</style>
</head>
<body>
<?
session_start();
if (isset($_SESSION['active']) && $_SESSION['active']) {
include 'inc/db.php';

$superTicketId = $_GET['superTicketId'];

if (isset($_GET['subTicketId'])) {
	$query = "update ticket set parentId = $superTicketId where id = " . $_GET['subTicketId'];

	mysqli_query($c, $query) or die(mysqli_error($c));

	$query = "select project.name as projectName, ticket.summary as ticketSummary from ticket " .
			"inner join project on ticket.projectId = project.id where ticket.id = " . $_GET['subTicketId'];

	$rs = mysqli_query($c, $query) or die(mysqli_error($c));

	$row = mysqli_fetch_array($rs);

	echo '<script type="text/javascript">' .
			'window.opener.addSubTicket(\'' .
				$_GET['subTicketId'] . '\', \'' .
				$row['projectName'] . '\', \'' .
				str_replace("'", "&#039;", $row['ticketSummary']) .
			'\');' .
			'window.close();' .
		'</script>';
}
?>

<table id="results">

<colgroup></colgroup><colgroup></colgroup><colgroup></colgroup><colgroup></colgroup>

<tr><th>Summary</th><th>Status</th><th>Type</th><th>Importance</th></tr>
<?
$query =	"select " .
			"ticket.id as ticketId, " .
			"ticket.summary as ticketSummary, " .
			"type.name as typeName, " .
			"importance.name as importanceName, " .
			"ticket.parentId as parentId, " .
			"status.name as statusName, " .
			"ticket.statusId as statusId, " .
			"project.name as projectName " .
		"from ticket " .
		"inner join project on " .
			"project.id = ticket.projectId " .
		"inner join type on " .
			"type.id = ticket.typeId " .
		"inner join importance on " .
			"importance.id = ticket.importanceId " .
		"inner join status on " .
			"status.id = ticket.statusId " .
		"where " .
			"ticket.id != $superTicketId " .
			"and " .
			"project.id = " .
				"(select project.id " .
				 "from project " .
				 "inner join ticket ".
					 "on ticket.projectId = project.id " .
				 "where " .
					 "ticket.id = $superTicketId ) " .
		"order by " .
			"status.value asc, " .
			"importance.value desc, " .
			"ticket.id desc";

$rs = mysqli_query($c, $query) or die(mysqli_error($c));

while ($row = mysqli_fetch_array($rs)) {
?>
<tr>
	<td onclick="window.location='subticket.php?superTicketId=<?= $superTicketId ?>&subTicketId=<?= $row['ticketId'] ?>'"><a href="subticket.php?superTicketId=<?= $superTicketId ?>&subTicketId=<?= $row['ticketId'] ?>"><span class="ticketId"><?= $row['projectName'] ?>-<?= $row['ticketId'] ?></span>: <?= $row['ticketSummary'] ?></a></td>
	<td><?= $row['statusName'] ?></td>
	<td><?= $row['typeName'] ?></td>
	<td><?= $row['importanceName'] ?></td>
</tr>
<? } ?>
</table>

<script type="text/javascript">
	$(function() {

		$("table#results").delegate('td','mouseover mouseleave', function(e) {
			if (e.type == 'mouseover') {
			  $(this).parent().addClass("hover");
			  //$("colgroup").eq($(this).index()).addClass("hover");
			} else {
			  $(this).parent().removeClass("hover");
			  //$("colgroup").eq($(this).index()).removeClass("hover");
			}
		});

	});
</script>
</body>
</html>
<?
include 'inc/dbc.php';
}
?>