<?php foreach($availableUsers as $user):?>
<li>
	<input class="checkSelItem" data="<?php echo $user->user_id;?>" type="checkbox" name="usrid-<?php echo $user->user_id;?>" id="usrid-<?php echo $user->user_id;?>" />
	<label for="usrid-<?php echo $user->user_id;?>">
		<?php echo $user->CompleteName; ?>
	</label>
</li>
<?php endforeach;?>