<?php $this->display('inc/header.html', array (
)); ?>
<div class="container" id="container">
	<img src="<?php echo base_url() ; ?>static/image/record-top.png" style="width:100%;" />
	<div class="file_title">
		<span>姓名 <?php echo $this->_vars->user_info['nickname'] ; ?></span>
		<span>年龄 <?php echo $this->_vars->user_info['age'] ; ?></span>
		<span>日期 <?php echo $this->_vars->user_info['subscribe_time']?date('Y-m-d',$this->_vars->user_info['subscribe_time']):'' ; ?></span>
	</div>
	<div style="width:96%;/* overflow:scroll; */margin:0 auto;">
	<table class="file_table" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th class="left-title">会员<br/>测试</th>
       			<th>测试<br/>结果</th>
       			<th>会员<br/>目标</th>
       			<th>测试<br/>结果</th>
       			<th>测试<br/>结果</th>
       			<th>测试<br/>结果</th>
       			<th>测试<br/>结果</th>
       			<?php if(isset($this->_vars->result['test_date']) && count($this->_vars->result['test_date']) > 6 ) {  ?>
      			<?php for($this->_vars->i=6;$this->_vars->i<count($this->_vars->result['test_date']);$this->_vars->i++ ) {  ?>
      			<th>测试<br/>结果</th>
      			<?php } ?>
      			<?php } ?>
			</tr>
		</thead>
		<tbody>
			<tr>
  				<td>日期</td>
  				<?php for($this->_vars->i=0;$this->_vars->i<count($this->_vars->result['test_date']);$this->_vars->i++ ) {  ?>
  				<td><?php echo isset($this->_vars->result['test_date'][$this->_vars->i]) ? $this->_vars->result['test_date'][$this->_vars->i] : '' ; ?></td>
  				<?php } ?>
  			</tr>
  			<tr>
  				<td>身高</td>
  				<?php for($this->_vars->i=0;$this->_vars->i<count($this->_vars->result['test_date']);$this->_vars->i++ ) {  ?>
  				<td><?php echo isset($this->_vars->result['test_height'][$this->_vars->i]) ? $this->_vars->result['test_height'][$this->_vars->i] : '' ; ?></td>
  				<?php } ?>
  			</tr>
  			<tr>
  				<td>体重</td>
  				<?php for($this->_vars->i=0;$this->_vars->i<count($this->_vars->result['test_date']);$this->_vars->i++ ) {  ?>
  				<td><?php echo isset($this->_vars->result['test_weight'][$this->_vars->i]) ? $this->_vars->result['test_weight'][$this->_vars->i] : '' ; ?></td>
  				<?php } ?>
  			</tr>
  			<tr>
  				<td>BMI</td>
  				<?php for($this->_vars->i=0;$this->_vars->i<count($this->_vars->result['test_date']);$this->_vars->i++ ) {  ?>
  				<td><?php echo isset($this->_vars->result['test_bmi'][$this->_vars->i]) ? $this->_vars->result['test_bmi'][$this->_vars->i] : '' ; ?></td>
  				<?php } ?>
  			</tr>
  			<tr>
  				<td>静息心率</td>
  				<?php for($this->_vars->i=0;$this->_vars->i<count($this->_vars->result['test_date']);$this->_vars->i++ ) {  ?>
  				<td><?php echo isset($this->_vars->result['test_heart_rate'][$this->_vars->i]) ? $this->_vars->result['test_heart_rate'][$this->_vars->i] : '' ; ?></td>
  				<?php } ?>
  			</tr>
  			<tr>
  				<td>
  					<div style="line-height:20px;">血压</div>
  					<div style="line-height:20px;"><small>(舒张压/收缩压)</small></div>
  				</td>
  				<?php for($this->_vars->i=0;$this->_vars->i<count($this->_vars->result['test_date']);$this->_vars->i++ ) {  ?>
  				<td><?php echo isset($this->_vars->result['test_blood_pressure'][$this->_vars->i]) ? $this->_vars->result['test_blood_pressure'][$this->_vars->i] : '' ; ?></td>
  				<?php } ?>
  			</tr>
  			<tr>
  				<td>大腿围</td>
  				<?php for($this->_vars->i=0;$this->_vars->i<count($this->_vars->result['test_date']);$this->_vars->i++ ) {  ?>
  				<td><?php echo isset($this->_vars->result['test_thign'][$this->_vars->i]) ? $this->_vars->result['test_thign'][$this->_vars->i] : '' ; ?></td>
  				<?php } ?>
  			</tr>
  			<tr>
  				<td>大臂围</td>
  				<?php for($this->_vars->i=0;$this->_vars->i<count($this->_vars->result['test_date']);$this->_vars->i++ ) {  ?>
  				<td><?php echo isset($this->_vars->result['test_arm'][$this->_vars->i]) ? $this->_vars->result['test_arm'][$this->_vars->i] : '' ; ?></td>
  				<?php } ?>
  			</tr>
  			<tr>
  				<td>腰围</td>
  				<?php for($this->_vars->i=0;$this->_vars->i<count($this->_vars->result['test_date']);$this->_vars->i++ ) {  ?>
  				<td><?php echo isset($this->_vars->result['test_waist'][$this->_vars->i]) ? $this->_vars->result['test_waist'][$this->_vars->i] : '' ; ?></td>
  				<?php } ?>
  			</tr>
  			<tr>
  				<td>臀围</td>
  				<?php for($this->_vars->i=0;$this->_vars->i<count($this->_vars->result['test_date']);$this->_vars->i++ ) {  ?>
  				<td><?php echo isset($this->_vars->result['test_hipline'][$this->_vars->i]) ? $this->_vars->result['test_hipline'][$this->_vars->i] : '' ; ?></td>
  				<?php } ?>
  			</tr>
  			<tr>
  				<td>腰臀比</td>
  				<?php for($this->_vars->i=0;$this->_vars->i<count($this->_vars->result['test_date']);$this->_vars->i++ ) {  ?>
  				<td><?php echo isset($this->_vars->result['test_whr'][$this->_vars->i]) ? $this->_vars->result['test_whr'][$this->_vars->i] : '' ; ?></td>
  				<?php } ?>
  			</tr>
  			<tr>
  				<td>
  					<div style="line-height:20px;">体脂</div>
  					<div style="line-height:20px;">(胸/三围)</div>
  				</td>
  				<?php for($this->_vars->i=0;$this->_vars->i<count($this->_vars->result['test_date']);$this->_vars->i++ ) {  ?>
  				<td><?php echo isset($this->_vars->result['test_fat_top'][$this->_vars->i]) ? $this->_vars->result['test_fat_top'][$this->_vars->i] : '' ; ?></td>
  				<?php } ?>
  			</tr>
  			<tr>
  				<td>体脂(大腿)</td>
  				<?php for($this->_vars->i=0;$this->_vars->i<count($this->_vars->result['test_date']);$this->_vars->i++ ) {  ?>
  				<td><?php echo isset($this->_vars->result['test_fat_thign'][$this->_vars->i]) ? $this->_vars->result['test_fat_thign'][$this->_vars->i] : '' ; ?></td>
  				<?php } ?>
  			</tr>
  			<tr>
  				<td>
  					<div style="line-height:20px;">体脂</div>
  					<div style="line-height:20px;">(腹/髂上)</div>
  				</td>
  				<?php for($this->_vars->i=0;$this->_vars->i<count($this->_vars->result['test_date']);$this->_vars->i++ ) {  ?>
  				<td><?php echo isset($this->_vars->result['test_fat_abdomen'][$this->_vars->i]) ? $this->_vars->result['test_fat_abdomen'][$this->_vars->i] : '' ; ?></td>
  				<?php } ?>
  			</tr>
  			<tr>
  				<td>总体体脂</td>
  				<?php for($this->_vars->i=0;$this->_vars->i<count($this->_vars->result['test_date']);$this->_vars->i++ ) {  ?>
  				<td><?php echo isset($this->_vars->result['test_fat_total'][$this->_vars->i]) ? $this->_vars->result['test_fat_total'][$this->_vars->i] : '' ; ?></td>
  				<?php } ?>
  			</tr>
		</tbody>
	</table>
	</div>
	
	<div class="train_title">
		<span>静态评估</span>
		<span class="train_title_en"> STATIC ACCESSMENT</span>
	</div>
	<div class="file_title">
		<span>部位 </span>
		<span>注意事项</span>
	</div>
	<table class="file_acc_table table_acc"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<th>祼关节姿势</th>
			<td>
				内旋 <input type="radio" disabled name="static_ankle" value="1" <?php echo isset($this->_vars->result['static_ankle']) && $this->_vars->result['static_ankle'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				外旋 <input type="radio" disabled name="static_ankle" value="2" <?php echo isset($this->_vars->result['static_ankle']) && $this->_vars->result['static_ankle'] == '2' ? 'checked' : '' ; ?> />
			</td>
			<td>
				超伸 <input type="radio" disabled name="static_ankle" value="3" <?php echo isset($this->_vars->result['static_ankle']) && $this->_vars->result['static_ankle'] == '3' ? 'checked' : '' ; ?> />
			</td>
		</tr>
		<tr>
			<th>足部姿势</th>
			<td>
				内翻 <input type="radio" disabled name="static_foot" value="1" <?php echo isset($this->_vars->result['static_foot']) && $this->_vars->result['static_foot'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				外翻 <input type="radio" disabled name="static_foot" value="2" <?php echo isset($this->_vars->result['static_foot']) && $this->_vars->result['static_foot'] == '2' ? 'checked' : '' ; ?> />
			</td>
			<td>
			</td>
		</tr>
		<tr>
			<th>膝关节</th>
			<td>
				内旋 <input type="radio" disabled name="static_knee" value="1" <?php echo isset($this->_vars->result['static_knee']) && $this->_vars->result['static_knee'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				外旋 <input type="radio" disabled name="static_knee" value="2" <?php echo isset($this->_vars->result['static_knee']) && $this->_vars->result['static_knee'] == '2' ? 'checked' : '' ; ?> />
			</td>
			<td>
				超伸 <input type="radio" disabled name="static_knee" value="3" <?php echo isset($this->_vars->result['static_knee']) && $this->_vars->result['static_knee'] == '3' ? 'checked' : '' ; ?> />
			</td>
		</tr>
		<tr>
			<th>骨盆</th>
			<td>
				 前倾<input type="radio" disabled name="static_bone" value="1" <?php echo isset($this->_vars->result['static_bone']) && $this->_vars->result['static_bone'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				后倾 <input type="radio" disabled name="static_bone" value="2" <?php echo isset($this->_vars->result['static_bone']) && $this->_vars->result['static_bone'] == '2' ? 'checked' : '' ; ?> />
			</td>
			<td>
				侧倾 <input type="radio" disabled name="static_bone" value="3" <?php echo isset($this->_vars->result['static_bone']) && $this->_vars->result['static_bone'] == '3' ? 'checked' : '' ; ?> />
			</td>
		</tr>
		<tr>
			<th>腰椎</th>
			<td>
				前弯 <input type="radio" disabled name="static_waist" value="1" <?php echo isset($this->_vars->result['static_waist']) && $this->_vars->result['static_waist'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				平背 <input type="radio" disabled name="static_waist" value="2" <?php echo isset($this->_vars->result['static_waist']) && $this->_vars->result['static_waist'] == '2' ? 'checked' : '' ; ?> />
			</td>
			<td>
				侧弯 <input type="radio" disabled name="static_waist" value="3" <?php echo isset($this->_vars->result['static_waist']) && $this->_vars->result['static_waist'] == '3' ? 'checked' : '' ; ?> />
			</td>
		</tr>
		<tr>
			<th>胸椎</th>
			<td>
				后弯 <input type="radio" disabled name="static_chest" value="1" <?php echo isset($this->_vars->result['static_chest']) && $this->_vars->result['static_chest'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				过伸 <input type="radio" disabled name="static_chest" value="2" <?php echo isset($this->_vars->result['static_chest']) && $this->_vars->result['static_chest'] == '2' ? 'checked' : '' ; ?> />
			</td>
			<td>
				圆肩 <input type="radio" disabled name="static_chest" value="3" <?php echo isset($this->_vars->result['static_chest']) && $this->_vars->result['static_chest'] == '3' ? 'checked' : '' ; ?> />
			</td>
		</tr>
		<tr>
			<th>肩部</th>
			<td>
				高低肩 <input type="radio" disabled name="static_shoulder" value="1" <?php echo isset($this->_vars->result['static_shoulder']) && $this->_vars->result['static_shoulder'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				耸肩 <input type="radio" disabled name="static_shoulder" value="2" <?php echo isset($this->_vars->result['static_shoulder']) && $this->_vars->result['static_shoulder'] == '2' ? 'checked' : '' ; ?> />
			</td>
			<td>
				翼状肩 <input type="radio" disabled name="static_shoulder" value="3" <?php echo isset($this->_vars->result['static_shoulder']) && $this->_vars->result['static_shoulder'] == '3' ? 'checked' : '' ; ?> />
			</td>
		</tr>
		<tr>
			<th>肩胛骨</th>
			<td>
				肩胛前引 <input type="radio" disabled name="static_shoulder_blade" value="1" <?php echo isset($this->_vars->result['static_shoulder_blade']) && $this->_vars->result['static_shoulder_blade'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
			</td>
			<td>
				翼状肩 <input type="radio" disabled name="static_shoulder_blade" value="2" <?php echo isset($this->_vars->result['static_shoulder_blade']) && $this->_vars->result['static_shoulder_blade'] == '2' ? 'checked' : '' ; ?> />
			</td>
		</tr>
		<tr>
			<th>头部</th>
			<td>
				前伸 <input type="radio" disabled name="static_head" value="1" <?php echo isset($this->_vars->result['static_head']) && $this->_vars->result['static_head'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				侧屈 <input type="radio" disabled name="static_head" value="2" <?php echo isset($this->_vars->result['static_head']) && $this->_vars->result['static_head'] == '2' ? 'checked' : '' ; ?> />
			</td>
			<td>
				旋转 <input type="radio" disabled name="static_head" value="3" <?php echo isset($this->_vars->result['static_head']) && $this->_vars->result['static_head'] == '3' ? 'checked' : '' ; ?> />
			</td>
		</tr>
		<tr>
			<th>双耳</th>
			<td>
				左低 <input type="radio" disabled name="static_binaural" value="1" <?php echo isset($this->_vars->result['static_binaural']) && $this->_vars->result['static_binaural'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				右低 <input type="radio" disabled name="static_binaural" value="2" <?php echo isset($this->_vars->result['static_binaural']) && $this->_vars->result['static_binaural'] == '2' ? 'checked' : '' ; ?> />
			</td>
			<td>
			</td>
		</tr>
	</table>
	
	<div class="train_title">
		<span>动态评估</span>
		<span class="train_title_en"> DYNAMIC ACCESSMENT</span>
	</div>
	<div class="file_title">
		<span>部位 </span>
		<span>评估结果</span>
	</div>
	<table class="file_acc_table table_dyn"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<th>托马斯测试</th>
			<td>左髋</td>
			<td>
				<span>紧张</span> <input type="radio" disabled name="dynamic_tms_left" value="1" <?php echo isset($this->_vars->result['dynamic_tms_left']) && $this->_vars->result['dynamic_tms_left'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				<span>正常</span> <input type="radio" disabled name="dynamic_tms_left" value="2" <?php echo isset($this->_vars->result['dynamic_tms_left']) && $this->_vars->result['dynamic_tms_left'] == '2' ? 'checked' : '' ; ?> />
			</td>
			<td>右髋</td>
			<td>
				<span>紧张</span> <input type="radio" disabled name="dynamic_tms_right" value="1" <?php echo isset($this->_vars->result['dynamic_tms_right']) && $this->_vars->result['dynamic_tms_right'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				<span>正常</span> <input type="radio" disabled name="dynamic_tms_right" value="2" <?php echo isset($this->_vars->result['dynamic_tms_right']) && $this->_vars->result['dynamic_tms_right'] == '2' ? 'checked' : '' ; ?> />
			</td>
		</tr>
		<tr>
			<th>被动抬腿实验</th>
			<td>左腿</td>
			<td>
				<span>紧张</span> <input type="radio" disabled name="dynamic_bdtt_left" value="1" <?php echo isset($this->_vars->result['dynamic_bdtt_left']) && $this->_vars->result['dynamic_bdtt_left'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				<span>正常</span> <input type="radio" disabled name="dynamic_bdtt_left" value="2" <?php echo isset($this->_vars->result['dynamic_bdtt_left']) && $this->_vars->result['dynamic_bdtt_left'] == '2' ? 'checked' : '' ; ?> />
			</td>
			<td>右腿</td>
			<td>
				<span>紧张</span> <input type="radio" disabled name="dynamic_bdtt_right" value="1" <?php echo isset($this->_vars->result['dynamic_bdtt_right']) && $this->_vars->result['dynamic_bdtt_right'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				<span>正常</span> <input type="radio" disabled name="dynamic_bdtt_right" value="2" <?php echo isset($this->_vars->result['dynamic_bdtt_right']) && $this->_vars->result['dynamic_bdtt_right'] == '2' ? 'checked' : '' ; ?> />
			</td>
		</tr>
		<tr>
			<th>肩关节外旋</th>
			<td>左肩</td>
			<td>
				<span>紧张</span> <input type="radio" disabled name="dynamic_jgjwx_left" value="1" <?php echo isset($this->_vars->result['dynamic_jgjwx_left']) && $this->_vars->result['dynamic_jgjwx_left'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				<span>正常</span> <input type="radio" disabled name="dynamic_jgjwx_left" value="2" <?php echo isset($this->_vars->result['dynamic_jgjwx_left']) && $this->_vars->result['dynamic_jgjwx_left'] == '2' ? 'checked' : '' ; ?> />
			</td>
			<td>右肩</td>
			<td>
				<span>紧张</span> <input type="radio" disabled name="dynamic_jgjwx_right" value="1" <?php echo isset($this->_vars->result['dynamic_jgjwx_right']) && $this->_vars->result['dynamic_jgjwx_right'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				<span>正常</span> <input type="radio" disabled name="dynamic_jgjwx_right" value="2" <?php echo isset($this->_vars->result['dynamic_jgjwx_right']) && $this->_vars->result['dynamic_jgjwx_right'] == '2' ? 'checked' : '' ; ?> />
			</td>
		</tr>
		<tr>
			<th>肩关节内旋</th>
			<td>左肩</td>
			<td>
				<span>紧张</span> <input type="radio" disabled name="dynamic_jgjnx_left" value="1" <?php echo isset($this->_vars->result['dynamic_jgjnx_left']) && $this->_vars->result['dynamic_jgjnx_left'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				<span>正常</span> <input type="radio" disabled name="dynamic_jgjnx_left" value="2" <?php echo isset($this->_vars->result['dynamic_jgjnx_left']) && $this->_vars->result['dynamic_jgjnx_left'] == '2' ? 'checked' : '' ; ?> />
			</td>
			<td>右肩</td>
			<td>
				<span>紧张</span> <input type="radio" disabled name="dynamic_jgjnx_right" value="1" <?php echo isset($this->_vars->result['dynamic_jgjnx_right']) && $this->_vars->result['dynamic_jgjnx_right'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				<span>正常</span> <input type="radio" disabled name="dynamic_jgjnx_right" value="2" <?php echo isset($this->_vars->result['dynamic_jgjnx_right']) && $this->_vars->result['dynamic_jgjnx_right'] == '2' ? 'checked' : '' ; ?> />
			</td>
		</tr>
		<tr>
			<th>肩关节伸展</th>
			<td>左肩</td>
			<td>
				<span>紧张</span> <input type="radio" disabled name="dynamic_jgjsz_left" value="1" <?php echo isset($this->_vars->result['dynamic_jgjsz_left']) && $this->_vars->result['dynamic_jgjsz_left'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				<span>正常</span> <input type="radio" disabled name="dynamic_jgjsz_left" value="2" <?php echo isset($this->_vars->result['dynamic_jgjsz_left']) && $this->_vars->result['dynamic_jgjsz_left'] == '2' ? 'checked' : '' ; ?> />
			</td>
			<td>右肩</td>
			<td>
				<span>紧张</span> <input type="radio" disabled name="dynamic_jgjsz_right" value="1" <?php echo isset($this->_vars->result['dynamic_jgjsz_right']) && $this->_vars->result['dynamic_jgjsz_right'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				<span>正常</span> <input type="radio" disabled name="dynamic_jgjsz_right" value="2" <?php echo isset($this->_vars->result['dynamic_jgjsz_right']) && $this->_vars->result['dynamic_jgjsz_right'] == '2' ? 'checked' : '' ; ?> />
			</td>
		</tr>
		<tr>
			<th>肩关节屈伸</th>
			<td>左肩</td>
			<td>
				<span>紧张</span> <input type="radio" disabled name="dynamic_jgjqs_left" value="1" <?php echo isset($this->_vars->result['dynamic_jgjqs_left']) && $this->_vars->result['dynamic_jgjqs_left'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				<span>正常</span> <input type="radio" disabled name="dynamic_jgjqs_left" value="2" <?php echo isset($this->_vars->result['dynamic_jgjqs_left']) && $this->_vars->result['dynamic_jgjqs_left'] == '2' ? 'checked' : '' ; ?> />
			</td>
			<td>右肩</td>
			<td>
				<span>紧张</span> <input type="radio" disabled name="dynamic_jgjqs_right" value="1" <?php echo isset($this->_vars->result['dynamic_jgjqs_right']) && $this->_vars->result['dynamic_jgjqs_right'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				<span>正常</span> <input type="radio" disabled name="dynamic_jgjqs_right" value="2" <?php echo isset($this->_vars->result['dynamic_jgjqs_right']) && $this->_vars->result['dynamic_jgjqs_right'] == '2' ? 'checked' : '' ; ?> />
			</td>
		</tr>
		<tr>
			<th>胸椎灵活性</th>
			<td>左转</td>
			<td>
				<span>紧张</span> <input type="radio" disabled name="dynamic_xzlhx_left" value="1" <?php echo isset($this->_vars->result['dynamic_xzlhx_left']) && $this->_vars->result['dynamic_xzlhx_left'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				<span>正常</span> <input type="radio" disabled name="dynamic_xzlhx_left" value="2" <?php echo isset($this->_vars->result['dynamic_xzlhx_left']) && $this->_vars->result['dynamic_xzlhx_left'] == '2' ? 'checked' : '' ; ?> />
			</td>
			<td>右转</td>
			<td>
				<span>紧张</span> <input type="radio" disabled name="dynamic_xzlhx_right" value="1" <?php echo isset($this->_vars->result['dynamic_xzlhx_right']) && $this->_vars->result['dynamic_xzlhx_right'] == '1' ? 'checked' : '' ; ?> />
			</td>
			<td>
				<span>正常</span> <input type="radio" disabled name="dynamic_xzlhx_right" value="2" <?php echo isset($this->_vars->result['dynamic_xzlhx_right']) && $this->_vars->result['dynamic_xzlhx_right'] == '2' ? 'checked' : '' ; ?> />
			</td>
		</tr>
	</table>

	<div class="file_title">
		<span>动作筛查 </span>
		<span>代偿情况</span>
		<span>可能原因</span>
	</div>
	<table class="file_table file_table_sc"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="small_th">过项蹲</td>
			<td>
				<?php echo isset($this->_vars->result['act_gdd_dc']) ? $this->_vars->result['act_gdd_dc'] : '' ; ?>
			</td>
			<td>
				<?php echo isset($this->_vars->result['act_gdd_yy']) ? $this->_vars->result['act_gdd_yy'] : '' ; ?>
			</td>
		</tr>
		<tr>
			<td class="small_th">跨栏上步</td>
			<td>
				<?php echo isset($this->_vars->result['act_klsb_dc']) ? $this->_vars->result['act_klsb_dc'] : '' ; ?>
			</td>
			<td>
				<?php echo isset($this->_vars->result['act_klsb_yy']) ? $this->_vars->result['act_klsb_yy'] : '' ; ?>
			</td>
		</tr>
		<tr>
			<td class="small_th">俯卧撑</td>
			<td>
				<?php echo isset($this->_vars->result['act_fwc_dc']) ? $this->_vars->result['act_fwc_dc'] : '' ; ?>
			</td>
			<td>
				<?php echo isset($this->_vars->result['act_fwc_yy']) ? $this->_vars->result['act_fwc_yy'] : '' ; ?>
			</td>
		</tr>
	</table>

	<div class="file_title">
		<span>动作筛查 </span>
		<span>时间</span>
	</div>
	<table class="file_acc_table table_qg"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td rowspan="7">躯干肌耐力测试</td>
			<td>躯干屈肌耐力实验</td>
			<td colspan="2"><?php echo isset($this->_vars->result['act_qj']) ? $this->_vars->result['act_qj'] : '' ; ?></td>
			<td></td>
		</tr>
		<tr>
			<td>躯干侧屈耐力实验</td>
			<td style="width:50px;">左<?php echo isset($this->_vars->result['act_cq_left']) ? $this->_vars->result['act_cq_left'] : '' ; ?></td>
			<td style="width:50px;">右<?php echo isset($this->_vars->result['act_cq_right']) ? $this->_vars->result['act_cq_right'] : '' ; ?></td>
			<td></td>
		</tr>
		<tr>
			<td>躯干伸肌耐力实验</td>
			<td colspan="2"><?php echo isset($this->_vars->result['act_sj']) ? $this->_vars->result['act_sj'] : '' ; ?></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td colspan="2"></td>
			<td></td>
		</tr>
		<tr>
			<td>屈/伸</td>
			<td colspan="2"><?php echo isset($this->_vars->result['act_qs']) ? $this->_vars->result['act_qs'] : '' ; ?></td>
			<td><1</td>
		</tr>
		<tr>
			<td>右侧桥/左侧桥</td>
			<td colspan="2"><?php echo isset($this->_vars->result['act_rlcq']) ? $this->_vars->result['act_rlcq'] : '' ; ?> </td>
			<td>0.95-1.05</td>
		</tr>
		<tr>
			<td>测桥/伸</td>
			<td colspan="2"><?php echo isset($this->_vars->result['act_cqs']) ? $this->_vars->result['act_cqs'] : '' ; ?></td>
			<td><0.75</td>
		</tr>
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