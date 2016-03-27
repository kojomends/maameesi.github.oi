<?
$level = 0;

if (isset($_SESSION['level']) && ($_SESSION['level'] > 0)) {
	$level = $_SESSION['level'];
}

include 'resultssearch.php';
?>

<table id="results">

<colgroup></colgroup><colgroup></colgroup><colgroup></colgroup><colgroup></colgroup>

<tr>
	<th style="cursor: pointer" onclick="updateParam('<?= $url ?>', 'sort', '')">Project : Summary</th>
	<th style="cursor: pointer" onclick="updateParam('<?= $url ?>', 'sort','status')">Status</th>
	<th style="cursor: pointer" onclick="updateParam('<?= $url ?>', 'sort','type')">Type</th>
	<th style="cursor: pointer" onclick="updateParam('<?= $url ?>', 'sort','importance')">Importance</th>
</tr>

<?
$andor = " and ";

$summaryKeys = "";
$type = "";
$typeKeys = "";
$importance = "";
$importanceKeys = "";
$parentId = "";
$status = "";
$statusKeys = "";
$project = "";
$projectKeys = "";

$levelKey = "project.visibility <= $level";

if (isset($_GET['summaryKeys'])) {
	$summaryKeys = "ticket.summary like '" . str_replace(" ", "%", $_GET['summaryKeys']) . "'";
}
if (isset($_GET['type'])) {
	$type = "type.id = '" . $_GET['type'] . "'";
}
if (isset($_GET['typeKeys'])) {
	$typeKeys = "type.name like '" . str_replace(" ", "%", $_GET['typeKeys']) . "'";
}
if (isset($_GET['importance'])) {
	$importance = "importance.id = '" . $_GET['importance'] . "'";
}
if (isset($_GET['importanceKeys'])) {
	$importanceKeys = "importance.name like '" . str_replace(" ", "%", $_GET['importanceKeys']) . "'";
}
if (isset($_GET['parentId'])) {
	$parentId = "ticket.parentId = '" . $_GET['parentId'] . "'";
}
if (isset($_GET['status'])) {
	$status = "status.id = '" . $_GET['status'] . "'";
}
if (isset($_GET['statusKeys'])) {
	$statusKeys = "status.name like '" . str_replace(" ", "%", $_GET['statusKeys']) . "'";
}
if (isset($_GET['project'])) {
	$project = "project.id = '" . $_GET['project'] . "'";
}
if (isset($_GET['projectKeys'])) {
	$projectKeys = "project.name like '" . str_replace(" ", "%", $_GET['projectKeys']) . "'";
}

$orderBy =	"project.id desc, " .
			"status.value asc, " .
			"importance.value desc, " .
			"ticket.id desc";

$sort = "project";
if (isset($_GET['sort'])) {
	$sort = $_GET['sort'];
}

if ($sort == "type") {
	$orderBy =	"type.name asc, " .
				"project.id desc, " .
				"status.value asc, " .
				"importance.value desc, " .
				"ticket.id desc";
} elseif ($sort == "status") {
	$orderBy =	"status.name asc, " .
				"project.id desc, " .
				"status.value asc, " .
				"importance.value desc, " .
				"ticket.id desc";
} elseif ($sort == "importance") {
	$orderBy =	"importance.name asc, " .
				"project.id desc, " .
				"status.value asc, " .
				"ticket.id desc";
} else {
	$orderBy =	"project.id desc, " .
				"ticket.parentId desc, " .
				"ticket.id desc";
}

$query =	"select " .
			"ticket.id as ticketId, " .
			"ticket.summary as ticketSummary, " .
			"type.name as typeName, " .
			"importance.name as importanceName, " .
			"ticket.parentId as parentId, " .
			"status.name as statusName, " .
			"ticket.statusId as statusId, " .
			"project.name as projectName, " .
			"project.id as projectId " .
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
			$levelKey . $andor .
			(isset($_GET['showall']) ? "" : "status.value < 100" . $andor) .
			$summaryKeys . ($summaryKeys == "" ? "" : $andor) .
			$type . ($type == "" ? "" : $andor) .
			$typeKeys . ($typeKeys == "" ? "" : $andor) .
			$importance . ($importance == "" ? "" : $andor) .
			$importanceKeys . ($importanceKeys == "" ? "" : $andor) .
			$parentId . ($parentId == "" ? "" : $andor) .
			$status . ($status == "" ? "" : $andor) .
			$statusKeys . ($statusKeys == "" ? "" : $andor) .
			$project . ($project == "" ? "" : $andor) .
			$projectKeys .
		"order by " .
			$orderBy;

if (preg_match("/where order by/", $query)) {
	$query = preg_replace("/where /", "", $query);
}

if (preg_match("/(and|or) order by/", $query)) {
	$query = preg_replace("/(and|or) order by/", "order by", $query);
}

