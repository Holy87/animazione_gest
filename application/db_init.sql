SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Struttura della tabella `animatori_party`
--

CREATE TABLE `animatori_party` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `party_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `feste`
--

CREATE TABLE `feste` (
  `party_id` int(11) NOT NULL,
  `cliente` varchar(100) NOT NULL,
  `indirizzo` text NOT NULL,
  `data` date NOT NULL,
  `ora` time NOT NULL,
  `creatore` int(11) DEFAULT NULL,
  `prezzo` float NOT NULL,
  `theme_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `inventario`
--

CREATE TABLE `inventario` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `item_number` int(11) NOT NULL DEFAULT '1',
  `item_ward` int(11) NOT NULL DEFAULT '0',
  `item_consumable` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `inventario`
--

INSERT INTO `inventario` (`item_id`, `item_name`, `item_number`, `item_ward`, `item_consumable`) VALUES
  (1, 'Macchina per zucchero filato', 3, 0, 0),
  (3, 'Computer portatile', 2, 0, 0),
  (4, 'Costume di Peter Pan', 4, 0, 0),
  (5, 'Costume di Bianca Neve', 3, 0, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `oggetti_party`
--

CREATE TABLE `oggetti_party` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `party_id` int(11) NOT NULL,
  `item_number` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `oggetti_temi`
--

CREATE TABLE `oggetti_temi` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `theme_id` int(11) NOT NULL,
  `item_number` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `oggetti_temi`
--

INSERT INTO `oggetti_temi` (`id`, `item_id`, `theme_id`, `item_number`) VALUES
  (1, 1, 1, 1),
  (2, 4, 1, 1),
  (3, 1, 2, 1),
  (4, 3, 2, 2),
  (5, 5, 2, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `temi`
--

CREATE TABLE `temi` (
  `theme_id` int(11) NOT NULL,
  `theme_name` varchar(50) NOT NULL,
  `theme_description` text,
  `theme_price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `temi`
--

INSERT INTO `temi` (`theme_id`, `theme_name`, `theme_description`, `theme_price`) VALUES
  (1, 'Spiderman', 'Tema con i costumi e le ragnatele dell\'uomo ragno', 80),
  (2, 'Disney', 'Tema con i personaggi dei classici Disney', 70);

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_friendlyname` varchar(100) NOT NULL,
  `user_mail` varchar(100) NOT NULL,
  `user_access` int(11) NOT NULL DEFAULT '1',
  `user_password` char(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_friendlyname`, `user_mail`, `user_access`, `user_password`) VALUES
  (1, 'admin', 'John Doe', 'doejohn@email.com', 3, 'd033e22ae348aeb5660fc2140aec35850c4da997'),
  (2, 'test', 'Mario Rossi', 'rossimario@gmail.com', 1, '250e77f12a5ab6972a0895d290c4792f0a326ea8');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `animatori_party`
--
ALTER TABLE `animatori_party`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_2` (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `party_id` (`party_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `feste`
--
ALTER TABLE `feste`
  ADD PRIMARY KEY (`party_id`),
  ADD KEY `tema` (`theme_id`);

--
-- Indici per le tabelle `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`item_id`),
  ADD UNIQUE KEY `item_name` (`item_name`);

--
-- Indici per le tabelle `oggetti_party`
--
ALTER TABLE `oggetti_party`
  ADD PRIMARY KEY (`id`),
  ADD KEY `party_id` (`party_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indici per le tabelle `oggetti_temi`
--
ALTER TABLE `oggetti_temi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `theme_id` (`theme_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indici per le tabelle `temi`
--
ALTER TABLE `temi`
  ADD PRIMARY KEY (`theme_id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_mail` (`user_mail`),
  ADD UNIQUE KEY `user_name` (`user_name`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `animatori_party`
--
ALTER TABLE `animatori_party`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `feste`
--
ALTER TABLE `feste`
  MODIFY `party_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `inventario`
--
ALTER TABLE `inventario`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT per la tabella `oggetti_party`
--
ALTER TABLE `oggetti_party`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `oggetti_temi`
--
ALTER TABLE `oggetti_temi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT per la tabella `temi`
--
ALTER TABLE `temi`
  MODIFY `theme_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `animatori_party`
--
ALTER TABLE `animatori_party`
  ADD CONSTRAINT `animatori_party_ibfk_1` FOREIGN KEY (`party_id`) REFERENCES `feste` (`party_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `animatori_party_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `feste`
--
ALTER TABLE `feste`
  ADD CONSTRAINT `feste_ibfk_1` FOREIGN KEY (`theme_id`) REFERENCES `temi` (`theme_id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Limiti per la tabella `oggetti_party`
--
ALTER TABLE `oggetti_party`
  ADD CONSTRAINT `oggetti_party_ibfk_1` FOREIGN KEY (`party_id`) REFERENCES `feste` (`party_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `oggetti_party_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `inventario` (`item_id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `oggetti_temi`
--
ALTER TABLE `oggetti_temi`
  ADD CONSTRAINT `oggetti_temi_ibfk_1` FOREIGN KEY (`theme_id`) REFERENCES `temi` (`theme_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `oggetti_temi_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `inventario` (`item_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;