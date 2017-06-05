<div class="wrap">
<h2>Login Attempts Log &bull; Settings</h2>

<?php
	if (isset($_POST['lal-do-settings-reset']) && ($_POST['lal-do-settings-reset'] == 'OK')
	    && isset($_POST['lal-reset']) && ($_POST['lal-reset'] == 'OK')): ?>
	    <div class="updated notice">
    <p>&#9989; Login Attempt Log database was reset</p>
</div>
<?php endif; ?>

<h3>Last 14 days</h3>


	<?php if (!$counts): ?>

		<p>
			<i>Unfortunately, there is no data to show yet since the plugin was just installed.</i>
		</p>

	<?php else: ?>

		<?php if (count($chart) > 1): ?>

			<script type="text/javascript">
				window.onload = function () {
					var chart = new CanvasJS.Chart("chartContainer", {
						backgroundColor: "#F1F1F1",
						toolTipContent: "attempts: {y} ",
						data: [{
							toolTipContent: "{y} attempts",
							type: "column",
							dataPoints: <?= json_encode($chart) ?>
						}]
					});

					chart.render();
				}
			</script>

			<div id="chartContainer" style="height: 300px; width: <?= count($chart)*50 ?>px;"></div>

		<?php else: ?>

			<p>
				<i>A graph will be shown when there's more data available.</i>
			</p>

		<?php endif; ?>

		<div id="lal-quickstats">

		<?php
		/*
		 * This will display interesting statistics, but there's no need
		 * to display the same number multiple times (which will happen in the
		 * first couple days and weeks)
		 */
	    ?>
		<div class="lal-qs-box" id="lal-qs-total">
			<div class="lal-qs-amount"><?=$counts->total?></div>
			<div class="lal-qs-time">Total Attempts</div>
		</div>
		<br style="clear:both;">
		<?php if($counts->month != $counts->total): ?>
		<div class="lal-qs-box" id="lal-qs-month">
			<div class="lal-qs-amount"><?=$counts->month?></div>
			<div class="lal-qs-time">This month</div>
		</div>
		<?php endif; ?>
		<?php if($counts->week != $counts->total): ?>
		<div class="lal-qs-box" id="lal-qs-week">
			<div class="lal-qs-amount"><?=$counts->week?></div>
			<div class="lal-qs-time">This week</div>
		</div>
		<?php endif; ?>
		<?php if($counts->day != $counts->total): ?>
		<div class="lal-qs-box" id="lal-qs-day">
			<div class="lal-qs-amount"><?=$counts->day?></div>
			<div class="lal-qs-time">Today</div>
		</div>
		<?php endif; ?>
		<?php if (($counts->month+$counts->week+$counts->day) != ($counts->average_per_week+$counts->average_per_day+$counts->average_per_month)): ?>

		<br style="clear:both;">
		<?php if($counts->average_per_month != $counts->total): ?>
		<div class="lal-qs-box" id="lal-qs-avg">
			<div class="lal-qs-amount"><?=round($counts->average_per_month,1)?></div>
			<div class="lal-qs-time">Per month (average)</div>
		</div>
		<?php endif; ?>
		<div class="lal-qs-box" id="lal-qs-avg">
			<div class="lal-qs-amount"><?=round($counts->average_per_week,1)?></div>
			<div class="lal-qs-time">Per week (average)</div>
		</div>
		<div class="lal-qs-box" id="lal-qs-avg">
			<div class="lal-qs-amount"><?=round($counts->average_per_day,1)?></div>
			<div class="lal-qs-time">Per day (average)</div>
		</div>
		<?php endif; ?>

	<?php endif; ?>
	<br />
</div>

<br style="clear:both;">

<a href="admin.php?page=lal_log_show">View more stats...</a>

<hr />

<h3>Settings</h3>

<form method="post">
	<input type="hidden" name="lal-do-settings" value="OK" />
    <?php settings_fields('lal-settings-group'); ?>
    <?php do_settings_sections('lal-settings-group'); ?>
	<?php $val = ''; if (get_option('lal-set-disableip') == 'YES') $val = ' checked="checked"'; ?>
	<label>
		<input type="checkbox" name="lal-set-disableip"<?=$val?>> Disable logging for the following IP addresses
	</label>
	<br />
	<textarea id="lal-disableip" name="lal-set-disableip-text"><?php echo get_option('lal-set-disableip-text'); ?></textarea>
	<?php submit_button(); ?>
</form>

<hr />

<h3>Reset Login Attempt Log</h3>
<form method="post">
	<input type="hidden" name="lal-do-settings-reset" value="OK" />
  <label for="reset"><input id="reset" type="checkbox" name="lal-reset" value="OK"> Yes, reset Login Attempt Log database</label>
	<?php submit_button('Reset everything'); ?>
</form>
</div>