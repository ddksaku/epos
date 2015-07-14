<?php
echo form_open('suppliers/save/'.$person_info->person_id,array('id'=>'supplier_form'));
?>
<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>
<fieldset id="supplier_basic_info">
	<legend><?php echo $this->lang->line("suppliers_basic_information"); ?></legend>

	<?php $this->load->view("people/form_basic_info"); ?>

</fieldset>

<fieldset id="supplier_login_info">
	<legend><?php echo $this->lang->line("suppliers_login_info"); ?></legend>
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
</fieldset>

<fieldset id="supplier_permission_info">
	<legend><?php echo $this->lang->line("suppliers_price_list"); ?></legend>
	<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('suppliers_company_name').':', 'company_name'); ?>
		<div class='form_field'><?php echo form_input(array('name'=>'company_name_input' , 'id'=>'company_name_input' , 'value'=>$person_info->company_name));?></div>
	</div>

	<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('suppliers_account_number').':', 'account_number' , array('class'=>'required')); ?>
		<div class='form_field'><?php echo form_input(array('name'=>'account_number' , 'id'=>'account_number' , 'value'=>$person_info->account_number));?></div>
	</div>

	<p><?php echo $this->lang->line("customers_choice_desc"); ?></p>
	<ul id="permission_list">
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


	</ul>
	<?php echo form_submit(array('name'=>'submit' , 'id'=>'submit' , 'value'=>$this->lang->line('common_submit') , 'class'=>'submit_button float_right'));?>
</fieldset>

<?php echo form_close();?>
<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
	$('#supplier_form').validate({
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
         	account_number:	"<?php echo $this->lang->line('customers_account_number_required'); ?>"
		}
	});
});
</script>