$rs = mysqli_query($c, $query) or die(mysqli_error($c));

$lastName = "";
$lastParentId = "";
while ($row = mysqli_fetch_array($rs)) {

	$parentEcho = "";
	if ($row['parentId'] != 0) {
		$query = "select " .
					"ticket.summary as summary, " .
					"project.name as projectName " .
				"from ticket " .
				"inner join project on " .
					"project.id = ticket.projectId " .
				"where " .
					"ticket.id = " . $row['parentId'];

		$rs2 = mysqli_query($c, $query) or die(mysqli_error($c));

		$row2 = mysqli_fetch_array($rs2);

		$parentEcho = '<span class="ticketId';

		if ($lastParentId != $row['parentId']) {
			$parentEcho .= '">';
			$parentEcho .=  '<a href="ticket.php?id=' . $row['parentId'] . '">' .
								$row2['projectName'] . '-' . $row['parentId'] .
							'</a>';

			$lastParentId = $row['parentId'];
		}
		else {
			$parentEcho .= ' invisible">';
			$parentEcho .= $row2['projectName'] . '-' . $row['parentId'];
		}

		$parentEcho .= '</span> &rArr; ';

		mysqli_free_result($rs2);
	} else {
		$lastParentId = "";
	}
?>

<? if ($row[$sort.'Name'] != $lastName) {
	$lastName = $row[$sort.'Name'];
?>

<tr>
	<td colspan="4">
		<h3 class="group">
			<a href="#" onclick="updateParam('<?= $_SERVER['REQUEST_URI'] ?>', '<?= $sort ?>', '<?= $row[$sort.'Id'] ?>');"><?= $row[$sort.'Name'] ?></a>
		</h3>
		<hr />
	</td>
</tr>

<?
}
?>

<tr>
	<td class="res" onclick="window.location='ticket.php?id=<?= $row['ticketId'] ?>'"><?= $parentEcho ?><a href="ticket.php?id=<?= $row['ticketId'] ?>"><span class="ticketId"><?= $row['projectName'] ?>-<?= $row['ticketId'] ?></span>: <?= $row['ticketSummary'] ?></a></td>
	<td class="res"><span class="field"><span class="val" id="status<?= $row['ticketId'] ?>" onclick="setupUI('status', <?= $row['ticketId'] ?>)" type="select" val="<?= $row['statusId'] ?>"><?= $row['statusName'] ?></span></span></td>
	<td class="res"><?= $row['typeName'] ?></td>
	<td class="res"><?= $row['importanceName'] ?></td>
</tr>

<?
}
?>

</table>

<script type="text/javascript">
	$(function() {
		$("table#results").delegate('td.res','mouseover mouseleave', function(e) {
			if (e.type == 'mouseover') {
			  $(this).parent().addClass("hover");
			  //$("colgroup").eq($(this).index()).addClass("hover");
			} else {
			  $(this).parent().removeClass("hover");
			  //$("colgroup").eq($(this).index()).removeClass("hover");
			}
		});
	});

	function setupUI(id, ticketId) {
		var type = $("#"+id+ticketId).attr('type');

		var value = $("#"+id+ticketId).attr('val');

		var replaceHTML = '<';
		var element;

		replaceHTML += 'select name="' + id + '">';

		element = $(replaceHTML);

		element.load('ticketselect.php?field=' + id + '&val=' + value);

		var origElement = $("#"+id+ticketId);
		element.on('focusout', function() {
			//ajax the new data back to db, then...

			var val = element.val();

			$.ajax({
				url: 'ticketupdate.php?ticketId=' + ticketId + '&field=' + id + '&val=' + val,
				success: function(data) {
					origElement.html(he.decode(data.name));

					if (type == "select") {
						origElement.attr('val', data.val);
					}
				},
				dataType: "json"
				});

			element.replaceWith(origElement);
		});

		$("#"+id+ticketId).replaceWith(element);

		element.focus();
	}

	function updateParam(uri, key, value) {
		var re = new RegExp("([?|&])" + key + "=.*?(&|#|$)", "i");
		if (value == 0) {
			var chk1 = uri.replace(re, '$1');
			var chk2 = uri.replace(re, '$2');
			if (chk2 == "&") {
				window.location = uri.replace(re, '$1');
			} else {
				window.location = uri.replace(re, '$2');
			}
		} else {
			if (uri.match(re)) {
				window.location = uri.replace(re, '$1' + key + "=" + value + '$2');
			} else {
				var hash =  '';
				var separator = uri.indexOf('?') !== -1 ? "&" : "?";
				if(uri.indexOf('#') !== -1) {
					hash = uri.replace(/.*#/, '#');
					uri = uri.replace(/#.*/, '');
				}
				window.location = uri + separator + key + "=" + value + hash;
			}
		}
	}
</script>
