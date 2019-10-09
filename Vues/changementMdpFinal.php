

<html>
  <head>
  		<title>Changement de mot de passe</title>
  		<!--<link rel="stylesheet" type="text/css" href="../stylevideo.css" media="all"/>-->
  </head>
  <body>
  	<div class="centrePage">
			<BR><BR><BR>
			<FORM action='index.php' method='get'>
				<h3><u>Changement de mot de passe</u></h3>
				<br>

				<i>Nouveau mot de passe</i><br>
				<INPUT type = 'text' name = 'newMdp'/>
				<br><br>
				<i>Confirmation du nouveau de passe</i><br>
				<INPUT type = 'text' name = 'emailOubliMotDePasse'/>
				<br><br>
				<input type='hidden' name='vue' value='compte'>
		        <input type='hidden' name='action' value='envoiMail'/>
				<INPUT type = 'submit' value = 'Valider'/>
			</FORM>
	</div>
  </body>
</html>