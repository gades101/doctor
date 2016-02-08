<script type="text/javascript">
function readURL(input) {
	if (input.files && input.files[0]) {//Check if input has files.
		var reader = new FileReader(); //Initialize FileReader.

		reader.onload = function (e) {
		$('#PreviewImage').attr('src', e.target.result);
		$("#PreviewImage").resizable({ aspectRatio: true, maxHeight: 300 });
		};
		reader.readAsDataURL(input.files[0]);
	}else {
		$('#PreviewImage').attr('src', "#");
	}
}
$( window ).load(function() {

	$('.confirmDelete').click(function(){
		return confirm("Are you sure you want to delete?");
	})

});
<?php
	$contact_id = 0;
	$first_name = "";
	$middle_name = "";
	$last_name = "";
	$profile_image = "";
	$address_type = "";
	$address_line_1 = "";
	$address_line_2 = "";
	$city = "";
	$state = "";
	$country = "";
	$postal_code = "";
	$joining_date = "";
	$experience = "";
	$specification = "";
	$gender  = "";
	$degree = "";
	$email = "";
	$phone_number = "";
	$licence_number = "";
	$department_id = 0;
	$user_id = "";
	if(isset($contacts)){
		$first_name = $contacts['first_name'];
		$middle_name = $contacts['middle_name'];
		$last_name = $contacts['last_name'];
		$profile_image = $contacts['contact_image'];
		$address_type = $contacts['type'];
		$address_line_1 = $contacts['address_line_1'];
		$address_line_2 = $contacts['address_line_2'];
		$city = $contacts['city'];
		$state = $contacts['state'];
		$country = $contacts['country'];
		$postal_code = $contacts['postal_code'];
		$email = $contacts['email'];
		$phone_number = $contacts['phone_number'];

	}
	if(isset($doctor_details)){
		$contact_id = $doctor_details['contact_id'];
		$joining_date = $doctor_details['joining_date'];
		$experience  = $doctor_details['experience'];
		$specification = $doctor_details['specification'];
		$gender = $doctor_details['gender'];
		$degree = $doctor_details['degree'];
		$licence_number = $doctor_details['licence_number'];
		$department_id = $doctor_details['department_id'];
		$user_id = $doctor_details['userid'];
	}
