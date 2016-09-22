<script type="text/javascript" charset="utf-8">
var page_num=1, page_2=false;


function displayPage(p_num){
	if (p_num==1){
		page_num=1;
		$(".page_1").show();
		$(".page_2").hide();
	}
	if (p_num==2){
		page_num=2;
		$(".page_2").show();
		$(".page_1").hide();
		if(page_2!=true){
			page_2=true;
			/*$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>index.php/payment/payment_ajax_report/"+page_num,
				dataType: "json",
				success: function(data){
					page_build(page_num,data);
				}
			});*/
		}
	}
}


function page_build(page_num,data){
	if(page_num==1){
		data=(data=="")?0:data;
		$('#summ').text('Сума: '+data+' грн.');
	}
	if (page_num==2){
		var tab=$('#page_2_tbody'),field_class,curr_date="<?=date('Y-m-d')?>",curr_time="<?=date('H:i')?>";
		var i=1;
		data=JSON.parse(data);tab.html("");
		data.forEach(function(item){
			var row=$('<tr></tr>').append($('<td></td>').text(i))
			.append($('<td></td>').text(item.first_name+" "+item.middle_name))
			.append($('<td></td>').text(item.summ));
			tab.append(row);
			i++;
		});
		//$("#page_2_tbody").dataTable({"pageLength": 50});
	}		
}

$(function() {
	$('.ajax_form').submit(function(e) {
	var $form = $(this);
	$.ajax({
		type: $form.attr('method'),
	  	//url: "<?php echo base_url(); ?>index.php/payment/payment_ajax_report/"+page_num,
	  	url: $form.attr('action'),
	  	data: $form.serialize()
	}).done(function(response) {
	  	page_build(page_num,response);
	}).fail(function() {
	  	console.log('fail');
	});
	//отмена действия по умолчанию для кнопки submit
	e.preventDefault(); 
	});
});

$( window ).load(function() {
	$('.confirmDelete').click(function(){
			return confirm("Ви впевнені");
	});
	$('.input_date').datetimepicker({
		timepicker:true,
		format: 'd-m-Y H:i',
		scrollInput:false,
	});	

	var searchtreatment=[<?php $i = 0;
	foreach ($treatments as $treatment) {
		if ($i > 0) { echo ",";}
		echo '{value:"' . $treatment['treatment'] . '",id:"' . $treatment['id'] . '",price:"' . $treatment['price'] . '",count:"' . $treatment['count'] . '"}';
		$i++;
	}
	?>];

	$("#treatment").autocomplete({
		autoFocus: true,
		source: searchtreatment,
		minLength: 1,//search after one characters

		select: function(event,ui){
			//do something
			$("#treatment_id").val(ui.item ? ui.item.id : '');
			$("#treatment").val(ui.item ? ui.item.treatment : '');
			$("#apps_remaining").val(ui.item ? ui.item.count : '');
			var amount=ui.item ? ui.item.price*((100-$("#discount").val())/100) : '';
			$("#pay_amount").val(amount);
			$("#paid").val(amount);
			price=ui.item ? ui.item.price : '';
		},
		change: function(event, ui) {
			 if (ui.item == null) {
				$("#treatment_id").val('');
				$("#treatment").val('');
				}
		},
		response: function(event, ui) {
			if (ui.content.length === 0)
			{
				$("#treatment_id").val('');
				$("#treatment").val('');
			}
		}
	});	

	$('#operation').on('change', function(){
		if(this.value==2){
			$('#treatment').prop('readonly',true);
		}
		else{
			$('#treatment').prop('readonly',false);			
		}
	});


} )
</script>
<?php
	$start_date = (isset($start_date)) ? ($start_date) : date($def_dateformate) . " 00:00";
	$end_date = (isset($end_date)) ? ($end_date) : date($def_dateformate, mktime(0,0,0,date("m"),date("d")+1,date("Y"))) . " 00:00";
?>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">	
			<div class="panel panel-primary">		
				<div class="panel-heading">
					<span class="tblHead btn-danger" onclick=displayPage(1) style="min-width:20%"/>Звіт по платежам/витратам</span>
					<span class="tblHead btn-danger" onclick=displayPage(2) />Звіт по пацієнтам</span>
				</div>
					<div class="panel-body page_1">
						<?php echo form_open('payment/payment_ajax_report/1',array('id'=>'main_form','class'=>'ajax_form')); ?>
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
							<div class="col-md-4 form-group">
								<label for="operation">Оплати/Витрати</label>
								<select id='operation' name='operation' class="form-control" value="">								
									<option value="1" selected=true />Оплати</option>
									<option value="2" />Витрати</option>								
								</select>
							</div>	
							<div class="col-md-4">
								<label for="treatment"><?php echo $this->lang->line('treatment');?></label>
								<input name="treatment" id="treatment" type="text" class="form-control" value=""/><br />
							</div>
							<div class="col-md-4 form-group">
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
			<div class="panel panel-primary page_1">
				<div class="panel-body">
					<div id='summ'></div>
				</div>
			</div>		
			<div class="panel panel-primary page_2" style="display: none">
				<div class="panel-body">
					<?php echo form_open('payment/payment_ajax_report/2',array('class'=>'ajax_form')); ?>
					<div class="col-md-3">
						<div class="form-group">
							<label for="start_date"><?php echo $this->lang->line("from_date");?></label>
							<input type="text" class="form-control input_date" name="start_date" id="start_date_2" value="<?=$start_date;?>"/>
							<?php echo form_error('start_date_2','<div class="alert alert-danger">','</div>'); ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="end_date"><?php echo $this->lang->line("to_date");?></label>								
							<input type="text" class="form-control input_date" name="end_date" id="end_date_2" value="<?=$end_date;?>" />
							<?php echo form_error('end_date_2','<div class="alert alert-danger">','</div>'); ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="number_of_patients">Кількість пацієнтів</label>								
							<input type="text" class="form-control" name="number_of_patients" id="number_of_patients" value="10" />
							<?php echo form_error('number_of_patients','<div class="alert alert-danger">','</div>'); ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="ok_2" style="height: 14px"> </label>
							<input id="ok_2" class="btn btn-primary form-control" type="submit" value="Вивести" name="submit" />
						</div>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
			<div class="table-responsive page_2">
				<table class="table table-striped table-bordered table-hover dataTable no-footer" id="">
					<thead>
						<tr>
							<th>№</th>
							<th>Користувач</th>
							<th>Сума (грн.)</th>
						</tr>
					</thead>
					<tbody id="page_2_tbody">
												
					</tbody>
				</table>
			</div>	
		</div>
	</div>
</div>