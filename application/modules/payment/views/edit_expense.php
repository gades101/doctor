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
if($edit_expense['expense_date'])$edit_expense['expense_date'] = date("d-m-Y H:i",strtotime($edit_expense['expense_date']));
?>
<script type="text/javascript" charset="utf-8">

$( window ).load(function() {
	$('.confirmDelete').click(function(){
			return confirm("Are you sure you want to delete?");
		});
	$('#expense_date').datetimepicker({
			timepicker:true,
			format: 'd-m-Y H:i',
			scrollInput:false,

	});

	$('#user_id').on('change', function(){
		$('#department_id').val($(this).children('option:selected').data('department_id'));
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
						<div class="col-md-2">
							<div class="form-group">
								<label for="expense_date">Дата</label>
								<input type="text" class="form-control" name="expense_date" id="expense_date" value="<?=$edit_expense['expense_date']; ?>"/>
								<?php echo form_error('expense_date','<div class="alert alert-danger">','</div>'); ?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
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
						</div>
						<div class="col-md-6 form-group">
							<label for="user_id">Користувач</label>
								<select id='user_id' name='user_id' class="form-control">
									<?php foreach($users as $user){ ?>									
											<option value="<?php echo $user['userid']; ?>"  data-department_id="<?= $user['department_id']; ?>" <?php if($user['userid']==$edit_expense['user_id']) echo 'selected=true';?> /><?= $user['name']; ?></option>				
									<?php } ?>
								</select>
						</div>	
						<div class="col-md-6 form-group">
								<label for="expense_sum">Сума</label>
								<input type="text" class="form-control"  name="sum" id="expense_sum" value="<?=$edit_expense['sum']; ?>"/>
								<?php echo form_error('expense_price','<div class="alert alert-danger">','</div>'); ?>
						</div>
                        <div class="col-md-6 form-group">
                            <label for="user_id">Відділення</label>
                            <?php
                                $department_list = array();
                                foreach ($departments as $department){
                                    $department_list[$department['department_id']] = $department['department_name'];
                                }
                            ?>
                            <?php echo form_dropdown('department_id', $department_list,$edit_expense['department_id'],'id="department_id" class="form-control"'); ?>
                            <?php echo form_error('department_id','<div class="alert alert-danger">','</div>'); ?>
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