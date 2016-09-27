<script type="text/javascript" charset="utf-8">
function page_build(data){
	if($.fn.DataTable.isDataTable("#app_table")) {$('#app_table').DataTable().destroy();}
	var tab=$('#app_tbody');head=$('#app_thead');
	var count=0;
	data=JSON.parse(data);tab.html("");head.html("");
	if($('#user_id').val()==""){
		head.append($('<tr></tr>').append($('<th></th>').text('Користувач')).append($('<th></th>').text('Кількість процедур')));
		data.forEach(function(item){
			var row=$('<tr></tr>').append($('<td></td>').text(item.name))
			.append($('<td></td>').text(item.app_count));
			tab.append(row);
			count+=parseInt(item.app_count);
		});
	}
	else{
		head.append($('<tr></tr>').append($('<th></th>').text('Процедура')).append($('<th></th>').text('Кількість процедур')));
		data.forEach(function(item){
			var row=$('<tr></tr>').append($('<td></td>').text(item.treatment))
			.append($('<td></td>').text(item.treatment_count));
			tab.append(row);
			count+=parseInt(item.treatment_count);
		});
	}
	$('#app_head').text('Загальна кількість процедур');
	$('#app_count').text('Всього: '+count+' процедур');
	$("#app_table").dataTable({"pageLength": 50, "order":  []});
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



	var maxdate=false, mindate=false;
	$('.confirmDelete').click(function(){
			return confirm("Ви впевнені");
	});
	$('#start_date').datetimepicker({
		timepicker:true,
		format: 'd-m-Y',
		scrollInput:false,
		maxDate: maxdate,
		minDate: mindate,

	});	
	$('#end_date').datetimepicker({
		timepicker:true,
		format: 'd-m-Y',
		maxDate: maxdate,
		scrollInput:false,
		maxDate: maxdate,		
		minDate: mindate,
	});	
} )
</script>
<?php
	$start_date = (isset($start_date)) ? ($start_date) : date($def_dateformate);
	$end_date = (isset($end_date)) ? ($end_date) : date($def_dateformate, mktime(0,0,0,date("m"),date("d")+1,date("Y")));
?>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">	
			<div class="panel panel-primary">		
				<div class="panel-heading">
					Звіт по прийомам
				</div>
				<div class="panel-body">
					<?php echo form_open('appointment/get_ajax_report',array('id'=>'main_form','class'=>'ajax_form')); ?>
					<input type="hidden" name="treatment_id" id="treatment_id" value=""/>	
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
					<div id="page_1" class="col-md-12">	
						<div class="col-md-6 form-group">
							<label for="user_id">Користувач</label>
							<select id='user_id' name='user_id' class="form-control" value="">
								<option value="">Усі</option>
								<?php foreach($users as $user){ ?>									
									<option value="<?php echo $user['userid']; ?>" data-department_id="<?= $user['department_id']; ?>" /><?= $user['name']; ?></option>				
								<?php } ?>
							</select>
						</div>	
						<div class="col-md-6 form-group">
							<label for="department_id">Відділ</label>
							<select id='department_id' name='department_id' class="form-control" value="">
								<option value="">Усі</option>
								<?php foreach($departments as $department){ ?>									
									<option value="<?= $department['department_id']; ?>" /><?= $department['department_name']; ?></option>				
								<?php } ?>
							</select>
						</div>
					</div>				
					<?php echo form_close(); ?>
				</div>
			</div>	

			<div class="panel panel-primary">
				<div class="panel-heading">
					<span id="app_head"></span>
					<span id="app_count" style="float:right" ></span>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover table-condensed dataTable no-footer" id="app_table">
						<thead  id="app_thead"></thead>
						<tbody id="app_tbody"></tbody>
					</table>
				</div>	
			</div>
		</div>
	</div>
</div>