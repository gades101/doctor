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
			<div class="panel panel-primary">
				<div class="panel-heading">
					<?php echo $this->lang->line("add")." Fees";?>
				</div>
				<div class="panel-body">
					<?php echo form_open_multipart('doctor/fees/') ?>						
						
						<div class="col-md-6">
							<div class="form-group">
								<label for="doctor">Doctor</label>
								<select name="doctor" class="form-control">  <option></option>
									<?php  foreach ($doctors as $doctor) { ?>
									<option value="<?php  echo $doctor['doctor_id'] ?>">
										<?= $doctor['first_name'] . ' ' . $doctor['middle_name']. ' ' . $doctor['last_name']; ?>
									</option>
									<?php } ?>
								</select>
								<!--input type="input" name="doctor_id" class="form-control" value=""/-->
								<?php echo form_error('doctor','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="detail">Detail</label>
								<input type="input" name="detail" class="form-control" value=""/>
								<?php echo form_error('detail','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="fees">Fees</label>
								<input type="input" name="fees" class="form-control" value=""/>
								<?php echo form_error('fees','<div class="alert alert-danger">','</div>'); ?>
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
					Fees Detail
				</div>
				<div class="panel-body">
					
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="doctor_table">
							<thead>
								<tr>
									<th><?php echo $this->lang->line("id");?></th>
									<th><?php  echo $this->lang->line("doctor");?></th>	
									<th><?php echo "Detail";?></th>
									<th><?php echo "Fees";?></th>									
									<th><?php echo "Delete";?></th>
									<th><?php echo "Edit";?></th>
								</tr>
							</thead>
							<tbody>
								<?php $i=1; ?>
								<?php foreach ($fees as $fee):  ?>      
									<?php foreach ($doctors as $doctor) { ?>
										<?php if($doctor['doctor_id']==$fee['doctor_id']){ ?>
											<tr <?php if ($i%2 == 0) { echo "class='even'"; } else { echo "class='odd'"; }?> >								
												<td><?php echo $fee['id']; ?></td>
												<td><?php echo $doctor['first_name'] . ' ' . $doctor['middle_name']. ' ' . $doctor['last_name']; ?> </td>									
												<td><?php echo $fee['detail']; ?></td>
												<td><?php echo $fee['fees']; ?></td>
												<td><a class="btn btn-danger btn-sm confirmDelete" title="<?php echo $this->lang->line('delete').' fee : ' . $fee['id']?>" href="<?php echo site_url("doctor/delete_fees/" . $fee['id']); ?>"><?php echo $this->lang->line("delete");?></a></td>
												<td><a class="btn btn-info btn-sm " title="<?php echo $this->lang->line('edit').' '.$this->lang->line('fee') . $fee['id'] ?>" href="<?php echo site_url("doctor/edit_fees/" . $fee['id']); ?>"><?php echo $this->lang->line("edit");?></a></td>
											</tr>
										<?php } ?>
									<?php } ?>
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
