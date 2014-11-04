<style>
	@import url(<?=plugin_dir_url(__FILE__)."../assets/lal.css"?>);
</style>
<h2>Login Attempts Log &bull; Data viewer</h2>

<form method="get">
	<input type="hidden" name="page" value="lal_log_show" />
	<input type="submit" value="Show top" /> 
	<?php $value = "100";if (isset($_GET['topnum'])) $value = $_GET['topnum']; ?>
	<input value="<?=$value?>" name="topnum" type="number" style="width:60px" /> 
	
	<select name="topwhich">
	<?php foreach (array("recent", "password", "username", "ip", "agent") as $item): ?>
	<option value="<?=$item?>" <?php
	if (isset($_GET['topwhich']) && $_GET['topwhich'] == $item)echo ' selected="selected"'; 
	?>><?=ucfirst($item)?>s</option>
	<?php endforeach; ?>
	</select>
</form>

<?php if($istop): ?>
<h3>Top <?=$_GET['topnum']?> <?=$_GET['topwhich']?>s</h3>
<style>
#lal-lt-<?=$_GET['topwhich']?> {
	font-weight: bold;
	color: #FFF;
	background: #575757;
	text-shadow: 0px 0px 10px #b9b9b9;
}
.lal-li-<?=$_GET['topwhich']?> {
	background: #2a2a2a;
	color: #CCC;
}
</style>
<?php else: ?>
<h3>Recent Attempts</h3>
<?php endif; ?>

<table id="login-table">
	<tr>
		<?php if (isset($log[0]->magnitude)): ?>
		<th id="lal-lt-num">Number of results</th>
		<?php endif; ?>
		<th id="lal-lt-username">Username</th>
		<th id="lal-lt-password">Password</th>
		<th id="lal-lt-time">Time</th>
		<th id="lal-lt-ip">IP Address</th>
		<th id="lal-lt-agent">Browser</th>
	</tr>
	
<?php foreach ($log as $item): ?>
	<tr>
		<?php if (isset($log[0]->magnitude)): ?>
		<td class="lal-li-magnitude"><?=$item->magnitude?></td>
		<?php endif; ?>
		<td class="lal-li-username"><?=htmlspecialchars($item->username)?></td>
		<td class="lal-li-password"><?=htmlspecialchars($item->password)?></td>
		<td class="lal-li-time"><?=$item->time?></td>
		<td class="lal-li-ip"><?=$item->ip?></td>
		<td class="lal-li-agent" title="<?=htmlspecialchars($item->agent)?>"><?=htmlspecialchars($item->agent);?></td>
	</tr>
<?php endforeach; ?>
</table>