<script type="text/javascript" charset="utf-8">
	
	$(window).load(function() {
		$('#start_time').datetimepicker({
			datepicker:false,
			format: '<?=$def_timeformate; ?>',
			formatTime:'<?=$def_timeformate; ?>'
		});
		$('#start_date').datetimepicker({
			timepicker:false,
			format: '<?=$def_dateformate; ?>',
		});
		$('#end_time').datetimepicker({
			datepicker:false,
			format: '<?=$def_timeformate; ?>',
			formatTime:'<?=$def_timeformate; ?>'
		});	
		$('#end_date').datetimepicker({
			timepicker:false,
			format: '<?=$def_dateformate; ?>',
		});
	});
	 $(function()
    {
        $('#start_time').timepicker({
            'minTime': '<?php echo $clinic_start_time; ?>',
            'maxTime': '<?php echo $clinic_end_time; ?>',
            'step' : '<?php echo ($time_interval * 60); ?>'
        });    
    });
    $(function()
    {
        $('#end_time').timepicker({
            'minTime': '<?php echo $clinic_start_time; ?>',
            'maxTime': '<?php echo $clinic_end_time; ?>',
            'step' : '<?php echo ($time_interval * 60); ?>'
        });    
    });		
function validate()
{
	if(document.getElementById('chkday').checked)
	{	
		document.getElementById('start_time').value ="<?php echo $clinic_start_time; ?>";
		document.getElementById('end_time').value ="<?php echo $clinic_end_time; ?>";
		$("#start_time").prop('readonly', true);
		$("#end_time").prop('readonly', true);
	}
	else
	{
		$("#start_time").prop('readonly', false);
		$("#end_time").prop('readonly', false);
	} 
}
</script>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			<div class="panel-heading">
					<?php echo $this->lang->line('doctor_availability');?> 
			</div>
			<div class="panel-body">
				<span class="err"><?php echo validation_errors(); ?></span>
				<?php echo form_open('doctor/edit_inavailability/'. $availability['appointment_id'].'/'.$availability['userid'].'/'.$availability['end_date']) ?>   
				<?php $level = $_SESSION['category']; ?>
				<div class="form-group">
					<label><?php echo $this->lang->line('doctor');?></label>
					<?php if ($level == 'Doctor')
					{  
						$doctor_name = $doctors['name'];
						$userid = $_SESSION['id'];
					 ?>
					<input type="text"  class="form-control"name="doctor" id="doctor" value="<?= $doctor_name?>" readonly="readonly"/><br/>
					<?php 
					}else{
						$userid = 0;
					?>
						<select name="doctor" class="form-control">  <option></option>
							<?php foreach ($doctors as $doctor) { ?>
							<option value="<?php echo $doctor['userid'];?>"<?php if($doctor['userid']== $availability['userid']) {echo 'selected';}?>><?= $doctor['name']; ?></option>
							<?php } ?>
						</select> 
						<?php	
					}
					?>
					<input type="hidden" name="doctor_id" value="<?= $userid; ?>"/>
				</div>
				<?php 
					$start_time = $availability['start_time'];
					$end_time = $availability['end_time'];
					$start_time = date($def_timeformate,strtotime($start_time));
					$end_time = date($def_timeformate,strtotime($end_time));
				?>
				<div class="form-group">
					<label for="start_date"> <?php echo $this->lang->line('start_date');?></label>
					<input name="start_date" id="start_date" class="form-control" type="text" value="<?php echo date($def_dateformate, strtotime($availability['appointment_date']));?>"/>
				</div>
				<div class="form-group">
					<label for="start_time"><?php echo $this->lang->line('start_time');?></label>
					<input name="start_time" id="start_time" class="form-control" type="input" value="<?php echo $start_time; ?>"/>
				</div>
				<div class="form-group">
					<label for="end_date"> <?php echo $this->lang->line('end_date');?></label>
					<input name="end_date" id="end_date" class="form-control" type="text"  value="<?php echo date($def_dateformate, strtotime($availability['end_date'])); ?>"/>
				</div>
				<div class="form-group">
					 <label for="end_time"> <?php echo $this->lang->line('end_time');?></label>
					<input name="end_time" id="end_time" class="form-control" type="input" value="<?php echo $end_time; ?>"/>
				</div>
				<script src="<?= base_url() ?>js/chosen.jquery.js" type="text/javascript"></script>
				<script type="text/javascript"> 
					var config = {
						'.chzn-select'           : {},
						'.chzn-select-deselect'  : {allow_single_deselect:true},
						'.chzn-select-no-single' : {disable_search_threshold:10},
						'.chzn-select-no-results': {no_results_text:'Oops, nothing found!'},
						'.chzn-select-width'     : {width:"95%"}
					}
					for (var selector in config) {
						$(selector).chosen(config[selector]);
					}
				</script>
				<div class="form-group">
					<button class="btn btn-primary" type="submit" name="submit" /><?php echo $this->lang->line('save');?></button>
					<a class="btn btn-primary" href="<?php echo base_url() . "/index.php/doctor/inavailability" ?>"><?php echo $this->lang->line('cancel');?></a>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>