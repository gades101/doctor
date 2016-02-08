<script type="text/javascript" charset="utf-8">
$( window ).load(function() {

	$('.confirmDelete').click(function(){
		return confirm("Are you sure you want to delete?");
	})

    $("#doctor_table").dataTable();
	$('#from_time').datetimepicker({
		datepicker:false,
		format: '<?=$def_timeformate; ?>',
		formatTime:'<?=$def_timeformate; ?>'
    });
    $('#to_time').datetimepicker({
		datepicker:false,
		format: '<?=$def_timeformate; ?>',
		formatTime:'<?=$def_timeformate; ?>'
    });
});
function checkAll(ele) {
     var checkboxes = document.getElementsByTagName('input');
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
             }
         }
     }
 }

</script>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			<div class="panel-heading">
			<?php
			 echo $this->lang->line('doctor_schedule');
			 $doctor_id = $doctor_details['doctor_id'];
			 ?>
			</div>
			<div class="panel-body">

			<?php echo form_open('doctor/doctor_schedule');
				 $level = $_SESSION["category"];
				?>
				<div>
					<div class="form-group">
						<label><?php echo $this->lang->line('doctor');?></label>
						<?php if ($level == 'Doctor') {
								$doctor_name = $doctor['first_name'] . ' ' . $doctor['middle_name']. ' ' . $doctor['last_name'];
								$userid = $_SESSION['id'];
							 ?>
							<input type="hidden" name="doctor" class="form-control" value="<?php echo $doctor['doctor_id'] ?>" readonly="readonly"/>
							<input type="text" name="doctor_name" class="form-control" id="doctor_name" value="<?= $doctor_name; ?>" readonly="readonly"/><br/>
							<?php
							} else {
								$userid = 0;
							?>
								<select name="doctor" class="form-control">  <option></option>
									<?php foreach ($doctors as $doctor) { ?>
									<option value="<?php echo $doctor['doctor_id'] ?>" <?php if($doctor_id==$doctor['doctor_id']){ echo "selected";}?> >
										<?= $doctor['first_name'] . ' ' . $doctor['middle_name']. ' ' . $doctor['last_name']; ?>
									</option>
						<?php } ?>
								</select>

						<?php }
							 echo form_error('doctor','<div class="alert alert-danger">','</div>'); ?>
							 <input type="hidden" name="doctor_id" value="<?= $userid; ?>"/>
					</div>
				</div>
				<div>
					<div class="form-group">
						<label> <?php echo $this->lang->line('day');?></label>
						<label class="checkbox-inline">
							<input type="checkbox" name="select-all" id="select-all" onClick="checkAll(this)"/><?php echo $this->lang->line('select_all'); ?>
						</label>
					</div>
					<div class="form-group">

						<label class="checkbox-inline">
							<input name="day[]" type="checkbox" value="Понеділок"><?php echo $this->lang->line('monday'); ?>
						</label>
						<label class="checkbox-inline">
							<input name="day[]" type="checkbox" value="Вівторок"><?php echo $this->lang->line('tuesday'); ?>
						</label>
						<label class="checkbox-inline">
							<input name="day[]" type="checkbox" value="Середа"><?php echo $this->lang->line('wednesday'); ?>
						</label>
						<label class="checkbox-inline">
							<input name="day[]" type="checkbox" value="Четвер"><?php echo $this->lang->line('thursday'); ?>
						</label>
						<label class="checkbox-inline">
							<input name="day[]" type="checkbox" value="П'ятниця"><?php echo $this->lang->line('friday'); ?>
						</label>
						<label class="checkbox-inline">
							<input name="day[]" type="checkbox" value="Субота"><?php echo $this->lang->line('saturday'); ?>
						</label>
						<label class="checkbox-inline">
							<input name="day[]" type="checkbox" value="Неділя"><?php echo $this->lang->line('sunday'); ?>
						</label>
						<?php echo form_error('day[]','<div class="alert alert-danger">','</div>'); ?>
					</div>
				</div>
				<div>
					<div class="form-group">
						<label for="from_time"><?php echo $this->lang->line('from_time');?></label>
						<input name="from_time" id="from_time" type="text" class="form-control" value=""/>
						<?php echo form_error('from_time','<div class="alert alert-danger">','</div>'); ?>
					</div>
				</div>

				<div>
					<div class="form-group">
						<label for="to_time"><?php echo $this->lang->line('to_time');?></label>
						<input name="to_time" id="to_time" type="text" class="form-control" value=""/>
						<?php echo form_error('to_time','<div class="alert alert-danger">','</div>'); ?>
					</div>
				</div>
				<div>
					<div class="form-group">
						<button type="submit" name="submit" class="btn btn-primary" /><?php echo $this->lang->line('save');?></button>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
		<!-- Advanced Tables -->
		<div class="panel panel-primary">
			<div class="panel-heading">
				Розклади лікарів
			</div>
			<div class="panel-body">

				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="doctor_table">
						<thead>
							<tr>
								<th><?php echo $this->lang->line("id");?></th>
								<th><?php echo $this->lang->line('doctor');?></th>
								<th><?php echo $this->lang->line('day');?></th>
								<th><?php echo $this->lang->line('from_time');?></th>
								<th><?php echo $this->lang->line('to_time');?></th>
								<th><?php echo $this->lang->line('edit');?></th>
								<th><?php echo $this->lang->line('delete');?></th>

							</tr>
						</thead>
						<tbody>
							<?php $i=1; ?>
							<?php foreach ($drschedules as $drschedule):  ?>
							<tr <?php if ($i%2 == 0) { echo "class='even'"; } else { echo "class='odd'"; }?> >
								<td><?php echo $drschedule['schedule_id']; ?></td>
								<td>
								<?php if ($level == 'Doctor') {  ?>
									<?= $doctor_name; ?>
								<?php }else{ ?>
									<?php  foreach ($doctors as $doctor) {
									if($doctor['doctor_id']==$drschedule['doctor_id']){
									?>
									<?php echo $doctor['first_name'] . ' ' . $doctor['middle_name']. ' ' . $doctor['last_name']; ?>
									<?php }} ?>
								<?php } ?>
								</td>
								<td><?php echo $drschedule['schedule_day']; ?></td>
								<td><?php echo date($def_timeformate,strtotime($drschedule['from_time'])); ?></td>
								<td><?php echo date($def_timeformate,strtotime($drschedule['to_time'])); ?></td>
								<td><a class="btn btn-info btn-sm " title="<?php echo $this->lang->line('edit').' doctor_sechedule : ' . $drschedule['schedule_id'] ?>" href="<?php echo site_url("doctor/edit_drschedule/" . $drschedule['schedule_id']); ?>"><?php echo $this->lang->line("edit");?></a></td>
								<td><a class="btn btn-danger btn-sm confirmDelete" title="<?php echo $this->lang->line('delete').' doctor_sechedule : ' . $drschedule['schedule_id']?>" href="<?php echo site_url("doctor/delete_drschedule/" . $drschedule['schedule_id']); ?>"><?php echo $this->lang->line("delete");?></a></td>
							</tr>
							<?php $i++; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

			</div>
		</div>
		<!--End Advanced Tables -->
		</div>
	</div>
</div>