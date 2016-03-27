<?
include "head.php";

if (isset($_SESSION['level']) && ($_SESSION['level'] >= 100)) {
?>

	<nav>
		<div><a href="admin.php?projects">Projects</a></div>
		<div><a href="admin.php?permissions">Permissions</a></div>
	</nav>

	<? if (isset($_GET['permissions'])) { ?>

	<? } else { ?>

		<table id="projects">
			<tr class="pad">
				<th>Name</th>
				<th># Tickets</th>
				<th>Visibility Level</th>
				<th>Edit Level</th>
			</tr>

			<?
			$query =	"select " .
							"project.id as id, " .
							"project.name as name, " .
							"project.visibility as visibility, " .
							"project.editability as editability, " .
							"j.numTickets as numTickets " .
						"from project " .
						"inner join ( " .
							"select " .
								"ticket.projectId as projectId, " .
								"count(ticket.id) as numTickets " .
							"from ticket " .
							"group by ticket.projectId " .
						") j on j.projectId = project.id";

			$rs = mysqli_query($c, $query) or die(mysqli_error($c));

			while ($row = mysqli_fetch_array($rs)) {
			?>

				<tr class="pad">
					<td><span class="val" id="name<?= $row['id'] ?>" onclick="setupUI('name', <?= $row['id'] ?>)"><?= $row['name'] ?></span></td>
					<td><?= $row['numTickets'] ?></td>
					<td><span class="val" id="visibility<?= $row['id'] ?>" onclick="setupUI('visibility', <?= $row['id'] ?>)"><?= $row['visibility'] ?></span></td>
					<td><span class="val" id="editability<?= $row['id'] ?>" onclick="setupUI('editability', <?= $row['id'] ?>)"><?= $row['editability'] ?></span></td>
				</tr>

			<? } ?>

		</table>

	<? } ?>

<script type="text/javascript">
	$(function() {
		$("table#projects").delegate('td','mouseover mouseleave', function(e) {
			if (e.type == 'mouseover') {
			  $(this).parent().addClass("hover");
			  //$("colgroup").eq($(this).index()).addClass("hover");
			} else {
			  $(this).parent().removeClass("hover");
			  //$("colgroup").eq($(this).index()).removeClass("hover");
			}
		});

	});

	function setupUI(id, projectId) {
		var replaceHTML = '<input name="' + id + '" type="text">';

		var element = $(replaceHTML);

		var origElement = $("#"+id+projectId);

		element.val(origElement.html());

		element.on('focusout', function() {
			//ajax the new data back to db, then...

			var val = element.val();

			$.ajax({
				type: 'POST',
				url: 'adminupdate.php',
				data: {
					projectId: projectId,
					field: id,
					val: val
				},
				success: function(data) {
					origElement.html(he.decode(data.val));
				},
				dataType: "json"
				});

			element.replaceWith(origElement);
		});

		$("#"+id+projectId).replaceWith(element);

		element.focus();
	}
</script>

<?
}

include "foot.php";
?>