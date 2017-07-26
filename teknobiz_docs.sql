-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2017 at 04:11 PM
-- Server version: 10.0.17-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `teknobiz_docs`
--

-- --------------------------------------------------------

--
-- Table structure for table `client_table`
--

CREATE TABLE `client_table` (
  `client_id` int(11) NOT NULL,
  `org_name` varchar(250) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `contact_person` varchar(250) DEFAULT NULL,
  `GSTN_No` varchar(250) NOT NULL,
  `web` varchar(250) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `client_table`
--

INSERT INTO `client_table` (`client_id`, `org_name`, `mobile`, `phone`, `email`, `contact_person`, `GSTN_No`, `web`, `is_deleted`) VALUES
(4, 'Canon', '9438753143', '9078640778', 'soumya@gmail.com', 'Soumya Mohanty', '546897321', 'teknobiz.in', 0),
(5, 'JIndal', '9078640778', '9078640778', 'santoshmajhi99@gmail.com', 'Santosh Majhi', '11234566', 'wekan.com', 0),
(6, 'JSPL', '9438753143', '9078640778', 'santoshmajhi99@gmail.com', 'Santosh Shann', '23234234', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `collection_table`
--

CREATE TABLE `collection_table` (
  `coll_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `trans_mode` varchar(250) DEFAULT NULL,
  `trans_no` varchar(250) DEFAULT NULL,
  `total_amount` decimal(10,0) DEFAULT NULL,
  `status` varchar(250) DEFAULT NULL,
  `approve_date` date DEFAULT NULL,
  `remark` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `enquiry_item_table`
--

CREATE TABLE `enquiry_item_table` (
  `item_id` int(11) NOT NULL,
  `enq_id` int(11) NOT NULL,
  `item_name` text NOT NULL,
  `description` text,
  `quantity` int(11) DEFAULT NULL,
  `uom` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `enquiry_item_table`
--

INSERT INTO `enquiry_item_table` (`item_id`, `enq_id`, `item_name`, `description`, `quantity`, `uom`) VALUES
(9, 5, 'CCTV', NULL, 5, 'PC'),
(10, 5, 'Mouse', NULL, 25, 'PC');

-- --------------------------------------------------------

--
-- Table structure for table `enquiry_table`
--

CREATE TABLE `enquiry_table` (
  `enq_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `enq_date` date NOT NULL,
  `due_date` date NOT NULL,
  `ref_no` varchar(250) DEFAULT NULL,
  `status` varchar(250) DEFAULT NULL,
  `remarks` text,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `enquiry_table`
--

INSERT INTO `enquiry_table` (`enq_id`, `client_id`, `enq_date`, `due_date`, `ref_no`, `status`, `remarks`, `is_deleted`) VALUES
(5, 5, '2017-07-11', '2017-07-13', 'TEK/ENQ/5', 'Purchase order added', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_details_table`
--

CREATE TABLE `invoice_details_table` (
  `inv_det_id` int(11) NOT NULL,
  `inv_id` int(11) NOT NULL,
  `po_det_id` int(11) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `tax` decimal(10,0) DEFAULT NULL,
  `total` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_table`
--

CREATE TABLE `invoice_table` (
  `inv_id` int(11) NOT NULL,
  `inv_date` date NOT NULL,
  `po_id` int(11) NOT NULL,
  `gstn_no` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_details_table`
--

CREATE TABLE `purchase_order_details_table` (
  `po_details_id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL,
  `quot_det_id` int(11) NOT NULL,
  `delivery_date` date NOT NULL,
  `description` text,
  `quantity` int(11) DEFAULT NULL,
  `price` float NOT NULL,
  `net_amount` float NOT NULL,
  `item_code` int(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_order_details_table`
--

INSERT INTO `purchase_order_details_table` (`po_details_id`, `po_id`, `quot_det_id`, `delivery_date`, `description`, `quantity`, `price`, `net_amount`, `item_code`) VALUES
(5, 3, 4, '2017-07-26', '', 5, 2000, 9550, NULL),
(6, 3, 4, '2017-07-28', '', 25, 250, 6093.75, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_table`
--

CREATE TABLE `purchase_order_table` (
  `po_id` int(11) NOT NULL,
  `quot_id` int(11) NOT NULL,
  `po_no` varchar(250) NOT NULL,
  `po_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_order_table`
--

INSERT INTO `purchase_order_table` (`po_id`, `quot_id`, `po_no`, `po_date`) VALUES
(3, 4, '5465897', '2017-07-25');

-- --------------------------------------------------------

--
-- Table structure for table `quotaion_details_table`
--

CREATE TABLE `quotaion_details_table` (
  `quot_det_id` int(11) NOT NULL,
  `quot_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `price` float NOT NULL,
  `discount` float DEFAULT NULL,
  `tot_price` float NOT NULL,
  `valid_upto` date DEFAULT NULL,
  `description` text,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quotaion_details_table`
--

INSERT INTO `quotaion_details_table` (`quot_det_id`, `quot_id`, `item_id`, `price`, `discount`, `tot_price`, `valid_upto`, `description`, `is_deleted`) VALUES
(7, 4, 9, 2500, 4.5, 11937.5, '2017-07-29', 'Test Description updated', 0),
(8, 4, 10, 200, 2.5, 4875, '2017-07-28', 'test Description', 0);

-- --------------------------------------------------------

--
-- Table structure for table `quotaion_table`
--

CREATE TABLE `quotaion_table` (
  `quot_id` int(11) NOT NULL,
  `enq_id` int(11) NOT NULL,
  `quot_date` date NOT NULL,
  `ref_no` varchar(250) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quotaion_table`
--

INSERT INTO `quotaion_table` (`quot_id`, `enq_id`, `quot_date`, `ref_no`, `is_deleted`) VALUES
(4, 5, '2017-07-26', 'TEK/QUOT/4', 0);

-- --------------------------------------------------------

--
-- Table structure for table `quotation_details_history_table`
--

CREATE TABLE `quotation_details_history_table` (
  `quot_det_his_id` int(11) NOT NULL,
  `history_id` int(11) NOT NULL,
  `quot_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `price` float NOT NULL,
  `discount` float NOT NULL,
  `total_price` float NOT NULL,
  `valid_upto` date NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quotation_details_history_table`
--

INSERT INTO `quotation_details_history_table` (`quot_det_his_id`, `history_id`, `quot_id`, `item_id`, `price`, `discount`, `total_price`, `valid_upto`, `description`) VALUES
(11, 15, 4, 9, 3000, 4.5, 14325, '2017-07-20', ''),
(12, 15, 4, 10, 250, 2.5, 6093.75, '2017-07-27', ''),
(13, 16, 4, 9, 2500, 4.5, 11937.5, '2017-07-29', ''),
(14, 16, 4, 10, 200, 2.5, 4875, '2017-07-28', ''),
(15, 17, 4, 9, 2500, 4.5, 11937.5, '2017-07-29', ''),
(16, 17, 4, 10, 200, 2.5, 4875, '2017-07-28', ''),
(17, 18, 4, 9, 2500, 4.5, 11937.5, '2017-07-29', 'Test Description'),
(18, 18, 4, 10, 200, 2.5, 4875, '2017-07-28', 'test Description');

-- --------------------------------------------------------

--
-- Table structure for table `quotation_history_table`
--

CREATE TABLE `quotation_history_table` (
  `quot_his_id` int(11) NOT NULL,
  `quot_id` int(11) NOT NULL,
  `quot_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quotation_history_table`
--

INSERT INTO `quotation_history_table` (`quot_his_id`, `quot_id`, `quot_date`) VALUES
(15, 4, '2017-07-12'),
(16, 4, '2017-07-26'),
(17, 4, '2017-07-26'),
(18, 4, '2017-07-26');

-- --------------------------------------------------------

--
-- Table structure for table `user_table`
--

CREATE TABLE `user_table` (
  `user_id` int(11) NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `user_name` text NOT NULL,
  `password` text NOT NULL,
  `token` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_table`
--

INSERT INTO `user_table` (`user_id`, `first_name`, `last_name`, `user_name`, `password`, `token`, `is_active`) VALUES
(1, 'Santosh', 'Majhi', 'santosh@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'yEVTZ3IIJ9W9oGWG4Qyhkm8oisv1xQ1BaKx8MSn3rtpmMdWvSmRsjCtGccEa', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client_table`
--
ALTER TABLE `client_table`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `collection_table`
--
ALTER TABLE `collection_table`
  ADD PRIMARY KEY (`coll_id`);

--
-- Indexes for table `enquiry_item_table`
--
ALTER TABLE `enquiry_item_table`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `enquiry_table`
--
ALTER TABLE `enquiry_table`
  ADD PRIMARY KEY (`enq_id`);

--
-- Indexes for table `invoice_details_table`
--
ALTER TABLE `invoice_details_table`
  ADD PRIMARY KEY (`inv_det_id`);

--
-- Indexes for table `invoice_table`
--
ALTER TABLE `invoice_table`
  ADD PRIMARY KEY (`inv_id`);

--
-- Indexes for table `purchase_order_details_table`
--
ALTER TABLE `purchase_order_details_table`
  ADD PRIMARY KEY (`po_details_id`);

--
-- Indexes for table `purchase_order_table`
--
ALTER TABLE `purchase_order_table`
  ADD PRIMARY KEY (`po_id`);

--
-- Indexes for table `quotaion_details_table`
--
ALTER TABLE `quotaion_details_table`
  ADD PRIMARY KEY (`quot_det_id`);

--
-- Indexes for table `quotaion_table`
--
ALTER TABLE `quotaion_table`
  ADD PRIMARY KEY (`quot_id`);

--
-- Indexes for table `quotation_details_history_table`
--
ALTER TABLE `quotation_details_history_table`
  ADD PRIMARY KEY (`quot_det_his_id`);

--
-- Indexes for table `quotation_history_table`
--
ALTER TABLE `quotation_history_table`
  ADD PRIMARY KEY (`quot_his_id`);

--
-- Indexes for table `user_table`
--
ALTER TABLE `user_table`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client_table`
--
ALTER TABLE `client_table`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `collection_table`
--
ALTER TABLE `collection_table`
  MODIFY `coll_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `enquiry_item_table`
--
ALTER TABLE `enquiry_item_table`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `enquiry_table`
--
ALTER TABLE `enquiry_table`
  MODIFY `enq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `invoice_details_table`
--
ALTER TABLE `invoice_details_table`
  MODIFY `inv_det_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `invoice_table`
--
ALTER TABLE `invoice_table`
  MODIFY `inv_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `purchase_order_details_table`
--
ALTER TABLE `purchase_order_details_table`
  MODIFY `po_details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `purchase_order_table`
--
ALTER TABLE `purchase_order_table`
  MODIFY `po_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `quotaion_details_table`
--
ALTER TABLE `quotaion_details_table`
  MODIFY `quot_det_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `quotaion_table`
--
ALTER TABLE `quotaion_table`
  MODIFY `quot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `quotation_details_history_table`
--
ALTER TABLE `quotation_details_history_table`
  MODIFY `quot_det_his_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `quotation_history_table`
--
ALTER TABLE `quotation_history_table`
  MODIFY `quot_his_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `user_table`
--
ALTER TABLE `user_table`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
