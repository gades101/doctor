<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<?php echo $this->lang->line("edit"). " ". $this->lang->line("fees");?>
				</div>
				<div class="panel-body">
					<?php echo form_open_multipart('doctor/edit_fees/') ?>						
											
						<div class="col-md-6">
							<div class="form-group">
							<input type="hidden" name="id" class="form-control" value="<?= $fees['id']; ?>"/>
								<label for="doctor">Doctor</label>
								<select name="doctor" class="form-control">  <option></option>
									<?php  foreach ($doctors as $doctor) { ?>
									<option value="<?php  echo $doctor['doctor_id'] ?>" <?php if($fees['doctor_id']==$doctor['doctor_id']){?> selected <?php } ?>>
										<?= $doctor['first_name'] . ' ' . $doctor['middle_name']. ' ' . $doctor['last_name']; ?>
									</option>
									<?php } ?>
								</select>
								<!--input type="input" name="doctor" class="form-control" value="<?//= $fees['doctor_id']; ?>"/-->
								<?php echo form_error('doctor_id','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="detail">Detail</label>
								<input type="input" name="detail" class="form-control" value="<?= $fees['detail']; ?>"/>
								<?php echo form_error('detail','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="fees">Fees</label>
								<input type="input" name="fees" class="form-control" value="<?= $fees['fees']; ?>"/>
								<?php echo form_error('fees','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<button class="btn btn-primary" type="submit" name="submit" /><?php echo $this->lang->line("save");?></button>
							</div>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>