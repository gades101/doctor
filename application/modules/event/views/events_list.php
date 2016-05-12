<script type="text/javascript" charset="utf-8">

$( window ).load(function() {
	$('.confirmDelete').click(function(){
			return confirm("Are you sure you want to delete?");
		});
		
    $('#events').dataTable();	
	
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
							<label for="event">Подія</label>
							<input type="text" class="form-control" name="event" id="event" value=""/>
							<?php echo form_error('event','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="form-group input-group">
							<label for="event_date"><?php echo $this->lang->line('charges_fees');?></label>
							<input type="text" class="form-control"  name="event_date" id="event_date" value=""/>
							<?php echo form_error('event_date','<div class="alert alert-danger">','</div>'); ?>
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
					<?php if ($events) { ?>
						<table class="table table-striped table-bordered table-hover dataTable no-footer" id="events" >
						<thead>
							<tr>
								<!--<th><?php echo $this->lang->line('no');?></th>-->
								<th>Назва</th>
								<th>Дата</th>
								<th><?php echo $this->lang->line('edit');?></th>
								<th><?php echo $this->lang->line('delete');?></th>
							</tr>
						</thead>
						<tbody>
						<?php $i=1; $j=1 ?>
						<?php foreach ($events as $event):  ?>
						<tr <?php if ($i%2 == 0) { echo "class='even'"; } else {echo "class='odd'";}?> >
							<td><?php echo $j; ?></td>
							<td><?php echo $event['title']; ?></td>
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