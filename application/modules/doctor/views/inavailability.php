<script type="text/javascript" charset="utf-8">
$(window).load(function() {
	$('#start_date').datetimepicker({
			timepicker:false,
			format: '<?=$def_dateformate; ?>',
	});
	$('#start_time').datetimepicker({
		datepicker:false,
		format: '<?=$def_timeformate; ?>',
		formatTime:'<?=$def_timeformate; ?>'
    });
	$('#end_date').datetimepicker({
			timepicker:false,
			format: '<?=$def_dateformate; ?>',
	});
	$('#end_time').datetimepicker({
		datepicker:false,
		format: '<?=$def_timeformate; ?>',
		formatTime:'<?=$def_timeformate; ?>'
    });
	$('.confirmDelete').click(function(){
		return confirm("<?=$this->lang->line('areyousure_delete');?>");
	});
	$('#appointment_table').dataTable();
});
</script>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<?php echo $this->lang->line('doctor_availability');?>
				</div>
				<div class="panel-body">
					<?php echo form_open('doctor/inavailability');
						 $level = $_SESSION['category'];
						?>
						<div class="form-group">
							<label><?=$this->lang->line('doctor');?></label>
							<?php if ($level == 'Doctor')
								{
									$doctor_name = $doctors['name'];
									$userid = $_SESSION['id'];
								 ?>
								<input type="text" class="form-control" name="doctor" id="doctor" value="<?= $doctor_name?>" readonly="readonly"/><br/>
								<?php
								}
								else
								{
									$userid = 0;
								?>
									<select name="doctor" class="form-control">  <option></option>
										<?php foreach ($doctors as $doctor) { ?>
										<option value="<?php echo $doctor['userid'] ?>"><?= $doctor['name']; ?></option>
										<?php } ?>
									</select>
							<?php }
								 echo form_error('doctor','<div class="alert alert-danger">','</div>'); ?>
								 <input type="hidden" name="doctor_id" value="<?= $userid; ?>"/>
						</div>
						<div class="form-group">
							<label for="start_date"> <?php echo $this->lang->line('start_date');?></label>
							<input name="start_date" id="start_date" type="text" class="form-control" value="<?php echo date($def_dateformate); ?>"/>
							<?php echo form_error('start_date','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<?php
							$start_time = date($def_timeformate);
							$end_time = date($def_timeformate);
						?>
						<div class="form-group">
							<label for="start_time"><?php echo $this->lang->line('start_time');?></label>
							<input type="input" name="start_time" id="start_time" value="<?=$start_time; ?>" class="form-control"/>
							<?php echo form_error('start_time','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="form-group">
							<label for="end_date"><?php echo $this->lang->line('end_date');?></label>
							<input name="end_date" id="end_date"  type="text"  class="form-control" value="<?php echo date($def_dateformate); ?>"/>
							<?php echo form_error('end_date','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="form-group">
							 <label for="end_time"><?php echo $this->lang->line('end_time');?></label>
							 <input type="input" name="end_time" id="end_time" value="<?=$end_time; ?>" class="form-control"/>
							<?php echo form_error('end_time','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="form-group">
							<button type="submit" name="submit" class="btn btn-primary" /><?php echo $this->lang->line('save');?></button>
						</div>
						<?php echo form_close(); ?>
				</div>
			</div>

			<?php if ($availability){ ?>
			<div class="panel panel-primary">
				<div class="panel-heading"><?=$this->lang->line('doctor') .' (' . $this->lang->line('inavailablity').')' ;?></div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="appointment_table">
						<thead>
							<tr>
								<th><?php echo $this->lang->line('doctor');?></th>
								<th width="100px;"><?php echo $this->lang->line('start_date');?></th>
								<th width="100px;"><?php echo $this->lang->line('end_date');?></th>
								<th width="100px;"><?php echo $this->lang->line('start_time');?></th>
								<th width="100px;"><?php echo $this->lang->line('end_time');?></th>
								<th><?php echo $this->lang->line('edit');?></th>
								<th><?php echo $this->lang->line('delete');?></th>
							</tr>
						</thead>
						<?php $i = 1; ?>
						<?php foreach ($availability as $avi) { ?>
						<tbody>
							<tr <?php if ($i % 2 == 0) {
								echo "class='alt'";
								} ?> >
								<td>
								<?php
								if ($level == 'Doctor'){
									echo $doctor_name;
								}
								else
								{
									foreach ($doctors as $doctor)
									{
										if ($avi['userid'] == $doctor['userid'])
										{
											echo $doctor['name'];
										}
									}
								}?>
								</td>
								<td><?= date($def_dateformate,strtotime($avi['appointment_date'])); ?></td>
								<td><?= date($def_dateformate,strtotime($avi['end_date'])); ?></td>
								<td><?= date($def_timeformate,strtotime($avi['start_time'])); ?></td>
								<td><?= date($def_timeformate,strtotime($avi['end_time'])); ?></td>

							<td><center><a class="btn btn-primary btn-sm square-btn-adjust" href="<?= site_url('doctor/edit_inavailability') . "/" . $avi['appointment_id'] ."/" . $avi['userid']. "/" . $avi['end_date']?>"><?php echo $this->lang->line('edit')?></a></center></td>
							<td><center><a class="btn btn-danger btn-sm square-btn-adjust confirmDelete" href="<?= site_url('doctor/delete_availability') . "/" . $avi['appointment_id'] ?>"><?php echo $this->lang->line('delete')?></a></center></td>
						</tr>
						</tbody>
						<?php $i++; ?>
						<?php } ?>
						</table>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>