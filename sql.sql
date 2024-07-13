CREATE TABLE `keys` (
  `ID` int(11) NOT NULL,
  `key` varchar(10) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `keys`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `keys`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
