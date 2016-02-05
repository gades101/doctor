<script type="text/javascript" charset="utf-8">
    $(function() {
        $( "#bill_from_date" ).datepicker({
            dateFormat: "dd-mm-yy",
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true
        });
    });

    $(function() {
        $( "#bill_to_date" ).datepicker({
            dateFormat: "dd-mm-yy",
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true
        });
    });
</script>
<?php

	$level = $_SESSION['category'];
?>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
						<?php echo $this->lang->line("report")." по Рахункам";?>
				</div>
				<div class="panel-body">
				<?php echo form_open('patient/bill_detail_report') ?>
					<div class="col-md-5">
						<div class="form-group">
							<label for="bill_from_date"><?php echo $this->lang->line("from_date");?></label>
							<input type="date" name="bill_from_date" id="bill_from_date" value="<?=$bill_from_date;?>" class="form-control"/>
							<?php echo form_error('bill_from_date','<div class="alert alert-danger">','</div>'); ?>
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-group">
							<label for="bill_to_date"><?php echo $this->lang->line("to_date")?></label>
							<input type="date" name="bill_to_date" id="bill_to_date" value="<?=$bill_to_date;?>" class="form-control" />
							<?php echo form_error('bill_to_date','<div class="alert alert-danger">','</div>'); ?>
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-group">
							<label for="doctor"><?php echo $this->lang->line("from_doctor");?></label>
							<select name="doctor" class="form-control" <?php if ($level == 'Doctor') { echo 'style = display:none;';} ?>>
								<option value="all" <?php if($selected_doctor == "all"){echo "selected='selected'";} ?>><?php echo $this->lang->line("all");?></option>
								<?php foreach ($doctors as $doctor) { ?>
									<option <?php if($selected_doctor == $doctor['userid']){echo "selected='selected'";} ?> value="<?php echo $doctor['userid'] ?>"><?= $doctor['name']; ?></option>
								<?php } ?>
							</select>
							<?php echo form_error('doctor','<div class="alert alert-danger">','</div>'); ?>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<button type="submit" name="submit" class="btn btn-primary" /><?php echo $this->lang->line("go");?></button>
						</div>
					</div>
					<input type="hidden" name="doctor_id" id="doctor_id" value="" />
				<?php echo form_close(); ?>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading">
						<?php echo $this->lang->line("report");?>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="bill_table">
							<thead>
								<tr>
									<th><?php echo $this->lang->line("date")." Рахунка";?></th>

									<th><?php echo $this->lang->line("name") . ' ' . $this->lang->line("doctor")."я";?></th>
									<th><?php echo $this->lang->line("id");?></th>
									<th><?php echo $this->lang->line("patient_name");?></th>
									<th><?php echo $this->lang->line("bill")." ".$this->lang->line("no");?></th>

									<th><?php echo $this->lang->line('amount').' на Рахунку';?></th>
									<th><?php echo $this->lang->line('payment_amount');?></th>
								</tr>
							</thead>
							<tbody>
							<?php $bill_amt=0; $pay_amt=0; ?>
							<?php if ($reports) { ?>
							<?php foreach ($reports as $report) { ?>
								<tr>
									<?php $bill_date = date('d-m-Y',strtotime($report['bill_date'])); ?>
									<td><?php echo $bill_date; ?></td>

									<td><?php echo $report['doctor_name']; ?></td>
									<td><?php echo $report['display_id']; ?></td>
                                    <td><?php echo $report['first_name'] . ' ' .$report['middle_name'] . ' ' . $report['last_name'] ?></td>
                                    <td><?php echo $report['bill_id']; ?></td>

                                    <td><?php echo currency_format($report['total_amount']);$bill_amt=$bill_amt+$report['total_amount'];if($currency_postfix) echo $currency_postfix['currency_postfix']; ?></td>
									<td><?php echo currency_format($report['pay_amount']);$pay_amt=$pay_amt+$report['pay_amount'];if($currency_postfix) echo $currency_postfix['currency_postfix']; ?></td>
                                </tr>
								<?php } ?>
								<script>
									$(window).load(function() {
										$('#bill_table').dataTable();
									});
								</script>

								<?php }else{ ?>
									<tr>
										<td colspan="7">По даним параметрам пошуку рахунків не знайдено</td>
									</tr>
								<?php } ?>
							</tbody>
							<thead>
								<tr>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th><?php echo currency_format($bill_amt); if($currency_postfix) echo $currency_postfix['currency_postfix']; ?></th>
									<th><?php echo currency_format($pay_amt); if($currency_postfix) echo $currency_postfix['currency_postfix']; ?></th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>