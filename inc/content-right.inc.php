<div id="custom_mails_content_right">
	<div id="custom_mails_content_right_inner">
		<button type="submit" class="button-primary">Save Changes</button>
		<p><button type="reset" class="button">Reset</button></p>
		<p style="margin-bottom:25px;">
			<button type="button" class="button" onclick="location.href='<?php echo $sLink; ?>';">
				Cancel
			</button>
		</p>
<?php

	if (isset($_REQUEST['t']) && $_REQUEST['t'] <= 15) {

?>
		<hr>
		<p><strong>Template Format</strong></p>
		<select id="custom-mails-template-format">
<?php 
	if (isset($_REQUEST['format']) && $_REQUEST['format'] == 'text') {
		echo '<option value="html">HTML</option>';
		echo '<option value="text" selected>Plain Text</option>';
	} else {
		echo '<option value="html" selected>HTML</option>';
		echo '<option value="text">Plain Text</option>';
	}
?>
		</select>
<?php 

	}

?>
	</div>
</div>