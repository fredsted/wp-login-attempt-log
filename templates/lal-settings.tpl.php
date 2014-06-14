<div class="wrap">
<h2>Login Attempts Log • Settings</h2>

<h3>Quick Stats</h3>

<style>
	.lal-qs-box {
		width: 25%;
		max-width: 200px;
		min-width: 50px;
		float: left;
		height: 120px;
		text-align: center;
		background: -webkit-linear-gradient(#2a2a2a 0%, #575757 100%);
		color: #dadada;
		border-radius: 5px;
		margin-right: 5px;
		margin-bottom: 5px
	}
	
	.lal-qs-amount {
		font-size: 45px;
		text-shadow: 0px 0px 10px #b9b9b9;
		font-family: "Helvetica Neue", "Helvetica", "Arial";
		font-weight: 100;
		padding-top: 30px;
	}

	.lal-qs-time {
		padding-top: 35px;
	}
	
	#lal-qs-total .lal-qs-amount {
		font-weight: 200;
	}
	
	#lal-quickstats {
		clear: both;
		height: 120px;
	}
	
	@media only screen and (max-width:900px) {
		.lal-qs-amount {
			font-size: 25px;
		}
		.lal-qs-time {
			padding-top: 20px;
		}
	}
</style>
<div id="lal-quickstats">
	<div class="lal-qs-box" id="lal-qs-total">
		<div class="lal-qs-amount"><?=$counts->total?></div>
		<div class="lal-qs-time">Total login attempts</div>
	</div>
	
	<br style="clear:both;">
	<?php
	/*
	 * This will display interesting statistics, but there's no need
	 * to display the same number multiple times (which will happen in the 
	 * first couple days and weeks)
	 */
  	?>
	<?php if($counts->month != $counts->week): ?>
	<div class="lal-qs-box" id="lal-qs-month">
		<div class="lal-qs-amount"><?=$counts->month?></div>
		<div class="lal-qs-time">This month</div>
	</div>
	<?php endif; ?>
	<?php if($counts->week != $counts->day): ?>
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
	
	<div class="lal-qs-box" id="lal-qs-avg">
		<div class="lal-qs-amount"><?=round($counts->average_per_month,1)?></div>
		<div class="lal-qs-time">Per month (average)</div>
	</div>		
	<div class="lal-qs-box" id="lal-qs-avg">
		<div class="lal-qs-amount"><?=round($counts->average_per_week,1)?></div>
		<div class="lal-qs-time">Per week (average)</div>
	</div>	
	<div class="lal-qs-box" id="lal-qs-avg">
		<div class="lal-qs-amount"><?=round($counts->average_per_day,1)?></div>
		<div class="lal-qs-time">Per day (average)</div>
	</div>
	<?php endif; ?>
	<br />
</div>

<br style="clear:both;">

<a href="admin.php?page=lal_log_show">View more stats...</a>

<hr />

<h3>Settings</h3>

<style>
#lal-disableip {
	width: 380px;
	height: 180px;
	margin-left: 24px;
	margin-top: 5px;
}
#disableip-documentation {
	margin-left: 24px;
	color: #999;
	font-size: 0.8em;
}
</style>

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
	<div id="disableip-documentation">
		Tip: You can use wildcards like <tt>45.234.222.*</tt>
	</div>
	<?php submit_button(); ?>
</form>
</div>