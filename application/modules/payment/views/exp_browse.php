<script type="text/javascript" charset="utf-8">

function page_build(data){
	//if($.fn.DataTable.isDataTable("#expenses")) {$('#expenses').DataTable().destroy();}
	var tab=$('#exp_tbody');
	data=JSON.parse(data);tab.html("");
	//head.append($('<tr></tr>').append($('<th></th>').text('Користувач')).append($('<th></th>').text('Кількість процедур')));
	data.forEach(function(item){
		var row=$('<tr></tr>').append($('<td></td>').text(item.expense_date))
		.append($('<td></td>').text(item.name))
		.append($('<td></td>').text(item.sum))
		.append($('<td></td>').text(item.title))
		.append($('<td></td>').text(item.goal))
		.append($('<td></td>').append($('<a></a>').attr('href',"<?= site_url('payment/edit_expense')?>"+"/"+item.id).addClass("btn btn-sm btn-primary square-btn-adjust").text('Редагувати')));
		tab.append(row);
	});
	//$("#expenses").dataTable({"pageLength": 50, "order":  []});
}

$(function() {
	$('.ajax_form').submit(function(e) {
	var $form = $(this);
	$.ajax({
		type: $form.attr('method'),
	  	url: $form.attr('action'),
	  	data: $form.serialize()
	}).done(function(response) {
	  	page_build(response);
	}).fail(function() {
	  	console.log('fail');
	});
	//отмена действия по умолчанию для кнопки submit
	e.preventDefault(); 
	});
});

$( window ).load(function() {
	$('.confirmDelete').click(function(){
			return confirm("Are you sure you want to delete?");
		});
	$('#expense_date').datetimepicker({
			timepicker:true,
			format: 'd-m-Y H:i',
			scrollInput:false,

	});
	var mindate=false;
	<?php if($_SESSION["category"] == 'Секретар'){?>
		$('.input_date').prop("readonly", true); mindate=new Date();mindate.setMonth(mindate.getMonth()-1);
	<?php } ?>
	$('.input_date').datetimepicker({
		timepicker:true,
		format: 'd-m-Y H:i',
		scrollInput:false,
		minDate: mindate,
	});	
	$('#user_id').on('change', function(){
		$('#department_id').val($(this).children('option:selected').data('department_id'));
	});	
	
} )
</script>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					Додати витрату
				</div>
				<div class="panel-body">
					<?php echo form_open('payment/expense'); ?>
						<input type="hidden" name="department_id" value="" />
						<div class="col-md-6 form-group" >
							<label for="expense_date">Дата</label>
							<input type="text" class="form-control" name="expense_date" id="expense_date" value=""/>
							<?php echo form_error('expense_date','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="col-md-6 form-group">
							<label for="cat_id">Категорія</label>
							<?php
								$cat_list = array();
								foreach ($expense_categories as $cat){
									$cat_list[$cat['id']] = $cat['view_id']." ".$cat['title'];
								}
							?>
							<?php echo form_dropdown('cat_id', $cat_list,array(),'class="form-control"'); ?>
							<?php echo form_error('cat_id','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="col-md-6 form-group">
							<label for="user_id">Користувач</label>
								<select id='user_id' name='user_id' class="form-control">
									<?php foreach($users as $user){ ?>									
											<option value="<?php echo $user['userid']; ?>"  data-department_id="<?= $user['department_id']; ?>"  /><?= $user['name']; ?></option>				
									<?php } ?>
								</select>
						</div>	
						<div class="col-md-6">
							<div class="form-group">
								<label for="expense_sum">Сума</label>
								<input type="text" class="form-control"  name="sum" id="expense_sum" value=""/>
								<?php echo form_error('expense_price','<div class="alert alert-danger">','</div>'); ?>
							</div>
						</div>
						<div class="col-md-6 form-group">
							<label for="departmentr_id">Відділення</label>
							<?php
								$department_list = array();
								foreach ($departments as $department){
									$department_list[$department['department_id']] = $department['department_name'];
								}
							?>
							<?php echo form_dropdown('department_id', $department_list,"",'id="department_id" class="form-control"'); ?>
							<?php echo form_error('department_id','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="col-md-12 form-group">
							<label for="goal">Призначення</label>
							<input type="text" name="goal" id="goal" value="" class="form-control"/>
							<?php echo form_error('goal','<div class="alert alert-danger">','</div>'); ?>
						</div>						
						<div class="form-group col-md-12">
							<button type="submit" name="submit" class="btn btn-primary"><?php echo $this->lang->line('add');?></button>
						</div>
					</form>
				</div>
			</div>	

			<div class="panel panel-primary">
				<div class="panel-heading">
					Витрати
				</div>
				<div class="panel-body">



					<?php echo form_open('payment/expense_list',array('id'=>'main_form','class'=>'ajax_form')); ?>
						<div class="col-md-12 form-group">							
							<div class="col-md-4">
								<div class="form-group">
									<label for="start_date"><?php echo $this->lang->line("from_date");?></label>
									<input type="text" class="form-control input_date" name="start_date" id="start_date" value="<?=$start_date;?>"/>
									<?php echo form_error('start_date','<div class="alert alert-danger">','</div>'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="end_date"><?php echo $this->lang->line("to_date");?></label>								
									<input type="text" class="form-control input_date" name="end_date" id="end_date" value="<?=$end_date;?>" />
									<?php echo form_error('end_date','<div class="alert alert-danger">','</div>'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="ok" style="height: 14px"> </label>
									<input id="ok" class="btn btn-primary form-control" type="submit" value="Вивести" name="submit" />
								</div>
							</div>
						</div>			
					<?php echo form_close(); ?>
						<table class="table table-striped table-bordered table-hover dataTable no-footer" id="expenses" >
							<thead>
								<tr>
									<th>Дата</th>
									<th>Користувач</th>
									<th>Сума</th>
									<th>Категорія витрат</th>
									<th>Призначення</th>
									<th><?php echo $this->lang->line('edit');?></th>
								</tr>
							</thead>
							<tbody id="exp_tbody">
				    	    </tbody>
			  			</table>
				</div> 
			</div>
		</div>
	</div>
</div>   
