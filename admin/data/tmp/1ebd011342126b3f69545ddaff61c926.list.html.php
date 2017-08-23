<?php $this->display('inc/header.html', array (
)); ?>

<!-- BEGIN Content -->
<div id="main-content">
    <!-- BEGIN Main Content -->
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="box-title">
                    <h3><i class="icon-table"></i> 菜单列表</h3>

                    <div class="btn-toolbar pull-right clearfix">
                        <div class="btn-group">
                            <a class="btn btn-circle show-tooltip" title="增加菜单"
                               href="<?php echo base_url() ; ?>sys/menu/add"><i class="icon-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="box-content">
                	<form name="form" id="pform" method="post" action="<?php echo base_url() ; ?>sys/menu/index/">
                	</form>
                    <div class="clearfix"></div>
                    <table class="table table-advance" id="table1">
                        <thead>
                        <tr>
                            <th>菜单名</th>
                            <th>parent_id</th>
                            <th>model</th>
                            <th>ctrl</th>
                            <th>状态</th>
                            <th style="width:100px">操作</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php if(!empty($this->_vars->list['data']) ) {  ?>
                        <?php foreach($this->_vars->list['data'] as $this->_vars->value ) {  ?>
                        <tr>
                            <td><?php echo $this->_vars->value['menu_name'] ; ?></td>
                            <td><?php echo $this->_vars->value['parent_id'] ; ?></td>
                            <td><?php echo $this->_vars->value['model'] ; ?></td>    
                            <td><?php echo $this->_vars->value['ctrl'] ; ?></td>
                            <th><?php if($this->_vars->value['disabled']=='false' ) {  ?>启用<?php } else { ?>禁用<?php } ?></th>
                            <td>
                                <div class="btn-group">
                                    <a class="btn btn-small show-tooltip" title="Edit"
                                       href="<?php echo base_url() ; ?>sys/menu/add/<?php echo $this->_vars->value['menu_id'] ; ?>"><i
                                            class="icon-edit"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php if(isset($this->_vars->pages) ) {  ?>
                    <div class="page"><?php echo $this->_vars->pages ; ?></div>
                    <?php } ?>
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