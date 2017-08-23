<?php $this->display('inc/header.html', array (
)); ?>
<style type="text/css">
.table th,.table td {
	padding: 2px;
	text-align:center;
    vertical-align: middle;
}
.more_data{
	background-color:#72be00;
}
</style>
<!-- BEGIN Content -->
<div id="main-content">
    <!-- BEGIN Main Content -->
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="box-title">
                    <h3><i class="icon-table"></i> 课程展示</h3>
                </div>
                <div class="box-content">
	                	<table class="table table-bordered" style="width:40%;">
	                		<thead>
	                			<tr>
		                			<th>分店</th>
		                			<th>日期</th>
	                			</tr>
	                		</thead>
	                		<tbody>
	                			<tr>
	                				<td>
	                					<select name="store_id" id="store" class="form-control">
								          	  <option></option>
											  <?php if(!empty($this->_vars->store_list) ) {  ?>						          
				                      		  <?php foreach($this->_vars->store_list as $this->_vars->key1=>$this->_vars->value1 ) {  ?>
				                      		  <option value="<?php echo $this->_vars->key1 ; ?>" ><?php echo $this->_vars->value1 ; ?></option>
				                      		  <?php } ?>
				                      		  <?php } ?>	
										</select>
	                				</td>
	                				<td>
	                					<input type="text" id="start_date" class="form-control" name="start_date"  value="<?php echo  date('Y-m-d') ; ?>" 
	                					onfocus="WdatePicker({isShowWeek:true,readOnly:true,onpicking:function(dp){change_start_date(dp)}})" 
	                					url="<?php echo base_url() ; ?>report/schedule/get_order_schedule/" />
	                				</td>
	                			</tr>
	                		</tbody>
	                	</table>
	                	<div class="clearfix"></div>
	                	<table class="table table-bordered" style="width:80%;">
	                		<thead>
	                			<tr class="week_item">
		                			<th></th>
		                			<th></th>
		                			<th></th>
		                			<th></th>
		                			<th></th>
		                			<th></th>
		                			<th></th>
		                			<th></th>
	                			</tr>
	                			<tr class="date_item">
		                			<th></th>
		                			<th></th>
		                			<th></th>
		                			<th></th>
		                			<th></th>
		                			<th></th>
		                			<th></th>
		                			<th></th>
	                			</tr>
	                		</thead>
	                		<tbody class="tbody">
	                			<tr>
	                				<th>8:00</th>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                			</tr>
	                			<tr>
	                				<th>9:00</th>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                			</tr>
	                			<tr>
	                				<th>10:00</th>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                			</tr>
	                			<tr>
	                				<th>11:00</th>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                			</tr>
	                			<tr>
	                				<th>12:00</th>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                			</tr>
	                			<tr>
	                				<th>13:00</th>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                			</tr>
	                			<tr>
	                				<th>14:00</th>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                			</tr>
	                			<tr>
	                				<th>15:00</th>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                			</tr>
	                			<tr>
	                				<th>16:00</th>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                			</tr>
	                			<tr>
	                				<th>17:00</th>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                			</tr>
	                			<tr>
	                				<th>18:00</th>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                			</tr>
	                			<tr>
	                				<th>19:00</th>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                			</tr>
	                			<tr>
	                				<th>20:00</th>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                			</tr>
	                			<tr>
	                				<th>21:00</th>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                			</tr>
	                			<tr>
	                				<th>22:00</th>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                			</tr>
	                			<tr>
	                				<th>23:00</th>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                				<td></td>
	                			</tr>
	                		</tbody>
	                	</table>	
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