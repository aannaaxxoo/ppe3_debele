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
			$login = $_SESSION['login'];
			echo 'login :'.$login.'<br>';
			$actif = $this->maVideotheque->donneActifDepuisLogin('CLIENT',$login);
			echo 'actif: '.$actif;
			if($actif == 0){	
				echo '<img src="Images/alert.png" alt="Alerte">';
				echo 'Votre compte est bien à jour';
			}	
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
				echo "<br>les modifications sont:".$nouveauPrenom." ".$nouveauNom." ".$nouveauEmail." ".$mdpActuel." ".$nouveauMdp." ".$confirmationNouveauMdp;
				echo "longueur prenom ".strlen($nouveauMdp);
				if($nouveauPrenom != "" && strlen($nouveauPrenom) > 2 && strlen($nouveauPrenom) < 50)
					{ 
						$this->maVideotheque->modifierPrenomClient($_SESSION['login'], $nouveauPrenom); 
					}

				if($nouveauNom != "" && strlen($nouveauNom) > 2 && strlen($nouveauNom) < 50)
					{ 
						$this->maVideotheque->modifierNomClient($_SESSION['login'], $nouveauNom); 
					}

				if($nouveauEmail != "" && filter_var($nouveauEmail, FILTER_VALIDATE_EMAIL)) {
					
						$this->maVideotheque->modifierEmailClient($_SESSION['login'], $nouveauEmail); 
					}
					
				if($nouveauMdp != "" && strlen($nouveauMdp) > 2 && strlen($nouveauMdp) < 50
					&& $nouveauMdp == $confirmationNouveauMdp
					&& $mdpActuel == $_SESSION['password'])
					{ 
						echo 'ce mot de passe est modifié bitch';
						$this->maVideotheque->modifierMdpClient($_SESSION['login'], $nouveauMdp); 
					}
				require 'Vues/enregistrer.php';
				break;


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
    				/*
        				//ENVOI DU MAIL A L'ADMIN
        				$headers1 ='From: message automatique\n';
        				$headers1 .='Reply-To: joseph.ppe3@gmail.com'."\n";
        				$headers1 .='Content-Type: text/html; charset="iso-8859-1"'."\n";
        				$headers1 .='Content-Transfer-Encoding: 8bit';				
        				$message1 ='<html><head><title>Inscription d\'un nouvel utilisateur</title></head>
                        <body>Confirmation d\'inscription de l\'utilisateur '.$unNomClient.' '.$unPrenomClient.'</body></html>';   
        				
        				mail('joseph.ppe3@gmail.com', 'Sujet', $message1, $headers1);
        				
        				//ENVOI DU MAIL DE TRAITEMENT A L'UTILISATEUR
        				$headers1 ='From: message automatique\n';
        				$headers1 .='Reply-To: joseph.ppe3@gmail.com'."\n";
        				$headers1 .='Content-Type: text/html; charset="iso-8859-1"'."\n";
        				$headers1 .='Content-Transfer-Encoding: 8bit';
        				$message1 ='<html><head><title>Votre inscription sera traitée dans les 24h</title></head>
                        <body></body></html>';
        				
        				mail($unEmailClient, 'Sujet', $message1, $headers1);*/		    
    				require 'Vues/enregistrer.php';
				} 
				break;

			case 'changementMdp' :
				require 'Vues/changementMdp.php';
				break;

			case 'envoiMail' :
				$destinataire = $_GET['emailOubliMotDePasse'];
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
					$headers .= 'From: "Nom_de_expediteur"<'.$expediteur.'>'."\n"; // Expediteur
					$headers .= 'Delivered-to: '.$destinataire."\n"; // Destinataire      
					$message = 'Bonjour
					Vous avez effectué une demande de changement de mot de passe sur PPE-FLIX 
					Votre nouveau mot de passe est : '.$newPassword;
					mail($destinataire, $objet, $message, $headers); // Envoi du message
				} 
				else 
				{
				    echo "<br>L'Adresse email ".$email." est considérée comme invalide.<br>";
				}
				require 'Vues/enregistrer.php';
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
					require 'Vues/menu.php';
					echo $this->maVideotheque->listeLesGenres();	
				}
				else
				{
					// destroy la session et je repars sur l'acceuil en affichant un texte pour prévenir la personne
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

			case 'retourAccueil':
				echo "vous etes connecte";
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
