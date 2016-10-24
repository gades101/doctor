<script type="text/javascript" charset="utf-8">

function page_build(data){
	if($.fn.DataTable.isDataTable("#payments")) {$('#payments').DataTable().destroy();}
	var tab=$('#pay_tbody');
	data=JSON.parse(data);tab.html("");
	//head.append($('<tr></tr>').append($('<th></th>').text('Користувач')).append($('<th></th>').text('Кількість процедур')));
	data.forEach(function(item){
		var row=$('<tr></tr>').append($('<td></td>').text(item.payment_id))
		.append($('<td></td>').text(item.pay_date))
		.append($('<td></td>').text(item.patient_name))
		.append($('<td></td>').text(item.username))
		.append($('<td></td>').text(item.paid))
		.append($('<td></td>').text(item.pay_amount))
		.append($('<td></td>').text(item.apps_remaining))
		.append($('<td></td>').append($('<a></a>').attr('href',"<?= site_url('payment/edit')?>"+"/"+item.payment_id).addClass("btn btn-sm btn-primary square-btn-adjust").text('Редагувати')));
		tab.append(row);
	});
	$("#payments").dataTable({"pageLength": 50, "order":  []});
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
	$('.confirmDelete').click(function(){
			return confirm("Ви впевнені");
		});
	
} )
</script>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<span><a title="<?php echo $this->lang->line("add")." ".$this->lang->line("payment");?>" href="<?php echo base_url()."index.php/payment/insert/0/payment" ?>" class="btn btn-success square-btn-adjust"/> <?php echo $this->lang->line("add")." ".$this->lang->line("payment");?> </a></span>
			<!-- Advanced Tables -->
			<div class="panel panel-primary">
				<div class="panel-heading">
					<span>Рахунки</span>
				</div>
			</div>
			<!--End Advanced Tables -->
			<div class="panel panel-primary">
				<div class="panel-body">
					<?php echo form_open('payment/payment_list',array('id'=>'main_form','class'=>'ajax_form')); ?>
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
				</div>
			</div>	
			<div class="panel panel-primary">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover table-condensed dataTable no-footer" id="payments">
						<thead>
							<tr>
								<th>№</th>
								<th><?php echo $this->lang->line("date");?></th>
								<th><?php echo $this->lang->line("patient");?></th>
								<th><?php echo $this->lang->line("doctor");?></th>
								<th>Сплачено (грн.)</th>
								<th>Загальна сума (грн.)</th>
								<th>Залишилось занять</th>
								<th><?php echo $this->lang->line("edit");?></th>
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