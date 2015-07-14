
<ul id="error_message_box"></ul>
<?php
echo form_open('products/reload_product/' , array('id'=>'reload_form'));
?>
<fieldset id="ftp_location_info">

<div class="field_row clearfix">
	<div class="load_product_status" id="please_wait">
		<img src="<?php echo base_url("/images/spinner_load.gif");?>" style="width:100px; height:100px;">
	</div>
</div>

	<?php
		echo form_submit(
				array('name'=>'submit' ,
					'id'=>'submit' ,
					'value' => 'Start' ,
					'class'=>'tiny_button',
					'style'=>'float:right;-moz-border-radius : 4px; -webkit-border-radius : 4px; border-radius : 4px; margin:0px 10px 10px 0px;',
					'onmouseover'=>'this.className=\'tiny_button_over\'',
					'onmouseout'=>'this.className=\'tiny_button\'')
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
	$("#category").autocomplete("<?php echo site_url('items/suggest_category');?>",{max:100,minChars:0,delay:10});
    $("#category").result(function(event, data, formatted){});
	$("#category").search();


	$('#reload_form').validate({
		submitHandler:function(form)
		{
			/*
			make sure the hidden field #item_number gets set
			to the visible scan_item_number value
			*/
			$('#please_wait').attr('class' , 'load_product_status_visible');
			$('#submit').css('visibility' , 'hidden');
			$(form).ajaxSubmit({
			success:function(response)
			{
				tb_remove();
				post_product_form_submit(response);
			},
			dataType:'text'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules:
		{
			ftp_location:"required"

   		},
		messages:
		{
			name:"<?php echo $this->lang->line('products_ftp_location_required'); ?>"
		}
	});
});
</script>