<script type="text/javascript" charset="utf-8">

$( window ).load(function() {
	$('.confirmDelete').click(function(){
			return confirm("Ви впевнені");
		});
		
    $('#discounts').dataTable();	
	
} )
</script>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					Додати знижку
				</div>
				<div class="panel-body">
					<?php echo form_open('discount/index'); ?>
						<div class="form-group input-group">
							<label for="amount">Сума</label>
							<input type="text" class="form-control" name="amount" id="amount" value=""/>
							<?php echo form_error('amount','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="form-group input-group">
							<label for="percent">Знижка (у відсотках)</label>
							<input type="text" name="percent" id="percent" value="" class="form-control"/>
							<?php echo form_error('percent','<div class="alert alert-danger">','</div>'); ?>
						</div>						
						<div class="form-group input-group">
							<button type="submit" name="submit" class="btn btn-primary"><?php echo $this->lang->line('add');?></button>
						</div>
					</form>
				</div>
			</div>	
			<div class="panel panel-primary">
				<div class="panel-heading">
					Знижки
				</div>
				<div class="panel-body">
					<?php if ($discounts) { ?>
						<table class="table table-striped table-bordered table-hover dataTable no-footer" id="discounts" >
						<thead>
							<tr>
								<th>Сума</th>
								<th>Відсоток</th>
								<th><?php echo $this->lang->line('edit');?></th>
								<th><?php echo $this->lang->line('delete');?></th>
							</tr>
						</thead>
						<tbody>
						<?php $i=1; $j=1 ?>
						<?php foreach ($discounts as $discount):  ?>
						<tr <?php if ($i%2 == 0) { echo "class='even'"; } else {echo "class='odd'";}?> >
							<td><?php echo $discount['amount']; ?></td>
							<td><?php echo $discount['percent']; ?></td>
							<td><a class="btn btn-primary btn-sm" href="<?php echo site_url("discount/edit_discount/".$discount['amount'].'/'.$discount['percent']); ?>"><?php echo $this->lang->line('edit');?></a></td>
							<td><a class="btn btn-danger btn-sm confirmDelete" href="<?php echo site_url("discount/delete_discount/".$discount['amount'].'/'.$discount['percent']); ?>"><?php echo $this->lang->line('delete');?></a></td>
            </tr>
            <?php $i++; $j++;?>
            <?php endforeach ?>
        </tbody>
    </table>
</div>  
<?php }else{ ?>
	Додайте знижку
<?php } ?>				
				</div>