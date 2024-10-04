-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : mariadb:3306
-- Généré le : ven. 04 oct. 2024 à 19:48
-- Version du serveur : 10.5.9-MariaDB-1:10.5.9+maria~focal
-- Version de PHP : 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tomtroc`
--
DROP DATABASE tomtroc;
CREATE DATABASE IF NOT EXISTS `tomtroc` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `tomtroc`;

-- --------------------------------------------------------

--
-- Structure de la table `book`
--

DROP TABLE IF EXISTS `book`;
CREATE TABLE `book` (
  `title` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `available` tinyint(1) NOT NULL,
  `cover` int(6) UNSIGNED NOT NULL,
  `seller` int(6) UNSIGNED NOT NULL,
  `id` int(6) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `book`
--

TRUNCATE TABLE `book`;
-- --------------------------------------------------------

--
-- Structure de la table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE `image` (
  `name` varchar(255) DEFAULT NULL,
  `src` varchar(255) DEFAULT NULL,
  `alt` varchar(255) DEFAULT NULL,
  `id` int(6) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `image`
--

TRUNCATE TABLE `image`;
--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`name`, `src`, `alt`, `id`) VALUES
('the kinfolk', '/assets/img/cover/the-kinfolk.png', 'the kinfolk', 1),
('i love coding', '/assets/img/cover/i-love-coding.png', 'i love coding', 2),
('i love biology', '/assets/img/cover/i-love-biology.jpg', 'i love biology', 3),
('default-user-photo', '/assets/img/default-user-photo.png', 'default-user-photo.png', 4),
('banner--phone', '/assets/img/banner--phone.png', 'banner--phone.png', 5),
('banner', '/assets/img/banner.png', 'banner.png', 6),
('cover', '/assets/img/cover', 'cover', 7),
('hamza-nouasria', '/assets/img/hamza-nouasria.png', 'hamza-nouasria.png', 8),
('heart', '/assets/img/heart.svg', 'heart.svg', 9),
('ico-account', '/assets/img/ico-account.svg', 'ico-account.svg', 10),
('ico-book', '/assets/img/ico-book.svg', 'ico-book.svg', 11),
('ico-message', '/assets/img/ico-message.svg', 'ico-message.svg', 12),
('login', '/assets/img/login.png', 'login.png', 13),
('logo-small', '/assets/img/logo-small.svg', 'logo-small.svg', 14),
('logo', '/assets/img/logo.svg', 'logo.svg', 15),
('search', '/assets/img/search.svg', 'search.svg', 16);

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` int(6) UNSIGNED NOT NULL,
  `content` text DEFAULT NULL,
  `sender` int(6) UNSIGNED NOT NULL,
  `receiver` int(6) UNSIGNED NOT NULL,
  `date` int(6) UNSIGNED NOT NULL,
  `checked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `message`
--

TRUNCATE TABLE `message`;
-- --------------------------------------------------------

--
-- Structure de la table `picture`
--

DROP TABLE IF EXISTS `picture`;
CREATE TABLE `picture` (
  `images` text NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `id` int(6) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `picture`
--

TRUNCATE TABLE `picture`;
--
-- Déchargement des données de la table `picture`
--

INSERT INTO `picture` (`images`, `name`, `id`) VALUES
('{\"0\":\"banner\",\"768\":\"banner--phone\"}', 'banner', 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `photo` int(6) UNSIGNED NOT NULL,
  `created_at` int(11) NOT NULL,
  `id` int(6) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tronquer la table avant d'insérer `user`
--

TRUNCATE TABLE `user`;
--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`name`, `password`, `email`, `photo`, `created_at`, `id`) VALUES
('John Doe', '$2y$10$HLPzSYj90u.gPJZZyVmwBuR7O1MHGQygvEjLcix41thFDNXy5ywPa', 'jhon.doe@example.com', 4, 1728069857, 1),
('Jane Doe', '$2y$10$3M7KXYh7KeReskaC7lyB8OgRycfFOG9lHrFgD31ULI0aDGDGGt3OC', 'jane.doe@example.com', 4, 1728069857, 2),
('Alice Doe', '$2y$10$yGFOwA/zmssTQPwyZ2jqMuedywusXT0HOzptmZFqf3qzysgfZT7LO', 'alice.doe@example.com', 4, 1728069857, 3);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`),
  ADD KEY `image_ref` (`cover`),
  ADD KEY `user_ref` (`seller`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receiver_ref` (`receiver`),
  ADD KEY `sender_ref` (`sender`);

--
-- Index pour la table `picture`
--
ALTER TABLE `picture`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photo_ref` (`photo`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `book`
--
ALTER TABLE `book`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `picture`
--
ALTER TABLE `picture`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `image_ref` FOREIGN KEY (`cover`) REFERENCES `image` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_ref` FOREIGN KEY (`seller`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `receiver_ref` FOREIGN KEY (`receiver`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sender_ref` FOREIGN KEY (`sender`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `photo_ref` FOREIGN KEY (`photo`) REFERENCES `image` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
