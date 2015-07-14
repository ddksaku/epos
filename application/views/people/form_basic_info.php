<div class="field_row clearfix">
<?php echo form_label($this->lang->line('common_name').':', 'name',array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'first_name',
		'id'=>'first_name',
		'value'=>$person_info->first_name)
	);?>
	</div>
</div>

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
<?php echo form_label($this->lang->line('common_comments').':', 'comments'); ?>
	<div class='form_field'>
	<?php echo form_textarea(array(
		'name'=>'comments',
		'id'=>'comments',
		'value'=>$person_info->comments,
		'rows'=>'15',
		'cols'=>'17')
	);?>
	</div>
</div>