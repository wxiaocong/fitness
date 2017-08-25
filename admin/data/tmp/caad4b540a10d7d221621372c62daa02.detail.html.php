<?php $this->display('inc/header.html', array (
)); ?>
<!-- BEGIN Content -->
<div id="main-content">
    <!-- BEGIN Main Content -->
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="box-title">
                    <h3><i class="icon-comment"></i> 用户编辑</h3>

                    <div class="box-tool">
                    	<a href="<?php echo base_url() ; ?>sys/admin" class="btn btn-info">返回上一级</a>
                    </div>
                </div>
                <div class="box-content">
                    <form name="addForm" id="addForm" method="post" action="<?php echo base_url() ; ?>sys/admin/save/<?php echo isset($this->_vars->result) ? $this->_vars->result['admin_id'] : '' ; ?>">
                        <table class="table table-advance">
                            <tr>
                                <td width="100" align="right">角色：</td>
                                <td>
                               	  <select name="role_id"  id="role_id">
						          <option value="0"></option>
						          <?php if(!empty($this->_vars->admin_role) ) {  ?>						          
                        		  <?php foreach($this->_vars->admin_role as $this->_vars->key => $this->_vars->value ) {  ?>
                        		  <option value="<?php echo $this->_vars->key ; ?>" <?php if(isset($this->_vars->result) && $this->_vars->key == $this->_vars->result['role_id'] ) {  ?>selected<?php } ?>><?php echo $this->_vars->value['role_name'] ; ?></option>
                        		  <?php } ?>
                        		  <?php } ?>	
						          </select>
                            </tr>
                             <tr id="store_id" <?php if( isset($this->_vars->result) && $this->_vars->result['role_id'] == 1 ) {  ?>style="display:none;"<?php } ?>>
                                <td width="100" align="right"><label></label>分店：</td>
                                <td>
                               	  <select name="store_id">
						          <option value="0"></option>
						          <?php if(!empty($this->_vars->store_list) ) {  ?>						          
                        		  <?php foreach($this->_vars->store_list as $this->_vars->k => $this->_vars->val ) {  ?>
                        		  <option value="<?php echo $this->_vars->k ; ?>" <?php if(isset($this->_vars->result) && $this->_vars->k == $this->_vars->result['store_id'] ) {  ?>selected<?php } ?>><?php echo $this->_vars->val ; ?></option>
                        		  <?php } ?>
                        		  <?php } ?>	
						          </select>
                            </tr>
                            <tr>
                                <td width="100" align="right"><label></label>用户名：</td>
                                <td>
                                    <input type="text" name="uname" id="uname" required value="<?php echo isset($this->_vars->result) ? $this->_vars->result['uname'] : '' ; ?>" ></td>
                            </tr>
                            <tr>
                                <td width="100" align="right"><label></label>真实姓名：</td>
                                <td>
                                    <input type="text" name="name" id="name" value="<?php echo isset($this->_vars->result) ? $this->_vars->result['name'] : '' ; ?>" ></td>
                            </tr>
                            <tr>
                                <td width="100" align="right"><label></label>密码：</td>
                                <td>
                                    <input type="password" name="passwd" id="passwd" <?php if(! isset($this->_vars->result) ) {  ?>required<?php } ?> ></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                	<input type="submit" class="btn btn-primary seperate_button" id="button" value="保存">
                                	<?php if(isset($this->_vars->result) ) {  ?>
                                	<a class="btn btn-warning seperate_button" href="<?php echo base_url() ; ?>sys/admin/status/<?php echo $this->_vars->result['admin_id'] ; ?>/<?php echo $this->_vars->result['disabled'] ; ?>"><?php if($this->_vars->result['disabled']=='0' ) {  ?>禁用<?php } else { ?>启用<?php } ?></a>
                                	<?php } ?>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- END Main Content -->

    <?php $this->display('inc/copyright.html', array (
)); ?>

    <a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
</div>
<!-- END Content -->

<?php $this->display('inc/footer.html', array (
)); ?>