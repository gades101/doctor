<script type="text/javascript" charset="utf-8">
	$(window).load(function(){
		$('.confirmCancel').click(function(){
			return confirm("<?=$this->lang->line('areyousure') . " " . $this->lang->line('cancel') . " " . $this->lang->line('appointment') . "?";?>");
		});

		$(".todo").change(function() {
			var element = $(this);
			var id = $(this).val();
			if($(this).is(':checked')){

				$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>index.php/appointment/todos_done/1/" + id,
					success: function(){
						element.parent().addClass("done");
					}
				});
			}else{
				$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>index.php/appointment/todos_done/0/" + id,
					success: function(){
						element.parent().removeClass("done");
					}
				});
			}
		});


	});
</script>
<?php
global $time_intervals;
global $doctor_inavailability;
global $doctors_details;
global $doctors_schedules;
global $day_of_week;
global $g_day;
global $g_month;
global $g_year;

$ukrday=array('Неділя','Понеділок','Вівторок','Середа','Четвер','П\'ятниця','Субота');
$day_of_week = $ukrday[date('w', strtotime($day . "-" . $month . "-" . $year))];
$g_day = $day;
$g_month = $month;
$g_year = $year;


if($doctor_active){
	$doctor_inavailability = $inavailability;
	$doctors_details = $doctors_data;
	$doctors_schedules = $drschedules;
}else{
	$doctor_inavailability = array();
	$doctors_details = array();
	$doctors_schedules = array();
}

//Converts Integer to Time. e.g. 9 -> 9:00 , 9.5 -> 9:30
function inttotime12($tm,$time_format) {
    //if ($tm >= 13) {  $tm = $tm - 12; }
	$time_format='H:i';
    $hr = intval($tm);
    $min = ($tm - intval($tm)) * 60;
    $format = '%02d:%02d';
	$time = sprintf($format, $hr, $min); //H:i
	$time = date($time_format, strtotime($time));
	return $time;
}
//Convert Time to integer.e.g. 09:00 -> 9, 09:30 -> 9.5
function timetoint12($time)
{
	$hours = idate('H', strtotime($time));
	$minutes = idate('i', strtotime($time));

	return $hours + ($minutes/60);
}

function inttotime($tm) {
    $hr = intval($tm);
    $min = ($tm - intval($tm)) * 60;
    $format = '%02d:%02d';
    return sprintf($format, $hr, $min);
}
function timetoint($time) {
    $hrcorrection = 0;
    if (strpos($time, 'PM') > 0){  $hrcorrection = 12;}
    list($hours, $mins) = explode(':', $time);
    $mins = str_replace('AM', '', $mins);
    $mins = str_replace('PM', '', $mins);
    return $hours + $hrcorrection + ($mins / 60);
}
function nearest_timeinterval($time){
	global $time_intervals;

	$prev_interval = 0;
	$next_interval = 0;
	foreach($time_intervals as $intervals){
		if($next_interval == 0 && $prev_interval !=0 ){
			$next_interval = $intervals;
		}
		if($prev_interval == 0){
			$prev_interval = $intervals;
		}
		if($time >= $prev_interval && $time < $next_interval){
			//Find Median
			$median = ($prev_interval + $next_interval)/2;
			if ($time < $median){
				return $prev_interval;
			}else{
				return $next_interval;
			}
		}else{
			$prev_interval = $next_interval;
			$next_interval = $intervals;
		}
	}
	return $next_interval;
}

