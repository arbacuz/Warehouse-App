-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 30, 2015 at 10:47 AM
-- Server version: 5.5.42
-- PHP Version: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `warehouse`
--

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `branchID` int(11) NOT NULL,
  `branchAddress` longtext,
  `branchName` tinytext NOT NULL,
  `branchTel` char(30) DEFAULT NULL,
  `capacity` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`branchID`, `branchAddress`, `branchName`, `branchTel`, `capacity`) VALUES
(1, '370 Redwood Drive Harrisonburg, VA 22801', 'Redwood', '202-555-0192', 100),
(2, '4095 Ivy Lane Bangor, ME 04401', 'Ivy Lane', '202-555-0124', 100);

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `companyID` int(11) NOT NULL,
  `companyName` tinytext NOT NULL,
  `companyAddress` longtext,
  `companyTel` char(10) DEFAULT NULL,
  `companyTypeID` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`companyID`, `companyName`, `companyAddress`, `companyTel`, `companyTypeID`) VALUES
(1, 'The Tiny Lamp warehouse Company', '86 Old York Road Hamburg, NY 14075', '202-555-15', 1),
(2, 'Pink Skunk warehouse', '470 Chapel Street Livonia, MI 48150', '202-525-11', 2);

-- --------------------------------------------------------

--
-- Table structure for table `companyType`
--

CREATE TABLE `companyType` (
  `companyTypeID` int(11) NOT NULL,
  `companyTypeName` tinytext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `companyType`
--

INSERT INTO `companyType` (`companyTypeID`, `companyTypeName`) VALUES
(1, 'Customer'),
(2, 'Supplier');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `itemID` int(11) NOT NULL,
  `itemCode` char(4) NOT NULL,
  `itemName` tinytext NOT NULL,
  `typeID` int(11) DEFAULT NULL,
  `costPerUnit` double DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`itemID`, `itemCode`, `itemName`, `typeID`, `costPerUnit`) VALUES
(1, 'P001', 'TV Sony', 2, 456000),
(2, 'M001', 'Mother board: RR225', 1, 20500);

-- --------------------------------------------------------

--
-- Table structure for table `itemBranch`
--

