<?
include "head.php";

$access = false;

if (isset($_SESSION['active']) && $_SESSION['active']) {
	$access = true;
}

$id = 0;

if ($access && isset($_GET['unSub']) && isset($_GET['id'])) {
	$query = "update ticket set parentId = 0 where id = " . $_GET['unSub'];

	mysqli_query($c, $query) or die(mysqli_error($c));

	header("Location: ticket.php?id=" . $_GET['id']);
}

if ($access && isset($_GET['unSuper']) && isset($_GET['id'])) {
	$query = "update ticket set parentId = 0 where id = " . $_GET['id'];

	mysqli_query($c, $query) or die(mysqli_error($c));

	header("Location: ticket.php?id=" . $_GET['id']);
}


if (isset($_GET['id']) && $access) {
	$id = $_GET['id'];

	$query =	"select " .
					"ticket.summary as ticketSummary, " .
					"project.name as projectName, " .
					"project.id as projectId, " .
					"type.name as typeName, " .
					"type.id as typeId, " .
					"importance.name as importanceName, " .
					"importance.id as importanceId, " .
					"status.name as statusName, " .
					"status.id as statusId, " .
					"ticket.parentId as parentId, " .
					"ticket.description as ticketDesc " .
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
					"ticket.id = $id";

	$rs = mysqli_query($c, $query) or die(mysqli_error($c));

	$row = mysqli_fetch_array($rs);

	$summary = html_entity_decode($row['ticketSummary'], ENT_QUOTES);
	$projectName = html_entity_decode($row['projectName'], ENT_QUOTES);
	$projectId = $row['projectId'];
	$typeName = html_entity_decode($row['typeName'], ENT_QUOTES);
	$typeId = $row['typeId'];
	$importanceName = html_entity_decode($row['importanceName'], ENT_QUOTES);
	$importanceId = $row['importanceId'];
	$statusName = html_entity_decode($row['statusName'], ENT_QUOTES);
	$statusId = $row['statusId'];
	$parentId = $row['parentId'];
	$description = html_entity_decode($row['ticketDesc'], ENT_QUOTES);

	$description = str_replace("\n", "<br />", $description);
}
else {
	$summary = "";
	$projectName = "";
	$projectId = 0;
	$typeName = "";
	$typeId = 0;
	$importanceName = "";
	$importanceId = 0;
	$statusName = "";
	$statusId = 0;
	$parentId = 0;
	$description = "";
}

//$match = "((https?://(www)?\.?(([a-zA-Z0-9-]+?)\.)+[a-z]{3,7}(/?[^/]+)+?)((\?(&[a-zA-Z0-9-_%]+=[^&#]+)*)?)(#[^\s]+?)?)\s";
//$match = "_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?\$_iuS";
$match = "(https?://[^ \n<]+)";
$replace = "<a auto=\"true\" href=\"$0\" onclick=\"window.open('$0');return false;\">$0</a>";

$description = preg_replace($match, $replace, $description);

$parentEcho = "";
if ($parentId != 0) {
	$query = "select " .
				"ticket.summary as summary, " .
				"project.name as projectName " .
			"from ticket " .
			"inner join project on " .
				"project.id = ticket.projectId " .
			"where " .
				"ticket.id = $parentId";

	$rs = mysqli_query($c, $query) or die(mysqli_error());

	$row = mysqli_fetch_array($rs);

	$parentEcho = '<div class="clear superTicket" id="superTicket">SuperTicket: <a href="ticket.php?id=' . $parentId . '"><span class="ticketId">' . $row['projectName'] . '-' . $parentId . '</span>: ' . $row['summary'] . '</a> <a href="ticket.php?id=' . $id . '&unSuper=1">x</a></div>';
}
else {
	$parentEcho = '<div class="clear superTicket" id="superTicket"><a href="javascript:;" onclick="window.open(\'superticket.php?subTicketId=' . $id . '\', \'superTicket\', \'height=400,width=500\');">Add a SuperTicket</a></div>';
}

