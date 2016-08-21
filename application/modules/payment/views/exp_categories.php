<?php
foreach ($expense_categories as $key => $expense) {
	$id=$expense['id'];
	$len=strlen($expense['id']);
	$j=$len-10;
	$view_id=substr($id, 0, $j-1);

	for ($i=0; $i < 10; $i+=2) {
		$para=substr($id, $j, 2);
		if($para==='00') break;
		else {
			$view_id.='.'.(int)$para;
			$j+=2;

		}
	}
	$expense_categories[$key]['view_id']=$view_id;
}

?>
<script type="text/javascript" charset="utf-8">

$( window ).load(function() {
	$('.confirmDelete').click(function(){
			return confirm("Are you sure you want to delete?");
		});
		
    $('#expense_categories').dataTable({
		"pageLength": 50,
		"order":  []	
	});	
	
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
							<label for="title">Назва Категорії</label>
							<input type="text" class="form-control"  name="title" id="title" value=""/>
							<?php echo form_error('title','<div class="alert alert-danger">','</div>'); ?>
						</div>
						

						<div class="form-group input-group">
							<label for="parent_id">Призначити підкатегорією для</label>
								<select id='parent_id' name='parent_id' class="form-control">
									<option value='0'>...</option>
									<?php if(isset($expense_categories)){
										foreach($expense_categories as $cat){ ?>
											<option value="<?php echo $cat['id']; ?>"  data-cat=""  /><?= $cat['view_id'].' '.$cat['title']; ?></option>				
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
							</tr>
						</thead>
						<tbody>
						<?php $i=1; $j=1 ?>
						<?php foreach ($expense_categories as $expense):  ?>
						<tr <?php if ($i%2 == 0) { echo "class='even'"; } else {echo "class='odd'";}?> >
							<td><?php echo $expense['view_id']; ?></td>
							<td><?php echo $expense['title']; ?></td>               
							<td><a class="btn btn-primary btn-sm" href="<?php echo site_url("payment/edit_expense_cat/" . $expense['id']); ?>"><?php echo $this->lang->line('edit');?></a></td>
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