function check_doctor_availability($i,$doctor_id){
	global $doctor_inavailability;
	global $doctors_details;
	global $doctors_schedules;
	global $day_of_week;
	global $g_day;
	global $g_month;
	global $g_year;

	$today =date('Y-m-d', strtotime($g_day . "-" . $g_month . "-" . $g_year));

	$doctor_is_available = TRUE;

	//Is this Doctors' Schedule Available?
	foreach ($doctors_details as $doctor_data){
		foreach ($doctors_schedules as $drschedules_availability){
			if($drschedules_availability['doctor_id']==$doctor_data['doctor_id']){
				if ($doctor_data['userid']==$doctor_id){
					//Except Schedule, Doctor is not available
					$doctor_is_available = FALSE;
					break;
				}
			}
		}
	}

	//Is this Doctor's Schedule?
	foreach ($doctors_details as $doctor_data){
		if ($doctor_data['userid']==$doctor_id){
			foreach ($doctors_schedules as $drschedules_availability){
				if($drschedules_availability['doctor_id']==$doctor_data['doctor_id']){
					$schedule_day = $drschedules_availability['schedule_day'];
					if (strpos($schedule_day,$day_of_week) !== false) {
						if ($i>= timetoint($drschedules_availability['from_time']) && $i<= timetoint($drschedules_availability['to_time']) ){
							//Doctor is not available
							$doctor_is_available = TRUE;
							break;
						}
					}
				}
			}
		}
	}
	//Is Doctor Out?
	if ($doctor_is_available){
		foreach ($doctor_inavailability as $inavailability){
			if ($inavailability['userid']==$doctor_id){
				if($today >= $inavailability['appointment_date'] && $today <= $inavailability['end_date']){
					if ($i>=timetoint($inavailability['start_time']) && $i<timetoint($inavailability['end_time'])){
						//Doctor is not available
						$doctor_is_available = FALSE;
					}
				}
			}
		}
	}
	return $doctor_is_available;
}

?>
<div id="page-inner">
    <div class="row">
		<div class="col-md-12">
			<div class="col-md-4">
				<!----------------------------  Display Calendar ----------------------------- -->
				<div class="panel panel-primary">
                    <div class="panel-heading">
						<?=$this->lang->line('calendar')?>
					</div>
                    <div class="panel-body" style="padding:0;">
						<div class="calendar">
						<?php
							for ($i = 1; $i <= 31; $i++) {
	$data[$i] = base_url() . 'index.php/appointment/index/'. $dep. '/' . $year . '/' . $month . '/' . $i;
							}
							echo $this->calendar->generate($year, $month, $data);
						?>
						</div>
						<div class="col-md-4">
						</div>
						<div class="col-md-4">
						<?php
							$today_year = date("Y");
							$today_month = date("m");
							$today_day = date("d");
						?>
						<a href="<?=site_url('appointment/index/'. $dep. '/' . $today_year.'/'.$today_month.'/'.$today_day);?>" class="btn btn-sm square-btn-adjust btn-success">Сьогодні</a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<!--------------------------- Display Event  ------------------------------->
				<div class="panel panel-primary">
                    <div class="panel-heading">Події</div>
					<div class="panel-body"  style="overflow:scroll;height:250px;padding:0;">
						<?php if ($events) { ?>
							<table class="table table-condensed table-striped table-bordered table-hover dataTable no-footer" id="followup_table">
								<thead>
									<th><?= $this->lang->line('date');?></th>
									<th>Подія</th>
								</thead>
								<tbody>
								<?php
									$i = 0;
									$time_now=time();
									foreach ($events as $event) {
										$event_class="";
										if ($i==0){
											$time_event=mktime(24,0,0,$event['month'],$event['day'],$event['year']);
											$difference=$time_event-$time_now;
											if ($difference<=2592000){
												if($difference<=604800){
													if($difference<=259200){
														$event_class="event_three";
													}
													else $event_class="event_week";
												}
												else $event_class="event_month";											
											}
											else $i=1;
										}
								?>
										<tr>
											<td class='<?= $event_class; ?>'> <a href='<?= base_url() ."index.php/event/edit_event/".$event["id"] ?>'><?= $event['day'].'/'.$event['month'].'/'.$event['year'];?></a></td>
											<?php if ($event['patient_id']) {?>
												<td><a href='<?= base_url() ."index.php/patient/edit/".$event["patient_id"]."/patient" ?>'><?=$event['title'];?></a></td>											
											<?php } else { ?>
												<td><?=$event['title'];?></td>
											<?php } ?>
										</tr>
								<?php 
									} 
								?>
								</tbody>
							</table>
						<?php }	?>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="panel panel-primary">
					<div class="panel-heading"><?=$this->lang->line('tasks');?></div>
					<div class="panel-body">
					<!--------------------------- Display To Do  ------------------------------->
					<?php echo form_open('appointment/todos'); ?>
						<div class="input-group">
							<input type="text" name="task"  class="form-control">
							<span class="form-group input-group-btn">
								<input type="submit" class="btn btn-primary" value='<?=$this->lang->line('submit');?>' />
							</span>
						</div>
					<?php echo form_close(); ?>
					<?php foreach ($todos as $todo) { ?>
						<div class="checkbox">
                            <label class="<?php if ($todo['done'] == 1) {echo 'done';} else {echo 'not_done';} ?>">
								<input type="checkbox" class="todo" name='todo' <?php if ($todo['done'] == 1) {echo 'checked="checked"';} ?> value="<?=$todo['id_num'];?>" /><?=$todo['todo'];?>
							</label>
							<a class='todo_img' href='<?=base_url() . "index.php/appointment/delete_todo/" . $todo['id_num'];?>'><i class='fa fa-remove'></i></a>
                        </div>
					<?php } ?>
					</div>
				</div>
				<!--------------------------- Display To Do  ------------------------------->
			</div>
			<div class="col-md-12">
				<!--------------------------- Display Appointments  ------------------------------->
				<div class="panel panel-primary">
                    <div class="panel-heading">
		<?php list($d,$n,$y,$w)=explode(' ',date('d n Y w', strtotime($day . "-" . $month . "-" . $year)));
		$ukrmon=array('Січня','Лютого', 'Березня', 'Квітня', 'Травня', 'Червня', 'Липня', 'Серпня', 'Вересня', 'Жовтня', 'Листопада', 'Грудня');
		echo $d.' '.$ukrmon[$n-1].' '.$y.', '.$ukrday[$w];


