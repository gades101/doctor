<script type="text/javascript" charset="utf-8">
$( window ).load(function() {
	var mindate=false;
	<?php if($_SESSION["category"] == 'Секретар'){?>
		$('.inp_date').prop("readonly", true); mindate=new Date();//mindate.setMonth(mindate.getMonth()-1);
		mindate.setDate(mindate.getDate()-mindate.getDay()+1);
	<?php } ?>
	$('.confirmDelete').click(function(){
			return confirm("Ви впевнені");
	});
	$('#start_date').datetimepicker({
		timepicker:true,
		format: 'd-m-Y H:i',
		scrollInput:false,
		minDate: mindate,

	});	
	$('#end_date').datetimepicker({
		timepicker:true,
		format: 'd-m-Y H:i',
		scrollInput:false,
		minDate: mindate,
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
					Звіт по платежам/витратам
				</div>
				<div class="panel-body">
					<?php echo form_open('payment/payment_report'); ?>
					<div class="col-md-12">
						<div class="col-md-4">					
							<label for="start_date"><?php echo $this->lang->line("from_date");?></label>
						</div>
						<div class="col-md-4">					
							<label for="end_date"><?php echo $this->lang->line("to_date");?></label>
						</div>			
					</div>
					<div class="col-md-12">					
						<div class="col-md-4">
							<div class="form-group">
								<input type="text" name="start_date" class="inp_date" id="start_date" value="<?=$start_date;?>" class="form-control"/>
								<?php echo form_error('start_date','<div class="alert alert-danger">','</div>'); ?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<input type="text" name="end_date" class="inp_date" id="end_date" value="<?=$end_date;?>" class="form-control" />
								<?php echo form_error('end_date','<div class="alert alert-danger">','</div>'); ?>
							</div>
						</div>
						<input class="btn btn-primary" type="submit" value="Вивести" name="submit" />
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>			
			<?php if(isset($report)){?>
			<div class="panel panel-primary">
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover dataTable no-footer" id="pay_report">
							<thead>
								<tr>
									<th>Відділення</th>
									<th>Оплати (грн.)</th>
									<th>Витрати (грн.)</th>
									<th>Різниця (грн.)</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									$total_pay=0.00;$total_exp=0.00;
									foreach($report as $elem):?>
									<tr>
										<td><?=$elem['department_name'];?></td>
										<td><?php echo $elem['pay_summ']+0; $total_pay+=$elem['pay_summ']; ?></td>
										<td><?php echo $elem['exp_summ']+0; $total_exp+=$elem['exp_summ'];?></td>
										<td><?= $elem['pay_summ']-$elem['exp_summ'];?></td>	
									</tr>
								<?php endforeach?>	
									<tr>
										<td>Усі відділення</td>	
										<td><?=$total_pay;?></td>
										<td><?=$total_exp;?></td>		
										<td><?=$total_pay-$total_exp;?></td>																		
									</tr>												
							</tbody>
						</table>
					</div>	
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>