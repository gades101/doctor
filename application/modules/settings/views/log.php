<script type="text/javascript" charset="utf-8">

function page_build(data){
	if($.fn.DataTable.isDataTable("#log")) {$('#log').DataTable().destroy();}
	var tab=$('#pay_tbody');
	data=JSON.parse(data);tab.html("");
	//head.append($('<tr></tr>').append($('<th></th>').text('Користувач')).append($('<th></th>').text('Кількість процедур')));
	data.forEach(function(item){
		var row=$('<tr></tr>').append($('<td></td>').text(item.user_name))
		.append($('<td></td>').text(item.event_table))
		.append($('<td></td>').text(item.type))
		.append($('<td></td>').text(item.date))
		.append($('<td></td>').text(item.vars));
		tab.append(row);
	});
	$("#log").dataTable({"pageLength": 50, "order":  []});
}

$(function() {
	$('.ajax_form').submit(function(e) {
	var $form = $(this);
	$.ajax({
		type: $form.attr('method'),
	  	url: $form.attr('action'),
	  	data: $form.serialize()
	}).done(function(response) {
		console.log(response);
	  	page_build(response);
	}).fail(function() {
	  	console.log('fail');
	});
	//отмена действия по умолчанию для кнопки submit
	e.preventDefault(); 
	});
});


$( window ).load(function() {
	$('.input_date').datetimepicker({
		timepicker:true,
		format: 'd-m-Y H:i',
		scrollInput:false,
	});	
} )
</script>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<!-- Advanced Tables -->
			<div class="panel panel-primary">
				<div class="panel-heading">
					<span>Лог</span>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-body">
					<?php echo form_open('settings/get_log',array('id'=>'main_form','class'=>'ajax_form')); ?>
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
							<div class="col-md-4 form-group">
								<label for="user_name">Користувач</label>
								<select id='user_name' name='user_name' class="form-control" value="">
									<option value="">Усі</option>
									<?php foreach($users as $user){ ?>									
										<option value="<?php echo $user['name']; ?>" data-department_id="<?= $user['department_id']; ?>" /><?= $user['name']; ?></option>				
									<?php } ?>
								</select>
							</div>	
							<div class="col-md-4 form-group">
								<label for="event_table">Об'єкт</label>
								<select id='event_table' name='event_table' class="form-control" value="">
									<option value="">Усі</option>
									<option value="Прийом">Прийом</option>
									<option value="Оплата">Оплата</option>
									<option value="Рахунок">Рахунок</option>
									<option value="Логін">Логін</option>
								</select>
							</div>
							<div class="col-md-4 form-group">
								<label for="type">Операція</label>
								<select id='type' name='type' class="form-control" value="">
									<option value="">Усі</option>
									<option value="Редагування">Редагування</option>
									<option value="Видалення">Видалення</option>
									<option value="Створення">Створення</option>
									<option value="Авторизація">Авторизація</option>
								</select>
							</div>	

							<div class="col-md-4">
								<div class="form-group">
									<label for="ok" style="height: 14px"> </label>
									<input id="ok" class="btn btn-primary form-control" type="submit" value="Вивести" name="submit" />
								</div>
							</div>
						</div>			
					<?php echo form_close(); ?>
				</div>
			</div>	
			<div class="panel panel-primary">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover table-condensed dataTable no-footer" id="log">
						<thead>
							<tr>
								<th>Користувач</th>
								<th>Об'єкт</th>
								<th>Операція</th>
								<th>Дата</th>
								<th>Дані</th>
							</tr>
						</thead>
						<tbody id="pay_tbody">

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>