?>
<?php $category = $_SESSION['category'];?>
</script>
<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<?= $this->lang->line('doctor')." (персональні дані)";?>
				</div>
				<div class="panel-body">
					<?php if ($file_error != ""){ ?>
						<div class="alert alert-danger"><?=$file_error;?></div>
					<?php } ?>
					<?php if ($doctor_id != 0){ ?>
						<?php echo form_open_multipart('doctor/doctor_detail/'.$doctor_id) ?>
						<input type="hidden" name="doctor_id" class="inline" value="<?=$doctor_id;?>"/>
						<input type="hidden" name="contact_id" class="inline" value="<?=$contact_id;?>"/>
					<?php }else{ ?>
						<?php echo form_open_multipart('doctor/doctor_detail/') ?>
					<?php } ?>


						<div class="col-md-12">
							<div class="col-md-2">
								<label for="first_name"><?php echo "ПІБ"; ?></label>
							</div>
							<div class="col-md-1">
								<label for="first_name"></label>
							</div>
							<div class="col-md-3">
								<input type="input" class="form-control" name="first_name" placeholder="Прізвище" value="<?=$first_name; ?>"/>
								<?php echo form_error('first_name','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="col-md-3">
								<input type="input" class="form-control" name="middle_name" placeholder="Ім'я" value="<?=$middle_name; ?>"/>
								<?php echo form_error('middle_name','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="col-md-3">
								<input type="input" class="form-control" name="last_name"  placeholder="По батькові" value="<?=$last_name; ?>"/><br/>
								<?php echo form_error('last_name','<div class="alert alert-danger">','</div>'); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="gender"><?php echo "Стать ";?></label>
								<input type="radio" name="gender" value="чоловік" <?php if($gender == 'чоловік'){echo "checked='checked'";}?>/>Чоловік
								<input type="radio" name="gender" value="жінка" <?php if($gender == 'жінка'){echo "checked='checked'";}?>/>Жінка
								<?php echo form_error('gender','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="degree">Ступені</label>
								<input type="input" name="degree" class="form-control" value="<?=$degree;?>"/>
								<?php echo form_error('degree','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="specification">Спеціалізація</label>
								<input type="input" name="specification" value="<?=$specification;?>" class="form-control"/>
								<?php echo form_error('specification','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="experience">Стаж</label>
								<input type="input" name="experience" value="<?=$experience;?>" class="form-control"/>
								<?php echo form_error('experience','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="email"><?php echo $this->lang->line("email");?></label>
								<input type="input" name="email" value="<?=$email;?>" class="form-control"/>
								<?php echo form_error('email','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="phone_number"><?php echo $this->lang->line("phone_number");?></label>
								<input type="input" name="phone_number" value="<?=$phone_number;?>" class="form-control"/>
								<?php echo form_error('phone_number','<div class="alert alert-danger">','</div>'); ?>
							</div>

							<div class="form-group">
								<label for="joining_date"><?php echo "Дата початку роботи";?></label>
								<input type="date" name="joining_date" value="<?=$joining_date;?>" class="form-control"/>
								<?php echo form_error('joining_date','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="licence_number"><?php echo "Номер Ліцензії";?></label>
								<input type="input" name="licence_number" value="<?=$licence_number;?>" class="form-control"/>
								<?php echo form_error('licence_number','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="department_id"><?php echo "Відділ";?></label>
								<select name="department_id" class="form-control">  <option></option>
									<?php if(isset($departments)) { ?>
										<?php  foreach ($departments as $department) { ?>
										<option value="<?=$department['department_id'] ?>" <?php if($department['department_id'] == $department_id){echo "selected='selected'";}?>><?= $department['department_name']; ?> </option>
										<?php } ?>
									<?php } ?>
								</select>
								<?php echo form_error('department_id','<div class="alert alert-danger">','</div>'); ?>
							</div>

							<div class="form-group">
								<button class="btn square-btn-adjust btn-primary" type="submit" name="submit" /><?php echo $this->lang->line("save");?></button>
								<a href="<?php echo base_url()."index.php/doctor/index/" ?>" class="btn square-btn-adjust btn-primary" /><?php echo $this->lang->line("back");?></a>
								<?php if($category != 'Doctor'){ ?>
								<a href="<?php echo base_url()."index.php/doctor/delete_doctor/".$doctor_id ?>" class="btn square-btn-adjust btn-danger confirmDelete" /><?php echo $this->lang->line("delete");?></a>
								<?php } ?>
								<?php if($user_id != ""){ ?>
								<a class="btn square-btn-adjust btn-warning" href="<?php echo site_url("admin/edit_user/" . $user_id); ?>"><?php echo "Редагувати користувача ";?></a>
								<?php } ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<?php if($profile_image!=""){ ?>
								<img id="PreviewImage" src="<?php echo base_url().$contacts['contact_image']; ?>" alt="Profile Image"  height="100" width="100" />
								<?php }else{ ?>
								<img id="PreviewImage" src="<?php echo base_url()."images/Profile.png"; ?>" alt="Profile Image"  height="100" width="100" />
								<?php } ?>
								<input class="form-control" type="file" id="file_name" name="file_name" size="20" onchange="readURL(this);" />
								<?php echo form_error('file_name','<div class="alert alert-danger">','</div>'); ?>
								<input type="hidden" id="src" name="src" value="" />
							</div>
							<div class="form-group">
								<label for="type"><?php echo $this->lang->line("address")." ".$this->lang->line("type");?></label>
								<select name="type" class="form-control">
									<option></option>
									<option value="Home" <?php if($address_type == "Home") {echo "selected='selected'";} ?>><?php echo $this->lang->line("home");?></option>
									<option value="Office" <?php if($address_type == "Office") {echo "selected='selected'";} ?>><?php echo $this->lang->line("office");?></option>
								</select>
								<?php echo form_error('type','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="address_line_1"><?php echo $this->lang->line("address")." ".$this->lang->line("line1");?></label>
								<input type="input" name="address_line_1" value="<?=$address_line_1;?>" class="form-control"/>
								<?php echo form_error('address_line_1','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="address_line_2"><?php echo $this->lang->line("address")." ".$this->lang->line("line2");?></label>
								<input type="input" name="address_line_2" value="<?=$address_line_2;?>" class="form-control"/>
								<?php echo form_error('address_line_2','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="city"><?php echo $this->lang->line("city");?></label>
								<input type="input" name="city" value="<?=$city;?>" class="form-control"/>
								<?php echo form_error('city','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="state"><?php echo $this->lang->line("state");?></label>
								<input type="input" name="state" value="<?=$state;?>" class="form-control"/>
								<?php echo form_error('state','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="postal_code"><?php echo $this->lang->line("postal_code");?></label>
								<input type="input" name="postal_code" value="<?=$postal_code;?>" class="form-control"/>
								<?php echo form_error('postal_code','<div class="alert alert-danger">','</div>'); ?>
							</div>
							<div class="form-group">
								<label for="country"><?php echo $this->lang->line("country");?></label>
								<input type="input" name="country" value="<?=$country;?>" class="form-control"/>
								<?php echo form_error('country','<div class="alert alert-danger">','</div>'); ?>
							</div>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>