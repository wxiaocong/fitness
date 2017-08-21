			<?php if(! empty($this->_vars->data) ) {  ?>
			<?php foreach($this->_vars->data as $this->_vars->val ) {  ?>
			<tr>
				<td><?php echo date('Y-m-d',strtotime($this->_vars->val['dateline'])) ; ?></td>
				<td><?php echo isset($this->_vars->pay_type_cn[$this->_vars->val['pay_type']])?$this->_vars->pay_type_cn[$this->_vars->val['pay_type']]:'未知类型' ; ?></td>
				<td><?php echo $this->_vars->val['gain'] ; ?></td>
				<td><?php echo $this->_vars->val['expense'] ; ?></td>
				<td><?php echo $this->_vars->val['balance'] ; ?></td>
			</tr>
            <?php } ?>
            <?php } ?>
            
