<p style="font-weight:bold;">Una notificaci&oacute;n fue enviada con &eacute;xito para los siguientes destinatarios<p>
<?php foreach($model as $client) : ?>
<p>
	<?php echo $client->Users->CompleteName; ?><br >
	<?php echo $client->Users->user_email; ?>
</p>
<?php endforeach; ?>