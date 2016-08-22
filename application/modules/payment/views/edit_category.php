<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			<div class="panel-heading">
				<?php echo $this->lang->line('edit_exp_category');?>
			</div>
			<div class="panel-body">
				<?php echo form_open('payment/edit_expense_cat/'.$exp_category['id']) ?>
					<input type="hidden" name="expense_id" id="expense_id" value="<?php echo $exp_category['id']; ?>" class="form-control"/>	
					<div class="form-group">
						<label for="title">Назва Категорії</label> 
						<input type="text" name="title" id="title" value="<?php echo $exp_category['title']; ?>" class="form-control"/>
						<?php echo form_error('title','<div class="alert alert-danger">','</div>'); ?>
					</div>
					<div class="form-group">
						<button type="submit" name="submit" class="btn btn-primary" /><?php echo $this->lang->line('save');?></button>
					</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>