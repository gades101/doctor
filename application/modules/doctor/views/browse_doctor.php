<script type="text/javascript" charset="utf-8">
$( window ).load(function() {

	$('.confirmDelete').click(function(){
		return confirm("Are you sure you want to delete?");
	})

    $("#doctor_table").dataTable();
});
</script>
<?php $category = $_SESSION["category"];?>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<!-- Advanced Tables -->
			<div class="panel panel-primary">
				<div class="panel-heading">
					<?= $this->lang->line("doctors");?>
				</div>
				<div class="panel-body">
					<?php if($category != 'Doctor') {?>
					<a title="<?= $this->lang->line("add");?>" href="<?php echo base_url()."index.php/doctor/doctor_detail/" ?>" class="btn btn-primary"><?= $this->lang->line("add");?></a>
					<?php }?>
					<a href="<?php echo base_url()."index.php/doctor/copy_from_users/" ?>" class="btn btn-primary"><?php echo $this->lang->line("add_from_users");?></a>
					<p></p>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="doctor_table">
							<thead>
								<tr>
									<th><?php echo $this->lang->line("id");?></th>
									<th><?php echo "ПІБ";?></th>
									<th><?php echo $this->lang->line("department");?></th>
									<th><?php echo $this->lang->line("specialization");?></th>
									<th><?php echo $this->lang->line("email");?></th>
									<th><?php echo $this->lang->line("phone_number");?></th>
									<th><?php echo "Розклад" ?></th>
								</tr>
							</thead>
							<tbody>
								<?php $i=1; ?>
								<?php if($doctors){ ?>
								<?php foreach ($doctors as $doctor):  ?>
								<tr <?php if ($i%2 == 0) { echo "class='even'"; } else { echo "class='odd'"; }?> >
									<td><?php echo $doctor['doctor_id']; ?><input type="hidden" value="<?php echo $doctor['contact_id']; ?>"></td>
									<td><a class="btn btn-info btn-sm square-btn-adjust" href="<?php echo site_url("doctor/doctor_detail/" . $doctor['doctor_id']); ?>"><?php echo $doctor['first_name'] . " " . $doctor['middle_name'] . " " . $doctor['last_name'] ?></a></td>
									<td>
									<?php foreach ($departments as $department):  ?>
										<?php if($department['department_id'] == $doctor['department_id']){ ?>
											<?php echo $department['department_name'];?>
										<?php } ?>
									<?php endforeach ?>
									</td>
									<td><?php echo $doctor['specification']; ?></td>
									<td><?php echo $doctor['email'];?></td>
									<td><?php echo $doctor['phone_number'];?></td>
									<td><a class="btn btn-primary btn-sm square-btn-adjust" href="<?php echo site_url("doctor/doctor_schedule/" . $doctor['doctor_id']); ?>">Розклад</a></td>
								</tr>
								<?php $i++; ?>
								<?php endforeach ?>
								<?php } ?>
							</tbody>
						</table>
					</div>

				</div>
			</div>
			<!--End Advanced Tables -->
		</div>
	</div>
</div>