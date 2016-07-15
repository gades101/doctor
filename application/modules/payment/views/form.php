<?php
	if(isset($payment)){
		$payment_cheque_no = $payment->cheque_no;
		$payment_pay_amount = $payment->pay_amount;
		$pay_mode = $payment->pay_mode;
		$pay_date=$payment->pay_date;
		$curr_treatment_name=$curr_treatment['treatment'];
		$pay_amount=$payment->pay_amount;
		$paid=$payment->paid;
		$discount=$patient['discount'];
		$notes=$payment->notes;
	} else {
		$pay_mode = "cash";
		$pay_date=date("d-m-Y");
		$pay_amount=0;
		$paid=0;
		$discount="";
		$curr_treatment_name="";
		$notes="";
	}
	if (isset($curr_patient)){
		$patient_id=$curr_patient['patient_id'];
		$discount=$curr_patient['discount'];
		$patient['first_name'] =$curr_patient['first_name'];
		$patient['middle_name'] =$curr_patient['middle_name'];
		$patient['last_name'] =$curr_patient['last_name'];
	}
?>
<script>
	$(window).load(function(){
		var price;
		<?php if(isset($payment)){?>
			price=<?= $curr_treatment['price']; ?>;
		<?php } ?>
		<?php if (isset($patients)) { ?>

			var discounts=[<?php $i = 0;
			foreach ($discounts as $d) {
				if ($i > 0) { echo ",";}
				echo '{amount:"' . $d['amount'] . '",percent:"' . $d['percent'] . '"}';
				$i++;
			}
			?>];		
			var calc_discount= function(user_disc, user_amount){
				var curr_disc=user_disc;
				discounts.every(function(item){
					if(+item.percent>+user_disc){
						if(+item.amount<=+user_amount){
							curr_disc=item.percent;
							return false;
						}
						return true;
					}
					else return false;
				});
				return curr_disc;
			}

			var searcharrpatient=[<?php $i = 0;
			foreach ($patients as $p) {
				if ($i > 0) { echo ",";}
				echo '{value:"' . $p['first_name'] . " " . $p['middle_name'] . " " . $p['last_name'] . '",id:"' . $p['patient_id'] . '",discount:"' . $p['discount'] . '",all_paid:"' . $p['all_paid'] .'"}';
				$i++;
			}?>];
			$("#patient_name").autocomplete({
				autoFocus: true,
				source: searcharrpatient,
				minLength: 1,//search after one characters

				select: function(event,ui){
					//do something
					$("#discount").val(calc_discount(ui.item.discount,ui.item.all_paid));
					$("#patient_id").val(ui.item ? ui.item.id : '');
					var this_patient_id = ui.item.id, amount=price ? price*((100-ui.item.discount)/100) : '';
					$("#pay_amount").val(amount);
					$("#paid").val(amount);
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
		<?php } ?>	
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
		
		$('#pay_date').datetimepicker({
			timepicker:false,
			format: '<?=$def_dateformate;?>',
			scrollInput:false,
		});
		$( "#pay_mode" ).change(function() {
			if($( "#pay_mode" ).val() == 'cheque'){
				$( "#cheque_number" ).parent().parent().show();
			}else{
				$( "#cheque_number" ).parent().parent().hide();
			}
		});
		<?php if ($pay_mode !='cheque') { ?>
			$( "#cheque_number" ).parent().parent().hide();
		<?php } ?>
		
		$('#discount').on('input', function(){
			var tval=$(this).val(),re=new RegExp("^[0-9]{0,2}$");
			if (re.test(tval)){
				if(price){
					value=(100-tval)/100;
					$('#pay_amount').val((price*value).toFixed(2));
				}
			}
			else $('#discount').val("");
		})
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
			<?php if(isset($payment)) { ?>	
			<?php echo form_open('payment/edit/'.$payment->payment_id) ?>
			<?php  }else{ ?>
			<?php echo form_open('payment/insert/') ?>
			<?php  } ?>

			<input type="hidden" name="payment_type" value="bill_payment" />
			<input type="hidden" name="treatment_id" id="treatment_id" value="<?php if(isset($curr_treatment)){echo $curr_treatment['id']; } ?>"/>
			<input type="hidden" name="apps_remaining" id="apps_remaining" value="<?php if(isset($curr_treatment)){echo $curr_treatment['count']; } ?>"/>


			<div class="col-md-12">
				<label for="patient_name"><?php echo "ПІБ Пацієнта";?></label>
				<?php if(isset($patient_id) && $patient_id != NULL) { ?>
					<input type="hidden" name="patient_id" id="patient_id" value="<?= $patient_id; ?>" />
					<input name="patient_name" id="patient_name" type="text" disabled="disabled" class="form-control" value="<?= $patient['first_name'] . ' ' . $patient['middle_name'] . ' ' . $patient['last_name'];?>"/><br />
					<?php echo form_error('patient_id','<div class="alert alert-danger">','</div>'); ?>
				<?php }else{ ?>
					<input name="patient_name" id="patient_name" type="text" class="form-control" value=""/><br />
					<input type="hidden" name="patient_id" id="patient_id" value="" />
					<?php echo form_error('patient_id','<div class="alert alert-danger">','</div>'); ?>
				<?php } ?>
			</div>
			<div class="col-md-12">
				<label for="treatment"><?php echo $this->lang->line('treatment');?></label>
				<input name="treatment" id="treatment" type="text" class="form-control" value="<?= $curr_treatment_name; ?>"/><br />
			</div>
			<div class="col-md-12">
				<label for="paid">Сплачено</label>
				<input name="paid" id="paid" type="text" class="form-control" value="<?= $paid; ?>"/><br />
			</div>			
			<div class="col-md-12">
				<label for="pay_amount">Всього до сплати(грн.)</label>
				<input name="pay_amount" id="pay_amount" type="text" readonly="readonly" class="form-control" value="<?php echo $pay_amount; ?>"/><br />
				<input name="due_amount" id="due_amount" type="hidden" class="form-control" value="<?= $pay_amount; ?>"/>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<label for="title">Знижка %</label>
					<input type="text"  name="discount" id="discount" class="form-control" value="<?= $discount; ?>" />
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label for="title"><?php echo $this->lang->line('payment_date');?></label>
					<input type="text" name="pay_date" id="pay_date" class="form-control" value="<?=$pay_date;?>" />
				</div>
			</div>
			
			<div class="col-md-6">
				<div class="form-group">
					<label for="doctor"><?php echo $this->lang->line('doctor');?></label>
					<?php
						$doctor_detail = array();
						foreach ($doctors as $doctor_list){
							$doctor_detail[$doctor_list['userid']] = $doctor_list['name'];
						}
					?>
					<?php echo form_dropdown('userid', $doctor_detail, $selected_doctor_id,'class="form-control"'); ?>
				</div>
			</div>
			
			<div class="col-md-12">
				<div class="form-group">
					<label for="title"><?php echo $this->lang->line('payment_mode');?></label>
					<select name="pay_mode" id="pay_mode" class="form-control">
						<option value="cash" <?php if ($pay_mode =='cash') {echo "selected";} ?>>Готівка</option>
						<option value="cheque" <?php if ($pay_mode =='cheque') {echo "selected";} ?>>Безготівковий розрах.</option>
					</select>
				</div>
			</div>
			<?php  if(!isset($payment)){ ?>
			<div class="col-md-12">
				<div class="form-group">
					<label for="new_payment">
						 Закрити платіж досроково
						 <input type="checkbox" name="close_payment" id="close_payment" class=""/>
					</label>
				</div>
			</div>	
			<?php } ?>		
			<div class="col-md-12">
				<div class="form-group">
					<label for="title">Примітки</label>
					<input type="text" name="notes" id="notes" class="form-control" value="<?=$notes;?>" />
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<?php  if(!isset($payment)){ ?>
					<input class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('add');?>" name="submit" />
					<?php }else{ ?>
					<input class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('edit');?>" name="submit" />
					<a class="btn btn-danger" href="<?=base_url() . "index.php/payment/del/" . $payment->payment_id;?>">Видалити</a> 
					<?php } ?>
				</div>
			</div>
			<?php
				if(!isset($payment)){
			?>
			<div class="col-md-6">
				<div class="form-group">
					<a href="<?=site_url("appointment/index/all"); ?>" class="btn btn-primary" ><?php echo $this->lang->line('back');?></a>
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