<script type="text/javascript" charset="utf-8">
$( window ).load(function() {
	
	$('.confirmDelete').click(function(){
		return confirm("Are you sure you want to delete?");
	})

    $("#doctor_table").dataTable();
});
</script>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo $this->lang->line("add")." Group";?>
				</div>
				<div class="panel-body">
					<?php echo form_open_multipart('doctor/add_group/') ?>						
											
						<div class="col-md-6">
							<div class="form-group">
								<label for="group_name">Group Name</label>
								<input type="input" name="group_name" class="form-control" value=""/>
								<?php echo form_error('group_name','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<button class="btn btn-primary" type="submit" name="submit" /><?php echo $this->lang->line("save");?></button>
							</div>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		
			<!-- Advanced Tables -->
			<div class="panel panel-primary">
				<div class="panel-heading">
					Group
				</div>
				<div class="panel-body">
					
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="doctor_table">
							<thead>
								<tr>
									<th><?php echo $this->lang->line("id");?></th>
									<th><?php echo "Group"?></th>									
									<th><?php echo "Delete";?></th>
									<th><?php echo "Edit";?></th>
								</tr>
							</thead>
							<tbody>
								<?php $i=1; ?>
								<?php foreach ($groups as $group):  ?>      
								
								
								<tr <?php if ($i%2 == 0) { echo "class='even'"; } else { echo "class='odd'"; }?> >								
									<td><?php echo $group['id']; ?></td>
									<td><?php echo $group['group_name']; ?></td>									
									<td><a class="btn btn-danger btn-sm confirmDelete" title="<?php echo $this->lang->line('delete').' group : ' . $group['id']?>" href="<?php echo site_url("doctor/delete_group/" . $group['id']); ?>"><?php echo $this->lang->line("delete");?></a></td>
									<td><a class="btn btn-info btn-sm " title="<?php echo $this->lang->line('edit').' group : ' . $group['id'] ?>" href="<?php echo site_url("doctor/edit_group/" . $group['id']); ?>"><?php echo $this->lang->line("edit");?></a></td>
								</tr>
								<?php $i++; ?>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
					
				</div>
			</div>
			<!--End Advanced Tables -->
		</div>
	</div>
</div>
