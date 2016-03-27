<?
$url = $_SERVER['REQUEST_URI'];
$_SESSION['search'] = $url;
?>
<div style="text-align: right"><span id="srchToggle" onclick="toggle()" style="cursor: pointer; text-align: right">+ Expand Search Params</span></div>
<div id="advSearch" style="display: none; margin: 10px 0; text-align: right">
	Type:
	<select id="type" onchange="updateParam('<?= $url ?>', 'type',this.value);">
		<option value="0"><?= isset($_GET['type']) ? "Clear Type" : "" ?></option>
		<?
		$query = "select id, name from type;";

		$rs = mysqli_query($c, $query) or die(mysqli_error($c));

		while ($line = mysqli_fetch_array($rs)) {
			echo '<option ';

			if (isset($_GET['type']) && ($_GET['type'] == $line['id'])) {
				echo 'selected ';
			}

			echo 'value="' . $line['id'] . '">' . $line['name'] . '</option>';
		}
		?>
	</select>

	Importance:
	<select id="importance" onchange="updateParam('<?= $url ?>', 'importance',this.value);">
		<option value="0"><?= isset($_GET['importance']) ? "Clear Importance" : "" ?></option>
		<?
		$query = "select id, name from importance;";

		$rs = mysqli_query($c, $query) or die(mysqli_error($c));

		while ($line = mysqli_fetch_array($rs)) {
			echo '<option ';

			if (isset($_GET['importance']) && ($_GET['importance'] == $line['id'])) {
				echo 'selected ';
			}

			echo 'value="' . $line['id'] . '">' . $line['name'] . '</option>';
		}
		?>
	</select>

	Status:
	<select id="status" onchange="updateParam('<?= $url ?>', 'status',this.value);">
		<option value="0"><?= isset($_GET['status']) ? "Clear Status" : "" ?></option>
		<?
		$query = "select id, name from status;";

		$rs = mysqli_query($c, $query) or die(mysqli_error($c));

		while ($line = mysqli_fetch_array($rs)) {
			echo '<option ';

			if (isset($_GET['status']) && ($_GET['status'] == $line['id'])) {
				echo 'selected ';
			}

			echo 'value="' . $line['id'] . '">' . $line['name'] . '</option>';
		}
		?>
	</select>

	Project:
	<select id="project" onchange="updateParam('<?= $url ?>', 'project',this.value);">
		<option value="0"><?= isset($_GET['project']) ? "Clear Project" : "" ?></option>
		<?
		$query = "select id, name from project;";

		$rs = mysqli_query($c, $query) or die(mysqli_error($c));

		while ($line = mysqli_fetch_array($rs)) {
			echo '<option ';

			if (isset($_GET['project']) && ($_GET['project'] == $line['id'])) {
				echo 'selected ';
			}

			echo 'value="' . $line['id'] . '">' . $line['name'] . '</option>';
		}
		?>
	</select>

	Show All <input <?= isset($_GET['showall']) ? "checked " : "" ?>type="checkbox" onclick="updateParam('<?= $url ?>', 'showall', this.checked);" value="1" />
</div>

<script type="text/javascript">
	function toggle() {
		$srch = $('#advSearch');
		//open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
		$srch.slideToggle(500, function () {
			//execute this after slideToggle is done
			//change text of header based on visibility of content div
			$("#srchToggle").html(function () {
				//change text based on condition
				return ($srch.is(":visible") ? "- Collapse" : "+ Expand") + " Search Params";
			});
		});
	}

	<?
	if (isset($_GET['type']) || isset($_GET['importance']) || isset($_GET['status']) || isset($_GET['project']) || isset($_GET['showall'])) {
	?>
		toggle();
	<?
	}
	?>
</script>

<br /><br />
