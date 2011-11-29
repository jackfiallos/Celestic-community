<html>
<style type="text/css">
#template{font-size:13px;font-family:Verdana, Tahoma, Arial;}
</style>
<body>
	<div align="center" style="margin:auto;">
		<table id="template" style="width:650px">
			<thead style="background-color:#263849; color:#CCCCCC;">
				<tr>
					<th scope="col" style="border-top:5px solid #5188C2; text-align:left;">
						<img src="https://qbit.com.mx/labs/celestic/images/celestic.png" alt="<?php echo $applicationName; ?>" />
					</th>
				</tr>
			</thead>
			<tfoot style="background-color:#263849; color:#CCCCCC; padding:5px;">
				<tr>
					<td id="footer" style="padding:5px;">
						<p>
							Copyright &copy; 2011 <?php echo $applicationName; ?>. Todos los derechos reservados. | <a href="<?php echo $applicationUrl; ?>/?r=site/privacy"></a>Declaraci&oacute;n de Privacidad<a>
						</p>
						<p style="font-size:11px;">
							Este mensaje y cualquier documento que lleve adjunto, es confidencial y destinado &uacute;nicamente a la persona o entidad a quien ha sido enviado. Si Usted ha recibido este mensaje por error, le informamos que el contenido en el mismo es reservado y el uso no autorizado est&aacute; prohibido legalmente, por ello, por favor, le rogamos que nos lo notifique al e-mail info@celestic.mx<br />
							Aviso: Qbit Mexhico y el equipo de <?php echo $applicationName; ?> en ning&uacute;n momento colecta informaci&oacute;n personal de sus usuarios.<br />
							No respondas a este mensaje, fue enviado utilizando un servicio autom&aacute;tico de notificaciones.
						</p>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td valign="top" id="maincontent" style="padding-top:15px;">
						<p>
							Hola <?php echo $userRequest; ?>,
						</p>
						<p>
							Al parecer has olvidado tus credenciales de acceso a <?php echo $applicationName; ?>, aunque tambi&eacute;n creemos que alguien quiere suplantar tu identidad, pero no te preocupes que no lo ha logrado, este email es para notificarte que por lo sucedido tu clave de acceso a <?php echo $applicationName; ?> ha cambiado temporalmente.
						</p>
						<div style="padding:5px; background-color:#DFF3FB;">
							<p>
								Usuario: <?php echo substr($user_email, 0, strpos($user_email, "@"))."***"; ?> (parcialmente oculto, para mantener la privacidad)<br />
								Clave de acceso: <?php echo $user_password; ?>
							</p>
							<p>
								Recuerda que esta clave de acceso es temporal y quiz&aacute;s despu&eacute;s de unos minutos no la recuerdes, por lo que te recomendamos una vez que hayas iniciado sesi&oacute;n desde las preferencias de usuario cambies esta clave a alguna palabra que puedas recordar.<br />
							</p>
						</div>
						<p>
							<a href="<?php echo $applicationUrl; ?>"><?php echo $applicationName; ?> Team</a>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</body>
</html>