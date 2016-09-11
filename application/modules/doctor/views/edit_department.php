<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<?php echo $this->lang->line("edit")." ".$this->lang->line("department");?>
				</div>
				<div class="panel-body">
					<?php echo form_open_multipart('doctor/edit_department/') ?>						
						<input type="hidden" name="department_id" class="inline" value="<?=$departments['department_id']?>"/>						
						<div class="col-md-6">
							<div class="form-group">
								<label for="department_name"><?php echo $this->lang->line("department");?> </label>
								<input type="input" name="department_name" class="form-control" value="<?=$departments['department_name']?>"/>
								<?php echo form_error('department_name','<div class="alert alert-danger">','</div>'); ?>
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