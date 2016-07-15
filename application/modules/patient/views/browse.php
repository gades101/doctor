<script type="text/javascript" charset="utf-8">
$( window ).load(function() {

	$('.confirmDelete').click(function(){
		return confirm("Are you sure you want to delete?");
	})

    $("#patient_table").dataTable({
		"pageLength": 50
	});
});
</script>
<?php
	$level = $_SESSION['category'];
	$date_time=date("Y/m/d/H/i");
?>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<!-- Advanced Tables -->
			<div class="panel panel-primary">
				<div class="panel-heading">
					<?php echo $this->lang->line('patients');?>
				</div>
				<div class="panel-body">
					<a title="<?php echo $this->lang->line("add")." ".$this->lang->line("patient")."а";?>" href="<?php echo base_url()."index.php/patient/insert/" ?>" class="btn btn-primary square-btn-adjust"><?php echo $this->lang->line("add")." ".$this->lang->line("patient")."а";?></a>
					<p></p>
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="patient_table">
							<thead>
								<tr>
									<th><?php echo $this->lang->line("id");?></th>
									<th><?php echo $this->lang->line("name");?></th>
									<!--<th><?php echo $this->lang->line("display")." ".$this->lang->line("name");?></th>-->
									<th><?php echo $this->lang->line("phone_number");?></th>
									<th><?php echo $this->lang->line("reference_by");?></th>
									<th><?php echo $this->lang->line("appointment");?></th>
									<th><?php echo $this->lang->line("payment");?></th>									
									<?php if($level != "Receptionist") { ?>
									<th><?php echo $this->lang->line("delete");?></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php $i=1; ?>
								<?php foreach ($patients as $patient):  ?>
								<?php if(isset($patient['followup_date']) && $patient['followup_date'] != '0000-00-00'){?>
								<?php $followup_date = $patient['followup_date']; ?>
								<?php $followup_date = date('d-m-Y',strtotime($patient['followup_date'])); ?>
								<?php }else{ ?>
								<?php $followup_date = "Призначити відвідування"; ?>
								<?php } ?>
								<tr <?php if ($i%2 == 0) { echo "class='even'"; } else { echo "class='odd'"; }?> >
									<td><?php echo $patient['display_id']; ?></td>
									<td><a class="btn btn-primary btn-sm square-btn-adjust" title="Редагувати" href="<?php echo site_url("patient/edit/" . $patient['patient_id']."/patient"); ?>"><?php echo $patient['first_name'] . " " . $patient['middle_name'] . " " . $patient['last_name'] ?></a></td>
									<!--<td><?php echo $patient['display_name']; ?></td>-->
									<td><?php echo $patient['phone_number']; ?></td>
									<td><?php echo $patient['reference_by'];?></td>
									
									<?php if($level != "Receptionist") { ?>
									<td><a class="btn btn-primary btn-sm square-btn-adjust" title="Записати на прийом" href="<?php echo site_url("appointment/add/".$date_time."/Appointments/".$patient['patient_id']); ?>"><?php echo $this->lang->line("appointment");?></a></td>
									<td><a class="btn btn-primary btn-sm square-btn-adjust" title="Створити платіж" href="<?php echo site_url('payment/insert/'.$patient['patient_id']); ?>"><?php echo $this->lang->line("payment");?></a></td>
									<?php } ?>
									
									<?php if($level != "Receptionist") { ?>
									<td><a class="btn btn-danger btn-sm square-btn-adjust confirmDelete" title="<?php echo $this->lang->line('delete').' '. $this->lang->line("patient").'а : ' . $patient['first_name'] . " " . $patient['middle_name'] . " " . $patient['last_name'] ?>" href="<?php echo site_url("patient/delete/" . $patient['patient_id']); ?>"><?php echo $this->lang->line("delete");?></a></td>
									<?php } ?>
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