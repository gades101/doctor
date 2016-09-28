

<html>
    <head>
        <title><?php echo $this->lang->line('main_title');?> - <?php echo $this->lang->line('sign_in'); ?></title>
		
		<!-- BOOTSTRAP STYLES-->
		<link href="<?= base_url() ?>assets/css/bootstrap.css" rel="stylesheet" />
		<!-- FONTAWESOME STYLES-->
		<link href="<?= base_url() ?>assets/css/font-awesome.css" rel="stylesheet" />
		<!-- CUSTOM STYLES-->
		<link href="<?= base_url() ?>assets/css/custom.css" rel="stylesheet" />
		<script src="<?= base_url() ?>assets/js/jquery-1.11.3.js"></script>
	</head>
<script type="text/javascript">

</script>
    <body>
        <div class="container">
            <div class="row text-center ">
                <div class="col-md-12">
                    <br /><br />
                    <h2><?php echo $this->lang->line('main_title'); ?></h2>
                    <h5>( <?=$this->lang->line('login_yourself_to_get_access');?> )</h5>
                    <br />
                </div>
            </div>
            <div class="row ">
                <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong><?=$this->lang->line('enter_details_to_login');?></strong>
                        </div>
                        <?php if(isset($error)) { ?><div class="alert alert-danger"><?=$error;?></div><?php } ?>
                        <?php if(isset($message)) { ?><div class="alert alert-info"><?=$message;?></div><?php } ?>
                        <div class="panel-body">
                            <?php echo form_open('login/valid_signin',array('id'=>'main_form')); ?>   
                            <div class="form-group input-group">
                                <span class="input-group-addon"><i class="fa fa-tag"  ></i></span>
                                <input type="text" name="username" id="username" class="form-control" autocomplete="off" placeholder="<?=$this->lang->line('your_username');?>" />
                                <?php echo form_error('username','<div class="alert alert-danger">','</div>'); ?>
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon"><i class="fa fa-lock"  ></i></span>
                                <input type="text" class="form-control" name="password" id="password" autocomplete="off" placeholder="<?=$this->lang->line('your_password');?>" />
                                <?php echo form_error('password','<div class="alert alert-danger">','</div>'); ?>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary"><?php echo $this->lang->line('sign_in');?></button>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>              
        </div>
    </body>
</html>
