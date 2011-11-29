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
							Hola <?php echo $userInvited; ?>, <?php echo $userCreateInvitation; ?> te ha enviado una invitaci&oacute;n para que te unas y participes desde <?php echo $applicationName; ?> en los proyectos que estan coordinando.
						</p>
						<p>
							<?php echo $applicationName; ?> es una aplicaci&oacute;n desarrollada por Qbit Mexhico para que de forma colaborativa se lleve un control sobre proyectos de software y al finalizar se cuente con un historial de avances, con esta nueva herramienta se pueden manejar informaci&oacute;n tal como puede ser presupuestos, gastos, facturaci&oacute;n, documentos, hitos, tareas y muchas cosas m&aacute;s.
						</p>
						<div style="padding:5px; background-color:#DFF3FB;">
							<p>
								Nombre de usuario: <?php echo $user_email; ?><br />
								Clave de Acceso: <?php echo $user_password; ?>
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