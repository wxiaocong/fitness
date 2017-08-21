			<?php foreach($this->_vars->ltime as $this->_vars->k=>$this->_vars->v ) {  ?>
			<div class="box-content">
				<div  class="content-item">
					<div class="yy_time"><?php echo $this->_vars->v ; ?></div>
					<div class="yy_cz">
						<?php if(!empty($this->_vars->schedule_order) && in_array($this->_vars->v, $this->_vars->schedule_order) ) {  ?>						
						<a href="javascript:void(0);" class="yy_false">已预约</a>
						<?php } elseif($this->_vars->date_num == 0 && intval($this->_vars->v) <= date('H') ) {  ?>
						<a href="javascript:void(0);" class="yy_false">已结束</a>
						<?php } elseif(!empty($this->_vars->schedule_time) && in_array($this->_vars->v, $this->_vars->schedule_time) ) {  ?>
						<a href="javascript:void(0);" time="<?php echo $this->_vars->k ; ?>" class="yy_true">可预约</a>
						<?php } else { ?>
						<a href="javascript:void(0);" class="yy_false">未开放</a>
						<?php } ?>
					</div>
				</div>	
			</div>
			<?php } ?>