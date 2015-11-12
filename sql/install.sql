-- --------------------------------------------------------
--
-- This file is part of the ProjectDOVE.
--
-- For the full copyright and license information, please view the LICENSE
-- file that was distributed with this source code.
--
-- --------------------------------------------------------

--
-- Exportiere Struktur von Tabelle dove.users
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `registrationDate` datetime NOT NULL,
  `lastAction` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `rememberMeToken` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `websocketTicket` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `unique_username` (`username`),
  UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

