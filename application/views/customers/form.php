
<?php
if($mode == 1)
{
?>
<fieldset id="customer_login_info">
	<legend><?php echo $this->lang->line("customers_login_info"); ?></legend>

	<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('common_email').':', 'email' , array('class'=>'wide')); ?>
		<div class="field_row clearfix"><?php echo form_label($person_info->email , 'email' , array('class'=>'wide')); ?></div>
	</div>
	<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('employees_username').':', 'username',array('class'=>'wide')); ?>
		<div class='form_field'><?php echo form_label($person_info->username, 'username',array('class'=>'wide')); ?></div>
	</div>
	<div class="field_row clearfix"><?php echo form_label($this->lang->line('employees_admin_privilege').':', 'admin_privilege',array('class' => 'wide')); ?>
		<div class='form_field'><?php echo form_checkbox(array('name'=>'admin_privilege' , 'id'=>'admin_privilege' , 'disabled'=>'true') , '2' , $person_info->account_category == '' ? FALSE : (boolean)$person_info->account_category);?></div>
	</div>
</fieldset>
<fieldset id="customer_permission_info">
	<legend><?php echo $this->lang->line("customers_price_list"); ?></legend>
	<p><?php echo $this->lang->line("customers_choise_desc"); ?></p>
	<ul id="permission_list">
		<li style="width:30px;"></li>
		<li>
			<?php echo form_checkbox(array('name'=>'price_list005' , 'id'=>'price_list005' , 'disabled'=>'true') , '005' , $person_info->price_list005 == '' ? FALSE : (boolean)$person_info->price_list005); ?>
			<span class="medium"><?php echo $this->lang->line('customers_price_list0');?></span>
		</li>
		<li>
			<?php echo form_checkbox(array('name'=>'price_list010' , 'id'=>'price_list010' , 'disabled'=>'true') , '010' , $person_info->price_list010 == '' ? FALSE : (boolean)$person_info->price_list010); ?>
			<span class="medium"><?php echo $this->lang->line('customers_price_list1');?></span>
		</li>
		<li>
			<?php echo form_checkbox(array('name'=>'price_list011' , 'id'=>'price_list011' , 'disabled'=>'true') , '011' , $person_info->price_list011 == '' ? FALSE : (boolean)$person_info->price_list011); ?>
			<span class="medium"><?php echo $this->lang->line('customers_price_list2');?></span>
		</li>
		<li>
			<?php echo form_checkbox(array('name'=>'price_list999' , 'id'=>'price_list999' , 'disabled'=>'true') , '999' , $person_info->price_list999 == '' ? FALSE : (boolean)$person_info->price_list999); ?>
			<span class="medium"><?php echo $this->lang->line('customers_price_list3');?></span>
		</li>
		<li>&nbsp;</li>
	</ul>
</fieldset>
<?php
}
else
{

echo form_open('customers/save/'.$person_info->person_id,array('id'=>'customer_form'));
?>
<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>
<fieldset id="customer_login_info">
	<legend><?php echo $this->lang->line("customers_login_info"); ?></legend>
	<div class="field_row clearfix">
	<?php echo form_label($this->lang->line('common_email').':', 'email' , array('class'=>'required')); ?>

		<div class='form_field'>
		<?php echo form_input(array(
			'name'=>'email',
			'id'=>'email',
			'value'=>$person_info->email)
		);?>
		</div>

	</div>
	<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('employees_username').':', 'username',array('class'=>'required')); ?>
		<div class='form_field'><?php echo form_input(array('name'=>'username' , 'id'=>'username' , 'value'=>$person_info->username));?></div>
	</div>
	<?php
		$password_label_attributes = $person_info->person_id == "" ? array('class'=>'required'):array();
	?>

	<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('employees_password').':', 'password',$password_label_attributes); ?>
		<div class='form_field'><?php echo form_password(array('name'=>'password' , 'id'=>'password'));?></div>
	</div>

	<div class="field_row clearfix"><?php echo form_label($this->lang->line('employees_repeat_password').':', 'repeat_password',$password_label_attributes); ?>
		<div class='form_field'><?php echo form_password(array('name'=>'repeat_password' , 'id'=>'repeat_password'));?></div>
	</div>
	<div class="field_row clearfix"><?php echo form_label($this->lang->line('employees_admin_privilege').':', 'admin_privilege',array('class' => 'wide')); ?>
		<div class='form_field'><?php echo form_checkbox(array('name'=>'admin_privilege' , 'id'=>'admin_privilege') , '2' , $person_info->account_category == '' ? FALSE : (boolean)$person_info->account_category);?></div>
	</div>

