-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 19, 2023 at 01:55 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `BookReservationDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `ISBN` varchar(255) NOT NULL,
  `BookTitle` varchar(255) DEFAULT NULL,
  `Author` varchar(255) DEFAULT NULL,
  `Edition` int(11) DEFAULT NULL,
  `Year` int(11) DEFAULT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `Reserved` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`ISBN`, `BookTitle`, `Author`, `Edition`, `Year`, `CategoryID`, `Reserved`) VALUES
('043705632-5', 'Meth', 'Maurene Whitchurch', 3, 2011, 16, 0),
('080522135-2', 'Son of Batman', 'Ashbey Verlinden', 17, 1991, 16, 0),
('096583275-9', 'Shadow of the Holy Book (Pyhän kirjan varjo)', 'Jocko Burtonwood', 8, 2012, 19, 0),
('104027403-X', 'Christmas at Pee Wee\'s Playhouse (a.k.a. Pee-Wee\'s Playhouse Christmas Special)', 'Petrina Manchester', 20, 2011, 15, 1),
('174737860-7', '66 Scenes From America', 'Nicola Jennick', 18, 2008, 15, 1),
('217991727-9', 'Climate of Change', 'Danila Silvermann', 6, 1994, 20, 0),
('264777373-4', 'Accidental Husband, The', 'Myrah Paniman', 19, 1989, 21, 1),
('337423107-1', 'Michael Clayton', 'Trevar Codling', 4, 1999, 18, 0),
('351767568-7', 'Dancing Masters, The', 'Kelley Falconer-Taylor', 19, 1999, 22, 0),
('413621492-8', 'Executive Protection (Livvakterna)', 'Haily Worboys', 17, 1992, 14, 0),
('441680369-9', 'Childhood of Maxim Gorky, The (Detstvo Gorkogo)', 'Kennith Barmadier', 19, 2004, 23, 0),
('454635667-6', 'Dragon Inn (Sun lung moon hak chan)', 'Harris Keeping', 4, 2002, 17, 0),
('465966843-6', 'Gamera vs. Gyaos (Daikaijû kûchûsen: Gamera tai Gyaosu)', 'Corbin Chandler', 6, 1993, 18, 0),
('491526289-8', 'Boys Town', 'Andrei Handley', 2, 1987, 19, 1),
('498148169-1', 'Love and Other Troubles', 'Roland Garrood', 10, 2001, 14, 0),
('582184453-3', 'Jerk, The', 'Mel Midden', 5, 2005, 23, 0),
('582979209-5', 'Garbage Warrior', 'Jarrid Couch', 20, 2002, 22, 1),
('633918112-0', 'Joe Somebody', 'Veriee Kembrey', 10, 2000, 14, 1),
('654491250-6', 'Ants in the Pants 2', 'Liesa Maker', 17, 2005, 22, 1),
('671391883-2', 'Ordet (Word, The)', 'Verine Borley', 8, 2007, 14, 0),
('681841492-3', 'Oz the Great and Powerful', 'Ilsa Brasher', 11, 2007, 17, 0),
('689972076-0', 'Carriers Are Waiting, The (Convoyeurs attendent, Les)', 'Alis Gass', 2, 1994, 15, 0),
('717387594-3', 'Hotel', 'Rochester Fessler', 14, 2011, 22, 0),
('719201532-6', 'Kill by Inches', 'Rozalie Gurnett', 7, 2009, 22, 0),
('800688835-3', 'Grudge, The', 'Felike Ackenson', 10, 1963, 17, 1),
('803755172-5', 'Wings of Hope (Julianes Sturz in den Dschungel)', 'Denny Stidworthy', 12, 2007, 17, 0),
('823693522-1', 'Trance', 'Hertha Ivetts', 20, 2011, 16, 1),
('823827889-9', 'Km. 0 - Kilometer Zero (Kilómetro cero)', 'Kele Zukerman', 2, 1991, 19, 0),
('872516687-5', 'Room, The', 'Antoinette Badgers', 9, 2004, 22, 0),
('880370981-9', 'Trouble with Bliss, The', 'Rakel Adamovitz', 5, 2011, 15, 0),
('943159934-1', '44 Inch Chest', 'Rolf Mesant', 3, 2003, 23, 1),
('980639239-6', 'Sisters (Syostry)', 'Barth Trevna', 20, 2007, 17, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`ISBN`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `Category` (`CategoryID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
