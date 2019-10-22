<div class="centrePage">
<BR><BR><BR>
	<FORM action='index.php' method='get'>
		<h3><u>Récupérer votre mot de passe ici</u></h3><br>
		Vous avez oublié votre mot de passe ? Saisissez dans le champ ci-dessous l'adresse email utilisée pour votre inscription.
		<br>Nous vous enverrons un email contenant un lien qui vous permettra de le réinitialiser.
		<br><br>

		<i>Adresse email</i><br>
		<INPUT type = 'text' name = 'emailOubliMotDePasse'/>

		<br><i>Login</i><br>
		<INPUT type = 'text' name = 'confirmationLogin'/>

		<br><br>
		<input type='hidden' name='vue' value='compte'>
        <input type='hidden' name='action' value='envoiMail'/>
		<INPUT type = 'submit' value = 'Valider'/>
	</FORM>
</div>