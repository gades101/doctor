<!--  display on Click of Appointment page  -->
<script src="<?= base_url() ?>assets/js/fliplightbox.min.js"></script>
<script type="text/javascript">
<?php if (isset($appointment)){ ?>
	var img_path="<?= base_url() ?>patient_media/<?= $curr_patient['patient_id'] ?>/<?= $appointment['appointment_id'] ?>/foto/";
	var showimages=function (){
		$.ajax({
			url: "<?= base_url() ?>index.php/appointment/showmedia/<?= $curr_patient['patient_id'] ?>/<?= $appointment['appointment_id'] ?>/",
			type: 'POST',
			cache: false,
			dataType: 'json',
			processData: false, // Не обрабатываем файлы (Don't process the files)
			contentType: false, // Так jQuery скажет серверу что это строковой запрос
			success: function( respond, textStatus, jqXHR ){
				if( typeof respond.error === 'undefined' ){
					// Файлы успешно загружены, делаем что нибудь здесь
					// выведем пути к загруженным файлам в блок '.ajax-respond'
					var html = '',filelist=$('#filelist'),elem;
					$('#filelist').html('');
						respond.forEach(function(item){
							if (item!="." && item!=".."){
								elem=$('<a></a>').addClass('flipLightBox').attr('href',img_path+item).append($('<img>').attr({src:img_path+item, width:'120px',height:'120px',alt:'img'})).append('<span>'+item+'</span>');
								filelist.append(elem);
							}
						});
				$('body').flipLightBox();
				}
			},
		});
	}

<?php } ?>
$(window).load(function(){
	var price;
	function loadPayments(patient_id){
		if(patient_id){
			$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>index.php/payment/payment_ajax_info/"+patient_id,
				dataType: "json",
				success: function(data){
					paylist=$('#payment_id');
					paylist.html('');
					paylist.append($('<option>').val('0').text('Не обрано'));
					data.forEach(function(item){
						paylist.append($('<option>').val(item.payment_id).attr({'data-treatment': item.treatment_id,'data-paid': item.paid,'data-pay_amount': item.pay_amount}).text(item.treatment+" (залишилось зайнять: "+item.apps_remaining+")"));
					});
				}
			});
		}
	}
	

	$("#new_payment").click(function() {
			var pay=$('#new_payment');
			if (pay.prop('checked')==true){
				if($('#treatment_id').val() && $('#patient_id').val()){
					//$('#discount').prop('disabled',false);
					$('#payment_id').val(0).prop('disabled',true);
					$('#treatment').prop('readonly',false);
					$('#department_id').val($('#doctor_id').children('option:selected').data('department_id'));
					$('#add_money').val("");
					$('#pay_block').show();
				}
				else{
					pay.prop('checked',false);
					alert('Пацієнт та процедура мають бути обрані');
				}
			}
			else {
				$('#pay_block').hide();
				$('#add_money').val("");
				//$('#discount').prop('disabled',true).val('');
				$('#payment_id').prop('readonly',false);
			}
		});

