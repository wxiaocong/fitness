<?php $this->display('inc/header.html', array (
)); ?>
<!-- BEGIN Content -->
<div id="main-content">
    <!-- BEGIN Main Content -->
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="box-title">
                    <h3><i class="icon-comment"></i> 系统参数管理</h3>

                    <div class="box-tool">
                    	<a href="<?php echo base_url() ; ?>sys/param" class="btn btn-info">返回上一级</a>
                    </div>
                </div>
                <div class="box-content">
                    <form name="addForm" id="addForm" method="post" action="<?php echo base_url() ; ?>sys/param/save/<?php echo isset($this->_vars->result) ? $this->_vars->result['id'] : '' ; ?>">
                        <table class="table table-advance">
                            <tr>
                                <td width="100" align="right"><label></label>参数：</td>
                                <td><label for="username"></label>
                                    <input type="text" name="name" id="name" required value="<?php echo isset($this->_vars->result) ? $this->_vars->result['s_key'] : '' ; ?>" ></td>
                            </tr>
                            <tr>
                                <td width="100" align="right"><label></label>值：</td>
                                <td><label for="username"></label>
                                    <textarea rows="5" cols="1000" name="value" id="value" required value="" ><?php echo isset($this->_vars->result) ? $this->_vars->result['s_val'] : '' ; ?></textarea></td>
                            </tr>
                            <tr>
                                <td width="100" align="right"><label></label>含义：</td>
                                <td><label for="username"></label>
                                    <input type="text" name="meaning" id="meaning" required value="<?php echo isset($this->_vars->result) ? $this->_vars->result['mome'] : '' ; ?>" ></td>
                            </tr>
 
 
                            <tr>
                                <td colspan="2">
                                	<input type="submit" class="btn btn-primary seperate_button" id="button" value="保存">
                                	<?php if(isset($this->_vars->result) ) {  ?>
                                	<a class="btn btn-danger" title="Delete" href="<?php echo base_url() ; ?>sys/param/del/<?php echo $this->_vars->result['id'] ; ?>" onclick="return confirm('确认删除？');">删除</a>
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