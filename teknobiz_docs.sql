-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 09, 2017 at 05:42 PM
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
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `address` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `client_table`
--

INSERT INTO `client_table` (`client_id`, `org_name`, `mobile`, `phone`, `email`, `contact_person`, `GSTN_No`, `web`, `is_deleted`, `address`) VALUES
(4, 'Canon', '9438753143', '9078640778', 'soumya@gmail.com', 'Soumya Mohanty', '5454545', 'jakdjfkla', 0, NULL),
(5, 'JIndal', '9078640778', '9078640778', 'santoshmajhi99@gmail.com', 'Santosh Majhi', '11234566', 'wekan.com', 0, 'jajpur'),
(6, 'JSPL', '9438753143', '9078640778', 'santoshmajhi99@gmail.com', 'Santosh Shann', '23234234', '', 0, NULL),
(7, 'Santosh', '9078640778', '9078640778', 'santoshmajhi99@gmail.com', 'Santosh Majhi', '123654987', 'teknobiz.in', 0, 'Bhupinder Sohata Ct djkfhkjasd asdfa'),
(8, 'test', '1234567890', '9868369258', 'test@gmail.com', 'test', '', 'dsfasdf', 0, 'adfasdfasdf'),
(9, 'TATA', '9078640778', '9438753143', 'aurobind@gmail.com', 'Aurobind', '', 'jkaj', 0, 'Kaling nagar, Jajpur,Odisha'),
(10, 'TATA', '9078640778', '9438753143', 'aurobind@gmail.com', 'Aurobind', '', 'jkaj', 0, 'Kaling nagar, Jajpur,Odisha'),
(11, 'sdfsdfg', '545454545454', NULL, 'sfgsd@gmail.com', 'sdfgsdfg', '7878978787', 'kjkajfdk', 0, 'kjkajfkjakf'),
(12, 'adfklj', '8989898989', NULL, 'jskfkajk@gmail.com', 'kkljadfjk', '546468787', 'jkajdfkja', 0, 'nadjklajf'),
(13, 'lkjadfkljadksf', '9989898988', NULL, 'dsjkjds@gmail.com', 'jkljaskjksdf', '45454', 'lkladfl', 0, 'lkalkl'),
(14, 'asdfasdfa', '98789895545', NULL, 'asfasdfa@gmail.com', 'adfasdf', '99898989', 'klakdlkf', 0, 'lklakfl');

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
(10, 5, 'Mouse', NULL, 25, 'PC'),
(11, 6, 'Monitor', NULL, 4, 'PC'),
(12, 6, 'UPS', NULL, 30, 'PC'),
(13, 6, 'Hard Disk', NULL, 25, 'pc'),
(14, 7, 'Test', NULL, 5, 'PC'),
(15, 7, 'Test2', NULL, 5, 'PC');

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
(5, 5, '2017-07-11', '2017-07-13', 'TEK/ENQ/5', 'Purchase order added', NULL, 0),
(6, 4, '2017-07-28', '2017-08-15', 'TEK/ENQ/6', 'Purchase order added', NULL, 0),
(7, 9, '2017-08-09', '2017-08-17', 'TEK/ENQ/7', 'Purchase order added', NULL, 0);

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
  `gstn_no` varchar(250) NOT NULL,
  `price` float NOT NULL,
  `tax_price` float NOT NULL,
  `total_price` float NOT NULL,
  `bill_path` text NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `is_updated` int(11) NOT NULL DEFAULT '0'
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
  `hsn_no` text,
  `tax` float NOT NULL DEFAULT '0',
  `tax_price` float NOT NULL DEFAULT '0',
  `net_amount` float NOT NULL,
  `item_code` int(30) DEFAULT NULL,
  `unbilled_qty` int(11) NOT NULL,
  `po_sl_no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_order_details_table`
--