//UPLOAD
	<?php if (isset($appointment)){ ?>
	showimages();
	var file_count,aj;
	if (window.File && window.FileReader && window.FileList && window.Blob) {
		document.getElementById('filesToUp').onchange = function(){
			var files = document.getElementById('filesToUp').files;
				 if(files) {document.getElementById('uploadStatus').innerHTML = 'Йде завантаження';}
			for(file_count = 0,aj=1; file_count < files.length; file_count++) {
				resizeAndUpload(files[file_count]);
			}
		};
	} else {
		alert('The File APIs are not fully supported in this browser.');
	}

	function resizeAndUpload(file) {
	var reader = new FileReader();
		reader.onloadend = function() {

		var tempImg = new Image();
		tempImg.src = reader.result;
		tempImg.onload = function() {

			var MAX_WIDTH = 1000;
			var MAX_HEIGHT = 1000;
			var tempW = tempImg.width;
			var tempH = tempImg.height;
			if (tempW > tempH) {
				if (tempW > MAX_WIDTH) {
				   tempH *= MAX_WIDTH / tempW;
				   tempW = MAX_WIDTH;
				}
			} else {
				if (tempH > MAX_HEIGHT) {
				   tempW *= MAX_HEIGHT / tempH;
				   tempH = MAX_HEIGHT;
				}
			}

			var canvas = document.createElement('canvas');
			canvas.width = tempW;
			canvas.height = tempH;
			var ctx = canvas.getContext("2d");
			ctx.drawImage(this, 0, 0, tempW, tempH);
			var dataURL = canvas.toDataURL("image/jpeg");
			var xhr = new XMLHttpRequest();
			//xhr.upload.addEventListener("progress", uploadProgress, false);
			xhr.addEventListener("load", uploadComplete, false);
			xhr.onreadystatechange = function(ev){
				//document.getElementById('progressNumber').innerHTML = aj;
			};
			xhr.open('POST', "<?=base_url()?>index.php/appointment/uploadfiles/<?= $curr_patient['patient_id'] ?>/<?= $appointment['appointment_id'] ?>", true);
			xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			var data = 'images=' + dataURL;
			xhr.send(data);

		  }
	   }
	   reader.readAsDataURL(file);
	}

      function uploadProgress(evt) {
        if (evt.lengthComputable) {
          var percentComplete = Math.round(evt.loaded * 100 / evt.total);
          var progress=document.createElement('div');
          var bar=document.getElementById('progressNumber');
          bar.appendChild(progress);
          progress.innerHTML = percentComplete.toString() + '%';
        }
        else {
          document.getElementById('progressNumber').innerHTML = 'unable to compute';
        }
      }

      function uploadComplete(evt) {
			if(aj==file_count){
         document.getElementById('uploadStatus').innerHTML = 'Завантаження завершено';
		 showimages();
			}
        aj++;
		}
	
	<?php if(isset($saved)) { echo 'alert("Збережено");';} ?>
		


<?php } ?>
//END UPLOAD
		var searchtreatment=[<?php $i = 0;
		foreach ($treatments as $treatment) {
			if ($i > 0) { echo ",";}
			echo '{value:"' . $treatment['treatment'] . '",id:"' . $treatment['id'] . '",price:"' . $treatment['price'] . '"}';
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
				var amount=ui.item ? ui.item.price*((100-$("#discount").val())/100) : '';
				$("#pay_amount").val(amount);
				$("#add_money").val(amount);
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

		var discounts=[<?php $i = 0;
		foreach ($discounts as $discount) {
			if ($i > 0) { echo ",";}
			echo '{amount:"' . $discount['amount'] . '",percent:"' . $discount['percent'] . '"}';
			$i++;
		}
		?>];		
		
		var calc_discount= function(user_disc, user_amount){
			var curr_disc=user_disc;
			discounts.every(function(discount){
				if(+discount.percent>+user_disc){
					if(+discount.amount<=+user_amount){
						curr_disc=discount.percent;
						return false;
					}
					return true;
				}
				else return false;
			});
			return curr_disc;
		}
		
		var searcharrpatient=[<?php $i = 0;
		foreach ($patients as $patient) {
			if ($i > 0) { echo ",";}
			echo '{value:"' . $patient['first_name'] . " " . $patient['middle_name'] . " " . $patient['last_name'] . '",id:"' . $patient['patient_id'] . '",display:"' . $patient['display_id'] . '",num:"' . $patient['phone_number'] . '",discount:"' . $patient['discount'] . '",all_paid:"' . $patient['all_paid'] .'"}';
			$i++;
		}
		?>];

		$("#patient_name").autocomplete({
			autoFocus: true,
			source: searcharrpatient,
			minLength: 1,//search after one characters

			select: function(event,ui){
				//do something
				$("#patient_id").val(ui.item ? ui.item.id : '');
				$("#phone_number").val(ui.item ? ui.item.num : '');
				$("#display_id").val(ui.item ? ui.item.display : '');
				$("#discount").val(calc_discount(ui.item.discount,ui.item.all_paid));
				var amount=price ? price*((100-ui.item.discount)/100) : '';
				$("#pay_amount").val(amount);
				$("#paid").val(amount);
				loadPayments(ui.item.id);

			},
			change: function(event, ui) {
				 if (ui.item == null) {
					$("#patient_id").val('');
					$("#phone_number").val('');
					$("#display_id").val('');
					$("#patient_name").val('');
					}
			},
			response: function(event, ui) {
				if (ui.content.length === 0)
				{

					$("#patient_id").val('');
					$("#phone_number").val('');
					$("#display_id").val('');
					$("#patient_name").val('');
				}
			}
		});
		var searcharrdispname=[<?php $i = 0;
		foreach ($patients as $patient) {
			if ($i > 0) {
				echo ",";
			}
				echo '{value:"' . $patient['display_id'] . '",id:"' . $patient['patient_id'] . '",num:"' . $patient['phone_number'] . '",patient:"' . $patient['first_name'] . " " . $patient['middle_name'] . " " . $patient['last_name'] . '"}';
			$i++;
		}?>];
		$("#display_id").autocomplete({
			autoFocus: true,
			source: searcharrdispname,
			minLength: 1,//search after one characters
			select: function(event,ui)
			{
				//do something
			   $("#patient_id").val(ui.item ? ui.item.id : '');
			   $("#patient_name").val(ui.item ? ui.item.patient : '');
			   $("#phone_number").val(ui.item ? ui.item.num : '');
			},
			change: function(event, ui)
			{
				if (ui.item == null) {
					$("#patient_id").val('');
					$("#phone_number").val('');
					$("#display_id").val('');
					$("#patient_name").val('');
				}
			},
			response: function(event, ui)
			{
				if (ui.content.length === 0)
				{
					$("#patient_id").val('');
					$("#phone_number").val('');
					$("#display_id").val('');
					$("#patient_name").val('');
				}
			}
		});
		var searcharrmob=[<?php $i = 0;
		foreach ($patients as $patient) {
			if ($i > 0) {
				echo ",";
			}
				echo '{value:"' . $patient['phone_number'] . '",id:"' . $patient['patient_id'] . '",display:"' . $patient['display_id'] . '",patient:"' . $patient['first_name'] . " " . $patient['middle_name'] . " " . $patient['last_name'] . '"}';
			$i++;
		}?>];
		$("#phone_number").autocomplete({
			autoFocus: true,
			source: searcharrmob,
			minLength: 1,//search after one characters
			select: function(event,ui){
				//do something
				$("#patient_id").val(ui.item ? ui.item.id : '');
				$("#patient_name").val(ui.item ? ui.item.patient : '');
				$("#display_id").val(ui.item ? ui.item.display : '');
			},
			change: function(event, ui) {
				if (ui.item == null) {
					$("#patient_id").val('');
					$("#phone_number").val('');
					$("#display_id").val('');
					$("#patient_name").val('');
				}
			},
			response: function(event, ui) {
				if (ui.content.length === 0)
				{
					$("#patient_id").val('');
					$("#phone_number").val('');
					$("#display_id").val('');
					$("#patient_name").val('');
				}
			}
		});
		$('#appointment_date').datetimepicker({
			timepicker:false,
			format: '<?=$def_dateformate; ?>',
			scrollInput:false,
			
		});
		$('#start_time').datetimepicker({
			datepicker:false,
			step:<?=$time_interval*60;?>,
			format: '<?=$def_timeformate; ?>',
			formatTime:'<?=$def_timeformate; ?>',
			scrollInput:false,
		});
		$('#end_time').datetimepicker({
			datepicker:false,
			step:<?=$time_interval*60;?>,
			format: '<?=$def_timeformate; ?>',
			formatTime:'<?=$def_timeformate; ?>',
			scrollInput:false,
		});	
		
		$('#payment_id').on('change', function(){
			if(this.value!=0){
				var opt=this.children[this.selectedIndex];
				$('#treatment_id').val(opt.dataset.treatment);
				$('#paid').val(opt.dataset.paid);
				$('#pay_amount').val(opt.dataset.pay_amount);
				$('#treatment').val(opt.textContent).prop('readonly',true);
				$('#add_money').val("");
				$('#pay_block').show();
			}
			else{
				$('#add_money').val("");
				$('#paid').val("");
				$('#pay_amount').val("");
				$('#treatment_id').val("");
				$('#treatment').val("").prop('readonly',false);
				$('#pay_block').hide();
			}
		});
		$('#discount').on('input', function(){
			var tval=$(this).val(),re=new RegExp("^[0-9]{0,2}$");
			if (re.test(tval)){
				if(price){
					value=(100-tval)/100;
					$('#pay_amount').val((price*value).toFixed(2));
					$('#paid').val((price*value).toFixed(2));
				}
			}
			else {$('#discount').val("");
				$('#pay_amount').val(price.toFixed(2));
				$('#paid').val(price.toFixed(2));
			}
		});
		$('#doctor_id').on('change', function(){
			$('#department_id').val($(this).children('option:selected').data('department_id'));
		});	
});

