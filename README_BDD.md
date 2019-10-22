# ppe3_debele
Projet PPE3 2019 - BTS SIO seconde année option SLAM - O.BERNARD LE ROUX - T.DENYS - R.LEBRAS
********************************* REQUETE SQL POUR AFFICHER LES IMAGES **********************

ALTER TABLE genre
ADD nomImage VARCHAR(32)


UPDATE GENRE
SET nomImage = 'drameaction.png'
where idGenre = 1,

nomImage = 'fantastique.png'
where idGenre = 2,

nomImage = 'avanture.png'
where idGenre = 3, 

nomImage = 'policier.png'
where idGenre = 4,

nomImage = 'comedie.png'
where idGenre = 5,

nomImage = 'sciencesfiction.png'
where idGenre = 6,

nomImage = 'drame.png'
where idGenre = 7,

nomImage = 'guerre.png'
where idGenre = 8, 

nomImage = 'horreur.png'
where idGenre = 9, 

nomImage = 'bibliographie.png'
where idGenre = 10

/!\******* SI LA REQUETE UPDATE NE MARCHE PAS (pcq jsuis pas sur de la syntaxe) FAIRE UN UPDATE-SET-WHERE POUR CHAQUE nomImage ******* /!\
