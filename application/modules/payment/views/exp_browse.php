<script type="text/javascript" charset="utf-8">

$( window ).load(function() {
	$('.confirmDelete').click(function(){
			return confirm("Are you sure you want to delete?");
		});
		
    $('#expenses').dataTable({
		"pageLength": 50,
		"order":  []
	});	
	$('#expense_date').datetimepicker({
			timepicker:false,
			format: '<?=$def_dateformate; ?>',
			scrollInput:false,

	});
	
} )
</script>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					Додати витрату
				</div>
				<div class="panel-body">
					<?php echo form_open('payment/expense'); ?>
						<div class="form-group input-group col-md3">
							<label for="expense_date">Дата</label>
							<input type="text" class="form-control" name="expense_date" id="expense_date" value=""/>
							<?php echo form_error('expense_date','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="form-group input-group col-md3">
							<label for="expense_cat">Категорія</label>
							<input type="text" class="form-control"  name="cat_id" id="expense_cat" value=""/>
							<?php echo form_error('expense_cat','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="form-group input-group">
							<label for="expense_user">Користувач</label>
							<input type="text" class="form-control"  name="user_id" id="expense_user" value=""/>
							<?php echo form_error('expense_user','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="form-group input-group">
							<label for="expense_sum">Сума</label>
							<input type="text" class="form-control"  name="sum" id="expense_sum" value=""/>
							<?php echo form_error('expense_price','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="form-group input-group">
							<label for="expense_title">Опис</label>
							<input type="text" name="goal" id="expense_title" value="" class="form-control"/>
							<?php echo form_error('expense_title','<div class="alert alert-danger">','</div>'); ?>
						</div>						
						<div class="form-group input-group">
							<button type="submit" name="submit" class="btn btn-primary"><?php echo $this->lang->line('add');?></button>
						</div>
					</form>
				</div>
			</div>	
			<div class="panel panel-primary">
				<div class="panel-heading">
					Витрати
				</div>
				<div class="panel-body">
					<?php if ($expenses) { ?>
						<table class="table table-striped table-bordered table-hover dataTable no-footer" id="expenses" >
						<thead>
							<tr>
								<th><?php echo $this->lang->line('no');?></th>
								<th>Призначення</th>
								<th>Сума</th>
								<th><?php echo $this->lang->line('edit');?></th>
								<th><?php echo $this->lang->line('delete');?></th>
							</tr>
						</thead>
						<tbody>
						<?php $i=1; $j=1 ?>
						<?php foreach ($expenses as $expense):  ?>
						<tr <?php if ($i%2 == 0) { echo "class='even'"; } else {echo "class='odd'";}?> >
							<td><?php echo $j; ?></td>
							<td><?php echo $expense['goal']; ?></td>
							<td><?php echo $expense['sum']; ?></td>               
							<td><a class="btn btn-primary btn-sm" href="<?php echo site_url("payment/edit_expense/" . $expense['id']); ?>"><?php echo $this->lang->line('edit');?></a></td>
							<td><a class="btn btn-danger btn-sm confirmDelete" href="<?php echo site_url("payment/delete_expense/" . $expense['id']); ?>"><?php echo $this->lang->line('delete');?></a></td>
			            </tr>
			            <?php $i++; $j++;?>
			            <?php endforeach ?>
			    	    </tbody>
			  			</table>
			  		<?php } ?>
				</div> 
			</div>
		</div>
	</div>
</div>   