function openReason(onof) {
	if (onof==1){
	   $('#cancel_details').show();
	   $('form').first().hide();
		$('#button_panel').hide();
	}
	if (onof==0){
	   $('#cancel_details').hide();
	   $('form').first().show();
		$('#button_panel').show();
	}
}
</script>
<?php
	if(isset($doctor)){
		$doctor_name = $doctor['name'];
		$doctor_id = $doctor['userid'];
	}
	if(isset($appointment)){
		//Edit Appointment
		$header = $this->lang->line("edit")." ".$this->lang->line("appointment");
		$patient_name = $patient['first_name'] . " " . $patient['middle_name'] . " " . $patient['last_name'];
		$title = $appointment['title'];
		$appointment_id = $appointment['appointment_id'];
		$start_time = $appointment['start_time'];
		$end_time = $appointment['end_time'];
		$appointment_date = $appointment['appointment_date'];
		$status = $appointment['status'];
		$appointment_id = $appointment['appointment_id'];
		$app_note=$appointment['app_note'];
		$curr_treatment_name=$curr_treatment['treatment'];
		$curr_payment_id=$appointment['payment_id'];
		$pay_amount=$curr_treatment['price']*(100 - $curr_patient['discount'])/100;
		if($status=='Cancel'){$appointment_details=$appointment['appointment_details'];}
		else {$appointment_details="";}
	}else{
		//Add Appointment
		$header = $this->lang->line("new")." ".$this->lang->line("appointment");
		$patient_name = "";
		$title = "";
		$time_interval =  $time_interval*60;
		$start_time = date($def_timeformate, strtotime($appointment_time));
		$end_time = date($def_timeformate, strtotime("+$time_interval minutes", strtotime($appointment_time)));
		$app_note="";
		$curr_treatment_name="";
		$appointment_date = $appointment_date;
		$pay_amount="";
		$status = "Appointments";
		$curr_payment_id=0;
	}
	if(isset($curr_patient)){
		$patient_id = $curr_patient['patient_id'];
	}else{
		$patient_id = 0;
	}
