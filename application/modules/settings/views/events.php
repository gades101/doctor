<div class="panel-body">
	<?php echo form_open('settings/edit_event/'.$treatment['id']) ?>
		<div class="form-group">
			<label for="title">Подия</label> 
			<input type="text" name="title" id="title" value="<?php echo $event['title']; ?>" class="form-control"/>
			<?php echo form_error('title','<div class="alert alert-danger">','</div>'); ?>
		</div>
		<div class="form-group">
			<label for="date">Дата</label>
			<input type="text" name="date" id="date" value="<?php echo $event['date']; ?>" class="form-control"/>
			<?php echo form_error('date','<div class="alert alert-danger">','</div>'); ?>
		</div>
		<div class="form-group">
			<button type="submit" name="submit" class="btn btn-primary" /><?php echo $this->lang->line('save');?></button>
		</div>
	<?php echo form_close(); ?>
</div>