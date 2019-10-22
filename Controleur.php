



<?php
//include du fichier GESTION pour les objets (Modeles)
include 'Modeles/gestionVideo.php';

//classe CONTROLEUR qui redirige vers les bonnes vues les bonnes informations
class Controleur
	{
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------ATTRIBUTS PRIVES-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	private $maVideotheque;

	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------CONSTRUCTEUR------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	public function __construct()
		{
		$this->maVideotheque = new gestionVideo();
		}


	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------METHODE D'AFFICHAGE DE L'ENTETE-----------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	public function afficheEntete()
		{
		//appel de la vue de l'entête
		if(isset($_SESSION['login']) && isset($_SESSION['password']))
		{
			echo "<br>La personne connecté est ".$_SESSION['login']."<br>";
		}
		require 'Vues/entete.php';
		}


	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------METHODE D'AFFICHAGE DU PIED DE PAGE------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	public function affichePiedPage()
		{
		//appel de la vue du pied de page
		require 'Vues/piedPage.php';
		}


	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------METHODE D'AFFICHAGE DU MENU-----------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	public function afficheMenu()
		{
		//appel de la vue du menu
		require 'Vues/menu.php';
		}


	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//--------------------------METHODE D'AFFICHAGE DES VUES----------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	public function affichePage($action,$vue)
		{
		echo "<br>action: ".$action."<br>vue: ".$vue;
		//SELON la vue demandée
		switch ($vue)
			{
			case 'compte':
				$this->vueCompte($action);
				break;
			case 'film':
				$this->vueFilm($action);
				break;
			case 'serie':
				$this->vueSerie($action);
				break;
			case 'Videotheque':
				$this->vueRessource($action);
				break;
			case "accueil":
				session_destroy();
				break;
			}
		}

	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------Mon Compte--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	private function vueCompte($action)
		{

		//SELON l'action demandée
		switch ($action)
			{

			//CAS visualisation de mes informations-------------------------------------------------------------------------------------------------
			case 'visualiser' :
				//ici il faut pouvoir avoir accès au information de l'internaute connecté
				echo '<br><br>coucou';
				$login = $_SESSION['login'];
				echo $login;
				$_SESSION['afficherInformationsCompte'] = $this->maVideotheque->donneInformationsSelonLogin($login);
				require 'Vues/voirCompte.php';
				break;

			//CAS enregistrement d'une modification sur le compte------------------------------------------------------------------------------
			case 'modifier' :
				// ici il faut pouvoir modifier le mot de passe de l'utilisateur
				require 'Vues/modifierCompte.php';
				break;

			case 'confirmerModificationCompte' :
				$nouveauPrenom = $_POST['nouveauPrenom'];
				$nouveauNom = $_POST['nouveauNom'];
				$nouveauEmail = $_POST['nouveauEmail'];
				$mdpActuel = $_POST['mdpActuel'];
				$nouveauMdp = $_POST['nouveauMdp'];
				$confirmationNouveauMdp = $_POST['confirmationNouveauMdp'];
				/*echo "<br>les modifications sont:".$nouveauPrenom." ".$nouveauNom." ".$nouveauEmail." ".$mdpActuel." ".$nouveauMdp." ".$confirmationNouveauMdp;
				echo "longueur prenom ".strlen($nouveauMdp);*/

				$erreur ="<br>ERREUR<br>";
				$aModifieAuMoinsUnParametre = false;
				$actif  = $this->maVideotheque->donneActifDepuisLogin($_SESSION['login']);
				if($actif == 1) // si le compte est actif
				{
					echo 'Le compte est actif';
					if($nouveauPrenom != "" && strlen($nouveauPrenom) > 2 && strlen($nouveauPrenom) < 50)
						{
							$this->maVideotheque->modifierPrenomClient($_SESSION['login'], $nouveauPrenom);
							$aModifieAuMoinsUnParametre = true;
						}
						else
						{
							if($nouveauPrenom != "")
							{
								$erreur = $erreur."<br>- Le prénom saisi n'est pas valide.";
								require 'Vues/modifierCompte.php';
								echo $erreur;
								break;
							}
						}

					if($nouveauNom != "" && strlen($nouveauNom) > 2 && strlen($nouveauNom) < 50)
						{
							$this->maVideotheque->modifierNomClient($_SESSION['login'], $nouveauNom);
							$aModifieAuMoinsUnParametre = true;
						}
						else
						{
							if($nouveauNom != "")
							{
								$erreur = $erreur."<br>- Le nom saisi n'est pas valide.";
								require 'Vues/modifierCompte.php';
								echo $erreur;
								break;
							}
						}

					if($nouveauEmail != "" && filter_var($nouveauEmail, FILTER_VALIDATE_EMAIL))
						{
							$this->maVideotheque->modifierEmailClient($_SESSION['login'], $nouveauEmail);
							$aModifieAuMoinsUnParametre = true;
						}
						else
						{
							if($nouveauEmail != "")
							{
								$erreur = $erreur."<br>- L'email saisi n'est pas valide.";
								require 'Vues/modifierCompte.php';
								echo $erreur;
								break;
							}
						}

					if($nouveauMdp != "" && strlen($nouveauMdp) > 2 && strlen($nouveauMdp) < 50
						&& $nouveauMdp == $confirmationNouveauMdp
						&& $mdpActuel == $_SESSION['password'])
						{
							$this->maVideotheque->modifierMdpClient($_SESSION['login'], $nouveauMdp);
							$aModifieAuMoinsUnParametre = true;
						}
						else
						{
							if($nouveauMdp != "")
							{
								$erreur = $erreur."<br>- Le mot de passe saisi n'est pas valide.";
								echo $erreur;
								require 'Vues/modifierCompte.php';
								break;
							}
						}
					if($aModifieAuMoinsUnParametre = false)
					{
						echo "<br>ERREUR<br>Aucune modification détectée.";
						require 'Vues/modifierCompte.php';
						break;
					}
					else
					{
						require 'Vues/enregistrer.php';
						break;
					}
				}
				else
				{
					$erreur = $erreur."<br>- VOTRE COMPTE N'EST PAS ACTIF.";
					require 'Vues/modifierCompte.php';
					echo $erreur;
					break;
				}




			//CAS ajouter un utilisateur ------------------------------------------------------------------------------
			case 'nouveauLogin' :
				// ici il faut pouvoir recuperer un nouveau utilisateur
				$unPrenomClient=$_GET['prenomClient'];
				$unNomClient=$_GET['nomClient'];
				$unEmailClient=$_GET['emailClient'];
				$uneDateAbonnementClient=$_GET['dateAbonnementClient'];
				$unLogin=$_GET['loginInscription'];
				$unPassword=$_GET['passwordInscription'];
				if($this->maVideotheque->verifLogin($unLogin, $unPassword) == 1 || empty($unLogin) || empty($unPassword))
				{
				    echo "ERREUR LORS DE L'INSCRIPTION";
				}
				else
				{
    				$unIdClient=$this->maVideotheque->donneProchainIdentifiant('CLIENT','idClient');
    				$this->maVideotheque->ajouteUnClient($unNomClient, $unPrenomClient, $unEmailClient,
    					$uneDateAbonnementClient, $unLogin, $unPassword, $unIdClient);

    				//ENVOI DU MAIL A L'UTILISATEUR
					$expediteur = 'joseph.ppe3@gmail.com';
					$objet = 'PPE-FLIX | Bienvenue sur notre plateforme !'; // Objet du message
					$headers  = 'MIME-Version: 1.0' . "\n"; // Version MIME
					$headers .= 'Reply-To: '.$expediteur."\n"; // Mail de reponse
					$headers .= 'From: <'.$expediteur.'>'."\n"; // Expediteur
					$headers .= 'Delivered-to: '.$unEmailClient."\n"; // Destinataire
					$message = 'Bienvenue sur PPE-FLIX !
					A la réception de votre chèque, votre compte sera validé dans les plus brefs délais.
					PPE-FLIX, 30 Boulevard Du Massacre, Nantes 44300';
					mail($unEmailClient, $objet, $message, $headers); // Envoi du message


    				//ENVOI DU MAIL A L'ADMIN
					$expediteur = 'joseph.ppe3@gmail.com';
					$objet = "Inscription d'un nouvel utilisateur"; // Objet du message
					$headers  = 'MIME-Version: 1.0' . "\n"; // Version MIME
					$headers .= 'Reply-To: '.$expediteur."\n"; // Mail de reponse
					$headers .= 'From: <'.$expediteur.'>'."\n"; // Expediteur
					$headers .= 'Delivered-to: '.$expediteur."\n"; // Destinataire
					$message = "Une nouvelle personne s'est inscrite sur PPE-FLIX.
					- Nom:".$unNomClient." ".$unPrenomClient."
					- Email:".$unEmailClient.".";
					mail($expediteur, $objet, $message, $headers); // Envoi du message
    				require 'Vues/enregistrer.php';
				}
				break;

			case 'envoiMail' :
				$destinataire = $_GET['emailOubliMotDePasse'];
				$confirmationLogin = $_GET['confirmationLogin'];
				if ($this->maVideotheque->confirmerClientAvecEmailEtLogin($confirmationLogin, $destinataire))
				{
					if($this->maVideotheque->donneActifDepuisLogin($confirmationLogin) == 1)
					{
						//echo "<br>Ce compte est OK<br>";
						if (filter_var($destinataire, FILTER_VALIDATE_EMAIL)) //et que l'adresse est dans la BDD
						{
							echo '<br>preparation de la requete UPDATE...<br>';
							$newPassword = $this->maVideotheque->genererChaineAleatoire(8); //génération du nouveau mot de passe
							$this->maVideotheque->modifierPasswordClient($destinataire, $newPassword);

						    echo "<br>L'adresse email ".$destinataire." est considérée comme valide.<br>";

						    //CREATION DU MAIL
							$expediteur = 'joseph.ppe3@gmail.com';
							$objet = 'PPE-FLIX | Demande de changement de votre mot de passe'; // Objet du message
							$headers  = 'MIME-Version: 1.0' . "\n"; // Version MIME
							$headers .= 'Reply-To: '.$expediteur."\n"; // Mail de reponse
							$headers .= 'From: <'.$expediteur.'>'."\n"; // Expediteur
							$headers .= 'Delivered-to: '.$destinataire."\n"; // Destinataire
							$message = 'Bonjour
							Vous avez effectué une demande de changement de mot de passe sur PPE-FLIX
							Votre nouveau mot de passe est : '.$newPassword;
							mail($destinataire, $objet, $message, $headers); // Envoi du message
							require 'Vues/enregistrer.php';
						}
						else //Format d'email incorrect
						{
						    echo "<br>L'Adresse email ".$email." est considérée comme invalide.<br>";
						    require 'Vues/changementMdp.php';
						}
					}
					else //Compte inactif
					{
						echo "<br>Le compte saisi n'est pas actif<br>";
						require 'Vues/changementMdp.php';
					}				
				}
				else //Le login rentré n'est pas associé à l'email ! Ce compte n'existe pas dans la BDD
				{
					echo "<br>Ce compte n'existe pas<br>";
					require 'Vues/changementMdp.php';
				}
				break;

			//CAS verifier un utilisateur ------------------------------------------------------------------------------
			case 'verifLogin' :
				// ici il faut pouvoir vérifier un login un nouveau utilisateur
				//Je récupère les login et password saisi et je verifie leur existancerequire
				//pour cela je verifie dans le conteneurClient via la gestion.
				$unLogin=$_GET['login'];
				$unPassword=$_GET['password'];
				$resultat=$this->maVideotheque->verifLogin($unLogin, $unPassword);
				//si le client existe alors j'affiche le menu et la page visuGenre.php
				if($resultat==1)
				{

					$actif  = $this->maVideotheque->donneActifDepuisLogin($unLogin);
					echo '<br>Compte actif: '.$actif.'<br>';
					if($actif == 0){
						echo '<img src="Images/alert.png" alt="Alerte" width=80px>';
						echo "<p class='messageErreur'>Votre compte n'est pas encore actif. Merci d'envoyer un chèque d'inscription à l'adresse 'PPE-FLEX, 30 Boulevard Du Massacre, Nantes 44300.'</p>";
					}
					require 'Vues/menu.php';
					echo $this->maVideotheque->listeLesGenres();
				}
				else
				{
					// destroy la session et je repars sur l'accueil en affichant un texte pour prévenir la personne
					//des mauvais identifiants;
					session_destroy();
					echo "</nav>
							<div class='container h-100'>
								<div class='row h-100 justify-content-center align-items-center'>
									<span class='text-white'>Identifiants incorrects</span>
								</div>
							</div>
							<meta http-equiv='refresh' content='1;index.php'>";
				}
				break;

			case 'oubliMdp' :
				require 'Vues/changementMdp.php';
				break;

			case 'retourAccueil':
				require 'index.php';
				echo $this->maVideotheque->listeLesGenres();
				break;
			}
		}

	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------Film--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	private function vueFilm($action)
		{
		//SELON l'action demandée
		switch ($action)
			{

			//CAS visualisation de tous les films-------------------------------------------------------------------------------------------------
			case "visualiser" :
				//ici il faut pouvoir visualiser l'ensemble des films
				echo 'titi';
				$_SESSION['nbFilms'] = $this->maVideotheque->donneNbFilms();
			    $_SESSION['lesFilms'] = $this->maVideotheque->listeLesFilms();
			    echo "Session: ".$_SESSION['lesFilms'];
				require 'Vues/voirFilm.php';
				break;

			}
		}

	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------Serie--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	private function vueSerie($action)
		{
		//SELON l'action demandée
		switch ($action)
			{

			//CAS visualisation de toutes les Series-------------------------------------------------------------------------------------------------
			case "visualiser" :
				//ici il faut pouvoir visualiser l'ensemble des Séries
				require 'Vues/construction.php';
				break;

			}
		}
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//----------------------------Vidéotheque-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	private function vueVideotheque($action)
		{
		//SELON l'action demandée
		switch ($action)
			{

			//CAS Choix d'un genre------------------------------------------------------------------------------------------------
			case "choixGenre" :
				if ($this->maVideotheque->donneNbGenres()==0)
					{
					$message = "il n existe pas de genre";
					$lien = 'index.php?vue=ressource&action=ajouter';
					$_SESSION['message'] = $message;
					$_SESSION['lien'] = $lien;
					require 'Vues/erreur.php';
					}
				else
					{
					$_SESSION['lesRessources'] = $this->maMairie->listeLesRessources();
					require 'Vues/voirRessource.php';
					}
				break;

			//CAS enregistrement d'une ressource dans la base------------------------------------------------------------------------------
			case "enregistrer" :
				$nom = $_POST['nomRessource'];
				if (empty($nom))
					{
					$message = "Veuillez saisir les informations";
					$lien = 'index.php?vue=ressource&action=ajouter';
					$_SESSION['message'] = $message;
					$_SESSION['lien'] = $lien;
					require 'Vues/erreur.php';
					}
				else
					{
					$this->maMairie->ajouteUneressource($nom);
					require 'Vues/enregistrer.php';
					//$_SESSION['Controleur'] = serialize($this);
					}
				break;
			}
		}

	}

?>
