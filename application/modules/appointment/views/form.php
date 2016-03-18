<!--display on Click of Appointment page -->
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
			//else{console.log('ОШИБКИ ОТВЕТА сервера: ' + respond.error );}
		},
		/*error: function( jqXHR, textStatus, errorThrown ){
			console.log('ОШИБКИ AJAX запроса: ' + textStatus );
		}*/
	});
}
<?php } ?>
$(window).load(function(){

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
		//console.log(dataURL);
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
         // console.log('progress: '+file_count);
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
<?php } ?>
//END UPLOAD

		var searchtreatment=[<?php $i = 0;
		foreach ($treatments as $treatment) {
			if ($i > 0) { echo ",";}
			echo '{value:"' . $treatment['treatment'] . '",id:"' . $treatment['id'] . '"}';
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


		var searcharrpatient=[<?php $i = 0;
		foreach ($patients as $patient) {
			if ($i > 0) { echo ",";}

			echo '{value:"' . $patient['first_name'] . " " . $patient['middle_name'] . " " . $patient['last_name'] . '",id:"' . $patient['patient_id'] . '",display:"' . $patient['display_id'] . '",num:"' . $patient['phone_number'] . '"}';
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
		});
		$('#start_time').datetimepicker({
			datepicker:false,
			step:<?=$time_interval*60;?>,
			format: '<?=$def_timeformate; ?>',
			formatTime:'<?=$def_timeformate; ?>'
		});
		$('#end_time').datetimepicker({
			datepicker:false,
			step:<?=$time_interval*60;?>,
			format: '<?=$def_timeformate; ?>',
			formatTime:'<?=$def_timeformate; ?>'
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
		$status = "Appointments";
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
			<?php if(!isset($appointment) && !isset($curr_patient)){ ?>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<?php echo $this->lang->line('add')." ".$this->lang->line('patient').'а';?>
				</div>
				<div class="panel-body">
					<?php $s_time = date('H:i',strtotime($start_time));?>
					<?php $time = explode(":", $s_time); ?>
					<?php echo form_open('appointment/insert_patient_add_appointment' . "/" . $time[0] . "/" . $time[1] . "/" . $appointment_date . "/" . $status . "/" . $selected_doctor_id."/0/") ?>
						<div class="col-md-3">
							<div class="form-group">
								<label for="first_name"><?php echo $this->lang->line('first');?></label>
								<input type="text" name="first_name" value="" class="form-control"/>
								<?php echo form_error('first_name','<div class="alert alert-danger">','</div>'); ?>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="middle_name"><?php echo $this->lang->line('middle');?></label>
								<input type="text" name="middle_name" value=""  class="form-control"/>
								<?php echo form_error('middle_name','<div class="alert alert-danger">','</div>'); ?>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="last_name"><?php echo $this->lang->line('last');?></label>
								<input type="text" name="last_name" value="" class="form-control"/>
								<?php echo form_error('last_name','<div class="alert alert-danger">','</div>'); ?>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="reference_by"><?php echo $this->lang->line('reference_by')?></label>
								<input type="text" name="reference_by" value="" class="form-control"/>
								<?php echo form_error('reference_by','<div class="alert alert-danger">','</div>'); ?>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<button class="btn btn-primary" type="submit" name="submit" /><?php echo $this->lang->line('add')." ".$this->lang->line('patient')."а";?></button>
							</div>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
			<?php } ?>
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
					<div class="col-md-6">
						<div class="form-group">
							<label for="doctor"><?php echo $this->lang->line('doctor');?></label>
							<?php
								$doctor_detail = array();
								foreach ($doctors as $doctor_list){
									$doctor_detail[$doctor_list['userid']] = $doctor_list['name'];
								}
							?>
							<?php echo form_dropdown('doctor_id', $doctor_detail, $selected_doctor_id,'class="form-control"'); ?>
						</div>
					</div>
					<?php if(!isset($appointment)) {?>
					<div class="col-md-4">
						<div class="form-group">
							<label for="appointment_date">Тип прийому</label>
							<?php echo form_dropdown('status', array('Appointments'=>'Прийом','Consultation'=>'Консультація'), 'Appointments','class="form-control", id="app_status"'); ?>
						</div>
					</div>
					<?php }?>
					<div class="col-md-4">
						<div class="form-group">
							<label for="appointment_date"><?php echo $this->lang->line('date');?></label>
							<input type="text" name="appointment_date" id="appointment_date" value="<?= $appointment_date; ?>" class="form-control"/>
							<?php echo form_error('appointment_date','<div class="alert alert-danger">','</div>'); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="start_time"><?php echo $this->lang->line('start_time');?></label>
							<input type="text" name="start_time" id="start_time" value="<?=date($def_timeformate,strtotime($start_time)); ?>" class="form-control"/>
							<?php echo form_error('start_time','<div class="alert alert-danger">','</div>'); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="end_time"><?=$this->lang->line('end_time');?></label>
							<input type="text" name="end_time" id="end_time" value="<?= date($def_timeformate,strtotime($end_time)); ?>" class="form-control"/>
							<?php echo form_error('end_time','<div class="alert alert-danger">','</div>'); ?>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="treatment">Процедура</label>
							<input type="text" name="treatment" value="<?=$curr_treatment_name;?>" id="treatment" class="form-control "/>
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
						</div>
					</div>

					<?php echo form_close() ?>
					</br>
					<?php if (isset($appointment)){?>
					<div id="cancel_details" style="display:none">
						<?php echo form_open("appointment/change_status/" . $appointment_id . "/Cancel" ) ?>
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
							<a class="btn btn-primary" href="<?=base_url() . "index.php/appointment/index/".$dep;?>"><?=$this->lang->line('back_to_app');?></a>
					<?php if(isset($appointment)){ ?>
						<a class="btn btn-primary" href="<?php echo site_url("patient/edit/" . $curr_patient['patient_id']."/patient"); ?>">Редагувати пацієнта</a>
						<?php if ($status != 'Appointments') { ?>
							<a class="btn btn-primary" href="<?=base_url() . "index.php/appointment/change_status/" . $appointment_id . "/Appointments";?>" ><?php echo $this->lang->line('appointment');?></a>
						<?php } ?>
						<?php if ($status != 'Cancel') { ?>
							<span class="btn btn-primary" onclick=openReason(1)><?php echo $this->lang->line('cancel')." ".$this->lang->line('appointment');?></span>
							<!--<a class="btn btn-primary" href="<?=base_url() . "index.php/appointment/change_status/" . $appointment_id . "/Cancel";?>" ><?php echo $this->lang->line('cancel')." ".$this->lang->line('appointment');?></a>
						<?php } ?>
						<?php if ($status != 'Waiting') { ?>
							<a class="btn btn-primary" href="<?=base_url() . "index.php/appointment/change_status/" . $appointment_id . "/Waiting";?>"><?php echo $this->lang->line('waiting');?></a>
						<?php } ?>
						<?php if ($status != 'Consultation') { ?>
							<a class="btn btn-primary" href="<?=base_url() . "index.php/appointment/change_status/" . $appointment_id . "/Consultation";?>">Консультація</a>
						<?php } ?>-->
						<?php if (!isset($doctor)) { ?>
							<a class="btn btn-danger" href="<?=base_url() . "index.php/appointment/del/" . $appointment_id;?>">Видалити запис</a>
						<?php } ?>
					<?php } ?>
						</div>
					</div>

					<?php if(isset($appointment)) {?>
						<div class="panel-body">
							<div class="wrapper">
								<label for="filesToUp">Завантажити фото</label>
								<input type="file" id="filesToUp" class="fileUpload" multiple="multiple" accept="image/*" style="display:inline">
								<!--<a href="#" class="submit button btn btn-info">Завантажити фото</a>-->
								<div class="ajax-respond"></div>
								<div id="filelist"></div>
								<div id="uploadStatus" style="font-size:30px"></div>
								<div id="progressNumber"></div>
							</div>
							<!--<button class="btn btn-info" onclick=showimages() >Показати фото</button>-->
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>