</fieldset>

<fieldset id="customer_permission_info">
	<legend><?php echo $this->lang->line("customers_price_list"); ?></legend>
	<p><?php echo $this->lang->line("customers_choise_desc"); ?></p>
	<ul id="permission_list">
		<li style="width:30px;"></li>
		<li>
			<?php echo form_checkbox(array('name'=>'price_list005' , 'id'=>'price_list005') , '005' , $person_info->price_list005 == '' ? FALSE : (boolean)$person_info->price_list005); ?>
			<span class="medium"><?php echo $this->lang->line('customers_price_list0');?></span>
		</li>
		<li>
			<?php echo form_checkbox(array('name'=>'price_list010' , 'id'=>'price_list010') , '010' , $person_info->price_list010 == '' ? FALSE : (boolean)$person_info->price_list010); ?>
			<span class="medium"><?php echo $this->lang->line('customers_price_list1');?></span>
		</li>
		<li>
			<?php echo form_checkbox(array('name'=>'price_list011' , 'id'=>'price_list011') , '011' , $person_info->price_list011 == '' ? FALSE : (boolean)$person_info->price_list011); ?>
			<span class="medium"><?php echo $this->lang->line('customers_price_list2');?></span>
		</li>
		<li>
			<?php echo form_checkbox(array('name'=>'price_list999' , 'id'=>'price_list999') , '999' , $person_info->price_list999 == '' ? FALSE : (boolean)$person_info->price_list999); ?>
			<span class="medium"><?php echo $this->lang->line('customers_price_list3');?></span>
		</li>
		<li>&nbsp;</li>
	</ul>
	<?php
		echo form_submit(
				array('name'=>'submit' ,
					'id'=>'submit' ,
					'value'=>$this->lang->line('common_submit') ,
					'class'=>'tiny_button',
					'style'=>'float:right;-moz-border-radius : 4px; -webkit-border-radius : 4px; border-radius : 4px; margin:0px 10px 10px 0px;',
					'onmouseover'=>'this.className=\'tiny_button_over\'',
					'onmouseout'=>'this.className=\'tiny_button\'')
				);
	?>
</fieldset>
<?php echo form_close();?>

<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
	$('#customer_form').validate({
		submitHandler:function(form)
		{
			$(form).ajaxSubmit({
			success:function(response)
			{
				tb_remove();
				post_person_form_submit(response);
			},
			dataType:'json'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules:
		{
			first_name: "required",
			email: "email",
			username:"required",
			<?php
				if($person_info->person_id == "")
				{
			?>
			password:"required",
			<?php
				}
			?>
			repeat_password:
			{
 				equalTo: "#password"
			},
			account_number:"required"
   		},
		messages:
		{
     		first_name: "<?php echo $this->lang->line('common_name_required'); ?>",
     		username: "<?php echo $this->lang->line('employees_username_required'); ?>",
			<?php
				if($person_info->person_id == "")
				{
			?>
			password:"<?php echo $this->lang->line('employees_password_required'); ?>",
			<?php
				}
			?>
			repeat_password:
			{
				equalTo: "<?php echo $this->lang->line('employees_password_must_match'); ?>"
     		},
     		email: "<?php echo $this->lang->line('common_email_invalid_format'); ?>",
         	account_number:	"<?php echo $this->lang->line('customers_account_number_required'); ?>",
		}
	});
});
</script>
<?php
}
?>