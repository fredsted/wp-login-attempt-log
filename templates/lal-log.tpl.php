<style>
	@import url(<?=plugin_dir_url(__FILE__)."../assets/lal.css"?>);
</style>
<h2>Login Attempts Log &bull; Data viewer</h2>

<script type=text/javascript>
  jQuery(function() {
    if (typeof $ == 'undefined') var $ = jQuery;
    
    var watchChange = function () {
      if ($("#resulttype").val() == 'recent') {
        $(".searchactive").show();
      } else {
        $(".searchactive").hide();
      }
    };
    
    $("#resulttype").change(function() {
      watchChange();
    });
    watchChange();
  });
  
</script>

<form method="get">
	<input type="hidden" name="page" value="lal_log_show" />
	
	<table>
  	 <tr>
    	  <td>Number of results</td>
        <td>
          <input value="<?=(isset($_GET['value']) ? $_GET['value'] : 100)?>" 
                 name="topnum" type="number" style="width:100px" /> 
          
        </td>
  	 </tr>
  	 <tr>
    	  <td>Result type</td>
        <td>
          <select name="topwhich" id="resulttype">
        	  <option value="recent" <?=(isset($_GET['topwhich']) && $_GET['topwhich'] == 'recent' ? 'selected' : '')?>>
        	    Recent attempts
        	  </option>
        	  <option disabled>──────────</option>  
        	  <?php foreach (array("password", "username", "ip", "host", "agent") as $item): ?>
              <option value="<?=$item?>" <?=(isset($_GET['topwhich']) && $_GET['topwhich'] == $item ? 'selected' : '')?>>
                <?=ucfirst($item)?>s
              </option>
            <?php endforeach; ?>
        	</select>
        </td>
  	 </tr>
  	 <tr class="searchactive">
    	 <td>Search field</td>
    	 <td>
      	   <select name="searchfield">  
        	  <?php foreach (array("password", "username", "ip", "host", "agent") as $item): ?>
              <option value="<?=$item?>" <?=(isset($_GET['searchfield']) && $_GET['searchfield'] == $item ? 'selected' : '')?>>
                <?=ucfirst($item)?>
              </option>
            <?php endforeach; ?>
        	</select>
    	 </td>
  	 </tr>
  	 <tr class="searchactive">
    	  <td>Search string</td>
    	  <td><input type="text" name="searchstring" value="<?=(isset($_GET['searchstring']) ? $_GET['searchstring'] : 100)?>" />
      	  <small>Tip: Use <b>*</b> as wildcard</small>
    	  </td>
  	 </tr>
  	 <tr>
    	  <td>Year</td>
        <td>
          <select name="topyear">
        	  <?php foreach ($years as $item): ?>
              <option value="<?=$item->year?>" 
                      <?=(isset($_GET['topyear']) && $_GET['topyear'] == $item->year ? 'selected' : '')?>>
                <?=$item->year?>
              </option>
            <?php endforeach; ?>
        	</select>
          
        </td>
  	 </tr>
  	 <tr>
    	  <td>&nbsp;</td>
        <td><input type="submit" value="Show" class="button" /></td>
  	 </tr>
	</table>
	
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