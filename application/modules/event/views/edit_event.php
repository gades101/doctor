<script type="text/javascript" charset="utf-8">

$( window ).load(function() {
	$('.confirmDelete').click(function(){
			return confirm("Are you sure you want to delete?");
		});
		
   $('#events').dataTable();	
	$('#date').datetimepicker({
		timepicker:false,
		format: 'd/m',
	});
	
} )
</script>

<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			<div class="panel-heading">
				Редагувати подію
			</div>
			
			<div class="panel-body">
				<?php echo form_open('event/edit_event/'.$event['id']); ?>
					<input type="hidden" name="id" id="id" value="<?php echo $event['id']; ?>" class="form-control"/>	
					<div class="form-group input-group">
						<label for="title">Подія</label>
						<input type="text" class="form-control" name="title" id="title" value="<?php echo $event['title']; ?>"/>
						<?php echo form_error('title','<div class="alert alert-danger">','</div>'); ?>
					</div>
					<div class="form-group input-group">
						<label for="date">Дата</label>
						<input type="text" class="form-control"  name="date" id="date" value="<?= $event['day'].'/'.$event['month']; ?>"/>
						<!--<?php echo form_error('date','<div class="alert alert-danger">','</div>'); ?>-->
					</div>
					
					<!--<div class="form-group input-group">
						<label for="event_month">Місяць</label>
						<input type="text" class="form-control"  name="month" id="event_month" value=""/>
						<?php echo form_error('event_month','<div class="alert alert-danger">','</div>'); ?>
					</div>-->
					<div class="form-group input-group">
						<label for="event_date">Рік</label>
						<input type="text" class="form-control"  name="year" id="event_year" value="<?php echo $event['year']; ?>"/>
						<?php echo form_error('year','<div class="alert alert-danger">','</div>'); ?>
					</div>
					<div class="form-group input-group">
						<button type="submit" name="submit" class="btn btn-primary"><?php echo $this->lang->line('edit');?></button>
						<a class="btn btn-danger" title="" href="<?php echo site_url("event/delete_event/" . $event['id']); ?>"><?php echo $this->lang->line('delete');?></a>

					</div>
				</form>
			</div>			
			
		</div>
	</div>
</div>