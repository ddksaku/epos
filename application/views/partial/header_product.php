<!doctype html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<base href="<?php echo base_url();?>" />
	<title><?php echo $this->config->item('company').' -- '.$this->lang->line('common_powered_by').' ePOS' ?></title>
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/epos.css" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/epos_print.css"  media="print"/>
	<script>BASE_URL = '<?php echo site_url(); ?>';</script>

	<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.css" />
	<script src="<?php echo base_url();?>js/jquery-1.9.1.js"></script>
	<script src="<?php echo base_url();?>js/jquery-ui.js"></script>

	<script src="<?php echo base_url();?>js/common.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/manage_tables.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/swfobject.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<style type="text/css">

    .ui-autocomplete
    {
        max-height: 300px;
        overflow-y: auto;
        overflow-x: hidden;
        font-size:12px;
    }

    html .ui-autocomplete
    {
        height: 300px;
        font-size:12px;
    }

		html {overflow: auto;}
	</style>

	<script type="text/javascript">
	$(document).ready(function(){
		var canvas = document.getElementById('CompanyTitleCanvas');
		var cx = canvas.getContext('2d');
		cx.fillStyle = "#01692E";
		cx.fillRect(20 , 10 , canvas.width , 80);

		var msg = "ECSO";
		cx.font = "normal bold 80px 'Arial'";
		cx.fillStyle="#FFFFFF";
		//context.textBaseline="bottom";
		cx.fillText(msg, 50  ,80);

/*
		var draw_canvas = document.getElementById('drawCanvas');
		var draw_context = draw_canvas.getContext('2d');

		draw_context.fillStyle = "#01692E";
		draw_context.beginPath();
		draw_context.moveTo(100 , 0);
		draw_context.bezierCurveTo(110 , 10 , draw_canvas.width - 150 , -20 , draw_canvas.width - 40 , draw_canvas.height - 25);
		draw_context.quadraticCurveTo(draw_canvas.width - 20 , draw_canvas.height , draw_canvas.width , draw_canvas.height);
		draw_context.lineTo(draw_canvas.width , draw_canvas.height);
		draw_context.lineTo(draw_canvas.width , 0);
		draw_context.closePath();
		draw_context.fill();

		var intro_canvas = document.getElementById('intro');
		var cx_intro = intro_canvas.getContext('2d');

		msg = "u";
		cx_intro.fillStyle = "#FFFFFF";
		cx_intro.font = "normal bold 100px 'Arial'";
		cx_intro.fillText(msg , 0 , 100 , 30);

		msg = "order it, we deliver it";
		cx_intro.fillStyle = "#FFFF00";
		cx_intro.font = "normal bold 70px 'Arial'";
		cx_intro.fillText(msg , 40 , 100 , 230);


		var draw_canvas = document.getElementById('drawCanvas');
		var draw_context = draw_canvas.getContext('2d');

		draw_context.fillStyle = "#01692E";
		draw_context.beginPath();
		draw_context.moveTo(50 , 0);
		draw_context.bezierCurveTo(78 , 100 , 138 , 0 , 180 , draw_canvas.height);
		draw_context.lineTo(draw_canvas.width , draw_canvas.height);
		draw_context.lineTo(draw_canvas.width , 0);
		draw_context.closePath();
		draw_context.fill();
*/
	});

	</script>
</head>
<body>
<div id="menubar">
	<div id="menubar_container">
		<div id="menubar_company_info">
			<canvas id="CompanyTitleCanvas" style="width:100%; height:120px;"></canvas>
		</div>
		<div id="menubar_navigation">
			<canvas id="drawCanvas" style="width:100%; height:100%;"></canvas>
		</div>
<!--
		<div id="menubar_intro">
			<canvas id="intro" style="width:100%; height:100%;"></canvas>
		</div>
 -->
		<div id="menubar_footer">
			<div class="menu_item">
				<a href="<?php echo site_url("home");?>" class="first-link"><?php echo $this->lang->line("module_home"); ?></a>
			</div>
			<?php
				foreach($allowed_modules->result() as $module)
				{
			?>
					<div class="menu_item">
						<a href="<?php echo site_url("$module->module_id");?>" class="first-link"><?php echo $this->lang->line("module_".$module->module_id) ?></a>
					</div>
			<?php
				}
			?>
			<div class="menu_item_logout"><?php echo anchor("home/logout",$this->lang->line("common_logout")); ?></div>

		</div>
	</div>

</div>
<!--
<div id="menubar_footer">

</div>
 -->
<div id="content_area_wrapper" style="position:absolute; height:200%;">


<div id="content_area">
