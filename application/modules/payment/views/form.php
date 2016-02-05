<?php
	if(isset($payment)){
		$payment_cheque_no = $payment->cheque_no;
		$payment_pay_amount = $payment->pay_amount;
		$payment_pay_mode = $payment->pay_mode;
	} else {
		$payment_cheque_no = "";
		$payment_pay_amount = 0;
		$payment_pay_mode = "";
	}
?>
<script>
	$(window).load(function(){
		var searcharrpatient=[<?php $i = 0;
		foreach ($patients as $p) {
			if ($i > 0) { echo ",";}
			echo '{value:"' . $p['first_name'] . " " . $p['middle_name'] . " " . $p['last_name'] . '",id:"' . $p['patient_id'] . '",display:"' . $p['display_id'] . '",num:"' . $p['phone_number'] . '"}';
			$i++;
		}?>];
		$("#patient_name").autocomplete({
			autoFocus: true,
			source: searcharrpatient,
			minLength: 1,//search after one characters

			select: function(event,ui){
				//do something
				$("#patient_id").val(ui.item ? ui.item.id : '');
				var this_patient_id = ui.item.id;
				var billArray = [
				<?php foreach($bills as $bill){
					echo '["'.$bill['bill_id'].'", "'.$bill['patient_id'].'","'.$bill['due_amount'].'"],';
				} ?>
				];
				var bill_id;
				var patient_id;
				var due_amount;
				$("#bill_id").empty();

				$.each(billArray, function(i,val) {
					$.each(val, function(index,value) {
						if(index == 0){	//bill id
							bill_id = value;
						}
						if(index == 1){	//patient id
							patient_id = value;
						}
						if(index == 2){	//due amount
							due_amount = value;
						}

					})
					if(this_patient_id == patient_id){
						$("#bill_id").append($("<option></option>").attr("value", bill_id).text(bill_id));
						$("#balance_amount").val(due_amount);
					}
				});
			},
			change: function(event, ui) {
				 if (ui.item == null) {
					$("#patient_id").val('');
					$("#patient_name").val('');
					}
			},
			response: function(event, ui) {
				if (ui.content.length === 0)
				{
					$("#patient_id").val('');
					$("#patient_name").val('');
				}
			}
		});
		$('#payment_date').datetimepicker({
			timepicker:false,
			format: '<?=$def_dateformate;?>',
		});
		$( "#pay_mode" ).change(function() {
			if($( "#pay_mode" ).val() == 'cheque'){
				$( "#cheque_number" ).parent().parent().show();
			}else{
				$( "#cheque_number" ).parent().parent().hide();
			}
		});
		<?php if ($payment_pay_mode !='cheque') { ?>
		$( "#cheque_number" ).parent().parent().hide();
		<?php } ?>
	});
</script>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			<div class="panel-heading">
				Форма оплати
			</div>
			<div class="panel-body">
			<?php if(!isset($payment)){ ?>
			<?php echo form_open('payment/insert/'.$bill_id) ?>
			<?php  }else{ ?>
			<?php echo form_open('payment/edit/'.$payment_id) ?>
			<?php  } ?>
			<?php print_r($this->input->post('visit_id'));
				if(isset($payment)){
					$payment_date = date($def_dateformate,strtotime($payment->pay_date));
				}else{
					$payment_date = date($def_dateformate);
				}
			?>
			<input type="hidden" name="payment_type" value="bill_payment" />
			<input type="hidden" name="visit_id" value="<?=$visit_id;?>" />
			<input type="hidden" name="called_from" value="<?=$called_from;?>" />
			<div class="col-md-12">
				<label for="patient_name"><?php echo "ПІБ Пацієнта";?></label>
				<?php if(isset($patient_id) && $patient_id != NULL) { ?>
					<input type="hidden" name="patient_id" id="patient_id" value="<?= $patient_id; ?>" />
					<input name="patient_name" id="patient_name" type="text" disabled="disabled" class="form-control" value="<?= $patient['first_name'] . ' ' . $patient['middle_name'] . ' ' . $patient['last_name'];?>"/><br />
					<?php echo form_error('patient_id','<div class="alert alert-danger">','</div>'); ?>
				<?php }else{ ?>
					<input name="patient_name" id="patient_name" type="text" class="form-control" value=""/><br />
					<input type="hidden" name="patient_id" id="patient_id" value="<?= $patient_id; ?>" />
					<?php echo form_error('patient_id','<div class="alert alert-danger">','</div>'); ?>
				<?php } ?>
			</div>
			<div class="col-md-12">
				<label for="bill_id"><?php echo $this->lang->line('bill')." (id)";?></label>
				<?php if(isset($bill_id) && $bill_id != 0) { ?>
					<input name="bill_id" id="bill_id" type="text" readonly="readonly" class="form-control" value="<?= $bill_id; ?>"/><br />
				<?php }else{ ?>
					<select name="bill_id" id="bill_id" class="form-control" >
					</select>
				<?php } ?>
				<?php echo form_error('bill_id','<div class="alert alert-danger">','</div>'); ?>
			</div>
			<div class="col-md-12">
				<label for="balance_amount"><?php echo $this->lang->line('balance_amount');?></label>
				<input name="balance_amount" id="balance_amount" type="text" readonly="readonly" class="form-control" value="<?php echo currency_format($due_amount);if($currency_postfix) echo $currency_postfix['currency_postfix']; ?>"/><br />
				<input name="due_amount" id="due_amount" type="hidden" class="form-control" value="<?php echo $due_amount; ?>"/>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label for="title"><?php echo $this->lang->line('payment_amount');?></label>
					<input type="text" name="payment_amount" id="payment_amount" class="form-control" value="<?=$payment_pay_amount;?>" />
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label for="title"><?php echo $this->lang->line('payment_date');?></label>
					<input type="text" name="payment_date" id="payment_date" class="form-control" value="<?=$payment_date;?>" />
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label for="title"><?php echo $this->lang->line('payment_mode');?></label>
					<select name="pay_mode" id="pay_mode" class="form-control">
						<option value="cash" <?php if ($payment_pay_mode =='cash') {echo "selected";} ?>>Готівка</option>
						<option value="cheque" <?php if ($payment_pay_mode =='cheque') {echo "selected";} ?>>Чек</option>
					</select>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label for="title"><?php echo $this->lang->line('cheque_number');?></label>
					<input type="text" name="cheque_number" id="cheque_number" class="form-control" value="<?=$payment_cheque_no;?>" />
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<?php  if(!isset($payment)){ ?>
					<input class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('add');?>" name="submit" />
					<?php }else{ ?>
					<input class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('edit');?>" name="submit" />
					<?php } ?>
				</div>
			</div>
			<?php
				if(!isset($payment)){
			?>
			<div class="col-md-6">
				<div class="form-group">
					<a href="<?=site_url("appointment/index/all"); ?>" class="btn btn-primary" ><?php echo $this->lang->line('back_to_app');?></a>
				</div>
			</div>
			<?php
				}
			?>
			<?php echo form_close(); ?>
			</div>
			</div>
		</div>
	</div>
</div>