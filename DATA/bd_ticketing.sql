-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 15 fév. 2024 à 14:22
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bd_ticketing`
--

-- --------------------------------------------------------

--
-- Structure de la table `libelle_ticket`
--

CREATE TABLE `libelle_ticket` (
  `id_libelle` int(11) NOT NULL,
  `libelle` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `libelle_ticket`
--

INSERT INTO `libelle_ticket` (`id_libelle`, `libelle`) VALUES
(9, 'Demande d\'assistance pour la configuration'),
(7, 'Problème avec un périphérique externe'),
(3, 'Problème d\'accès à un logiciel'),
(4, 'Problème d\'impression'),
(1, 'Problème de connexion réseau'),
(6, 'Problème de matériel'),
(5, 'Problème de messagerie électronique'),
(8, 'Problème de stockage');

-- --------------------------------------------------------

--
-- Structure de la table `log`
--

CREATE TABLE `log` (
  `id_ticket` int(11) NOT NULL,
  `login` int(32) NOT NULL,
  `contenu` text NOT NULL,
  `technicien` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tickets`
--

CREATE TABLE `tickets` (
  `id_ticket` int(11) NOT NULL,
  `login` varchar(32) NOT NULL,
  `id_libelle` int(11) NOT NULL,
  `description` text NOT NULL,
  `salle` varchar(3) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `priorite` enum('Faible','Moyen','Important','Urgent') NOT NULL,
  `date_creation` date NOT NULL,
  `statut` enum('Ouvert','En cours','Fermé') NOT NULL DEFAULT 'Ouvert',
  `technicien` varchar(32) NOT NULL DEFAULT 'Personne'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tickets`
--

INSERT INTO `tickets` (`id_ticket`, `login`, `id_libelle`, `description`, `salle`, `ip`, `priorite`, `date_creation`, `statut`, `technicien`) VALUES
(45, 'armand', 9, '(\"y\"\'r', 'G22', '192.168.1.60', 'Faible', '2024-02-11', 'En cours', 'armand');

-- --------------------------------------------------------

--
-- Structure de la table `tickets_close`
--

CREATE TABLE `tickets_close` (
  `id_ticket` int(11) NOT NULL,
  `login` varchar(32) NOT NULL,
  `id_libelle` int(11) NOT NULL,
  `description` text NOT NULL,
  `priorite` enum('Faible','Moyen','Important','Urgent') NOT NULL,
  `date_creation` date NOT NULL,
  `date_fermeture` date NOT NULL DEFAULT current_timestamp(),
  `statut` enum('Ouvert','En cours','Fermé') NOT NULL,
  `technicien` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tickets_close`
--

INSERT INTO `tickets_close` (`id_ticket`, `login`, `id_libelle`, `description`, `priorite`, `date_creation`, `date_fermeture`, `statut`, `technicien`) VALUES
(33, 'rayan', 9, 'test', 'Faible', '2024-01-11', '2024-01-11', 'Fermé', 'tech1'),
(34, 'rayan', 4, 'toita', 'Faible', '2024-01-11', '2024-01-11', 'Fermé', 'tech1'),
(35, 'rayan', 9, 'sdfdf\r\n\r\n', 'Faible', '2024-01-11', '2024-01-11', 'Fermé', 'tech1'),
(36, 'rayan', 4, 'qfqfqf', 'Important', '2024-01-11', '2024-01-12', 'Fermé', 'tech2'),
(37, 'rayan', 8, 'blablabla', 'Important', '2024-01-12', '2024-01-12', 'Fermé', 'tech1'),
(39, 'rayan', 9, 'zfaf', 'Faible', '2024-01-12', '2024-01-12', 'Fermé', 'tech1');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nom` varchar(32) NOT NULL,
  `login` varchar(32) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mdp` varchar(32) NOT NULL,
  `user_role` enum('utilisateur','admin_systeme','admin_web','technicien') NOT NULL DEFAULT 'utilisateur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `nom`, `login`, `email`, `mdp`, `user_role`) VALUES
(9, 'ryry', 'ryry', 'ryry@gmail.com', '3f1c4215f8cb', 'utilisateur'),
(11, 'Gestion', 'gestion', 'gestion@gestion.com', '3f1c4215f8cb', 'admin_web'),
(12, 'tech1', 'tech1', 'tech1@gmail.com', '3f1c4215f8cb', 'technicien'),
(13, 'rayan', 'rayan', 'rayan@gmail.com', '3f1c4215f8cb', 'utilisateur'),
(14, 'tech2', 'tech2', 'tech2@gmail.com', '3f1c4215f8cb', 'technicien'),
(16, 'admin système', 'admin', 'admin_sys@gmail.com', '2a095308', 'admin_systeme'),
(17, 'Polo', 'tech3', 'tech3@gmail.com', '3f1c4215f8cb', 'technicien'),
(18, 'Armand', 'armand', 'armand@gmail.fr', '3f1c4215f8cb', 'utilisateur'),
(19, 'test', 'test2', 'test@outlook.com', '2a035413', 'utilisateur');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `libelle_ticket`
--
ALTER TABLE `libelle_ticket`
  ADD PRIMARY KEY (`id_libelle`),
  ADD UNIQUE KEY `libelle` (`libelle`);

--
-- Index pour la table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id_ticket`);

--
-- Index pour la table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id_ticket`);

--
-- Index pour la table `tickets_close`
--
ALTER TABLE `tickets_close`
  ADD PRIMARY KEY (`id_ticket`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `libelle_ticket`
--
ALTER TABLE `libelle_ticket`
  MODIFY `id_libelle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `log`
--
ALTER TABLE `log`
  MODIFY `id_ticket` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id_ticket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
