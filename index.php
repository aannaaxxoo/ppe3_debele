﻿<?php
session_start();
include 'Controleur.php';

function chargerPage()
{
	$monControleur = new Controleur();
	$monControleur->afficheEntete();
	

			/*if(!$_GET['emailOubliMotDePasse'] == ''){
				echo "envoi de mail à : ".$_GET['emailOubliMotDePasse'];
				$destinataire = $_GET['emailOubliMotDePasse'];
				// Pour les champs $expediteur / $copie / $destinataire, séparer par une virgule s'il y a plusieurs adresses
				$expediteur = 'joseph.ppe3@gmail.com';
				$copie = 'joseph.ppe3@gmail.com';
				$copie_cachee = 'joseph.ppe3@gmail.com';
				$objet = 'Test'; // Objet du message
				$headers  = 'MIME-Version: 1.0' . "\n"; // Version MIME
				$headers .= 'Reply-To: '.$expediteur."\n"; // Mail de reponse
				$headers .= 'From: "Nom_de_expediteur"<'.$expediteur.'>'."\n"; // Expediteur
				$headers .= 'Delivered-to: '.$destinataire."\n"; // Destinataire
				//$headers .= 'Cc: '.$copie."\n"; // Copie Cc
				//$headers .= 'Bcc: '.$copie_cachee."\n\n"; // Copie cachée Bcc        
				$message = 'Hihi coucou coucou!';
				if (mail($destinataire, $objet, $message, $headers)) // Envoi du message
				{
				    echo 'Votre message a bien été envoyé ';
				}
				else // Non envoyé
				{
				    echo "Votre message n'a pas pu être envoyé";
				}
			}*/

		if(isset($_GET['login']) && isset($_GET['password']))
		{	echo "<br>je suis dans le remplissage du login";    
		    $_SESSION['login'] = $_GET['login'];
		    $_SESSION['password'] = $_GET['password'];
		}
		
		if(isset($_SESSION['login']) && isset($_SESSION['password']))
		{ 	echo "<br>la session existe";
		    if ((isset($_GET['vue'])) && (isset($_GET['action'])))
				{   
				    echo $_GET['action']; 
				    echo $_GET['vue'];
				    $monControleur->affichePage($_GET['action'],$_GET['vue']);
				}
		}
		else
		{
			echo "<br>pas de session";
			if ((isset($_GET['vue'])) && (isset($_GET['action'])))
			{   
				echo "<br>je crée un nouvel utilisateur";
				$monControleur->affichePage($_GET['action'],$_GET['vue']);
			}
			else
			{
				echo "<br>je viens d arriver";
				premier_affichage();
			}		
		}
	$monControleur->affichePiedPage();
}

	function premier_affichage()
	{
		/*echo "<div class = 'centrePage'>
				<table border=3 align=center>
					<tr align=center>
						<td>
							<h2> Nouveau compte</h2>
						</td>
						<td>
							<h2>    Je suis déjà client  </h2>
						</td>
					</tr>
					<tr align=center>
						<td>
								<form href = 'index.php?vue=compte&action=nouveauLogin' method='POST'>
									<input type='text' name='nomClient' value='saisir votre nom'/><br>
									<input type='text' name='prenomClient' value='Saisir votre prenom'/><br>
									<input type='text' name='emailClient' value='Saisir votre email'/><br>
									<input type='text' name='dateAbonnementClient' value='Date souhaitée d abonnement'/><br>
									<input type='text' name='login' value='Saisir votre login'/><br>
									<input type='text' name='Password' value='Choisir un mot de passe'/><br>
									<input type='submit' value='Accéder'/>
								   </form>
						</td>
						<td>
							<form action=index.php method=POST>
									<input type='text' name='login'/><br>
									<input type='text' name='password'/><br>
									<input type='hidden' name='vue' value='compte'>
									<input type='hidden' name='action' value='verifLogin'/>
									<input type='submit' value='Accéder'/>
								</form>
						</td>
					</tr>
				</table>
			</div>";*/
		echo "</nav>
                <div class='container h-100'>
                    <div class='row h-100 justify-content-center align-items-center'>
                        <table class='table w-50'>
                            <thead>
                                <td class='head-table-connexion text-white'>Je suis déjà client</td>
                                <td class='head-table-connexion text-white'>Je crée mon compte</td>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class='td-table justify-content-center'>
                                        <form action=index.php method=GET>
                                            <input class='form-group' type='text' placeholder='Login' name='login'/><br>
                                            <input class='form-group' type='password' placeholder='Mot de passe' name='password'/><br>
                                            <input type='hidden' name='vue' value='compte'>
                                            <input type='hidden' name='action' value='verifLogin'/>
                                            <input class='btn btn-secondary mx-auto' type='submit' value='Accéder'/>
                                        </form>

                                        <form action = 'index.php' method=GET>
                                        	<input type='hidden' name='vue' value='compte'>
                                            <input type='hidden' name='action' value='changementMdp'/>
                                            <input class='btn btn-secondary' type='submit' value='Mot de passe oublié'/>
                                        </form>

                                    </td>
                                    <td class='justify-content-center td-table'>
                                        <form action = 'index.php' method=GET>
                                            <input class='form-group' type='text' name='nomClient' placeholder='saisir votre nom'/><br>
                                            <input class='form-group' type='text' name='prenomClient' placeholder='Saisir votre prenom'/><br>
                                            <input class='form-group' type='text' name='emailClient' placeholder='Saisir votre email'/><br>
                                            <input class='form-group' type='date' name='dateAbonnementClient' placeholder='Date souhaitée d abonnement'/><br>
                                            <input class='form-group' type='text' name='loginInscription' placeholder='Saisir votre login'/><br>
                                            <input class='form-group' type='password' name='passwordInscription' placeholder='Choisir un mot de passe'/><br>
                                            <input type='hidden' name='vue' value='compte'>
                                            <input type='hidden' name='action' value='nouveauLogin'/>
                                            <input class='btn btn-secondary' type='submit' value='Accéder'/>
                                        </form>
                                    </td>
                            </tbody>
                        </table>
                    </div>
                </div>";
	}
	chargerPage();
?>
