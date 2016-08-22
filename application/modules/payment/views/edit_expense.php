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
if($edit_expense['expense_date'])$edit_expense['expense_date'] = date("d-m-Y",strtotime($edit_expense['expense_date']));
?>
<script type="text/javascript" charset="utf-8">

$( window ).load(function() {
	$('.confirmDelete').click(function(){
			return confirm("Are you sure you want to delete?");
		});
	$('#expense_date').datetimepicker({
			timepicker:false,
			format: '<?=$def_dateformate; ?>',
			scrollInput:false,

	});
	
});
</script>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					Редагувати витрату
				</div>
				<div class="panel-body">
					<?php echo form_open('payment/edit_expense/'.$edit_expense['id']); ?>
						<div class="col-md-2 form-group" >
							<label for="expense_date">Дата</label>
							<input type="text" class="form-control" name="expense_date" id="expense_date" value="<?=$edit_expense['expense_date']; ?>"/>
							<?php echo form_error('expense_date','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="col-md-4">
							<label for="cat_id">Категорія</label>
							<?php
								$cat_list = array();
								foreach ($expense_categories as $cat){
									$cat_list[$cat['id']] = $cat['view_id']." ".$cat['title'];
								}
							?>
							<?php echo form_dropdown('cat_id', $cat_list,$edit_expense['cat_id'],'class="form-control"'); ?>
							<?php echo form_error('cat_id','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="col-md-6">
							<label for="user_id">Користувач</label>
							<?php
								$user_list = array();
								foreach ($users as $user){
									$user_list[$user['userid']] = $user['name'];
								}
							?>
							<?php echo form_dropdown('user_id', $user_list,$edit_expense['user_id'],'class="form-control"'); ?>
							<?php echo form_error('user_id','<div class="alert alert-danger">','</div>'); ?>
						</div>
						<div class="col-md-12">
							<div class="form-group input-group">
								<label for="expense_sum">Сума</label>
								<input type="text" class="form-control"  name="sum" id="expense_sum" value="<?=$edit_expense['sum']; ?>"/>
								<?php echo form_error('expense_price','<div class="alert alert-danger">','</div>'); ?>
							</div>
						</div>
						<div class="col-md-12 form-group">
							<label for="expense_title">Призначення</label>
							<input type="text" name="goal" id="expense_title" value="<?=$edit_expense['goal']; ?>" class="form-control"/>
							<?php echo form_error('expense_title','<div class="alert alert-danger">','</div>'); ?>
						</div>						
						<div class="form-group col-md-12">
							<button type="submit" name="submit" class="btn btn-primary"><?php echo $this->lang->line('save');?></button>
						</div>
					</form>
				</div>
			</div>	
		</div>
	</div>
</div>   