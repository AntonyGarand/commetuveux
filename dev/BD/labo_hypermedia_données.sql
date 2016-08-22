-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2016 at 09:34 PM
-- Server version: 5.6.25
-- PHP Version: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `labo_hypermedia`
--

-- --------------------------------------------------------

--
-- Table structure for table `adresse`
--

CREATE TABLE IF NOT EXISTS `adresse` (
  `pk_adresse` int(11) NOT NULL,
  `no_civique` varchar(10) DEFAULT NULL,
  `rue` varchar(75) DEFAULT NULL,
  `fk_ville` int(11) DEFAULT NULL,
  `code_postal` varchar(6) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `adresse`
--

INSERT INTO `adresse` (`pk_adresse`, `no_civique`, `rue`, `fk_ville`, `code_postal`) VALUES
(1, '335', 'King Ouest', 1, 'J1H3P9'),
(2, '1418', 'Bachand', 2, 'J2P3L4'),
(3, '140', 'de la rivière', 3, 'J5K7N6'),
(4, '25', 'Després', 4, 'J9S8K5'),
(5, '75', 'Laurier', 5, 'J4F6H8'),
(6, '180', 'Lafontaine', 6, 'J4F6H8'),
(7, '1414', 'Davignon', 4, 'J4F6H8'),
(8, '1587', 'Cartier', 4, 'J4F6H8'),
(9, '35', 'Savage', 5, 'J4F6H8'),
(10, '11', 'Bernier', 6, 'J4F6H8'),
(11, '26', 'Crémazie', 2, 'J4F6H8'),
(12, '56', 'Pie IX', 6, 'J4F6H8'),
(13, '98', '9e  rang', 3, 'J4F6H8'),
(14, '157', 'Fréchette', 3, 'J4F6H8'),
(15, '123', 'rue des lilas', 7, 'J4F6H8'),
(16, '147', 'Ste-Catherine', 5, 'J4F6H8'),
(17, '359', 'Papineau', 4, 'J4F6H8'),
(18, '247', 'Decelles', 4, 'J4F6H8'),
(19, '324', 'Viger', 4, 'J4F6H8'),
(20, '6851', 'Saint-Antoine', 5, 'J4F6H8'),
(21, '127', 'des Cascades', 1, 'J4F6H8'),
(22, '678', 'Farwell', 1, 'J4F6H8'),
(23, '777', 'Prospect', 1, 'J4F6H8'),
(24, '457', 'Boul. Jacques Cartier', 1, 'J4F6H8'),
(25, '651', 'Argil', 1, 'J4F6H8'),
(26, '3532', 'Durham', 1, 'J4F6H8'),
(27, '2415', 'Victoria', 1, 'J4F6H8'),
(28, '167', 'Montréal', 1, 'J4F6H8'),
(29, '154', 'Terril', 1, 'J4F6H8'),
(30, '1489', '12e Avenue', 1, 'J4F6H8'),
(31, '1674', 'Codère', 5, 'J4F6H8'),
(32, '598', 'John', 6, 'J4F6H8'),
(33, '24', 'Abot', 2, 'J4F6H8'),
(34, '59', 'Pricipale', 4, 'J4F6H8'),
(35, '789', 'Fontaine', 2, 'J4F6H8'),
(36, '521', 'Maisonneuve', 5, 'J4F6H8'),
(37, '12', 'Bowen', 2, 'J4F6H8');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE IF NOT EXISTS `client` (
  `pk_client` int(11) NOT NULL,
  `fk_utilisateur` int(11) DEFAULT NULL,
  `prenom` varchar(75) DEFAULT NULL,
  `nom` varchar(75) DEFAULT NULL,
  `fk_adresse` int(11) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `infolettre` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`pk_client`, `fk_utilisateur`, `prenom`, `nom`, `fk_adresse`, `telephone`, `infolettre`) VALUES
(1, 4, 'Didier', 'Desrosiers', 1, '819-565-1425', 0),
(2, 5, 'Marc', 'Beaudoin', 1, '819-475-2142', 1),
(3, 6, 'Carlos', 'Gendron', 2, '819-658-6325', 1),
(4, 7, 'Geneviève', 'Pommerleau', 3, '819-145-5865', 0),
(5, 8, 'Ronald', 'Caron', 4, '819-145-5774', 0),
(6, 9, 'Karine', 'Thibault', 25, '819-335-6585', 0),
(7, 10, 'Paul', 'Robert', 24, '819-993-5685', 1),
(8, 11, 'Thierry', 'Robitaille', 23, '819-991-2541', 1),
(9, 12, 'Line', 'Lauzon', 22, '819-474-2365', 1),
(10, 13, 'Roger', 'Bouchard', 21, '819-147-2586', 0),
(11, 14, 'Pascale', 'Larivère', 20, '819-145-3252', 1),
(12, 15, 'Michel', 'Desautels', 19, '819-787-5899', 0),
(13, 16, 'Paul', 'Ménard', 18, '819-414-6355', 0),
(14, 17, 'Christian', 'Bournival', 17, '819-885-2475', 1),
(15, 18, 'Carole', 'Coté', 16, '819-992-5821', 1),
(16, 19, 'Kim', 'Bergeron', 15, '819-586-5874', 1),
(17, 20, 'Alex', 'Labbé', 14, '819-444-7777', 0),
(18, 21, 'Samuel', 'Tremblay', 31, '819-352-6698', 0),
(19, 22, 'Fancis', 'Lamothe', 12, '819-919-2525', 1),
(20, 23, 'Laurie', 'Landry', 11, '819-266-2525', 1),
(21, 24, 'Brigitte', 'Masson', 10, '819-444-1919', 0),
(22, 25, 'Isabelle', 'Bellehumeur', 9, '819-888-9999', 1),
(23, 26, 'Martin', 'Marin', 8, '819-254-7474', 0),
(24, 27, 'Claude', 'Lapointe', 7, '819-695-8747', 1),
(25, 28, 'Matthew', 'Fréchette', 6, '819-222-2424', 0),
(26, 29, 'Steve', 'Gates', 5, '819-666-6666', 1);

-- --------------------------------------------------------

--
-- Table structure for table `facture`
--

CREATE TABLE IF NOT EXISTS `facture` (
  `pk_facture` int(11) NOT NULL,
  `fk_client` int(11) DEFAULT NULL,
  `date_service` datetime DEFAULT NULL,
  `paiement_status` tinyint(1) DEFAULT NULL,
  `no_confirmation` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `facture`
--

INSERT INTO `facture` (`pk_facture`, `fk_client`, `date_service`, `paiement_status`, `no_confirmation`) VALUES
(1, 2, '2016-08-03 00:00:00', 1, 'hfg5165fgh6152g'),
(2, 3, '2016-08-17 00:00:00', 1, 'g1mu65ykfh65li'),
(3, 2, '2016-08-15 00:00:00', 1, 'fgh1liu651hg2j16u'),
(4, 6, '2016-08-17 00:00:00', 1, '1j66k8t84jk4li654fb'),
(5, 8, '2016-08-30 00:00:00', 1, 'dndg65m1gd5m16dg5hm'),
(6, 10, '2016-08-31 00:00:00', 1, 'tyui5h416dg84md61m'),
(7, 8, '2016-09-20 00:00:00', 1, 'd1651n6s5fghn651'),
(8, 10, '2016-09-21 00:00:00', 1, 'dghn41dg65m1ui6'),
(9, 11, '2016-10-25 00:00:00', 1, '2hjm16f5h1jm1h6j5m'),
(10, 12, '2016-10-26 00:00:00', 1, '651nvbn6516ynm'),
(11, 12, '2016-11-22 00:00:00', 1, 'f561n6fdg51n65f1gnh'),
(12, 13, '2016-11-29 00:00:00', 1, '1n65gdhn6gd1hnr'),
(13, 14, '2016-08-31 00:00:00', 1, 'vdf1651nsfghn651um'),
(14, 18, '2016-09-14 00:00:00', 1, '1mu618g6mdgh2bb'),
(15, 23, '2016-12-28 00:00:00', 1, '5ng6d5h1n65gd1hn65'),
(16, 25, '2016-12-25 00:00:00', 1, 'b4d6b1dfhg1n6f8541n6fr');

-- --------------------------------------------------------

--
-- Table structure for table `promotion`
--

CREATE TABLE IF NOT EXISTS `promotion` (
  `pk_promotion` int(11) NOT NULL,
  `promotion_titre` varchar(75) DEFAULT NULL,
  `promotion_description` longtext,
  `rabais` decimal(2,2) DEFAULT NULL,
  `image` varchar(150) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `promotion`
--

INSERT INTO `promotion` (`pk_promotion`, `promotion_titre`, `promotion_description`, `rabais`, `image`) VALUES
(1, 'C''est la rentrée !', 'Vous venez de vous procurer un nouvel appareil et souhaitez apprendre à maîtriser la bête ? Venez suivre une de nos formations pour un maximum d''efficacité. Un rabais de 20% vous est offert jusqu''au 30 septembre!', '0.99', NULL),
(2, 'Le savoir en cadeau', 'Vous ne savez pas quoi offrir à un être cher. Offrez lui la liberté ! Rien de tel que de maîtriser son Windows pour en profiter pleinement! Un rabais de 25% vous est offert sur nos formations et ce jusqu''au 31 décembre 2016. Dépêchez vous !', '0.99', NULL),
(3, 'C''est le printemps!', 'Enfin l''hiver nous a quitté ! Vous rêvez de réaliser de beaux clichés avec votre appareil photo, mais vous manquez de connaissances sur les 8 millions de paramètres possibles. Profitez de cette promotion sur nos formation sur la photo numérique.', '0.99', NULL),
(4, 'Un cadeau pour la fête des pères', 'Votre papa se débrouille un peu à l''ordinateur, mais aimerais être plus à l''aise ? Offrez lui une formation.', '0.99', NULL),
(5, 'Besoin d''une mise à niveau', 'Vous venez de passez à la nouvelle suite Office 365. Laissez nous vous aider à profiter pleinement des nouvelles fonctionnalité!', '0.99', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE IF NOT EXISTS `service` (
  `pk_service` int(11) NOT NULL,
  `service_titre` varchar(75) DEFAULT NULL,
  `service_description` longtext,
  `durée` int(11) DEFAULT NULL,
  `tarif` decimal(6,2) DEFAULT NULL,
  `actif` tinyint(1) DEFAULT NULL,
  `image` varchar(150) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`pk_service`, `service_titre`, `service_description`, `durée`, `tarif`, `actif`, `image`) VALUES
(1, 'Access 2016', 'Apprenez à créer des bases de données simple et à créer des applications personnalisées pour vous aider à gérer votre entreprise.', 16, '320.00', 1, NULL),
(2, 'Excel débutant', 'Ce cours a pour objectif de vous initier au chiffrier Excel, pour vous permettre de créer des classeurs et de les mettre en forme professionnellement.', 25, '200.00', 1, NULL),
(3, 'Initiation à la photographie numérique', 'Étudiez les concepts de base en photographie tel que les différentes prises de vue, le cadrage et l''éclairage. Apprenez à utiliser les différents modes de votre appareil photo numérique et commencez à faire de la retouche photo avec Photoshop. La formation comprend des exercices en studio et à l’extérieur. ', 14, '280.00', 1, NULL),
(4, 'Matériel informatique', 'Étudiez les composantes d’un ordinateur PC compatible. Effectuez le montage et la configuration d’un ordinateur. Apprenez à établir un diagnostic et à réparer un ordinateur. La formation comprend des laboratoires pratiques.', 16, '320.00', 1, NULL),
(5, 'Linux', 'Apprenez à installer et à utiliser le système d''exploitation Linux, une alternative gratuite et performante à Windows.', 12, '240.00', 1, NULL),
(6, 'Outlook 2016', 'Communiquez de manière plus efficace et centralisée la gestion de vos contacts, votre calendrier et vos tâches facilement avec Outlook.', 4, '80.00', 1, NULL),
(7, 'Windows 7', 'Découvrez comment naviguer sur votre ordinateur avec la formation Windows. Démystifiez les menus et apprenez comment gérer vos fichiers et vos dossiers. Devenez un utilisateur éclairé en comprenant comment gérer les paramètres de votre système d’exploitation et comment naviguer sécuritairement sur Internet.', 18, '360.00', 1, NULL),
(8, 'Windows 10', 'Découvrez comment naviguer sur votre ordinateur avec la formation Windows. Démystifiez les menus et apprenez comment gérer vos fichiers et vos dossiers. Devenez un utilisateur éclairé en comprenant comment gérer les paramètres de votre système d’exploitation et comment naviguer sécuritairement sur Internet.', 18, '360.00', 1, NULL),
(9, 'Office 365', 'Suivez la formation sur Microsoft Office 365 dont vous avez besoin. Développez les compétences nécessaires pour configurer et gérer Microsoft Office 365 pour votre organisation.', 12, '360.00', 1, NULL),
(10, 'Word 2016', 'Apprenez à créer des documents soignés avec Word en utilisant efficacement les outils de mise en forme et les tableaux. Gérez la rédaction de plusieurs lettres et l’impression d’étiquettes à différents destinataires et apprenez à créer des modèles pour simplifier votre travail ou pour créer des formulaires personnalisés.', 320, '160.00', 1, NULL),
(11, 'Initiation à internet et aux courriels', 'Apprenez à naviguer sur Internet. Vous y découvrirez une source d''information incroyable. De plus, apprenez à communiquer avec vos amis avec des courriels.', 5, '75.00', 1, NULL),
(12, 'Excel intermédiaire', 'Ce cours a pour objectif d''approfondir vos acquis en Excel, introduire les fonctions, les graphiques et les fonctionnalités plus pointues du logiciel.', 25, '200.00', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ta_facture_service`
--

CREATE TABLE IF NOT EXISTS `ta_facture_service` (
  `pk_facture_service` int(11) NOT NULL,
  `fk_facture` int(11) DEFAULT NULL,
  `fk_service` int(11) DEFAULT NULL,
  `tarif_facturé` decimal(6,2) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ta_facture_service`
--

INSERT INTO `ta_facture_service` (`pk_facture_service`, `fk_facture`, `fk_service`, `tarif_facturé`) VALUES
(1, 1, 1, '320.00'),
(2, 1, 5, '240.00'),
(3, 2, 3, '280.00'),
(4, 2, 4, '320.00'),
(5, 3, 3, '280.00'),
(6, 4, 6, '80.00'),
(7, 5, 8, '360.00'),
(8, 5, 10, '160.00'),
(9, 6, 11, '75.00'),
(10, 7, 7, '288.00'),
(11, 8, 4, '320.00'),
(12, 9, 9, '360.00'),
(13, 10, 2, '480.00'),
(14, 11, 3, '280.00'),
(15, 12, 9, '306.00'),
(16, 13, 3, '280.00'),
(17, 14, 6, '80.00'),
(18, 15, 3, '280.00'),
(19, 16, 8, '360.00'),
(20, 16, 4, '240.00');

-- --------------------------------------------------------

--
-- Table structure for table `ta_promotion_service`
--

CREATE TABLE IF NOT EXISTS `ta_promotion_service` (
  `pk_promotion_service` int(11) NOT NULL,
  `fk_promotion` int(11) DEFAULT NULL,
  `fk_service` int(11) DEFAULT NULL,
  `date_debut` datetime DEFAULT NULL,
  `date_fin` datetime DEFAULT NULL,
  `code` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ta_promotion_service`
--

INSERT INTO `ta_promotion_service` (`pk_promotion_service`, `fk_promotion`, `fk_service`, `date_debut`, `date_fin`, `code`) VALUES
(1, 1, 7, '2016-08-01 00:00:00', '2016-09-30 00:00:00', 'rentree2016'),
(2, 2, 4, '2016-11-15 00:00:00', '2016-12-31 00:00:00', 'noel2016'),
(3, 5, 9, '2016-11-01 00:00:00', '2016-11-30 00:00:00', 'o365');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `pk_utilisateur` int(11) NOT NULL,
  `courriel` varchar(100) DEFAULT NULL,
  `mot_de_passe` varchar(100) DEFAULT NULL,
  `administrateur` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`pk_utilisateur`, `courriel`, `mot_de_passe`, `administrateur`) VALUES
(1, 'admin@gmail.com', 'vanillefrancaise', 1),
(2, 'simduf15@gmail.com', 'pamplemousseBleu', 1),
(3, 'jujug@gmail.com', 'fraiseetmenthe', 1),
(4, 'didier124@gmail.com', 'tomateauriz', 0),
(5, 'marcbeaudoin@outlook.com', 'bonnepomme', 0),
(6, 'carlosamigo@gmail.com', 'cokemystere', 0),
(7, 'gengen25@sympatico.com', 'sauceauprunne', 0),
(8, 'salutmonron@outlook.com', 'mayoauoignon', 0),
(9, 'karine_thibault@outlook.com', 'churasco', 0),
(10, 'paul.robert@sympatico.com', 'gruaunature', 0),
(11, 'thierry_robitaille@hotmail.com', 'fejouada', 0),
(12, 'line.lauzon@outlook.com', 'citrouillemauve', 0),
(13, 'Roger_bouchard@hotmail.com', 'poivreetsel', 0),
(14, 'pascale.lariviere22@gmail.com', 'thetutisanetoi', 0),
(15, 'michel.desautel34@hotmail.com', 'glucoseausucre', 0),
(16, 'paul_menard@gmail.com', 'cacaocacabas', 0),
(17, 'christian.bournival@hotmail.com', 'lamontagnedunord', 0),
(18, 'carole_cote23@outlook.com', 'avoineetmiel', 0),
(19, 'kim_bergeron@gmail.com', 'finesherbeetfromage', 0),
(20, 'alex_labbe123@hotmail.com', 'titesaucebbq', 0),
(21, 'sonicetmario@yahoo.com', 'fritesauce', 0),
(22, 'frank_lamothe@yahoo.com', 'saladecesar', 0),
(23, 'laurie-landry@yahoo.com', 'oeufalacoq', 0),
(24, 'brigitte-masson@gmail.com', 'crepejambon', 0),
(25, 'isa-bellehumeur@hotmail.com', 'cafedumatin', 0),
(26, 'martin-marin@videotron.ca', 'vanilleouchocolat', 0),
(27, 'claude_lapointe@hotmail.com', 'yogourtaupistache', 0),
(28, 'mathetfr@videotron.ca', 'carottecuite', 0),
(29, 'alainD567@hotmail.com', 'feveaulard', 0),
(30, 'systeme.d@videotron.ca', 'questcequonmange', 0),
(31, 'code18@hotmail.com', 'jaifaim', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ville`
--

CREATE TABLE IF NOT EXISTS `ville` (
  `pk_ville` int(11) NOT NULL,
  `ville` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ville`
--

INSERT INTO `ville` (`pk_ville`, `ville`) VALUES
(1, 'Sherbrooke'),
(2, 'Magog'),
(3, 'Orford'),
(4, 'North Hatley'),
(5, 'Windsor'),
(6, 'Waterville'),
(7, 'Saint-Denis-de-Brompton'),
(8, 'Eastman'),
(9, 'Racine');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adresse`
--
ALTER TABLE `adresse`
  ADD PRIMARY KEY (`pk_adresse`),
  ADD KEY `fk_ville_idx` (`fk_ville`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`pk_client`),
  ADD KEY `fk_adresse_idx` (`fk_adresse`),
  ADD KEY `fk_utilisateur_idx` (`fk_utilisateur`);

--
-- Indexes for table `facture`
--
ALTER TABLE `facture`
  ADD PRIMARY KEY (`pk_facture`),
  ADD KEY `fk_client_idx` (`fk_client`);

--
-- Indexes for table `promotion`
--
ALTER TABLE `promotion`
  ADD PRIMARY KEY (`pk_promotion`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`pk_service`);

--
-- Indexes for table `ta_facture_service`
--
ALTER TABLE `ta_facture_service`
  ADD PRIMARY KEY (`pk_facture_service`),
  ADD KEY `fk_facture_idx` (`fk_facture`),
  ADD KEY `fk_promotion_service_idx` (`fk_service`);

--
-- Indexes for table `ta_promotion_service`
--
ALTER TABLE `ta_promotion_service`
  ADD PRIMARY KEY (`pk_promotion_service`),
  ADD KEY `fk_service_idx` (`fk_service`),
  ADD KEY `fk_promotion_idx` (`fk_promotion`);

--
-- Indexes for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`pk_utilisateur`);

--
-- Indexes for table `ville`
--
ALTER TABLE `ville`
  ADD PRIMARY KEY (`pk_ville`),
  ADD KEY `pk_ville` (`pk_ville`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adresse`
--
ALTER TABLE `adresse`
  MODIFY `pk_adresse` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `pk_client` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `facture`
--
ALTER TABLE `facture`
  MODIFY `pk_facture` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `promotion`
--
ALTER TABLE `promotion`
  MODIFY `pk_promotion` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `pk_service` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `ta_facture_service`
--
ALTER TABLE `ta_facture_service`
  MODIFY `pk_facture_service` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `ta_promotion_service`
--
ALTER TABLE `ta_promotion_service`
  MODIFY `pk_promotion_service` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `pk_utilisateur` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `ville`
--
ALTER TABLE `ville`
  MODIFY `pk_ville` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `adresse`
--
ALTER TABLE `adresse`
  ADD CONSTRAINT `fk_ville` FOREIGN KEY (`fk_ville`) REFERENCES `ville` (`pk_ville`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `fk_adresse` FOREIGN KEY (`fk_adresse`) REFERENCES `adresse` (`pk_adresse`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_utilisateur` FOREIGN KEY (`fk_utilisateur`) REFERENCES `utilisateur` (`pk_utilisateur`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `facture`
--
ALTER TABLE `facture`
  ADD CONSTRAINT `fk_client` FOREIGN KEY (`fk_client`) REFERENCES `client` (`pk_client`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `ta_facture_service`
--
ALTER TABLE `ta_facture_service`
  ADD CONSTRAINT `fk_facture` FOREIGN KEY (`fk_facture`) REFERENCES `facture` (`pk_facture`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_service1` FOREIGN KEY (`fk_service`) REFERENCES `service` (`pk_service`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `ta_promotion_service`
--
ALTER TABLE `ta_promotion_service`
  ADD CONSTRAINT `fk_promotion` FOREIGN KEY (`fk_promotion`) REFERENCES `promotion` (`pk_promotion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_service` FOREIGN KEY (`fk_service`) REFERENCES `service` (`pk_service`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
