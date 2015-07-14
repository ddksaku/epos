<?php
echo form_open_multipart(site_url('products/do_excel_import/'),array('id'=>'product_form'));
?>
<div id="required_fields_message">Import products from Excel sheet</div>
<ul id="error_message_box"></ul>
<!--
<b><a href="<?php echo site_url('products/excel'); ?>">Download Import Excel Template (CSV)</a></b>
 -->
<fieldset id="product_basic_info">
<legend>Import</legend>

<div class="field_row clearfix">
<?php echo form_label('File path:', 'name',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_upload(array(
		'name'=>'file_path',
		'id'=>'file_path',
		'value'=>'')
	);?>
	</div>
</div>

<?php
echo form_submit(array(
	'name'=>'submitf',
	'id'=>'submitf',
	'value'=>$this->lang->line('common_submit'),
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
				//post_product_form_submit(response);
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