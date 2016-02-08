<!DOCTYPE html>
<?php
	$level = $_SESSION['category'];
?>
<html>
    <head>
        <title><?php echo $this->lang->line('main_title');?></title>
  		<meta http-equiv="Content-Type" charset=utf-8"> 

        <!-- BOOTSTRAP STYLES-->
		<link href="<?= base_url() ?>assets/css/bootstrap.css" rel="stylesheet" />
		<!-- JQUERY UI STYLES-->
		<link href="<?= base_url() ?>assets/css/jquery-ui-1.9.1.custom.min.css" rel="stylesheet" />
		<!-- FONTAWESOME STYLES-->
		<link href="<?= base_url() ?>assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
		<link href="<?= base_url() ?>assets/css/custom.css" rel="stylesheet" />
		<!-- CHIKITSA STYLES-->
		<link href="<?= base_url() ?>assets/css/chikitsa.css" rel="stylesheet" />
		<!-- TABLE STYLES-->
		<link href="<?= base_url() ?>assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />


		<!-- JQUERY SCRIPTS -->
		<script src="<?= base_url() ?>assets/js/jquery-1.11.3.js"></script>
		<!-- JQUERY UI SCRIPTS -->
		<script src="<?= base_url() ?>assets/js/jquery-ui.js"></script>
		<!-- BOOTSTRAP SCRIPTS -->
		<script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
		<!-- METISMENU SCRIPTS -->
		<script src="<?= base_url() ?>assets/js/jquery.metisMenu.js"></script>
		 <!-- DATA TABLE SCRIPTS -->
		<script src="<?= base_url() ?>assets/js/dataTables/jquery.dataTables.js"></script>
		<script src="<?= base_url() ?>assets/js/dataTables/dataTables.bootstrap.js"></script>
		<script src="<?= base_url() ?>/assets/js/dataTables/moment.min.js"></script>
		<script src="<?= base_url() ?>/assets/js/dataTables/datetime-moment.js"></script>
		<!-- TimePicker SCRIPTS-->
		<script src="<?= base_url() ?>assets/js/jquery.datetimepicker.js"></script>
		<link href="<?= base_url() ?>assets/js/jquery.datetimepicker.css" rel="stylesheet" />
		<!-- CHOSEN SCRIPTS-->
		<script src="<?= base_url() ?>assets/js/chosen.jquery.min.js"></script>
		<link href="<?= base_url() ?>assets/css/chosen.min.css" rel="stylesheet" />
		<!-- Lightbox SCRIPTS-->
		<script src="<?= base_url() ?>assets/js/lightbox.min.js"></script>
		<link href="<?= base_url() ?>assets/css/lightbox.css" rel="stylesheet" />
		<!-- Sketch SCRIPTS-->
		<script src="<?= base_url() ?>assets/js/sketch.js"></script>
		<!-- CUSTOM SCRIPTS -->
		<script src="<?= base_url() ?>assets/js/custom.js"></script>
    </head>
    <body>
        <?php


            $query = $this->db->get('clinic');
            $clinic = $query->row_array();

            $user_id = $_SESSION['id'];
            $this->db->where('userid', $user_id);
            $query = $this->db->get('users');
            $user = $query->row_array();
        ?>
        <div id="wrapper">
		<nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
				<?php if($clinic['clinic_logo'] != NULL){  ?>
					<a class="navbar-brand" style="padding:0px;background:#FFF;" href="<?= site_url("appointment/index"); ?>">
						<img src="<?php echo base_url().$clinic['clinic_logo']; ?>" alt="Logo"  height="60" width="260" />
					</a>
				<?php  }elseif($clinic['clinic_name'] != NULL){  ?>
					<a class="navbar-brand" href="<?= site_url("appointment/index"); ?>">
						<?= $clinic['clinic_name'];?>
					</a>
				<?php  } else { ?>
					<a class="navbar-brand" href="<?= site_url("appointment/index"); ?>">
						<?= $this->lang->line('app_name');?>
					</a>
				<?php }  ?>
            </div>
			<div style="color: white;float:left;font-size: 16px;margin-left:25px;">
                    <h4><?php if($clinic['tag_line'] == NULL){
								echo $this->lang->line('tag_line');
							  }else {
								echo $clinic['tag_line'];
							  } ?>
					</h4>
            </div>
			<div style="color: white;padding: 15px 50px 5px 50px;float: right;font-size: 16px;">
				Ласкаво просимо, <?=$user['name']; ?>
				<a href="<?=site_url("admin/change_profile"); ?>" class="btn btn-primary square-btn-adjust">Змінити профіль</a>
				<a href="<?= site_url("login/logout"); ?>" class="btn btn-danger square-btn-adjust"><?php echo $this->lang->line('log_out');?></a>
			</div>
        </nav>