?>
		<?php $day_date=date('d', strtotime($day . "-" . $month . "-" . $year));?>
		<?php //$day = date('l', strtotime($day . "-" . $month . "-" . $year));
		?>

		
                    </div>
                    <div class="panel-body">
					<?php
						$level = $_SESSION['category'];
						//Clinic Start Time and Clinic End Time
						$start_time = timetoint($start_time);
						$end_time = timetoint($end_time);

					?>
						<!--------------------------- Display Doctor's Screen  ----------------------------- -->
					<?php if ($level == 'Doctor') {?>
						<div class="table-responsive"  style='position:relative;'>
							<table class="table table-condensed table-striped table-bordered table-hover dataTable no-footer"  >
								<thead>
									<tr>
										<th class='appTime'><?=$this->lang->line('time');?></th>
										<th class='docAppTable' style='width:20%'><?=$this->lang->line('appointments');?></th>
										<th class='docAppTable'><?=$this->lang->line('waiting');?></th>
										<th class='docAppTable'><?=$this->lang->line('consultation');?></th>
										<th class='docAppTable'>Завершені</th>
										<th class='docAppTable'>Скасовані</th>
									</tr>
								</thead>
								<tbody>
								<?php
									//Doctor ID
									$userid = $_SESSION['id'];
									global $time_intervals;
									$time_intervals = array();
									for ($i = $start_time; $i < $end_time; $i = $i + $time_interval) {
										$time = explode(":",inttotime($i));
										$time_intervals[] = $i*100;
										//echo $time;
										?>
										<tr>
											<th><?=inttotime12( $i ,$time_format);?></th><!-- Display the Time -->
											<td id="app<?=$i*100;?>" class="appointments"><a href='<?=base_url() . "index.php/appointment/add/"  . $year . "/" . $month . "/" . $day_date . "/" . $time[0] . "/" . $time[1] . "/Appointments" ?>' class="add_appointment"></a></div></td>
											<td id="wai<?=$i*100;?>" class="waiting"><a href='<?=base_url() . "index.php/appointment/add/" .  $year . "/" . $month . "/" . $day_date . "/" . $time[0] . "/" . $time[1] . "/Waiting" ?>' class="add_appointment" ></a></div></td>
											<td id="con<?=$i*100;?>" class="consultation"><a href='<?=base_url() . "index.php/appointment/add/" . $year . "/" . $month . "/" . $day . "/" . $time[0] . "/" . $time[1] . "/Consultation" ?>' class="add_appointment" ></a></div></td>
											<td id="com<?=$i*100;?>" class="complete"></td>
											<td id="can<?=$i*100;?>" class="cancel"></td>
										</tr>
							<?php }
								foreach ($appointments as $appointment) {
									$patient_id = $appointment['patient_id'];

									$appointment_id = $appointment['appointment_id'];
									if (strlen($appointment['title'])>12){
										$appointment_title = mb_substr($appointment['title'],0,9)."..." ;
									}else{
										$appointment_title = $appointment['title'];
									}
									//Check if there are any more appointments of same time


									$start_position =  timetoint($appointment['start_time'])*100;
									$start_position = round($start_position);
									$start_position = nearest_timeinterval($start_position);

									$end_position =  timetoint($appointment['end_time'])*100;
									$end_position = round($end_position);
									$end_position = nearest_timeinterval($end_position);

									$appointment_column = 0;
									$nxt=false;
									$ca=false;
									switch($appointment['status']){
										case 'Appointments':
											$class = "btn-primary";
											$start_position = "app".$start_position;
											$end_position = "app".$end_position;
											$href = base_url() . "index.php/appointment/edit_appointment/" . $appointment_id ;
											$nxt=true;
											$nextstatus= base_url() ."index.php/appointment/change_status/". $appointment_id."/Waiting";
											$ca=true;
											$cancelapp= base_url() ."index.php/appointment/change_status/". $appointment_id."/Cancel";
											$d_width='60%';
											break;
										case 'Consultation':
											$class = "btn-danger";
											$start_position = "con".$start_position;
											$end_position = "con".$end_position;
											$href = base_url() . "index.php/patient/visit/" . $patient_id ."/" . $appointment_id ;
											$nxt=false;
											$ca=false;
											$d_width='100%';
											break;
										case 'Complete':
											$class = "btn btn-success";
											$start_position = "com".$start_position;
											$end_position = "com".$end_position;
											$href = base_url() . "index.php/patient/visit/" . $patient_id ."/" . $appointment_id ;
											$nxt=false;
											$ca=false;
											$d_width='100%';
											break;
										case 'Cancel':
											$class = "btn btn-info";
											$start_position = "can".$start_position;
											$end_position = "can".$end_position;
											$href = base_url() . "index.php/appointment/edit_appointment/" . $appointment_id ;
											$nxt=false;
											$ca=false;
											$d_width='100%';
											break;
										case 'Waiting':
											$class = "btn-warning";
											$start_position = "wai".$start_position;
											$end_position = "wai".$end_position;
											$href = base_url() . "index.php/appointment/edit_appointment/" . $appointment_id ;
											$nxt=true;
											$nextstatus= base_url() ."index.php/appointment/change_status/". $appointment_id."/Consultation";
											$ca=true;
											$cancelapp= base_url() ."index.php/appointment/change_status/". $appointment_id."/Cancel";
											$d_width='60%';
											break;
										default:
											break;
									}
													
						?>

									<div id="<?=$appointment_id;?>" start_position="<?=$start_position;?>" end_position="<?=$end_position;?>" appointment_column="<?=$appointment_column;?>"  style="display:none;" >
										<a href='<?=$href;?>' title="<?=$appointment['title'];?>" class="btn square-btn-adjust <?=$class;?>" style="height:100%;width:<?=$d_width;?>"; ><?= $appointment_title;?></a><?php if ($nxt){?><a href='<?=$nextstatus;?>' class="btn square-btn-adjust <?=$class;?> " style="height:100%;width:20%;"><i class="fa fa-arrow-circle-right"></i></a><?php } ?><?php if($ca){ ?><a href='<?=$cancelapp;?>'class="btn square-btn-adjust <?=$class;?>" style="height:100%;width:19%;"><i class="fa fa-times"></i></a><?php } ?>
									</div>
									<script>
										//$(window).load(function() {
											var start_position = $("#<?=$appointment_id;?>").attr( "start_position" );
											var end_position = $("#<?=$appointment_id;?>").attr( "end_position" );
											var s_position = $( "#" + start_position ).position();
											var e_position = $( "#" + end_position ).position();
											var height = e_position.top - s_position.top - 2;
											var elem_width=$("#"+start_position).outerWidth()-1;
											var appointment_column = $("#<?=$appointment_id;?>").attr( "appointment_column" ) - 1;
											var width = 100;
											width = width + 2;
											var element_left = s_position.left;// + ( appointment_column * width );
											$("#<?=$appointment_id;?>").attr("style","position:absolute;top:"+ s_position.top +"px;left:" + element_left +"px;height:"+height+"px;width:"+elem_width+"px");
											$("#<?=$appointment_id;?>").show();
										//});
									</script>
									<?php
									}

									?>
								</tbody>
							</table>
						</div>
					<?php
					} else {
					?><!--------------------------- Display Administration's Screen / Staff Scrren  ----------------------------- -->
					<div class="table-responsive"  style='position:relative;overflow:scroll;'>
						<a href="<?=site_url('appointment/add/');?>" class="btn square-btn-adjust btn-primary">Додати прийом</a>
						<table class="table table-condensed table-striped table-bordered table-hover dataTable no-footer"  >
							<thead>
								<tr>
									<th class='appTime'><?=$this->lang->line('time');?></th>
									<?php
									foreach ($doctors as $doctor) {
										if (strlen($doctor['name'])>12){
											$doctor_name = mb_substr($doctor['name'],0,10).".." ;
										}else{
											$doctor_name = $doctor['name'];
										}
									?>
									<th class='appDoc'><?=$doctor_name;?></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php
								global $time_intervals;
								$time_intervals = array();

								for ($i = $start_time; $i < $end_time; $i = $i + $time_interval){
									$time = explode(":",inttotime($i));
									$time_intervals[] = $i*100;
									?>
										<tr>
										<th><?=inttotime12( $i ,$time_format);?></th><!-- Display the Time -->
									<?php
										foreach ($doctors as $doctor) {
											$doctor_is_available = check_doctor_availability($i,$doctor['userid']);
											/*
											if($doctor_active){
												if ($doctor_is_available){
													foreach ($doctors_data as $doctor_data){
														foreach ($drschedules as $drschedules_inavailability){
															if($drschedules_inavailability['doctor_id']==$doctor_data['doctor_id']){
																if ($doctor_data['userid']==$doctor['userid']){
																	$schedule_day = $drschedules_inavailability['schedule_day'];
																	if (strpos($schedule_day,$day) !== false) {
																		if ($i>= timetoint($drschedules_inavailability['from_time']) && $i<= timetoint($drschedules_inavailability['to_time']) ){
																			//Doctor is not available
																			$doctor_is_available = TRUE;
																			break;
																		}else{
																			$doctor_is_available = FALSE;
																		}
																	}else{
																		$doctor_is_available = FALSE;
																		break;
																	}
																}
															}
														}
													}
												}
											}*/
											if ($doctor_is_available){
												?><td id="<?=$doctor['userid'];?>_<?=$i*100;?>"><a href='<?=base_url() . "index.php/appointment/add/" . $year . "/" . $month . "/" . $day_date . "/" . $time[0] . "/" . $time[1] . "/Appointments/0/".$doctor['userid'] ?>' class="add_appointment"></a></td>	<?php
											}else{
												?><td id="<?=$doctor['userid'];?>_<?=$i*10;?>" bgcolor="gray"></td><?php
											}

										} ?>
										</tr>
								<?php }
									$cell = array();
									foreach ($appointments as $appointment) {
										$patient_id = $appointment['patient_id'];
										$appointment_id = $appointment['appointment_id'];
										$doctor_id = $appointment['userid'];
										if (strlen($appointment['title'])>12){
											$appointment_title = mb_substr($appointment['title'],0,10).".." ;
										}else{
											$appointment_title = $appointment['title'];
										}
										$start_position = timetoint($appointment['start_time'])*100;
										$start_position = round($start_position);
										$start_position = nearest_timeinterval($start_position);

										$end_position =  timetoint($appointment['end_time'])*100;
										$end_position = round($end_position);
										$end_position = nearest_timeinterval($end_position);

										$appointment_column = 0;
										//Select a column inside the doctor column
										for($column = 1;$column <= 3;$column = $column + 1){
											//Check if cell is empty
											if(isset($cell[$doctor_id][$start_position][$column]) && ($cell[$doctor_id][$start_position][$column] != 0)){
												//Cell is occupied
											}else{
												//Cell is not occupied
												$cell[$doctor_id][$start_position][$column] = $appointment_id;
												$appointment_column = $column-1;
												break;
											}
										}


										switch($appointment['status']){
											case 'Appointments':
												$class=($appointment['payment_id']>0) ? "btn-success" : "btn-primary";
												$href = base_url() . "index.php/appointment/edit_appointment/" . $appointment_id ;
												break;
											case 'Consultation':
												$class = "btn-danger";
												$href = base_url() . "index.php/appointment/edit_appointment/" . $appointment_id ;
											//$href = base_url() . "index.php/patient/visit/" . $patient_id."/".$appointment_id ;
												break;
											case 'Complete':
												$class = "btn-success";
												$href = base_url() . "index.php/appointment/view_appointment	/". $appointment_id  ;
												break;
											case 'Cancel':
												$class = "btn-info";
												$href = base_url() . "index.php/appointment/edit_appointment/" . $appointment_id ;
												break;
											case 'Waiting':
												$class = "btn-warning";
												$href = base_url() . "index.php/appointment/edit_appointment/" . $appointment_id ;
												break;
											default:
												$class = "btn-primary";
												$href = base_url() . "index.php/appointment/edit_appointment/" . $appointment_id ;
												break;
										}
										$start_position = $appointment['userid']."_".$start_position;
										$end_position = $appointment['userid']."_".$end_position;
								?>
									<div id="<?=$appointment_id;?>" class="appoint" start_position="<?=$start_position;?>" end_position="<?=$end_position;?>" appointment_column="<?=$appointment_column;?>"  style="display:none;" >
<a href='<?=$href;?>' title="<?=$appointment['title'];?>" class="btn square-btn-adjust <?=$class;?> " style="height:100%;width:100%;">
											<?= $appointment_title;?>
										</a>
									</div>

									<script>
										//$(window).load(function() {
											var start_position = $("#<?=$appointment_id;?>").attr( "start_position" );
											var end_position = $("#<?=$appointment_id;?>").attr( "end_position" );
											var s_position = $( "#" + start_position ).position();
											var e_position = $( "#" + end_position ).position();
											var height = e_position.top - s_position.top - 2;

											var appointment_column = $("#<?=$appointment_id;?>").attr( "appointment_column" ) ;
											var width = 100;
											width = width + 2;
											var elem_width=$("#"+start_position).outerWidth()-1;
											var element_left = s_position.left + ( appointment_column * width );
											$("#<?=$appointment_id;?>").attr("style","position:absolute;top:"+ s_position.top +"px;left:" + element_left +"px;height:"+height+"px;width:"+elem_width+"px");
											$("#<?=$appointment_id;?>").show();
										//});
									</script>
									<?php
									}
									?>
							</tbody>
						</table>
					</div>
                    <?php
                }

			echo "</tbody></table>";
			?>
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-6">
					<span class="btn square-btn-adjust btn-primary"><?=$this->lang->line('appointment').'и';?></span>
				</div>
				<div class="col-md-6">
					<span class="btn square-btn-adjust btn-danger">Консультації</span>
				</div>
				<div class="col-md-6">
					<span class="btn square-btn-adjust btn-success"><?='Завершені '. $this->lang->line('appointment').'и';?></span>
				</div>
				<div class="col-md-6">
					<span class="btn square-btn-adjust btn-info"><?='Відмінені '. $this->lang->line('appointment').'и';?></span>
				</div>
				<div class="col-md-6">
					<span class="btn square-btn-adjust btn-warning"><?=$this->lang->line('waiting');?></span>
				</div>
				<div class="col-md-6">
					<span class="btn square-btn-adjust btn-grey"><?=$this->lang->line('not_available');?></span>
				</div>
            </div>
			<?php
			echo "</div></br>";
			?>
			</div>

		</div>
	</div>
</div>