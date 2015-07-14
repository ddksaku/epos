<?php $this->load->view("partial/header"); ?>
<script type="text/javascript">
$(document).ready(function()
{
	$('#phone_number').focus();
});

function send_message()
{
	var phone_number = $('#phone_number').val();
	var msg = $('#msg').val();

	if(phone_number == '')
	{
		$('#phone_number').focus();
		return;
	}

	if(msg == '')
	{
		$('#msg').focus();
		return;
	}

    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo site_url("$controller_name/send_message");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : "phone_number=" + phone_number + "&msg=" + msg
        , error : function(request, status, error) {

         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {

			if(response == -1)
				alert("Send mail fail.");
			else
				alert("Send mail success");


        }
    });
}

</script>

<table id="contactus_container">
	<tr>
		<td style="font-family:Arial; font-size:24px; text-align: center; height:34px; padding:10px 40px 10px 40px;"><?php echo $this->lang->line("contactus_title");?></td>
	</tr>
	<tr>
		<td style="padding: 0px 30px 30px 30px;">
			<table style="background-color:#DDDDDD; width:100%; height:100%;">
				<tr style="">
					<td style="width:30%; padding-left:50px;">
						<span style="font-family: Arial; font-size:18px;">
							<?php echo $this->lang->line("contactus_username");?>
						</span>
					</td>
					<td style="padding:10px 250px 0px 30px;">
						<div style="width:100%;  background-color:#FFFFFF; padding-top:10px; padding-bottom:10px;">
							<span style="font-family: Arial; font-size:18px;">
								&nbsp;&nbsp;&nbsp;<?php echo $user_info->username;?>
							</span>
						</div>
					</td>
				</tr>
				<tr>
					<td style="width:30%; padding-left:50px;">
						<span style="font-family: Arial; font-size:18px;">
							<?php echo $this->lang->line("contactus_email");?>
						</span>
					</td>
					<td style="padding:10px 250px 0px 30px;">
						<div style="width:100%;  background-color:#FFFFFF; padding-top:10px; padding-bottom:10px;">
							<span style="font-family: Arial; font-size:18px;">
								<?php echo $user_info->email;?>
							</span>
						</div>
					</td>
				</tr>
				<tr>
					<td style="width:30%; padding-left:50px;">
						<span style="font-family: Arial; font-size:18px;">
							<?php echo $this->lang->line("contactus_phone_number");?>
						</span>
					</td>
					<td style="padding:10px 250px 0px 30px;">
						<input type="text" name="phone_number" id="phone_number" class="product_search_cell" style="width:100%; height:40px; font-size:20px;">
					</td>
				</tr>
				<tr>
					<td style="width:30%; vertical-align:top; padding-top:20px; padding-left:50px;">
						<span style="font-family: Arial; font-size:18px;">
							<?php echo $this->lang->line("contactus_message");?>
						</span>
					</td>
					<td style="padding:10px 20px 0px 30px;">
						<?php echo form_textarea(array('name' => 'msg' , 'id' => 'msg' , 'value'=> '' , 'rows' => '5',
							'cols' => '67' , 'style' => 'border:1px solid #CCCCCC;')
	);?>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="border:30px 30px 30px 30px; padding-left:420px;">
						<div class='tiny_long_long_button' onmouseover="this.className='tiny_long_long_button_over'" onmouseout="this.className='tiny_long_long_button'" onclick="send_message();">
							<span><?php echo $this->lang->line('contactus_send_message');?></span>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div></div>