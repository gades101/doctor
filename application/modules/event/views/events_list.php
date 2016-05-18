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
					Додати подію
				</div>
				<div class="panel-body">
					<?php echo form_open('event/index'); ?>
						<div class="form-group input-group">
							<label for="title">Подія</label>
							<input type="text" class="form-control" name="title" id="title" value=""/>
							<?php echo form_error('title','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="form-group input-group">
							<label for="date">Дата</label>
							<input type="text" class="form-control"  name="date" id="date" value=""/>
							<!--<?php echo form_error('date','<div class="alert alert-danger">','</div>'); ?>-->
						</div>
						
						<!--<div class="form-group input-group">
							<label for="event_month">Місяць</label>
							<input type="text" class="form-control"  name="month" id="event_month" value=""/>
							<?php echo form_error('event_month','<div class="alert alert-danger">','</div>'); ?>
						</div>-->
						<div class="form-group input-group">
							<label for="event_date">Рік</label>
							<input type="text" class="form-control"  name="year" id="event_year" value=""/>
							<?php echo form_error('event_year','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="form-group input-group">
							<button type="submit" name="submit" class="btn btn-primary"><?php echo $this->lang->line('add');?></button>
						</div>
					</form>
				</div>
			</div>	
			<div class="panel panel-primary">
				<div class="panel-heading">
					<?php echo $this->lang->line('events');?>
				</div>
				<div class="panel-body">
					<?php if (isset($events)) { ?>
						<table class="table table-striped table-bordered table-hover dataTable no-footer" id="events" >
						<thead>
							<tr>
								<!--<th><?php echo $this->lang->line('no');?></th>-->
								<th>Назва</th>
								<th>Дата</th>
								<th>Рік</th>
								<th><?php echo $this->lang->line('edit');?></th>
								<th><?php echo $this->lang->line('delete');?></th>
							</tr>
						</thead>
						<tbody>
						<?php $i=1; $j=1 ?>
						<?php foreach ($events as $event):  ?>
						<tr <?php if ($i%2 == 0) { echo "class='even'"; } else {echo "class='odd'";}?> >
							<td><?php echo $event['title']; ?></td>
							<td><?php echo $event['date']; ?></td>
							<td><?php echo $event['year']; ?></td>
							<td><a class="btn btn-primary btn-sm" title="" href="<?php echo site_url("event/edit_event/" . $event['id']); ?>"><?php echo $this->lang->line('edit');?></a></td>
							<td><a class="btn btn-danger btn-sm confirmDelete" title="" href="<?php echo site_url("event/delete_event/" . $event['id']); ?>"><?php echo $this->lang->line('delete');?></a></td>
							</tr>
            <?php $i++; $j++;?>
            <?php endforeach ?>
        </tbody>
    </table>
</div>  
<?php }else{ ?>
	No events added. Add a event.
<?php } ?>				
				</div>