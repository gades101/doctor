<script type="text/javascript" charset="utf-8">

$( window ).load(function() {
	$('.confirmDelete').click(function(){
			return confirm("Ви впевнені");
	});
	$('#report_from_date').datetimepicker({
		timepicker:false,
		format: 'd-m-Y',
		scrollInput:false,
	});	
	$('#report_to_date').datetimepicker({
		timepicker:false,
		format: 'd-m-Y',
		scrollInput:false,
	});	
} )
</script>
<?php
	$start_date = date($def_dateformate);
	$end_date = date($def_dateformate, mktime(0,0,0,date("m"),date("d")+1,date("Y")));
?>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					Звіт по платежам
				</div>
				<div class="panel-body">
					<?php echo form_open('payment/payment_report'); ?>
					<div class="col-md-12">
						<div class="col-md-4">					
							<label for="report_from_date"><?php echo $this->lang->line("from_date");?></label>
						</div>
						<div class="col-md-4">					
							<label for="report_to_date"><?php echo $this->lang->line("to_date")?></label>
						</div>			
					</div>
					<div class="col-md-12">					
						<div class="col-md-4">
							<div class="form-group">
								<input type="date" name="report_from_date" id="report_from_date" value="<?=$start_date;?>" class="form-control"/>
								<?php echo form_error('report_from_date','<div class="alert alert-danger">','</div>'); ?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<input type="date" name="report_to_date" id="report_to_date" value="<?=$end_date;?>" class="form-control" />
								<?php echo form_error('report_to_date','<div class="alert alert-danger">','</div>'); ?>
							</div>
						</div>
						<input class="btn btn-primary" type="submit" value="Вивести" name="submit" />
					</div>
					<?php echo form_close(); ?>
					<?php if(isset($report)){?>
						<div class="col-md-12">
							<strong>Оплати: <?=$report['total_payment'];?></strong>
						</div>
						<div class="col-md-12">
							<strong>Витрати: <?=$report['total_expense'];?></strong>
						</div>
						<div class="col-md-12">
							<strong>Залишок: <?=$report['total_payment']-$report['total_expense'];?></strong>
						</div>									
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>