INSERT INTO `purchase_order_details_table` (`po_details_id`, `po_id`, `quot_det_id`, `delivery_date`, `description`, `quantity`, `price`, `hsn_no`, `tax`, `tax_price`, `net_amount`, `item_code`, `unbilled_qty`, `po_sl_no`) VALUES
(7, 4, 9, '2017-07-31', 'This is a demo details', 4, 1500, '123654987', 15, 859.5, 5730, 45654454, 4, 120),
(8, 4, 10, '2017-07-31', 'Demo details for description', 25, 250, '36548976', 25, 1484.38, 5937.5, 98989899, 25, 86),
(9, 6, 11, '2017-08-10', '22 HP Monitor', 4, 12000, '454545', 3, 1389.6, 46320, 0, 4, 120),
(10, 6, 12, '2017-08-11', 'Frontech UPS', 30, 4500, '454545', 3, 2632.5, 131625, 0, 30, 121),
(11, 6, 13, '2017-08-12', '500 GB hard disk', 25, 5000, '89898989', 5, 6250, 125000, 0, 25, 123),
(12, 7, 14, '2017-08-11', 'test description', 3, 20, '123456', 18, 10.26, 57, 87878787, 3, 100),
(13, 7, 15, '2017-08-11', 'description', 5, 25, '9898989', 12, 14.55, 121.25, 878787, 5, 101);

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
(4, 5, '4252523523', '2017-07-30'),
(5, 8, '8787878798', '2017-08-08'),
(6, 8, '8787878798', '2017-08-08'),
(7, 9, '436546346356', '2017-08-10');

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
  `hsn_no` varchar(250) DEFAULT NULL,
  `tax` float NOT NULL DEFAULT '0',
  `tax_price` float NOT NULL DEFAULT '0',
  `valid_upto` date DEFAULT NULL,
  `description` text,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quotaion_details_table`
--

INSERT INTO `quotaion_details_table` (`quot_det_id`, `quot_id`, `item_id`, `price`, `discount`, `tot_price`, `hsn_no`, `tax`, `tax_price`, `valid_upto`, `description`, `is_deleted`) VALUES
(9, 5, 9, 1500, 4.5, 7162.5, '123654987', 15, 1074.38, '2017-08-01', 'This is a demo details', 0),
(10, 5, 10, 250, 5, 5937.5, '36548976', 25, 1484.38, '2017-08-01', 'Demo details for description', 0),
(11, 8, 11, 12000, 3.5, 46320, '454545', 3, 1389.6, '2017-07-31', '22 HP Monitor', 0),
(12, 8, 12, 4500, 2.5, 131625, '8989898', 2, 2632.5, '2017-08-09', 'Frontech UPS', 0),
(13, 8, 13, 5000, 0, 125000, '89898989', 5, 6250, '2017-08-04', '500 GB hard disk', 0),
(14, 9, 14, 20, 5, 95, '123456', 18, 17.1, '2017-08-10', 'test description', 0),
(15, 9, 15, 25, 3, 121.25, '9898989', 12, 14.55, '2017-08-11', 'description', 0);

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
(5, 5, '2017-07-29', 'TEK/QUOT/5', 0),
(6, 6, '2017-07-28', 'TEK/QUOT/6', 1),
(7, 6, '2017-07-28', 'TEK/QUOT/7', 1),
(8, 6, '2017-07-28', 'TEK/QUOT/8', 0),
(9, 7, '2017-08-09', 'TEK/QUOT/9', 0);

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
  `hsn_no` varchar(250) DEFAULT NULL,
  `tax` float NOT NULL DEFAULT '0',
  `tax_price` float NOT NULL DEFAULT '0',
  `valid_upto` date NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quotation_details_history_table`
--

INSERT INTO `quotation_details_history_table` (`quot_det_his_id`, `history_id`, `quot_id`, `item_id`, `price`, `discount`, `total_price`, `hsn_no`, `tax`, `tax_price`, `valid_upto`, `description`) VALUES
(21, 20, 5, 9, 1500, 4.5, 7162.5, '123654987', 15, 1074.38, '2017-07-31', 'This is a demo details'),
(22, 20, 5, 10, 250, 5, 5937.5, '36548976', 25, 1484.38, '2017-07-31', 'Demo details for description');

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
(20, 5, '2017-07-28');

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
(1, 'Santosh', 'Majhi', 'santosh@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'etgMuAV8OAjDnkoQWaBSHVXjnWTgc5ojRHVyCBsAa9rvIcST14dQgCqXacYk', 1);

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
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `collection_table`
--
ALTER TABLE `collection_table`
  MODIFY `coll_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `enquiry_item_table`
--
ALTER TABLE `enquiry_item_table`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `enquiry_table`
--
ALTER TABLE `enquiry_table`
  MODIFY `enq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
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
  MODIFY `po_details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `purchase_order_table`
--
ALTER TABLE `purchase_order_table`
  MODIFY `po_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `quotaion_details_table`
--
ALTER TABLE `quotaion_details_table`
  MODIFY `quot_det_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `quotaion_table`
--
ALTER TABLE `quotaion_table`
  MODIFY `quot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `quotation_details_history_table`
--
ALTER TABLE `quotation_details_history_table`
  MODIFY `quot_det_his_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `quotation_history_table`
--
ALTER TABLE `quotation_history_table`
  MODIFY `quot_his_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `user_table`
--
ALTER TABLE `user_table`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