?>

<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<?=$header;?>
				</div>
				<div class="panel-body">
					<?php $timezone = $this->settings_model->get_time_zone();
						if (function_exists('date_default_timezone_set'))
							date_default_timezone_set($timezone);
						$appointment_date = date($def_dateformate,strtotime($appointment_date)); ?>
					<?php if(isset($appointment)){ ?>
					<?php echo form_open('appointment/edit_appointment/'.$appointment['appointment_id']) ?>
					<?php }else{ ?>
					<?php echo form_open('appointment/add/'.$year.'/'.$month.'/'.$day.'/'.$hour.'/'.$min.'/'.$status.'/'.$patient_id) ?>
					<?php } ?>
					<input type="hidden" name="appointment_id" value="<?= $appointment_id; ?>"/>
					<input type="hidden" name="patient_id" id="patient_id" value="<?php if(isset($curr_patient)){echo $curr_patient['patient_id']; } ?>"/>
					<input type="hidden" name="treatment_id" id="treatment_id" value="<?php if(isset($curr_treatment)){echo $curr_treatment['id']; } ?>"/>
					<input type="hidden" name="payment_id_orig" id="payment_id_orig" value="<?= $curr_payment_id; ?>"/>
					<input type="hidden" name="department_id" id="department_id" value=""/>

					<div class="panel panel-success">
						<div class="panel-heading">
							<?= $this->lang->line('search')." ".$this->lang->line('patient').'а';?>
						</div>
						<div class="panel-body">
							<input type="hidden" name="title" id="title" value="<?= $title; ?>" class="form-control"/>
							<div class="col-md-3">
								<label for="display_id"><?php echo $this->lang->line('patient_id');?></label>
								<input type="text" name="display_id" id="display_id" value="<?php if(isset($curr_patient)){echo $curr_patient['display_id']; } ?>" class="form-control"/>
							</div>
							<div class="col-md-3">
								<label for="patient"><?php echo $this->lang->line('patient');?></label>
								<input type="text" name="patient_name" id="patient_name" value="<?php if(isset($curr_patient)){echo $curr_patient['first_name']." " .$curr_patient['middle_name']." " .$curr_patient['last_name']; } ?>" class="form-control"/>
								<?php echo form_error('patient_id','<div class="alert alert-danger">','</div>'); ?>
							</div>

							<div class="col-md-3">
								<label for="phone"><?php echo $this->lang->line('mobile');?></label>
								<input type="text" name="phone_number" id="phone_number" value="<?php if(isset($curr_patient)){echo $curr_patient['phone_number']; } ?>" class="form-control"/>
							</div>
						</div>
					</div>

					<div class="col-md-6 form-group">
						<label for="user_id"><?php echo $this->lang->line('doctor');?></label>
						<select id='doctor_id' name='doctor_id' class="form-control">
							<?php foreach($doctors as $doctor){ ?>									
								<option value="<?php echo $doctor['userid']; ?>"  data-department_id="<?= $doctor['department_id']; ?>" <?php if($doctor['userid']==$selected_doctor_id) echo 'selected=true';?> /><?= $doctor['name']; ?></option>				
							<?php } ?>
						</select>
					</div>

					<?php if(!isset($appointment)) {?>
					<div class="col-md-4">
						<div class="form-group">
							<label for="appointment_date">Тип прийому</label>
							<?php echo form_dropdown('status', array('Appointments'=>'Прийом','Consultation'=>'Консультація'), 'Appointments','class="form-control", id="app_status"'); ?>
						</div>
					</div>
					<?php }?>
					<div class="col-md-2">
						<div class="form-group">
							<label for="appointment_date"><?php echo $this->lang->line('date');?></label>
							<input type="text" name="appointment_date" id="appointment_date" value="<?= $appointment_date; ?>" class="form-control"/>
							<?php echo form_error('appointment_date','<div class="alert alert-danger">','</div>'); ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="start_time"><?php echo $this->lang->line('start_time');?></label>
							<input type="text" name="start_time" id="start_time" value="<?=date($def_timeformate,strtotime($start_time)); ?>" class="form-control"/>
							<?php echo form_error('start_time','<div class="alert alert-danger">','</div>'); ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="end_time"><?=$this->lang->line('end_time');?></label>
							<input type="text" name="end_time" id="end_time" value="<?= date($def_timeformate,strtotime($end_time)); ?>" class="form-control"/>
							<?php echo form_error('end_time','<div class="alert alert-danger">','</div>'); ?>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="treatment">Процедура</label>
							<input type="text" name="treatment" id="treatment" class="form-control" value="<?=$curr_treatment_name;?>" <?php if($curr_payment_id!=0) echo "readonly=true"; ?> />
							<?php echo form_error('treatment','<div class="alert alert-danger">','</div>'); ?>
						</div>
					</div>
					
					<div class="col-md-12">
						<div class="form-group">
							<label for="payment_id">Рахунки Пацієнта</label></br>
							<select id='payment_id' name='payment_id' class="form-control">
								<option value='0'>Не обрано</option>
								<?php if(isset($curr_payments)){
									foreach($curr_payments as $payment){ ?>
										<option value="<?php echo $payment['payment_id']; ?>"  data-treatment="<?= $payment['treatment_id']; ?>" data-paid="<?= $payment['paid']; ?>" data-pay_amount="<?= $payment['pay_amount']; ?>" <?php if($payment['payment_id']==$curr_payment_id){echo 'selected=true';} ?> /><?= $payment['treatment'].' (залишилось зайнять: '.$payment['apps_remaining'].')'; ?></option>				
								<?php } } ?>
							</select>
						</div>
					</div>
	
					<div class="col-md-12" id="new_payment_div">
						<div class="form-group">
							<label for="new_payment">
								 Створити Рахунок
								 <input type="checkbox" name="new_payment" id="new_payment" class=""/>
							</label>
						</div>
					</div>
					</br>
					<div id="pay_block" class="panel-body" style="display:none">
							<div class="col-md-3">
								<label for="add_money">Додати оплату (грн.)</label>
								<input type="text" name="add_money" id="add_money" class="form-control"/>
								<?php echo form_error('add_money','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="col-md-3">
								<label for="paid">Сплачено (грн.)</label>
								<input type="text" name="paid" id="paid" value="<?= $pay_amount; ?>" readonly="readonly" class="form-control"/>
								<?php echo form_error('paid','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="col-md-3">
								<label for="pay_amount">Загальна сума (грн.)</label>
								<input type="text" name="pay_amount" id="pay_amount" value="<?= $pay_amount; ?>" readonly="readonly" class="form-control"/>
								<?php echo form_error('pay_amount','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="col-md-3">
								<label for="discount">Знижка %</label>
								<input type="text" name="discount" id="discount" value="<?php if(isset($curr_patient)){echo $curr_patient['discount']; } ?>" class="form-control"/>
							</div>
					</div>
					
					<br/>
					<?php if (isset($appointment)){?>
					<div class="col-md-12" id='mform_details'  <?php if ($status!='Cancel'){echo "style='display: none'";}?> >
						<div class="form-group">
							<label for="details_text">Причина скасування</label>
							<textarea name="appointment_details" id="details_text" class="form-control"/><?=$appointment_details ?></textarea>
						</div>
					</div>
					<?php }?>
					<div class="col-md-12">
						<div class="form-group">
							<label for="details_text">Примітки</label>
							<textarea name="app_note" id="app_note" class="form-control"/><?=$app_note ?></textarea>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<button class="btn btn-primary" type="submit" name="submit" /><?php echo $this->lang->line('save');?></button>
							<a class="btn btn-primary" href="<?=base_url() . "index.php/appointment/index/".$dep.'/'.$date_str;?>"><?=$this->lang->line('back_to_app');?></a>
						</div>
					</div>

					<?php echo form_close() ?>
					</br>
					<?php if (isset($appointment)){?>
					<div id="cancel_details" style="display:none">
						<?php echo form_open("appointment/change_status/" . $appointment_id . "/Cancel/". $curr_payment_id ) ?>
							<div class="col-md-12">
								<div class="form-group">
									<label for="appointment_details">Причина скасування</label>
									<input type="text" name="appointment_details" value="" class="form-control"/></br>
									<button class="btn btn-primary" type="submit" /><?php echo $this->lang->line('save');?></button>
									<span class="btn btn-primary" onclick=openReason(0) id='back_to_edit'>Назад</span>

								</div>
							</div>
						<?php echo form_close() ?>
						</br>
					</div>
					<?php }?>

					<div class="col-md-12" id="button_panel">
						<div class="form-group">
					<?php if(isset($appointment)){ ?>
						<a class="btn btn-primary" href="<?php echo site_url("patient/edit/" . $curr_patient['patient_id']."/patient"); ?>">Редагувати пацієнта</a>
						<?php if ($status != 'Appointments') { ?>
							<a class="btn btn-primary" href="<?=base_url() . "index.php/appointment/change_status/" . $appointment_id . "/Appointments";?>" ><?php echo $this->lang->line('appointment');?></a>
						<?php } ?>
						<?php if ($status != 'Cancel') { ?>
							<span class="btn btn-primary" onclick=openReason(1)><?php echo $this->lang->line('cancel')." ".$this->lang->line('appointment');?></span>
						<?php } ?>		
						<?php if ($status != 'Consultation') { ?>
							<a class="btn btn-primary" href="<?=base_url() . "index.php/appointment/change_status/" . $appointment_id . "/Consultation";?>">Консультація</a>
						<?php } ?>
						<!--<?php if (!isset($doctor)) { ?>
							<a class="btn btn-danger" href="<?=base_url() . "index.php/appointment/del/" . $appointment_id;?>">Видалити запис</a>
						<?php } ?>-->
					<?php } ?>

						</div>
					</div>

					<?php if(isset($appointment)) {?>
						<div class="col-md-6">
							<div class="wrapper">
								<label for="filesToUp">Завантажити фото</label>
								<input type="file" id="filesToUp" class="fileUpload" multiple="multiple" accept="image/*" style="display:inline">
								<div class="ajax-respond"></div>
								<div id="filelist"></div>
								<div id="uploadStatus" style="font-size:30px"></div>
								<div id="progressNumber"></div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
