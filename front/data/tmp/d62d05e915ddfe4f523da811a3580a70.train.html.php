<?php $this->display('inc/header.html', array (
)); ?>
<div class="container" id="container">
	<img src="<?php echo base_url() ; ?>static/image/train-top.png" style="width:160%;" />
	<div class="train_title">
		<span>热身</span>
		<span class="train_title_en">WARM</span>
	</div>
	<table class="train_table" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th>ACTIVITY</th>
				<th>时间</th>
				<th>距离</th>
				<th>组</th>
				<th>次</th>
				<th>强度</th>
				<th>备注</th>
			</tr>
		</thead>
		<tbody>
			<tr class="train_sept"><td colspan="7"> </td></tr>
			<?php for($this->_vars->i=0;$this->_vars->i<$this->_vars->max_warn;$this->_vars->i++ ) {  ?>
			<tr class="train_data">
				<td><?php echo isset($this->_vars->result['warn_activity']) ? $this->_vars->result['warn_activity'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['warn_time']) ? $this->_vars->result['warn_time'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['warn_dist']) ? $this->_vars->result['warn_dist'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['warn_sets']) ? $this->_vars->result['warn_sets'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['warn_rep']) ? $this->_vars->result['warn_rep'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['warn_intensity']) ? $this->_vars->result['warn_intensity'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['warn_notes']) ? $this->_vars->result['warn_notes'][$this->_vars->i] : '' ; ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	
	<div class="train_title">
		<span>力量训练</span>
		<span class="train_title_en">STRENGTH TRAINING</span>
	</div>
	<table class="train_table" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th>EXERCISES</th>
				<th>组</th>
				<th>次</th>
				<th>重量/RM</th>
				<th>REST TIME</th>
				<th>备注</th>
			</tr>
		</thead>
		<tbody>
			<tr class="train_sept"><td colspan="7"> </td></tr>
			<?php for($this->_vars->i=0;$this->_vars->i<$this->_vars->max_stre;$this->_vars->i++ ) {  ?>
			<tr class="train_data">
				<td><?php echo isset($this->_vars->result['stre_exercises']) ? $this->_vars->result['stre_exercises'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['stre_sets']) ? $this->_vars->result['stre_sets'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['stre_reps']) ? $this->_vars->result['stre_reps'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['stre_weight']) ? $this->_vars->result['stre_weight'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['stre_resttime']) ? $this->_vars->result['stre_resttime'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['stre_notes']) ? $this->_vars->result['stre_notes'][$this->_vars->i] : '' ; ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	
	<div class="train_title">
		<span>有氧训练</span>
		<span class="train_title_en">CARDIO TRAINNING</span>
	</div>
	<table class="train_table" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th>EXERCISES</th>
				<th>时间</th>
				<th>距离</th>
				<th>靶心率</th>
				<th>强度</th>
				<th>备注</th>
			</tr>
		</thead>
		<tbody>
			<tr class="train_card"><td colspan="7"> </td></tr>
			<?php for($this->_vars->i=0;$this->_vars->i<$this->_vars->max_card;$this->_vars->i++ ) {  ?>
			<tr class="train_data">
				<td><?php echo isset($this->_vars->result['card_execises']) ? $this->_vars->result['card_execises'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['card_time']) ? $this->_vars->result['card_time'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['card_dist']) ? $this->_vars->result['card_dist'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['card_target']) ? $this->_vars->result['card_target'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['card_intensity']) ? $this->_vars->result['card_intensity'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['card_notes']) ? $this->_vars->result['card_notes'][$this->_vars->i] : '' ; ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	
	<div class="train_title">
		<span>冷身</span>
		<span class="train_title_en">COOL DOWN</span>
	</div>
	<table class="train_table" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th>ACTIVITY</th>
				<th>时间</th>
				<th>距离</th>
				<th>组</th>
				<th>次</th>
				<th>强度</th>
				<th>备注</th>
			</tr>
		</thead>
		<tbody>
			<tr class="train_sept"><td colspan="7"> </td></tr>
			<?php for($this->_vars->i=0;$this->_vars->i<$this->_vars->max_cool;$this->_vars->i++ ) {  ?>
			<tr class="train_data">
				<td><?php echo isset($this->_vars->result['cool_activity']) ? $this->_vars->result['cool_activity'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['cool_time']) ? $this->_vars->result['cool_time'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['cool_dist']) ? $this->_vars->result['cool_dist'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['cool_sets']) ? $this->_vars->result['cool_sets'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['cool_reps']) ? $this->_vars->result['cool_reps'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['cool_intensity']) ? $this->_vars->result['cool_intensity'][$this->_vars->i] : '' ; ?></td>
				<td><?php echo isset($this->_vars->result['cool_notes']) ? $this->_vars->result['cool_notes'][$this->_vars->i] : '' ; ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	
</div>
<script type="text/javascript">
document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
    WeixinJSBridge.call('hideOptionMenu');
});
</script>
<?php $this->display('inc/tabbar.html', array (
)); ?>
<?php $this->display('inc/footer.html', array (
)); ?>