CREATE TABLE `itemBranch` (
  `itemID` int(11) NOT NULL,
  `branchID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `lastUpdatedDate` datetime NOT NULL,
  `staffID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `itemBranch`
--

INSERT INTO `itemBranch` (`itemID`, `branchID`, `quantity`, `lastUpdatedDate`, `staffID`) VALUES
(1, 1, 200, '2015-10-29 00:00:00', 2),
(2, 2, 3000, '2015-10-29 00:00:00', 2);

-- --------------------------------------------------------

--
-- Table structure for table `itemType`
--

CREATE TABLE `itemType` (
  `typeID` int(11) NOT NULL,
  `typeName` tinytext
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `itemType`
--

INSERT INTO `itemType` (`typeID`, `typeName`) VALUES
(1, 'raw material'),
(2, 'Material'),
(3, 'Product');

-- --------------------------------------------------------

--
-- Table structure for table `orderItem`
--

CREATE TABLE `orderItem` (
  `orderID` int(11) NOT NULL,
  `itemID` int(11) NOT NULL,
  `orderQuantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orderItem`
--

INSERT INTO `orderItem` (`orderID`, `itemID`, `orderQuantity`) VALUES
(1, 1, 100),
(2, 2, 200);

-- --------------------------------------------------------

--
-- Table structure for table `orderType`
--

CREATE TABLE `orderType` (
  `orderTypeID` int(11) NOT NULL,
  `orderType` tinytext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orderType`
--

INSERT INTO `orderType` (`orderTypeID`, `orderType`) VALUES
(1, 'Order'),
(2, 'Request');

-- --------------------------------------------------------

--
-- Table structure for table `order_list`
--

CREATE TABLE `order_list` (
  `orderID` int(11) NOT NULL,
  `orderDate` datetime NOT NULL,
  `branchID` int(11) NOT NULL,
  `staffID` int(11) NOT NULL,
  `companyID` int(11) DEFAULT NULL,
  `statusID` int(11) DEFAULT NULL,
  `invoiceCode` char(4) DEFAULT NULL,
  `deliverdDate` datetime DEFAULT NULL,
  `orderTypeID` int(11) NOT NULL,
  `toBranchID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_list`
--

INSERT INTO `order_list` (`orderID`, `orderDate`, `branchID`, `staffID`, `companyID`, `statusID`, `invoiceCode`, `deliverdDate`, `orderTypeID`, `toBranchID`) VALUES
(1, '2015-10-30 00:00:00', 1, 2, 1, 1, 'I001', NULL, 1, NULL),
(2, '2015-10-30 00:00:00', 1, 2, 1, 1, NULL, NULL, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `positionID` int(11) NOT NULL,
  `positionName` tinytext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`positionID`, `positionName`) VALUES
(1, 'Director'),
(2, 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `registerID` int(11) NOT NULL,
  `registerDate` datetime NOT NULL,
  `staffID` int(11) NOT NULL,
  `branchID` int(11) NOT NULL,
  `companyID` int(11) NOT NULL,
  `registerCode` char(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`registerID`, `registerDate`, `staffID`, `branchID`, `companyID`, `registerCode`) VALUES
(1, '2015-10-10 00:00:00', 2, 1, 1, 'REG001'),
(2, '2015-10-10 00:00:00', 2, 2, 2, 'REG002');

-- --------------------------------------------------------

--
-- Table structure for table `registerItem`
--

CREATE TABLE `registerItem` (
  `registerID` int(11) NOT NULL,
  `itemID` int(11) NOT NULL,
  `registerQuantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `registerItem`
--

INSERT INTO `registerItem` (`registerID`, `itemID`, `registerQuantity`) VALUES
(1, 1, 200),
(2, 2, 1000);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staffID` int(11) NOT NULL,
  `staffName` text NOT NULL,
  `email` tinytext,
  `password` tinytext NOT NULL,
  `positionID` int(11) DEFAULT NULL,
  `branchID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staffID`, `staffName`, `email`, `password`, `positionID`, `branchID`) VALUES
(1, 'Michelle', 'michell.moris@yoal.com', 'moris115', 1, 1),
(2, 'Smuel', 'Smuel.john@yoal.com', 'john556', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `statusID` int(11) NOT NULL,
  `statusName` tinytext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`statusID`, `statusName`) VALUES
(1, 'Delivered'),
(2, 'Canceled'),
(3, 'Pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`branchID`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`companyID`),
  ADD KEY `companyTypeID` (`companyTypeID`);

--
-- Indexes for table `companyType`
--
ALTER TABLE `companyType`
  ADD PRIMARY KEY (`companyTypeID`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`itemID`),
  ADD KEY `typeID` (`typeID`);

--
-- Indexes for table `itemBranch`
--
ALTER TABLE `itemBranch`
  ADD PRIMARY KEY (`itemID`,`branchID`),
  ADD KEY `branchID` (`branchID`),
  ADD KEY `staffID` (`staffID`);

--
-- Indexes for table `itemType`
--
ALTER TABLE `itemType`
  ADD PRIMARY KEY (`typeID`);

--
-- Indexes for table `orderItem`
--
ALTER TABLE `orderItem`
  ADD PRIMARY KEY (`orderID`,`itemID`),
  ADD KEY `itemID` (`itemID`);

--
-- Indexes for table `orderType`
--
ALTER TABLE `orderType`
  ADD PRIMARY KEY (`orderTypeID`);

--
-- Indexes for table `order_list`
--
ALTER TABLE `order_list`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `statusID` (`statusID`),
  ADD KEY `orderTypeID` (`orderTypeID`),
  ADD KEY `staffID` (`staffID`),
  ADD KEY `branchID` (`branchID`),
  ADD KEY `companyID` (`companyID`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`positionID`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`registerID`),
  ADD KEY `branchID` (`branchID`),
  ADD KEY `staffID` (`staffID`),
  ADD KEY `companyID` (`companyID`);

--
-- Indexes for table `registerItem`
--
ALTER TABLE `registerItem`
  ADD PRIMARY KEY (`registerID`,`itemID`),
  ADD KEY `itemID` (`itemID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staffID`),
  ADD KEY `positionID` (`positionID`),
  ADD KEY `branchID` (`branchID`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`statusID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `branchID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `companyID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `companyType`
--
ALTER TABLE `companyType`
  MODIFY `companyTypeID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `itemID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `itemType`
--
ALTER TABLE `itemType`
  MODIFY `typeID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `orderType`
--
ALTER TABLE `orderType`
  MODIFY `orderTypeID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `order_list`
--
ALTER TABLE `order_list`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `positionID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `registerID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staffID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `statusID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `company`
--
ALTER TABLE `company`
  ADD CONSTRAINT `company_ibfk_1` FOREIGN KEY (`companyTypeID`) REFERENCES `companyType` (`companyTypeID`);

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`typeID`) REFERENCES `itemType` (`typeID`);

--
-- Constraints for table `itemBranch`
--
ALTER TABLE `itemBranch`
  ADD CONSTRAINT `itembranch_ibfk_1` FOREIGN KEY (`branchID`) REFERENCES `branch` (`branchID`),
  ADD CONSTRAINT `itembranch_ibfk_2` FOREIGN KEY (`itemID`) REFERENCES `item` (`itemID`),
  ADD CONSTRAINT `itembranch_ibfk_3` FOREIGN KEY (`staffID`) REFERENCES `staff` (`staffID`);

--
-- Constraints for table `orderItem`
--
ALTER TABLE `orderItem`
  ADD CONSTRAINT `orderitem_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `order_list` (`orderID`),
  ADD CONSTRAINT `orderitem_ibfk_2` FOREIGN KEY (`itemID`) REFERENCES `item` (`itemID`);

--
-- Constraints for table `order_list`
--
ALTER TABLE `order_list`
  ADD CONSTRAINT `order_list_ibfk_1` FOREIGN KEY (`statusID`) REFERENCES `status` (`statusID`),
  ADD CONSTRAINT `order_list_ibfk_2` FOREIGN KEY (`orderTypeID`) REFERENCES `orderType` (`orderTypeID`),
  ADD CONSTRAINT `order_list_ibfk_3` FOREIGN KEY (`staffID`) REFERENCES `staff` (`staffID`),
  ADD CONSTRAINT `order_list_ibfk_4` FOREIGN KEY (`branchID`) REFERENCES `branch` (`branchID`),
  ADD CONSTRAINT `order_list_ibfk_5` FOREIGN KEY (`companyID`) REFERENCES `company` (`companyID`);

--
-- Constraints for table `register`
--
ALTER TABLE `register`
  ADD CONSTRAINT `register_ibfk_1` FOREIGN KEY (`branchID`) REFERENCES `branch` (`branchID`),
  ADD CONSTRAINT `register_ibfk_2` FOREIGN KEY (`staffID`) REFERENCES `staff` (`staffID`),
  ADD CONSTRAINT `register_ibfk_3` FOREIGN KEY (`companyID`) REFERENCES `company` (`companyID`);

--
-- Constraints for table `registerItem`
--
ALTER TABLE `registerItem`
  ADD CONSTRAINT `registeritem_ibfk_1` FOREIGN KEY (`registerID`) REFERENCES `register` (`registerID`),
  ADD CONSTRAINT `registeritem_ibfk_2` FOREIGN KEY (`itemID`) REFERENCES `item` (`itemID`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`positionID`) REFERENCES `position` (`positionID`),
  ADD CONSTRAINT `staff_ibfk_2` FOREIGN KEY (`branchID`) REFERENCES `branch` (`branchID`);
