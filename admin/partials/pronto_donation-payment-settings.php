<div class="wrap">
	<h1>Payment Settings</h1>

	<form method="post" action="">
		<table class="form-table">
		<?php
			foreach($forms as $field):
		?>
				<tr>
					<th><label><?php echo $field['label'] ?></label></th>
					<td><?php echo $field['field'] ?></td>
				</tr>
		<?php
			endforeach;
		?>
		</table>
		<input type="hidden" name="payment_type" value="<?php echo $_GET['payment'] ?>" />
		<input type="hidden" name="action" value="save_settings" />
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('payment_'.$_GET['payment']); ?>" />
		<p class="submit">
			<button type="submit" class="button button-primary">Save Changes</button>&nbsp;<a href='<?php echo admin_url() . 'admin.php?page=donation-payment' ?> ' class="button button-">Cancel</a>
		</p>
	</form>


</div>