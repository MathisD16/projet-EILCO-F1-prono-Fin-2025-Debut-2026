-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : ven. 17 juil. 2026 à 09:06
-- Version du serveur : 8.0.40
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `f1_prono`
--

-- --------------------------------------------------------

--
-- Structure de la table `circuits`
--

CREATE TABLE `circuits` (
  `id` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `drapeau` varchar(10) NOT NULL,
  `lieu` varchar(100) NOT NULL,
  `longueur` varchar(50) NOT NULL,
  `virages` int NOT NULL,
  `annee_apparition` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `circuits`
--

INSERT INTO `circuits` (`id`, `nom`, `drapeau`, `lieu`, `longueur`, `virages`, `annee_apparition`) VALUES
(1, 'Monza', '🇮🇹', 'Monza', '5.793 km', 11, 1950),
(2, 'Monaco', '🇲🇨', 'Monte-Carlo', '3.337 km', 19, 1950),
(3, 'Silverstone', '🇬🇧', 'Silverstone', '5.891 km', 18, 1950),
(4, 'Spa-Francorchamps', '🇧🇪', 'Spa', '6.976 km', 19, 1950),
(5, 'Zandvoort', '🇳🇱', 'Zandvoort', '4.259 km', 14, 1952),
(6, 'Hermanos Rodríguez', '🇲🇽', 'Mexico City', '4.304 km', 17, 1963),
(7, 'Red Bull Ring', '🇦🇹', 'Spielberg', '4.318 km', 10, 1970),
(8, 'Interlagos', '🇧🇷', 'São Paulo', '4.309 km', 15, 1973),
(9, 'Gilles Villeneuve', '🇨🇦', 'Montréal', '4.361 km', 14, 1978),
(10, 'Hungaroring', '🇭🇺', 'Budapest', '4.381 km', 14, 1986),
(11, 'Suzuka', '🇯🇵', 'Suzuka', '5.807 km', 18, 1987),
(12, 'Barcelona-Catalunya', '🇪🇸', 'Barcelone', '4.675 km', 16, 1991),
(13, 'Albert Park', '🇦🇺', 'Melbourne', '5.303 km', 16, 1996),
(14, 'Shanghai Intl.', '🇨🇳', 'Shanghai', '5.451 km', 16, 2004),
(15, 'Bahrain Intl.', '🇧🇭', 'Sakhir', '5.412 km', 15, 2004),
(16, 'Marina Bay', '🇸🇬', 'Singapour', '5.063 km', 23, 2008),
(17, 'Yas Marina', '🇦🇪', 'Abu Dhabi', '5.281 km', 16, 2009),
(18, 'COTA', '🇺🇸', 'Austin', '5.513 km', 20, 2012),
(19, 'Baku City', '🇦🇿', 'Bakou', '6.003 km', 20, 2016),
(20, 'Jeddah Corniche', '🇸🇦', 'Djeddah', '6.174 km', 27, 2021),
(21, 'Lusail Intl.', '🇶🇦', 'Qatar', '5.380 km', 16, 2021),
(22, 'Miami Autodrome', '🇺🇸', 'Miami', '5.412 km', 19, 2022),
(23, 'Las Vegas Strip', '🇺🇸', 'Las Vegas', '6.120 km', 17, 2023),
(24, 'Madrid (IFEMA)', '🇪🇸', 'Madrid', '5.474 km', 22, 2026);

-- --------------------------------------------------------

--
-- Structure de la table `courses`
--

CREATE TABLE `courses` (
  `id` int NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_course` datetime NOT NULL,
  `type_course` enum('grille','sprint','course') COLLATE utf8mb4_general_ci DEFAULT 'course',
  `statut` enum('a_venir','en_cours','termine') COLLATE utf8mb4_general_ci DEFAULT 'a_venir'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `courses`
--

INSERT INTO `courses` (`id`, `nom`, `date_course`, `type_course`, `statut`) VALUES
(116, 'Qualifications - GP d\'Australie', '2026-03-07 06:00:00', 'grille', 'a_venir'),
(117, 'Course - GP d\'Australie', '2026-03-08 05:00:00', 'course', 'a_venir'),
(118, 'Sprint - GP de Chine', '2026-03-14 04:00:00', 'sprint', 'a_venir'),
(119, 'Qualifications - GP de Chine', '2026-03-14 08:00:00', 'grille', 'a_venir'),
(120, 'Course - GP de Chine', '2026-03-15 08:00:00', 'course', 'a_venir'),
(121, 'Qualifications - GP du Japon', '2026-03-28 07:00:00', 'grille', 'a_venir'),
(122, 'Course - GP du Japon', '2026-03-29 07:00:00', 'course', 'a_venir'),
(123, 'Qualifications - GP de Bahreïn', '2026-04-11 18:00:00', 'grille', 'a_venir'),
(124, 'Course - GP de Bahreïn', '2026-04-12 17:00:00', 'course', 'a_venir'),
(125, 'Qualifications - GP d\'Arabie Saoudite', '2025-04-18 19:00:00', 'grille', 'a_venir'),
(126, 'Course - GP d\'Arabie Saoudite', '2025-04-19 19:00:00', 'course', 'termine'),
(127, 'Sprint - GP de Miami', '2026-05-02 18:00:00', 'sprint', 'a_venir'),
(128, 'Qualifications - GP de Miami', '2026-05-02 22:00:00', 'grille', 'a_venir'),
(129, 'Course - GP de Miami', '2026-05-03 22:00:00', 'course', 'a_venir'),
(130, 'Sprint - GP du Canada', '2026-05-23 18:00:00', 'sprint', 'a_venir'),
(131, 'Qualifications - GP du Canada', '2026-05-23 22:00:00', 'grille', 'a_venir'),
(132, 'Course - GP du Canada', '2026-05-24 22:00:00', 'course', 'a_venir'),
(133, 'Qualifications - GP de Monaco', '2026-06-06 16:00:00', 'grille', 'a_venir'),
(134, 'Course - GP de Monaco', '2026-06-07 15:00:00', 'course', 'a_venir'),
(135, 'Qualifications - GP de Barcelone', '2026-06-13 16:00:00', 'grille', 'a_venir'),
(136, 'Course - GP de Barcelone', '2026-06-14 15:00:00', 'course', 'a_venir'),
(137, 'Qualifications - GP d\'Autriche', '2026-06-27 16:00:00', 'grille', 'a_venir'),
(138, 'Course - GP d\'Autriche', '2026-06-28 15:00:00', 'course', 'a_venir'),
(139, 'Sprint - GP de Grande-Bretagne', '2026-07-04 13:00:00', 'sprint', 'a_venir'),
(140, 'Qualifications - GP de Grande-Bretagne', '2026-07-04 17:00:00', 'grille', 'a_venir'),
(141, 'Course - GP de Grande-Bretagne', '2026-07-05 16:00:00', 'course', 'a_venir'),
(142, 'Qualifications - GP de Belgique', '2026-07-18 16:00:00', 'grille', 'a_venir'),
(143, 'Course - GP de Belgique', '2026-07-19 15:00:00', 'course', 'a_venir'),
(144, 'Qualifications - GP de Hongrie', '2026-07-25 16:00:00', 'grille', 'a_venir'),
(145, 'Course - GP de Hongrie', '2026-07-26 15:00:00', 'course', 'a_venir'),
(146, 'Sprint - GP des Pays-Bas', '2026-08-22 12:00:00', 'sprint', 'a_venir'),
(147, 'Qualifications - GP des Pays-Bas', '2026-08-22 16:00:00', 'grille', 'a_venir'),
(148, 'Course - GP des Pays-Bas', '2026-08-23 15:00:00', 'course', 'a_venir'),
(149, 'Qualifications - GP d\'Italie', '2026-09-05 16:00:00', 'grille', 'a_venir'),
(150, 'Course - GP d\'Italie', '2026-09-06 15:00:00', 'course', 'a_venir'),
(151, 'Qualifications - GP d\'Espagne', '2026-09-12 16:00:00', 'grille', 'a_venir'),
(152, 'Course - GP d\'Espagne', '2026-09-13 15:00:00', 'course', 'a_venir'),
(153, 'Qualifications - GP d\'Azerbaïdjan', '2026-09-25 14:00:00', 'grille', 'a_venir'),
(154, 'Course - GP d\'Azerbaïdjan', '2026-09-26 13:00:00', 'course', 'a_venir'),
(155, 'Sprint - GP de Singapour', '2026-10-10 11:00:00', 'sprint', 'a_venir'),
(156, 'Qualifications - GP de Singapour', '2026-10-10 15:00:00', 'grille', 'a_venir'),
(157, 'Course - GP de Singapour', '2026-10-11 14:00:00', 'course', 'a_venir'),
(158, 'Qualifications - GP des USA', '2026-10-24 23:00:00', 'grille', 'a_venir'),
(159, 'Course - GP des USA', '2026-10-25 21:00:00', 'course', 'a_venir'),
(160, 'Qualifications - GP de Mexico', '2026-10-31 22:00:00', 'grille', 'a_venir'),
(161, 'Course - GP de Mexico', '2026-11-01 21:00:00', 'course', 'a_venir'),
(162, 'Qualifications - GP de São Paulo', '2026-11-07 19:00:00', 'grille', 'a_venir'),
(163, 'Course - GP de São Paulo', '2026-11-08 18:00:00', 'course', 'a_venir'),
(164, 'Qualifications - GP de Las Vegas', '2026-11-21 05:00:00', 'grille', 'a_venir'),
(165, 'Course - GP de Las Vegas', '2026-11-22 05:00:00', 'course', 'a_venir'),
(166, 'Qualifications - GP du Qatar', '2026-11-28 19:00:00', 'grille', 'a_venir'),
(167, 'Course - GP du Qatar', '2026-11-29 17:00:00', 'course', 'a_venir'),
(168, 'Qualifications - GP d\'Abu Dhabi', '2026-12-05 15:00:00', 'grille', 'a_venir'),
(169, 'Course - GP d\'Abu Dhabi', '2026-12-06 14:00:00', 'course', 'a_venir');

-- --------------------------------------------------------

--
-- Structure de la table `equipes`
--

CREATE TABLE `equipes` (
  `id` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `pays` varchar(10) NOT NULL,
  `annee_debut` int NOT NULL,
  `victoires` int NOT NULL DEFAULT '0',
  `titres` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `equipes`
--

INSERT INTO `equipes` (`id`, `nom`, `pays`, `annee_debut`, `victoires`, `titres`) VALUES
(1, 'Ferrari', '🇮🇹', 1950, 248, 16),
(2, 'McLaren', '🇬🇧', 1966, 203, 10),
(3, 'Red Bull', '🇦🇹', 2005, 130, 6),
(4, 'Mercedes', '🇩🇪', 2010, 131, 8),
(5, 'Williams', '🇬🇧', 1975, 114, 9),
(6, 'Alpine', '🇫🇷', 2021, 1, 0),
(7, 'Aston Martin', '🇬🇧', 2021, 0, 0),
(8, 'Haas', '🇺🇸', 2016, 0, 0),
(9, 'Racing Bulls', '🇮🇹', 2006, 2, 0),
(10, 'Audi', '🇩🇪', 2026, 0, 0),
(11, 'Cadillac', '🇺🇸', 2026, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `pilotes`
--

CREATE TABLE `pilotes` (
  `id` int NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `numero` int DEFAULT NULL,
  `nationalite` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `ecurie` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `age` int NOT NULL,
  `gp_disputes` int NOT NULL,
  `victoires` int NOT NULL DEFAULT '0',
  `titres` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `pilotes`
--

INSERT INTO `pilotes` (`id`, `nom`, `numero`, `nationalite`, `ecurie`, `age`, `gp_disputes`, `victoires`, `titres`) VALUES
(1, 'Max Verstappen', 3, '🇳🇱', 'Red Bull', 28, 233, 70, 4),
(2, 'Arvid Lindblad', 36, '🇬🇧', 'Racing Bulls', 18, 0, 0, 0),
(3, 'Charles Leclerc', 16, '🇲🇨', 'Ferrari', 28, 171, 8, 0),
(4, 'Lewis Hamilton', 44, '🇬🇧', 'Ferrari', 40, 380, 105, 7),
(5, 'Lando Norris', 1, '🇬🇧', 'McLaren', 26, 152, 11, 1),
(6, 'Oscar Piastri', 81, '🇦🇺', 'McLaren', 24, 70, 7, 0),
(7, 'George Russell', 63, '🇬🇧', 'Mercedes', 27, 152, 5, 0),
(8, 'Kimi Antonelli', 12, '🇮🇹', 'Mercedes', 19, 24, 0, 0),
(9, 'Fernando Alonso', 14, '🇪🇸', 'Aston Martin', 44, 425, 32, 2),
(10, 'Lance Stroll', 18, '🇨🇦', 'Aston Martin', 27, 189, 0, 0),
(11, 'Isack Hadjar', 6, '🇫🇷', 'Red Bull', 21, 23, 0, 0),
(12, 'Liam Lawson', 30, '🇳🇿', 'Racing Bulls', 23, 15, 0, 0),
(13, 'Carlos Sainz', 55, '🇪🇸', 'Williams', 31, 229, 4, 0),
(14, 'Alexander Albon', 23, '🇹🇭', 'Williams', 29, 128, 0, 0),
(15, 'Oliver Bearman', 87, '🇬🇧', 'Haas', 20, 26, 0, 0),
(16, 'Esteban Ocon', 31, '🇫🇷', 'Haas', 29, 180, 1, 0),
(17, 'Nico Hülkenberg', 27, '🇩🇪', 'Audi', 38, 250, 0, 0),
(18, 'Gabriel Bortoleto', 5, '🇧🇷', 'Audi', 21, 24, 0, 0),
(19, 'Pierre Gasly', 10, '🇫🇷', 'Alpine', 29, 177, 1, 0),
(20, 'Franco Colapinto', 43, '🇦🇷', 'Alpine', 22, 26, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `predictions`
--

CREATE TABLE `predictions` (
  `id` int NOT NULL,
  `pronostic_id` int NOT NULL,
  `position` int NOT NULL,
  `pilote_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `predictions`
--

INSERT INTO `predictions` (`id`, `pronostic_id`, `position`, `pilote_id`) VALUES
(61, 4, 1, 14),
(62, 4, 2, 13),
(63, 4, 3, 2),
(64, 4, 4, 3),
(65, 4, 5, 16),
(66, 4, 6, 9),
(67, 4, 7, 20),
(68, 4, 8, 18),
(69, 4, 9, 7),
(70, 4, 10, 11),
(111, 5, 1, 14),
(112, 5, 2, 2),
(113, 5, 3, 13),
(114, 5, 4, 3),
(115, 5, 5, 16),
(116, 5, 6, 9),
(117, 5, 7, 20),
(118, 5, 8, 18),
(119, 5, 9, 7),
(120, 5, 10, 11);

-- --------------------------------------------------------

--
-- Structure de la table `pronostics`
--

CREATE TABLE `pronostics` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `course_id` int NOT NULL,
  `type_pronostic` enum('grille','course','sprint') COLLATE utf8mb4_general_ci NOT NULL,
  `date_pronostic` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `points_obtenus` int DEFAULT '0',
  `statut` enum('actif','supprime') COLLATE utf8mb4_general_ci DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `pronostics`
--

INSERT INTO `pronostics` (`id`, `user_id`, `course_id`, `type_pronostic`, `date_pronostic`, `points_obtenus`, `statut`) VALUES
(4, 23, 126, 'course', '2026-01-21 12:53:29', 12, 'actif'),
(5, 23, 121, 'grille', '2026-01-21 12:56:18', 0, 'actif');

-- --------------------------------------------------------

--
-- Structure de la table `resultats`
--

CREATE TABLE `resultats` (
  `id` int NOT NULL,
  `course_id` int NOT NULL,
  `type_resultat` enum('grille','course','sprint') COLLATE utf8mb4_general_ci NOT NULL,
  `pilote_id` int NOT NULL,
  `position_reelle` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `resultats`
--

INSERT INTO `resultats` (`id`, `course_id`, `type_resultat`, `pilote_id`, `position_reelle`) VALUES
(21, 126, 'course', 14, 1),
(22, 126, 'course', 16, 2),
(23, 126, 'course', 10, 3),
(24, 126, 'course', 13, 4),
(25, 126, 'course', 8, 5),
(26, 126, 'course', 1, 6),
(27, 126, 'course', 2, 7),
(28, 126, 'course', 9, 8),
(29, 126, 'course', 17, 9),
(30, 126, 'course', 19, 10);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `pseudo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('user','admin') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `points_totaux` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `email`, `password`, `date`, `role`, `points_totaux`) VALUES
(23, 'MathisD16', 'test@exemple.fr', '$2y$12$odubYTNiJuzuEF1eXfBiku.8mHsDSGcghdPNGxge5gVGIQoFsIqIq', '2026-01-11 20:21:21', 'user', 12),
(24, 'Admin', 'admin@exemple.fr', '$2y$12$c2QmA2t79Oj76.s2DgvEeOZEXxF/urAOqlcZ6ZpsLcvcs8qIyDuNe', '2026-01-11 20:21:44', 'admin', 0),
(25, 'ClarenceD', 'test2@exemple.fr', '$2y$12$GGWPtNs6T6b8CmO8lLghxe0CkwRSScKW6N2EC8nXGN5k0VL./.TF2', '2026-01-11 21:26:10', 'user', 3),
(26, 'PaulD', 'test3@exemple.fr', '$2y$12$iWkZPl272YK/9qEyb85wg.i.r7Lc4NJpiEA1Y9b6IYwD8DEJg4j56', '2026-01-11 21:26:36', 'user', 17);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `circuits`
--
ALTER TABLE `circuits`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `equipes`
--
ALTER TABLE `equipes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pilotes`
--
ALTER TABLE `pilotes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `predictions`
--
ALTER TABLE `predictions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pronostics`
--
ALTER TABLE `pronostics`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `resultats`
--
ALTER TABLE `resultats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_type_pilote` (`course_id`,`type_resultat`,`pilote_id`),
  ADD KEY `pilote_id` (`pilote_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `circuits`
--
ALTER TABLE `circuits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT pour la table `equipes`
--
ALTER TABLE `equipes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `pilotes`
--
ALTER TABLE `pilotes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `predictions`
--
ALTER TABLE `predictions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT pour la table `pronostics`
--
ALTER TABLE `pronostics`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `resultats`
--
ALTER TABLE `resultats`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `resultats`
--
ALTER TABLE `resultats`
  ADD CONSTRAINT `resultats_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `resultats_ibfk_2` FOREIGN KEY (`pilote_id`) REFERENCES `pilotes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
