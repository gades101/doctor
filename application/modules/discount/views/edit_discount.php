<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			<div class="panel-heading">
				Редагувати знижку
			</div>
			<div class="panel-body">
				<?php echo form_open('discount/edit_discount/'.$discount['amount'].'/'.$discount['percent']) ?>
					<div class="form-group">
						<label for="amount">Сума</label> 
						<input type="text" name="amount" id="amount" value="<?php echo $discount['amount']; ?>" class="form-control"/>
						<?php echo form_error('amount','<div class="alert alert-danger">','</div>'); ?>
					</div>
					<div class="form-group">
						<label for="percent">Відсоток</label>
						<input type="text" name="percent" id="percent" value="<?php echo $discount['percent']; ?>" class="form-control"/>
						<?php echo form_error('percent','<div class="alert alert-danger">','</div>'); ?>
					</div>

					<div class="form-group">
						<button type="submit" name="submit" class="btn btn-primary" /><?php echo $this->lang->line('save');?></button>
					</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>