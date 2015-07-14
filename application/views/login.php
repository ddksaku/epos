<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/login.css" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>ePOS <?php echo $this->lang->line('login_login'); ?></title>
		<script src="<?php echo base_url();?>js/jquery-1.2.6.min.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
		<script type="text/javascript">
			$(document).ready(function()
			{
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
				cx_intro.font = "normal bold 80px 'Arial'";
				cx_intro.fillText(msg , 145 , 100 , 13);

				msg = "order it, we deliver it";
				cx_intro.fillStyle = "#FFFF00";
				cx_intro.font = "normal bold 50px 'Arial'";
				cx_intro.fillText(msg , 185 , 100 , 100);

				$("#login_form input:first").focus();
*/
			});

		</script>
	</head>
	<body>
		<div id="login_title">
			<div id="title_container">
				<div id="title_company_info">
					<canvas id="CompanyTitleCanvas" style="width:100%; height:120px;"></canvas>
				</div>
				<div id="title_navigation">
					<!-- <canvas id="drawCanvas" style="width:100%; height:100%;"></canvas> -->
					<canvas id="intro" style="width:100%; height:100%; float:center;"></canvas>

				</div>
<!-- 			<div id="title_intro">
					<canvas id="intro" style="width:100%; height:100%;"></canvas>
				</div>
 -->
				<div id="title_footer">&nbsp;</div>
			</div>

		</div>
		<!-- <canvas id="CompanyTitleCanvas" style="width:580px; height:200px;"></canvas> -->
		<?php echo form_open('login') ?>
		<table id="container">
			<tr>
				<td><?php echo validation_errors(); ?></td>
			</tr>
			<tr>
				<td class="form_field_label"><?php echo $this->lang->line('login_username'); ?>:</td>
				<td class="form_field"><?php echo form_input(array('name'=>'username' , 'size'=>'20' , 'class'=>'cell1')); ?></td>
			</tr>
			<tr>
				<td class="form_field_label"><?php echo $this->lang->line('login_password'); ?>: </td>
				<td class="form_field"><?php echo form_password(array('name'=>'password' , 'size'=>'20' , 'class'=>'cell1')); ?></td>
			</tr>
			<tr>
				<td id="submit_button"><input type="submit" name="loginButton" id="loginButton" value="<?php echo $this->lang->line('login_login');?>" onmouseover="this.className='btn42_over';" onmouseout="this.className='btn42';" class="btn42"></td>
			</tr>
			<tr>
				<td id="footer_message"><?php echo $this->lang->line('login_footer_message');?></td>
			</tr>
		</table>
		<?php echo form_close(); ?>
	</body>
</html>
