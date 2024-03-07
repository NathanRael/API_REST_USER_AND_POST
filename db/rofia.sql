-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 07 mars 2024 à 22:08
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `rofia`
--

-- --------------------------------------------------------

--
-- Structure de la table `event`
--

CREATE TABLE `event` (
  `eventId` varchar(255) NOT NULL,
  `eventTitle` varchar(255) NOT NULL,
  `eventDesc` text NOT NULL,
  `eventPostDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `eventImage` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `event`
--

INSERT INTO `event` (`eventId`, `eventTitle`, `eventDesc`, `eventPostDate`, `eventImage`) VALUES
('100458112765e9f99e1e62f0.02017360.d27a57a8af737f199907c077f36726f1', 'Gaming Party 1.0', 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse similique ex blanditiis odit amet ipsam dolorem, vel, velit perferendis explicabo perspiciatis ipsum nam temporibus accusamus deleniti exercitationem magnam. Sequi, sed!', '2024-03-07 17:30:06', NULL),
('202301258465e9f27fa70d47.33367115.d0c724e6047dd100f5cf9c44c9fcfb7c', 'Hackaton', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt praesentium eum non iusto error quo beatae commodi ipsam, voluptas animi molestias accusantium excepturi quae molestiae repudiandae vel reprehenderit suscipit inventore.     Sunt, incidunt numquam aliquid, provident nobis, corrupti ullam obcaecati adipisci aspernatur eos quod. Eum quis totam obcaecati harum nostrum animi iure, aperiam quam ut saepe culpa rem, ipsam dolore official ', '2024-03-07 17:01:28', NULL),
('86118140765e9f2d24e45c0.13716778.90cbed67eb2f1bdfdb10f15bd3bea9a2', 'Color Party', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt praesentium eum non iusto error quo beatae commodi ipsam, voluptas animi molestias accusantium excepturi quae molestiae repudiandae vel reprehenderit suscipit inventore.     Sunt, incidunt numquam aliquid, provident nobis, corrupti ullam obcaecati adipisci aspernatur eos quod. Eum quis totam obcaecati harum nostrum animi iure, aperiam quam ut saepe culpa rem, ipsam dolore official ', '2024-03-07 17:01:28', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `saved_event`
--

CREATE TABLE `saved_event` (
  `savedEventId` varchar(255) NOT NULL,
  `eventId` varchar(255) NOT NULL,
  `userId` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `saved_event`
--

INSERT INTO `saved_event` (`savedEventId`, `eventId`, `userId`) VALUES
('207984688265ea18fa0bb810.21397905.2444d81c70b979df2057af128c290d34', '100458112765e9f99e1e62f0.02017360.d27a57a8af737f199907c077f36726f1', '148617535865e8201a745685.07019920-4859dbf2bffa7977cc1cd57827555d0d'),
('62370638765ea198fa5e1a1.76158492.f3e4805895472e83974db114cc7c1b5e', '202301258465e9f27fa70d47.33367115.d0c724e6047dd100f5cf9c44c9fcfb7c', '148617535865e8201a745685.07019920-4859dbf2bffa7977cc1cd57827555d0d');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `userId` varchar(255) NOT NULL,
  `userName` varchar(255) NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `userImageUrl` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`userId`, `userName`, `userEmail`, `password`, `userImageUrl`) VALUES
('148617535865e8201a745685.07019920-4859dbf2bffa7977cc1cd57827555d0d', 'steve', 'steve@gmail.com', '$2y$10$j6BJtdo317Bgmqh/0t6Uz.ztKxikQ7WxdlP.DzOgihPAzYTb1Qb9.', NULL),
('162670438165e75d2b171234.78312484-0dcb7f1bd8d6a3bb9748f8ef5b012ca6', 'Audric Brian', 'audric@gmail.com', '$2y$10$MJny4KRdmvgWrjAzQ.NKl.w5y8bofk65IhwxGAg1Dba6Nzhhbngce', NULL),
('177422450265e781aa90daf0.80647393-75bac53489eb9af8b3f3ff88d3c64db0', 'emmanuel', 'emmanuel@gmail.com', '$2y$10$ij1zRJeesO6vMgLXGKBMJ.gjsLnSKcPgAZwG47lNTCshQvgWRY0DC', 'Undefined'),
('179585931365e76d64067fa8.93858524-e3569da8a63d64e7e6418d2d92afa807', 'nathan', 'nathan@gmail.com', '$2y$10$U5soZaw2FvYs4Epx6FgqXecVGAbiykkr2DgT.nq5aVW1lv5F.T7lO', NULL),
('70098622265e81529edd1b8.03583817-ae5abef19b06c3840f0390fa6bc1d38c', 'blast', 'blast@gmail.com', '$2y$10$ZNzjR/s7mEhnP/U51fA8su0QlRRGpO871vtRKB7OPJJB7vUqwzHMO', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user_request`
--

CREATE TABLE `user_request` (
  `reqId` varchar(255) NOT NULL,
  `reqDesc` text NOT NULL,
  `reqDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `reqState` varchar(20) NOT NULL DEFAULT 'Loading',
  `userId` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`eventId`);

--
-- Index pour la table `saved_event`
--
ALTER TABLE `saved_event`
  ADD PRIMARY KEY (`savedEventId`),
  ADD KEY `saved_event_ibfk_1` (`eventId`),
  ADD KEY `saved_event_ibfk_2` (`userId`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`);

--
-- Index pour la table `user_request`
--
ALTER TABLE `user_request`
  ADD PRIMARY KEY (`reqId`),
  ADD KEY `fk_user_request_userId` (`userId`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `saved_event`
--
ALTER TABLE `saved_event`
  ADD CONSTRAINT `saved_event_ibfk_1` FOREIGN KEY (`eventId`) REFERENCES `event` (`eventId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `saved_event_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `user_request`
--
ALTER TABLE `user_request`
  ADD CONSTRAINT `fk_user_request` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`),
  ADD CONSTRAINT `fk_user_request_userId` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
