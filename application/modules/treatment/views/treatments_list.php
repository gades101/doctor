<script type="text/javascript" charset="utf-8">

$( window ).load(function() {
	$('.confirmDelete').click(function(){
			return confirm("Are you sure you want to delete?");
		});
			
    $('#treatments').dataTable({
		"pageLength": 50,
		"order":  []
	});		
} )
</script>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
		<?php if($_SESSION['category']!='Doctor') { ?>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<?php echo $this->lang->line('add_treatment');?>
				</div>
				<div class="panel-body">
					<?php echo form_open('treatment/index'); ?>
						<div class="form-group input-group">
							<label for="treatment"><?php echo $this->lang->line('treatment');?></label>
							<input type="text" class="form-control" name="treatment" id="treatment" value=""/>
							<?php echo form_error('treatment','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="form-group input-group">
							<label for="treatment_price">Сума</label>
							<input type="text" class="form-control"  name="treatment_price" id="treatment_price" value=""/>
							<?php echo form_error('treatment_price','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="form-group input-group">
							<label for="treatment_count">Кількість</label>
							<input type="text" name="treatment_count" id="treatment_count" value="" class="form-control"/>
							<?php echo form_error('treatment_count','<div class="alert alert-danger">','</div>'); ?>
						</div>						
						<div class="form-group input-group">
							<button type="submit" name="submit" class="btn btn-primary"><?php echo $this->lang->line('add');?></button>
						</div>
					</form>
				</div>
			</div>
		<?php } ?>	
			<div class="panel panel-primary">
				<div class="panel-heading">
					<?php echo $this->lang->line('treatments');?>
				</div>
				<div class="panel-body">
					<?php if ($treatments) { ?>
						<table class="table table-striped table-bordered table-hover dataTable no-footer" id="treatments" >
						<thead>
							<tr>
								<th>id</th>
								<th><?php echo $this->lang->line('treatment_name');?></th>
								<th><?php echo $this->lang->line('treatment_charges');?></th>
								<th>Кількість</th>
								<?php if($_SESSION['category']!='Doctor') { ?>
								<th><?php echo $this->lang->line('edit');?></th>
								<th><?php echo $this->lang->line('delete');?></th>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
						<?php $i=1; $j=1 ?>
						<?php foreach ($treatments as $treatment):  ?>
						<tr <?php if ($i%2 == 0) { echo "class='even'"; } else {echo "class='odd'";}?> >
							<td><?php echo $treatment['id']; ?></td>
							<td><?php echo $treatment['treatment']; ?></td>
							<td class="right"><?php echo currency_format($treatment['price']);if($currency_postfix) echo $currency_postfix['currency_postfix']; ?></td>
							<td><?php echo $treatment['count']; ?></td>	
							<?php if($_SESSION['category']!='Doctor') { ?>						
							<td><a class="btn btn-primary btn-sm" title="" href="<?php echo site_url("treatment/edit_treatment/" . $treatment['id']); ?>"><?php echo $this->lang->line('edit');?></a></td>
							<td><a class="btn btn-danger btn-sm confirmDelete" title="<?php echo $this->lang->line('delete_treatment')." : " . $treatment['treatment'] ?>" href="<?php echo site_url("treatment/delete_treatment/" . $treatment['id']); ?>"><?php echo $this->lang->line('delete');?></a></td>
							<?php } ?>
            </tr>
            <?php $i++; $j++;?>
            <?php endforeach ?>
        </tbody>
    </table>
</div>  
<?php }else{ ?>
	No Treatments added. Add a treatment.
<?php } ?>				
				</div>