<?
include "head.php";

$access = false;

if (isset($_SESSION['active']) && $_SESSION['active']) {
	$access = true;
}

if ($access && isset($_POST['summary'])) {
	if ($_SESSION['canUpdate']) {
		$summary = htmlentities($_POST['summary'], ENT_QUOTES);
		$project = $_POST['project'];
		$type = $_POST['type'];
		$importance = $_POST['importance'];
		$description = htmlentities($_POST['description'], ENT_QUOTES);

		$query =	"insert into ticket " .
					"(summary,projectId,typeId,importanceId,description) " .
				"values (" .
					"'" . $summary . "'," .
					"'" . $project . "'," .
					"'" . $type . "'," .
					"'" . $importance . "'," .
					"'" . $description . "'" .
				");";

		mysqli_query($c, $query) or die(mysqli_error($c));
	}
	header("Location: index.php");
}

if ($access) {
?>

<div class="edit">
	<div class="w75">
		<form action="newticket.php" method="post">
			<input name="summary" id="summary" type="text" />
			<span class="field">Project:
				<select name="project" id="project">
					<?
					$query = "select name, id from project";

					$rs = mysqli_query($c, $query) or die(mysqli_error($c));

					while ($row = mysqli_fetch_array($rs)) { ?>
						<option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
					<? } ?>
				</select>
			</span>
			<span class="field">Type:
				<select name="type" id="type">
					<?
					$query = "select name, id from type";

					$rs = mysqli_query($c, $query) or die(mysqli_error($c));

					while ($row = mysqli_fetch_array($rs)) { ?>
						<option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
					<? } ?>
				</select>
			</span>
			<span class="field">Importance:
				<select name="importance" id="importance">
					<?
					$query = "select name, id from importance";

					$rs = mysqli_query($c, $query) or die(mysqli_error($c));

					while ($row = mysqli_fetch_array($rs)) { ?>
						<option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
					<? } ?>
				</select>
			</span>
			<span class="field">Description:
				<textarea name="description" id="description"></textarea>
			</span>
			<input type="submit" value="Submit" />
		</form>
	</div>
	<div class="w25">
	</div>
</div>

<?
}

include "foot.php"; ?>
