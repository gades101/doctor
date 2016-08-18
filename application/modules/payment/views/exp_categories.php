<script type="text/javascript" charset="utf-8">

$( window ).load(function() {
	$('.confirmDelete').click(function(){
			return confirm("Are you sure you want to delete?");
		});
		
    $('#expense_categories').dataTable();	
	
} )
</script>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					Додати категорію витрат
				</div>
				<div class="panel-body">
					<?php echo form_open('payment/expense_categories'); ?>
						<div class="form-group input-group col-md3">
							<label for="id">Категорія id</label>
							<input type="text" class="form-control"  name="id" id="id" value=""/>
							<?php echo form_error('id','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="form-group input-group col-md3">
							<label for="title">Категорія</label>
							<input type="text" class="form-control"  name="title" id="title" value=""/>
							<?php echo form_error('title','<div class="alert alert-danger">','</div>'); ?>
						</div>
						

						<div class="form-group input-group">
							<label for="parent_id">Призначити підкатегорією для</label>
								<select id='payment_id' name='payment_id' class="form-control">
									<option value='0'>--/--</option>
									<?php if(isset($expense_categories)){
										foreach($expense_categories as $cat){ ?>
											<option value="<?php echo $cat['id']; ?>"  data-cat=""  /><?= $cat['id'].' '.$cat['title'].')'; ?></option>				
									<?php } } ?>
								</select>
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
					<?php if ($expense_categories) { ?>
						<table class="table table-striped table-bordered table-hover dataTable no-footer" id="expense_categories" >
						<thead>
							<tr>
								<th><?php echo $this->lang->line('no');?></th>
								<th>Назва категорії</th>
								<th><?php echo $this->lang->line('edit');?></th>
								<th><?php echo $this->lang->line('delete');?></th>
							</tr>
						</thead>
						<tbody>
						<?php $i=1; $j=1 ?>
						<?php foreach ($expense_categories as $expense):  ?>
						<tr <?php if ($i%2 == 0) { echo "class='even'"; } else {echo "class='odd'";}?> >
							<td><?php echo $expense['id']; ?></td>
							<td><?php echo $expense['title']; ?></td>               
							<td><a class="btn btn-primary btn-sm" href="<?php echo site_url("payment/edit_expense_cat/" . $expense['id']); ?>"><?php echo $this->lang->line('edit');?></a></td>
							<td><a class="btn btn-danger btn-sm confirmDelete" title="<?php echo $this->lang->line('delete_expense_cat')." : " . $expense['title'] ?>" href="<?php echo site_url("payment/delete_expense_cat/" . $expense['id']); ?>"><?php echo $this->lang->line('delete');?></a></td>
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
