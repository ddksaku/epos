<?php
echo form_open_multipart(site_url('orders/do_excel_import/'),array('id'=>'product_form'));
?>

<ul id="error_message_box"></ul>
<!--
<b><a href="<?php echo site_url('products/excel'); ?>">Download Import Excel Template (CSV)</a></b>
 -->
<fieldset id="product_basic_info">
<legend>Import</legend>

<div class="field_row clearfix">

	<div class='form_field'>
	<?php echo form_upload(array(
		'name'=>'file_path',
		'id'=>'file_path',
		'value'=>'')
	);?>
	</div>
</div>
<div class="field_row clearfix">
	<div class='form_field'>
			<?php echo form_checkbox(array('name'=>'empty_trolley' , 'id'=>'empty_trolley') , '1' , FALSE); ?>
			<span class="medium"><?php echo $this->lang->line('orders_empty_trolley');?></span>
	</div>
</div>

<?php
echo form_submit(array(
	'name'=>'submitf',
	'id'=>'submitf',
	'value'=>$this->lang->line('common_go'),
	'class'=>'tiny_button',
	'style'=>'float:right;-moz-border-radius : 4px; -webkit-border-radius : 4px; border-radius : 4px; margin:0px 10px 10px 0px;',
	'onmouseover'=>'this.className=\'tiny_button_over\'',
	'onmouseout'=>'this.className=\'tiny_button\''
	)
);
?>
</fieldset>
<?php
echo form_close();
?>
<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
	$('#product_form').validate({
		submitHandler:function(form)
		{

			$(form).ajaxSubmit({
			success:function(response)
			{
				tb_remove();
				location.reload();
			},
			dataType:'text'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules:
		{
			file_path:"required"
   		},
		messages:
		{
   			file_path:"Full path to excel file required"
		}
	});
});
</script>