if ($access) {
?>
<div style="font-size: 14px; text-align: right"><input type="button" onclick="window.location='<?= isset($_SESSION['search']) ? $_SESSION['search'] : "/" ?>'" value="&laquo; Back" /></div>

<div class="edit">
	<div class="clear">
		<?= $parentEcho ?>
		<h2 class="ticketId">
			<?= $projectName ?>-<?= $id ?>
		</h2>
		<h1>
			<span class="val" id="summary" type="text"><?= $summary ?></span>
		</h1>
	</div>
	<div class="w75 fl">
		<span class="field">Type: <span class="val" id="type" type="select" val="<?= $typeId ?>"><?= $typeName ?></span></span>
		<span class="field">Importance: <span class="val" id="importance" type="select" val="<?= $importanceId ?>"><?= $importanceName ?></span></span>
		<span class="field">Description: <span class="val" id="description" type="textarea"><?= $description ?></span></span>
	</div>
	<div class="w25 fr">
		<span class="field">Project: <span class="val" id="project" type="select" val="<?= $projectId ?>"><?= $projectName ?></span></span>
		<span class="field">Status: <span class="val" id="status" type="select" val="<?= $statusId ?>"><?= $statusName ?></span></span>
		<input type="button" id="addSubTickets" onclick="window.open('subticket.php?superTicketId=<?= $id ?>', 'subTicket', 'height=400,width=500');" value="+Add SubTickets" />
		<?
		$query = 	"select " .
						"ticket.id as ticketId, " .
						"ticket.summary as summary, " .
						"project.name as projectName " .
					"from ticket " .
					"inner join project on " .
						"project.id = ticket.projectId " .
					"where " .
						"ticket.parentId = " . $id;

		$rs = mysqli_query($c, $query) or die(mysqli_error($c));

		if (mysqli_num_rows($rs) > 0) {
		?>
		<fieldset class="subTickets" id="subTickets"><legend>SubTickets</legend>
			<?
			while ($row = mysqli_fetch_array($rs)) {
			?>
			<div class="clear"><a href="ticket.php?id=<?= $row['ticketId'] ?>"><?= $row['projectName'] ?>-<?= $row['ticketId'] ?>: <?= $row['summary'] ?></a> <a class="fr" href="ticket.php?id=<?= $id ?>&unSub=<?= $row['ticketId'] ?>">x</a></div>
			<? } ?>
		</fieldset>
		<? } ?>
	</div>
</div>

<script type="text/javascript">
	var toEditFind = [/<br ?\/?>/gi, /<a auto=\"true\" href=\"(.+)\" onclick=\".+\">.+<\/a>/gi, /<tab ?\/?>(<\/tab>)?/gi]; //"
	var toEditRepl = ['\n', "$1", '\t'];

	var toViewFind = [/\n/g, /(<tab ?\/?>(<\/tab>)?|\t)/gi];
	var toViewRepl = ["<br />", "<tab />"];


	$(document).ready(function() {
		$(".val").each(function() {
			var id = $(this).attr("id");
			$(this).attr("ondblclick", "setupUI('" + id + "')");
		});
	});

	function addSubTicket(subTicketId, projectName, summary) {
		var div = $("<div></div>");

		div.addClass("clear");

		var a = $("<a></a>");

		a.attr("href", "ticket.php?id=" + subTicketId);
		a.html(projectName + "-" + subTicketId + ": " + summary);

		div.append(a);

		$("#subTickets").append(div);
	}

	function setSuperTicket(superTicketId, projectName, summary) {
		var content = 'SuperTicket: <a href="ticket.php?id=' + superTicketId + '"><span class="ticketId">' + projectName + '-' + superTicketId + '</span>: ' + summary + '</a>';

		$("#superTicket").html(content);
	}

	function setupUI(id) {
		var type = $("#"+id).attr('type');

		var value;
		if (type == "text" || type == "textarea") {
			value = $("#"+id).html();
		}
		else {
			value = $("#"+id).attr('val');
		}

		var replaceHTML = '<';
		var element;

		if (type == "text") {
			value = he.encode(value);

			replaceHTML += 'input type="text" id="' + id + '" name="' + id + '" value="' + value + '" />';

			element = $(replaceHTML);
		}
		else if (type == "textarea") {
			for (var i=0;i<toEditFind.length;i++) {
				value = value.replace(toEditFind[i], toEditRepl[i]);
			}

			replaceHTML += 'textarea id="' + id + '" name="' + id + '">' + value + '</textarea>';

			element = $(replaceHTML);
		}
		else {
			replaceHTML += 'select name="' + id + '">';

			element = $(replaceHTML);

			element.load('ticketselect.php?field=' + id + '&val=' + value);
		}

		var origElement = $("#"+id);

		element.on('focusout', function() {
			//ajax the new data back to db, then...

			var val = element.val();

			for (var i=0;i<toViewFind.length;i++) {
				val = val.replace(toViewFind[i], toViewRepl[i]);
			}

			val = he.encode(val);

			$.ajax({
				type: "POST",
				url: 'ticketupdate.php',
				data: {
					ticketId: '<?= $id ?>',
					field: id,
					val: val
				},
				success: function(data) {
					origElement.html(he.decode(data.name));

					if (type == "select") {
						origElement.attr('val', data.val);
					}

					element.replaceWith(origElement);
				},
				error: function(data) {
					console.log(data);
					console.log(data.responseText);
					element.replaceWith(origElement);
					origElement.parent().append("Error!");
				},
				dataType: "json"
			});
		});

		$("#"+id).replaceWith(element);

		$("textarea").autosize();

		element.focus();
	}
</script>

<?
}

include "foot.php"; ?>
