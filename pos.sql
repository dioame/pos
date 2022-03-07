-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2019 at 03:43 AM
-- Server version: 5.6.25
-- PHP Version: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts_payable_invoice`
--

CREATE TABLE IF NOT EXISTS `accounts_payable_invoice` (
  `id` int(11) NOT NULL,
  `invoice_number` varchar(50) DEFAULT NULL,
  `supplier` int(11) DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `po_number` int(11) DEFAULT NULL,
  `type_of_expense` int(8) NOT NULL,
  `amount` double NOT NULL,
  `jev` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `accounts_payable_payment`
--

CREATE TABLE IF NOT EXISTS `accounts_payable_payment` (
  `id` int(11) NOT NULL,
  `ap_id` int(11) NOT NULL,
  `dv_number` varchar(30) DEFAULT NULL,
  `pcv_number` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `auth_assignment`
--

CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('super-admin', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item`
--

CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('bookkeeper-only', 0, 'for bookkeepers only', NULL, NULL, NULL, NULL),
('cashier-only', 0, 'can access cashier modules', NULL, NULL, NULL, NULL),
('manager-only', 0, 'for managers only', NULL, NULL, NULL, NULL),
('production-only', 0, 'for production employees', NULL, NULL, NULL, NULL),
('super-admin', 0, 'can access everything', NULL, NULL, NULL, NULL),
('treasurer-only', 0, 'for treasurer only', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('super-admin', 'bookkeeper-only'),
('super-admin', 'cashier-only'),
('super-admin', 'manager-only'),
('super-admin', 'production-only'),
('super-admin', 'treasurer-only');

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `capitals`
--

CREATE TABLE IF NOT EXISTS `capitals` (
  `id` int(11) NOT NULL,
  `membersId` int(11) NOT NULL,
  `amount` float NOT NULL,
  `type` enum('cash','others') NOT NULL,
  `date_posted` date NOT NULL,
  `arNo` varchar(300) DEFAULT NULL,
  `jev` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cash_dec`
--

CREATE TABLE IF NOT EXISTS `cash_dec` (
  `id` int(11) NOT NULL,
  `date_posted` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cash_dec_det`
--

CREATE TABLE IF NOT EXISTS `cash_dec_det` (
  `id` int(11) NOT NULL,
  `type` varchar(300) NOT NULL,
  `count` float DEFAULT NULL,
  `cash_dec` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL,
  `lastname` varchar(300) NOT NULL,
  `firstname` varchar(300) NOT NULL,
  `contactNo` varchar(300) NOT NULL,
  `address` varchar(300) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `lastname`, `firstname`, `contactNo`, `address`) VALUES
(1, 'Customer', 'Various', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `dv_tracking`
--

CREATE TABLE IF NOT EXISTS `dv_tracking` (
  `id` int(11) NOT NULL,
  `dv_number` varchar(30) NOT NULL,
  `date_posted` date NOT NULL,
  `amount` double NOT NULL,
  `payee` int(11) DEFAULT NULL,
  `particular` varchar(300) DEFAULT NULL,
  `type` varchar(300) NOT NULL,
  `debit` int(11) NOT NULL,
  `credit` int(11) NOT NULL,
  `prepared_by` int(11) DEFAULT NULL,
  `requested_by` int(11) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `received_by` varchar(300) NOT NULL,
  `jev` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
  `id` int(11) NOT NULL,
  `lastname` varchar(300) NOT NULL,
  `firstname` varchar(300) NOT NULL,
  `date_started` date NOT NULL,
  `address` varchar(300) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(300) NOT NULL,
  `position` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE IF NOT EXISTS `expenses` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `type` int(11) NOT NULL,
  `quantity` float NOT NULL,
  `unit` varchar(30) DEFAULT NULL,
  `unit_cost` float NOT NULL,
  `amount` float NOT NULL,
  `amount_paid` float NOT NULL,
  `balance` float NOT NULL,
  `supplier` int(11) DEFAULT NULL,
  `jev` varchar(300) DEFAULT NULL,
  `dv` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `expenses_payments`
--

CREATE TABLE IF NOT EXISTS `expenses_payments` (
  `id` int(11) NOT NULL,
  `expense_id` int(11) NOT NULL,
  `type` enum('cash','accounts payable','petty cash fund') NOT NULL,
  `amount_paid` float NOT NULL,
  `date_recorded` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `expenses_payment_payable`
--

CREATE TABLE IF NOT EXISTS `expenses_payment_payable` (
  `id` int(11) NOT NULL,
  `expense_id` int(11) NOT NULL,
  `amount_paid` float NOT NULL,
  `jev` varchar(300) DEFAULT NULL,
  `transaction_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `expense_types`
--

CREATE TABLE IF NOT EXISTS `expense_types` (
  `id` int(11) NOT NULL,
  `name` varchar(300) NOT NULL,
  `amount` float NOT NULL,
  `recommended_supplier` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_loss`
--

CREATE TABLE IF NOT EXISTS `inventory_loss` (
  `id` int(11) NOT NULL,
  `date_posted` date NOT NULL,
  `pID` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jev_entries`
--

CREATE TABLE IF NOT EXISTS `jev_entries` (
  `id` int(11) NOT NULL,
  `jev` varchar(30) NOT NULL,
  `accounting_code` int(11) NOT NULL,
  `type` enum('debit','credit') NOT NULL,
  `amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jev_tracking`
--

CREATE TABLE IF NOT EXISTS `jev_tracking` (
  `id` int(11) NOT NULL,
  `jev` varchar(30) NOT NULL,
  `date_posted` date NOT NULL,
  `remarks` varchar(300) NOT NULL,
  `source` varchar(300) DEFAULT NULL,
  `isClosingEntry` enum('no','yes') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `id` int(11) NOT NULL,
  `lastname` varchar(300) NOT NULL,
  `firstname` varchar(300) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `date_started` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1525682866),
('m130524_201442_init', 1525682868);

-- --------------------------------------------------------

--
-- Table structure for table `monthly_dues`
--

CREATE TABLE IF NOT EXISTS `monthly_dues` (
  `id` int(11) NOT NULL,
  `mID` int(11) NOT NULL,
  `month` enum('1','2','3','4','5','6','7','8','9','10','11','12') NOT NULL,
  `year` year(4) NOT NULL,
  `amount` double NOT NULL,
  `jev` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `officers`
--

CREATE TABLE IF NOT EXISTS `officers` (
  `id` int(11) NOT NULL,
  `pID` int(11) NOT NULL,
  `mID` int(11) NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `officers_positions`
--

CREATE TABLE IF NOT EXISTS `officers_positions` (
  `id` int(11) NOT NULL,
  `title` varchar(300) NOT NULL,
  `honorarium` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `or_ar_tracking`
--

CREATE TABLE IF NOT EXISTS `or_ar_tracking` (
  `id` int(11) NOT NULL,
  `tracking` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payments_receivable`
--

CREATE TABLE IF NOT EXISTS `payments_receivable` (
  `id` int(11) NOT NULL,
  `sales_id` int(11) NOT NULL,
  `transaction_date` datetime NOT NULL,
  `amount_paid` double NOT NULL,
  `jev` varchar(300) DEFAULT NULL,
  `orNo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment_voucher`
--

CREATE TABLE IF NOT EXISTS `payment_voucher` (
  `id` int(11) NOT NULL,
  `dv_number` varchar(30) NOT NULL,
  `description` varchar(300) NOT NULL,
  `account` int(11) NOT NULL,
  `quantity` double NOT NULL,
  `unit_price` double NOT NULL,
  `discount` double DEFAULT NULL,
  `tax` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE IF NOT EXISTS `payroll` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `number_of_hours` float NOT NULL,
  `hourly_rate` float NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `dv` varchar(30) DEFAULT NULL,
  `pcv` varchar(30) DEFAULT NULL,
  `jev` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_deductions`
--

CREATE TABLE IF NOT EXISTS `payroll_deductions` (
  `id` int(11) NOT NULL,
  `pID` int(11) NOT NULL,
  `dID` int(11) NOT NULL,
  `amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_deductions_honorarium`
--

CREATE TABLE IF NOT EXISTS `payroll_deductions_honorarium` (
  `id` int(11) NOT NULL,
  `pID` int(11) NOT NULL,
  `dID` int(11) NOT NULL,
  `amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_dividends`
--

CREATE TABLE IF NOT EXISTS `payroll_dividends` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `number_of_hours` float NOT NULL,
  `hourly_rate` float NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `dividends_payable_jev` varchar(300) DEFAULT NULL,
  `dv` varchar(30) DEFAULT NULL,
  `pcv` varchar(30) DEFAULT NULL,
  `jev` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_honorarium`
--

CREATE TABLE IF NOT EXISTS `payroll_honorarium` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `number_of_hours` float NOT NULL,
  `hourly_rate` float NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `dv` varchar(30) DEFAULT NULL,
  `pcv` varchar(30) DEFAULT NULL,
  `jev` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payslip_deduction_types`
--

CREATE TABLE IF NOT EXISTS `payslip_deduction_types` (
  `id` int(11) NOT NULL,
  `type` varchar(300) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payslip_deduction_types`
--

INSERT INTO `payslip_deduction_types` (`id`, `type`) VALUES
(1, 'Late & Absences');

-- --------------------------------------------------------

--
-- Table structure for table `pcv_tracking`
--

CREATE TABLE IF NOT EXISTS `pcv_tracking` (
  `id` int(11) NOT NULL,
  `pcv_number` varchar(30) NOT NULL,
  `date_posted` date NOT NULL,
  `amount` double NOT NULL,
  `payee` int(11) DEFAULT NULL,
  `particular` varchar(300) DEFAULT NULL,
  `type` varchar(300) NOT NULL,
  `debit` int(11) DEFAULT NULL,
  `credit` int(11) DEFAULT NULL,
  `prepared_by` int(11) DEFAULT NULL,
  `requested_by` int(11) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `received_by` varchar(300) DEFAULT NULL,
  `jev` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `petty_cash`
--

CREATE TABLE IF NOT EXISTS `petty_cash` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `type` int(11) NOT NULL,
  `quantity` float NOT NULL,
  `unit` varchar(30) DEFAULT NULL,
  `unit_cost` float NOT NULL,
  `amount` float NOT NULL,
  `amount_paid` float NOT NULL,
  `balance` float NOT NULL,
  `supplier` int(11) DEFAULT NULL,
  `jev` varchar(300) DEFAULT NULL,
  `dv` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ppe`
--

CREATE TABLE IF NOT EXISTS `ppe` (
  `id` int(11) NOT NULL,
  `uacs` int(11) NOT NULL,
  `particular` varchar(300) NOT NULL,
  `quantity` float NOT NULL,
  `unit` varchar(300) NOT NULL,
  `unit_cost` float NOT NULL,
  `date_acquired` date NOT NULL,
  `eul` float NOT NULL,
  `warranty_period` date DEFAULT NULL,
  `receipt_number` varchar(300) DEFAULT NULL,
  `jev` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ppe_depreciation`
--

CREATE TABLE IF NOT EXISTS `ppe_depreciation` (
  `id` int(11) NOT NULL,
  `ppeID` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `amount` float NOT NULL,
  `date_posted` date NOT NULL,
  `jev1` varchar(30) DEFAULT NULL,
  `jev2` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pricelist`
--

CREATE TABLE IF NOT EXISTS `pricelist` (
  `id` int(11) NOT NULL,
  `date_adjusted` date NOT NULL,
  `pId` int(11) NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pricelist`
--

INSERT INTO `pricelist` (`id`, `date_adjusted`, `pId`, `price`) VALUES
(1, '2018-12-11', 1, 0),
(2, '2018-12-11', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL,
  `sku` varchar(300) NOT NULL,
  `product_name` varchar(300) NOT NULL,
  `price` double NOT NULL,
  `category` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `sku`, `product_name`, `price`, `category`) VALUES
(1, '52732', 'Banana', 0, 1),
(2, '49170', 'Eggplant', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE IF NOT EXISTS `product_categories` (
  `id` int(11) NOT NULL,
  `category` varchar(300) NOT NULL,
  `salesAccount` int(8) NOT NULL,
  `purchaseAccount` int(8) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `category`, `salesAccount`, `purchaseAccount`) VALUES
(1, 'Fruits & Vegetables', 40000001, 50000001);

-- --------------------------------------------------------

--
-- Table structure for table `revenues`
--

CREATE TABLE IF NOT EXISTS `revenues` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `transaction_date` date NOT NULL,
  `total` double NOT NULL,
  `amount_paid` double NOT NULL,
  `paid` tinyint(1) NOT NULL,
  `sales_on_credit` float DEFAULT NULL,
  `jev` varchar(300) DEFAULT NULL,
  `orNo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `revenue_payments_receivable`
--

CREATE TABLE IF NOT EXISTS `revenue_payments_receivable` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `revenue_id` int(11) NOT NULL,
  `transaction_date` datetime NOT NULL,
  `amount_paid` double NOT NULL,
  `jev` varchar(300) DEFAULT NULL,
  `orNo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE IF NOT EXISTS `sales` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `transaction_date` date NOT NULL,
  `total` double NOT NULL,
  `amount_paid` double NOT NULL,
  `sales_on_credit` float DEFAULT NULL,
  `jev` varchar(30) DEFAULT NULL,
  `orNo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sales_details`
--

CREATE TABLE IF NOT EXISTS `sales_details` (
  `id` int(11) NOT NULL,
  `sales_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` double NOT NULL,
  `unit` varchar(30) DEFAULT NULL,
  `product_price` double NOT NULL,
  `buying_price` double DEFAULT NULL,
  `sub_total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `attribute` varchar(300) NOT NULL,
  `value` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`attribute`, `value`) VALUES
('address', 'San Antonio (Poblacion), Libjo, Dinagat Islands'),
('name', 'Libjo Ice Making Facility');

-- --------------------------------------------------------

--
-- Table structure for table `stock_card`
--

CREATE TABLE IF NOT EXISTS `stock_card` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `sku` varchar(300) NOT NULL,
  `price` double NOT NULL,
  `existing` double NOT NULL,
  `added` double NOT NULL,
  `total` double NOT NULL,
  `remarks` varchar(300) DEFAULT NULL,
  `finished` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` int(11) NOT NULL,
  `supplier_name` varchar(300) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email_address` varchar(300) DEFAULT NULL,
  `tin` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `uacs`
--

CREATE TABLE IF NOT EXISTS `uacs` (
  `classification` varchar(100) DEFAULT NULL,
  `sub_class` varchar(100) DEFAULT NULL,
  `grouping` varchar(100) DEFAULT NULL,
  `object_code` varchar(94) DEFAULT NULL,
  `uacs` int(8) NOT NULL,
  `status` varchar(6) DEFAULT NULL,
  `isEnabled` varchar(10) DEFAULT NULL,
  `payment_account` enum('no','yes') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uacs`
--

INSERT INTO `uacs` (`classification`, `sub_class`, `grouping`, `object_code`, `uacs`, `status`, `isEnabled`, `payment_account`) VALUES
('Asset', '', '', '', 10000000, '', '', 'no'),
('Asset', 'Cash and Cash Equivalents', '', '', 10100000, '', NULL, 'no'),
('Asset', 'Cash and Cash Equivalents', 'Cash on Hand', 'Cash on hand', 10101000, 'Active', '1', 'yes'),
('Asset', 'Cash and Cash Equivalents', 'Cash on Hand', 'Cash - Collecting Officer', 10101010, 'Active', '0', 'no'),
('Asset', 'Cash and Cash Equivalents', 'Cash on Hand', 'Petty Cash', 10101020, 'Active', '0', 'yes'),
('Asset', 'Cash and Cash Equivalents', 'Cash in Bank - Local Currency', '', 10102000, '', NULL, 'no'),
('Asset', 'Cash and Cash Equivalents', 'Cash in Bank - Local Currency', 'Cash in Bank', 10102010, 'Active', '1', 'yes'),
('Asset', 'Cash and Cash Equivalents', 'Cash in Bank - Local Currency', 'Cash in Bank - Local Currency, Current Account', 10102020, 'Active', NULL, 'no'),
('Asset', 'Cash and Cash Equivalents', 'Cash in Bank - Local Currency', 'Cash in Bank - Local Currency, Savings Account', 10102030, 'Active', NULL, 'no'),
('Asset', 'Cash and Cash Equivalents', 'Cash in Bank - Local Currency', 'Cash in Bank - Local Currency, Time Deposits', 10102040, 'Active', NULL, 'no'),
('Asset', 'Cash and Cash Equivalents', 'Cash in Bank - Foreign Currency', '', 10103000, '', NULL, 'no'),
('Asset', 'Cash and Cash Equivalents', 'Cash in Bank - Foreign Currency', 'Cash in Bank - Foreign Currency, Bangko Sentral ng Pilipinas', 10103010, 'Active', NULL, 'no'),
('Asset', 'Cash and Cash Equivalents', 'Cash in Bank - Foreign Currency', 'Cash in Bank - Foreign Currency, Current Account', 10103020, 'Active', NULL, 'no'),
('Asset', 'Cash and Cash Equivalents', 'Cash in Bank - Foreign Currency', 'Cash in Bank - Foreign Currency, Savings Account', 10103030, 'Active', NULL, 'no'),
('Asset', 'Cash and Cash Equivalents', 'Cash in Bank - Foreign Currency', 'Cash in Bank - Foreign Currency, Time Deposits', 10103040, 'Active', NULL, 'no'),
('Asset', 'Cash and Cash Equivalents', 'Treasury/Agency Cash Accounts', '', 10104000, '', NULL, 'no'),
('Asset', 'Cash and Cash Equivalents', 'Treasury/Agency Cash Accounts', 'Cash - Treasury/Agency Deposit, Regular', 10104010, 'Active', NULL, 'no'),
('Asset', 'Cash and Cash Equivalents', 'Treasury/Agency Cash Accounts', 'Cash - Treasury/Agency Deposit, Special Account', 10104020, 'Active', NULL, 'no'),
('Asset', 'Cash and Cash Equivalents', 'Treasury/Agency Cash Accounts', 'Cash - Treasury/Agency Deposit, Trust', 10104030, 'Active', NULL, 'no'),
('Asset', 'Cash and Cash Equivalents', 'Treasury/Agency Cash Accounts', 'Cash - Modified Disbursement System (MDS), Regular', 10104040, 'Active', NULL, 'no'),
('Asset', 'Cash and Cash Equivalents', 'Treasury/Agency Cash Accounts', 'Cash - Modified Disbursement System (MDS), Special Account', 10104050, 'Active', NULL, 'no'),
('Asset', 'Cash and Cash Equivalents', 'Treasury/Agency Cash Accounts', 'Cash - Modified Disbursement System (MDS), Trust', 10104060, 'Active', NULL, 'no'),
('Asset', 'Cash and Cash Equivalents', 'Treasury/Agency Cash Accounts', 'Cash - Tax Remittance Advice', 10104070, 'Active', NULL, 'no'),
('Asset', 'Cash and Cash Equivalents', 'Treasury/Agency Cash Accounts', 'Cash - Constructive Income Remittance', 10104080, 'Active', NULL, 'no'),
('Asset', 'Cash and Cash Equivalents', 'Cash Equivalents', '', 10105000, '', NULL, 'no'),
('Asset', 'Cash and Cash Equivalents', 'Cash Equivalents', 'Treasury Bills', 10105010, 'Active', NULL, 'no'),
('Asset', 'Investments', '', '', 10200000, '', NULL, 'no'),
('Asset', 'Investments', 'Financial Assets at Fair Value Through Surplus or Deficit', '', 10201000, '', NULL, 'no'),
('Asset', 'Investments', 'Financial Assets at Fair Value Through Surplus or Deficit', 'Financial Assets Held for Trading', 10201010, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Financial Assets at Fair Value Through Surplus or Deficit', 'Financial Assets Designated at Fair Value Through Surplus or Deficit', 10201020, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Financial Assets at Fair Value Through Surplus or Deficit', 'Derivative Financial Assets Held for Trading', 10201030, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Financial Assets at Fair Value Through Surplus or Deficit', 'Derivative Financial Assets Designated at Fair Value Through Surplus or Deficit', 10201040, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Financial Assets - Held to Maturity', '', 10202000, '', NULL, 'no'),
('Asset', 'Investments', 'Financial Assets - Held to Maturity', 'Investments in Treasury Bills - Local', 10202010, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Financial Assets - Held to Maturity', 'Allowance for Impairment - Investments in Treasury Bills - Local', 10202011, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Financial Assets - Held to Maturity', 'Investments in Treasury Bills - Foreign', 10202020, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Financial Assets - Held to Maturity', 'Allowance for Impairment - Investments in Treasury Bills - Foreign', 10202021, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Financial Assets - Held to Maturity', 'Investments in Treasury Bonds - Local', 10202030, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Financial Assets - Held to Maturity', 'Allowance for Impairment - Investments in Bonds - Local', 10202031, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Financial Assets - Held to Maturity', 'Investments in Treasury Bonds - Foreign', 10202040, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Financial Assets - Held to Maturity', 'Allowance for Impairment - Investments in Treasury Bonds - Foreign', 10202041, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Financial Assets - Others', '', 10203000, '', NULL, 'no'),
('Asset', 'Investments', 'Financial Assets - Others', 'Investments in Stocks', 10203010, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Financial Assets - Others', 'Investments in Bonds', 10203020, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Financial Assets - Others', 'Other Investments', 10203990, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Investments in GOCCs', '', 10204000, '', NULL, 'no'),
('Asset', 'Investments', 'Investments in GOCCs', 'Investments in GOCCs', 10204010, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Investments in GOCCs', 'Allowance for Impairment - Investments in GOCCs', 10204011, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Investments in Joint Venture', '', 10205000, '', NULL, 'no'),
('Asset', 'Investments', 'Investments in Joint Venture', 'Investments in Joint Venture', 10205010, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Investments in Joint Venture', 'Allowance for Impairment - Investments in Joint Venture', 10205011, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Investments in Associates', '', 10206000, '', NULL, 'no'),
('Asset', 'Investments', 'Investments in Associates', 'Investments in Associates', 10206010, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Investments in Associates', 'Allowance for Impairment - Investments in Associates', 10206011, 'Active', NULL, 'no'),
('Asset', 'Investments', 'Sinking Fund', 'Sinking Fund', 10207010, 'Active', NULL, 'no'),
('Asset', 'Receivables', '', '', 10300000, '', NULL, 'no'),
('Asset', 'Receivables', 'Loans and Receivable Accounts', '', 10301000, '', NULL, 'no'),
('Asset', 'Receivables', 'Loans and Receivable Accounts', 'Accounts Receivable', 10301010, 'Active', '1', 'no'),
('Asset', 'Receivables', 'Loans and Receivable Accounts', 'Allowance for Impairment - Accounts Receivable', 10301011, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Loans and Receivable Accounts', 'Notes Receivable', 10301020, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Loans and Receivable Accounts', 'Allowance for Impairment - Notes Receivable', 10301021, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Loans and Receivable Accounts', 'Loans Receivable - Government-Owned and/or Controlled Corporations', 10301030, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Loans and Receivable Accounts', 'Allowance for Impairment - Loans Receivable - Government-Owned and/or Controlled Corporations', 10301031, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Loans and Receivable Accounts', 'Loans Receivable - Local Government Units', 10301040, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Loans and Receivable Accounts', 'Allowance for Impairment - Loans Receivable - Local Government Units', 10301041, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Loans and Receivable Accounts', 'Interests Receivable', 10301050, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Loans and Receivable Accounts', 'Allowance for Impairment - Interests Receivable', 10301051, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Loans and Receivable Accounts', 'Dividends Receivable', 10301060, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Loans and Receivable Accounts', 'Loans Receivable - Others', 10301990, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Loans and Receivable Accounts', 'Allowance for Impairment - Loans Receivables - Others', 10301991, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Lease Receivable', '', 10302000, '', NULL, 'no'),
('Asset', 'Receivables', 'Lease Receivable', 'Operating Lease Receivable', 10302010, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Lease Receivable', 'Allowance for Impairment - Operating Lease Receivable', 10302011, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Lease Receivable', 'Finance Lease Receivable', 10302020, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Lease Receivable', 'Allowance for Impairment - Finance Lease Receivable', 10302021, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Inter-Agency Receivables', '', 10303000, '', NULL, 'no'),
('Asset', 'Receivables', 'Inter-Agency Receivables', 'Due from National Government Agencies', 10303010, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Inter-Agency Receivables', 'Due from Government-Owned and/or Controlled Corporations', 10303020, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Inter-Agency Receivables', 'Due fom Local Government Units', 10303030, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Inter-Agency Receivables', 'Due from Joint Venture', 10303040, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Intra-Agency Receivables', '', 10304000, '', NULL, 'no'),
('Asset', 'Receivables', 'Intra-Agency Receivables', 'Due from Central Office ', 10304010, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Intra-Agency Receivables', 'Due from Bureaus', 10304020, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Intra-Agency Receivables', 'Due from Regional Offices', 10304030, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Intra-Agency Receivables', 'Due from Operating Units', 10304040, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Other Receivables', '', 10305000, '', NULL, 'no'),
('Asset', 'Receivables', 'Other Receivables', 'Receivables  - Disallowances/Charges', 10305010, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Other Receivables', 'Due from Officers and Employees', 10305020, 'Active', '1', 'no'),
('Asset', 'Receivables', 'Other Receivables', 'Due from Non-Government Organizations/People''s Organizations', 10305030, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Other Receivables', 'Other Receivables', 10305990, 'Active', NULL, 'no'),
('Asset', 'Receivables', 'Other Receivables', 'Allowance for Impairment -  Other Receivables', 10305991, 'Active', NULL, 'no'),
('Asset', 'Inventories', '', '', 10400000, '', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Sale', '', 10401000, '', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Sale', 'Merchandise Inventory', 10401010, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Distribution', '', 10402000, '', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Distribution', 'Food Supplies for Distribution', 10402010, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Distribution', 'Welfare Goods for Distribution', 10402020, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Distribution', 'Drugs and Medicines for Distribution', 10402030, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Distribution', 'Medical, Dental and Laboratory Supplies for Distribution', 10402040, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Distribution', 'Agricultural and Marine Supplies for Distribution', 10402050, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Distribution', 'Agricultural Produce for Distribution', 10402060, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Distribution', 'Textbooks and Instructional Materials for Distribution', 10402070, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Distribution', 'Construction Materials for Distribution', 10402080, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Distribution', 'Property and Equipment for Distribution', 10402090, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Distribution', 'Other Supplies and  Materials for Distribution', 10402990, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Manufacturing', '', 10403000, '', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Manufacturing', 'Raw Materials Inventory', 10403010, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Manufacturing', 'Work-In-Process Inventory', 10403020, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Manufacturing', 'Finished Goods Inventory', 10403030, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Consumption', '', 10404000, '', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Consumption', 'Office Supplies Inventory', 10404010, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Consumption', 'Accountable Forms, Plates and Stickers Inventory', 10404020, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Consumption', 'Non-Accountable Forms Inventory', 10404030, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Consumption', 'Animal/Zoological Supplies Inventory', 10404040, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Consumption', 'Food Supplies Inventory', 10404050, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Consumption', 'Drugs and Medicines Inventory', 10404060, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Consumption', 'Medical, Dental and Laboratory Supplies Inventory', 10404070, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Consumption', 'Fuel, Oil and Lubricants Inventory', 10404080, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Consumption', 'Agricultural and Marine Supplies Inventory', 10404090, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Consumption', 'Textbooks and Instructional Materials Inventory', 10404100, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Consumption', 'Military, Police and Traffic Supplies Inventory', 10404110, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Consumption', 'Chemical and Filtering Supplies Inventory', 10404120, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Consumption', 'Construction Materials Inventory', 10404130, 'Active', NULL, 'no'),
('Asset', 'Inventories', 'Inventory Held for Consumption', 'Other Supplies and Materials Inventory', 10404990, 'Active', NULL, 'no'),
('Asset', 'Investment Property', '', '', 10500000, '', NULL, 'no'),
('Asset', 'Investment Property', 'Land and Buildings', '', 10501000, '', NULL, 'no'),
('Asset', 'Investment Property', 'Land and Buildings', 'Investment Property, Land', 10501010, 'Active', NULL, 'no'),
('Asset', 'Investment Property', 'Land and Buildings', 'Accumulated Impairment Losses - Investment Property, Land', 10501011, 'Active', NULL, 'no'),
('Asset', 'Investment Property', 'Land and Buildings', 'Investment Property, Buildings', 10501020, 'Active', NULL, 'no'),
('Asset', 'Investment Property', 'Land and Buildings', 'Accumulated Depreciation - Investment Property, Buildings', 10501021, 'Active', NULL, 'no'),
('Asset', 'Investment Property', 'Land and Buildings', 'Accumulated Impairment Losses - Investment Property, Buildings', 10501022, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', '', '', 10600000, '', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Land', '', 10601000, '', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Land', 'Accumulated Impairment Losses - Land', 10601011, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Land Improvements', '', 10602000, '', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Land Improvements', 'Land Improvements - Aquaculture Structures', 10602010, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Land Improvements', 'Accumulated Depreciation - Land Improvements, Aquaculture Structures', 10602011, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Land Improvements', 'Accumulated Impairment Losses - Land Improvements, Aquaculture Structures', 10602012, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Land Improvements', 'Land Improvements, Reforestation Projects', 10602020, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Land Improvements', 'Accumulated Impairment Losses - Land Improvements, Reforestation Projects', 10602021, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Land Improvements', 'Other Land Improvements', 10602990, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Land Improvements', 'Accumulated Depreciation - Other Land Improvements', 10602991, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Land Improvements', 'Accumulated Impairment Losses - Other Land Improvements', 10602992, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', '', 10603000, '', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Road Networks', 10603010, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Accumulated Depreciation - Road Networks', 10603011, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Accumulated Impairment Losses - Road Networks', 10603012, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Flood Control Systems', 10603020, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Accumulated Depreciation - Flood Control Systems', 10603021, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Accumulated Impairment Losses - Flood Control Systems', 10603022, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Sewer Systems', 10603030, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Accumulated Depreciation - Sewer Systems ', 10603031, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Accumulated Impairment Losses - Sewer Systems', 10603032, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Water Supply Systems', 10603040, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Accumulated Depreciation - Water Supply Systems', 10603041, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Accumulated Impairment Losses - Water Supply Systems', 10603042, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Power Supply Systems', 10603050, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Accumulated Depreciation - Power Supply Systems', 10603051, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Accumulated Impairment Losses - Power Supply Systems', 10603052, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Communication Networks', 10603060, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Accumulated Depreciation - Communication Networks', 10603061, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Accumulated Impairment Losses - Communication Networks', 10603062, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Seaport Systems', 10603070, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Accumulated Depreciation - Seaport Systems', 10603071, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Accumulated Impairment Losses - Seaport Systems', 10603072, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Airport Systems', 10603080, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Accumulated Depreciation - Airport Systems ', 10603081, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Accumulated Impairment Losses - Airport Systems', 10603082, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Parks, Plazas and Monuments', 10603090, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Accumulated Depreciation - Parks, Plazas and Monuments', 10603091, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Accumulated Impairment Losses - Parks, Plazas and Monuments', 10603092, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Other Infrastructure Assets', 10603990, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Accumulated Depreciation - Other Infrastructure Assets', 10603991, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Infrastructure Assets', 'Accumulated Impairment Losses - Other Infrastructure Assets', 10603992, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', '', 10604000, '', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'Buildings', 10604010, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'Accumulated Depreciation - Buildings', 10604011, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'Accumulated Impairment Losses - Buildings', 10604012, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'School Buildings', 10604020, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'Accumulated Depreciation - School Buildings', 10604021, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'Accumulated Impairment Losses - School Buildings', 10604022, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'Hospitals and Health Centers', 10604030, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'Accumulated Depreciation - Hospitals and Health Centers ', 10604031, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'Accumulated Impairment Losses - Hospitals and Health Centers', 10604032, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'Markets', 10604040, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'Accumulated Depreciation - Markets', 10604041, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'Accumulated Impairment Losses - Markets', 10604042, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'Slaughterhouses', 10604050, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'Accumulated Depreciation - Slaughterhouses ', 10604051, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'Accumulated Impairment Losses - Slaughterhouses', 10604052, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'Hostels and Dormitories', 10604060, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'Accumulated Depreciation - Hostels and Dormitories', 10604061, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'Accumulated Impairment Losses - Hostels and Dormitories', 10604062, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'Other Structures', 10604990, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'Accumulated Depreciation - Other Structures', 10604991, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Buildings and Other Structures', 'Accumulated Impairment Losses - Other Structures', 10604992, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', '', 10605000, '', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Machinery', 10605010, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Depreciation - Machinery', 10605011, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Impairment Losses - Machinery', 10605012, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Office Equipment', 10605020, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Depreciation - Office Equipment', 10605021, 'Active', '1', 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Impairment Losses - Office Equipment', 10605022, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Information and Communication Technology Equipment', 10605030, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Depreciation - Information and Communication Technology Equipment', 10605031, 'Active', '1', 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Impairment Losses - Information and Communication Technology Equipment', 10605032, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Agricultural and Forestry Equipment', 10605040, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Depreciation - Agricultural and Forestry Equipment', 10605041, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Impairment Losses - Agricultural and Forestry Equipment', 10605042, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Marine and Fishery Equipment', 10605050, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Depreciation - Marine and Fishery Equipment', 10605051, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Impairment Losses - Marine and Fishery Equipment', 10605052, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Airport Equipment', 10605060, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Depreciation - Airport Equipment', 10605061, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Impairment Losses - Airport Equipment', 10605062, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Communication Equipment', 10605070, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Depreciation - Communication Equipment', 10605071, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Impairment Losses - Communication Equipment', 10605072, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Construction and Heavy Equipment', 10605080, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Depreciation - Construction and Heavy Equipment', 10605081, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Impairment Losses - Construction and Heavy Equipment', 10605082, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Disaster Response and Rescue Equipment', 10605090, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Depreciation - Disaster Response and Rescue Equipment', 10605091, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Impairment Losses - Disaster Response and Rescue Equipment', 10605092, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Military, Police and Security Equipment', 10605100, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Depreciation - Military, Police and Security Equipment', 10605101, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Impairment Losses - Military, Police and Security Equipment', 10605102, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Medical Equipment', 10605110, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Depreciation - Medical Equipment', 10605111, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Impairment Losses - Medical Equipment', 10605112, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Printing Equipment', 10605120, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Depreciation - Printing Equipment', 10605121, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Impairment Losses - Printing Equipment', 10605122, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Sports Equipment', 10605130, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Depreciation - Sports Equipment', 10605131, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Impairment Losses - Sports Equipment', 10605132, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Technical and Scientific Equipment', 10605140, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Depreciation - Technical and Scientific Equipment', 10605141, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Impairment Losses - Technical and Scientific Equipment', 10605142, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Other Machinery and Equipment', 10605990, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Depreciation - Other Machinery and Equipment', 10605991, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Machinery and Equipment', 'Accumulated Impairment Losses - Other Machinery and Equipment', 10605992, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Transportation Equipment', '', 10606000, '', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Transportation Equipment', 'Motor Vehicles', 10606010, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Transportation Equipment', 'Accumulated Depreciation - Motor Vehicles', 10606011, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Transportation Equipment', 'Accumulated Impairment Losses - Motor Vehicles', 10606012, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Transportation Equipment', 'Trains', 10606020, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Transportation Equipment', 'Accumulated Depreciation - Trains', 10606021, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Transportation Equipment', 'Accumulated Impairment Losses - Trains', 10606022, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Transportation Equipment', 'Aircrafts and Aircrafts Ground Equipment', 10606030, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Transportation Equipment', 'Accumulated Depreciation - Aircrafts and Aircrafts Ground Equipment', 10606031, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Transportation Equipment', 'Accumulated Impairment Losses - Aircrafts and Aircrafts Ground Equipment', 10606032, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Transportation Equipment', 'Watercrafts', 10606040, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Transportation Equipment', 'Accumulated Depreciation - Watercrafts', 10606041, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Transportation Equipment', 'Accumulated Impairment Losses - Watercrafts', 10606042, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Transportation Equipment', 'Other Transportation Equipment', 10606990, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Transportation Equipment', 'Accumulated Depreciation - Other Transportation Equipment', 10606991, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Transportation Equipment', 'Accumulated Impairment Losses - Other Transportation Equipment', 10606992, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Furniture, Fixtures and Books', '', 10607000, '', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Furniture, Fixtures and Books', 'Furniture and Fixtures', 10607010, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Furniture, Fixtures and Books', 'Accumulated Depreciation - Furniture and Fixtures', 10607011, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Furniture, Fixtures and Books', 'Accumulated Impairment Losses - Furniture and Fixtures', 10607012, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Furniture, Fixtures and Books', 'Books', 10607020, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Furniture, Fixtures and Books', 'Accumulated Depreciation - Books', 10607021, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Furniture, Fixtures and Books', 'Accumulated Impairment Losses - Books', 10607022, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets', '', 10608000, '', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets', 'Leased Assets, Land', 10608010, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets', 'Leased Assets, Buildings and Other Structures', 10608020, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets', 'Accumulated Depreciation - Leased Assets, Buildings and Other Structures', 10608021, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets', 'Accumulated Impairment Losses - Leased Assets, Buildings and Other Structures', 10608022, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets', 'Leased Assets, Machinery and Equipment', 10608030, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets', 'Accumulated Depreciation - Leased Assets, Machinery and Equipment', 10608031, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets', 'Accumulated Impairment Losses - Leased Assets, Machinery and Equipment', 10608032, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets', 'Leased Assets, Transportation Equipment', 10608040, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets', 'Accumulated Depreciation - Leased Assets, Transportation Equipment', 10608041, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets', 'Accumulated Impairment Losses - Leased Assets, Transportation Equipment', 10608042, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets', 'Other Leased Assets', 10608990, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets', 'Accumulated Depreciation - Other Leased Assets', 10608991, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets', 'Accumulated Impairment Losses - Other Leased Assets', 10608992, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets - Improvements', '', 10609000, '', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets - Improvements', 'Leased Assets Improvements, Land', 10609010, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets - Improvements', 'Accumulated Depreciation - Leased Assets Improvements, Land', 10609011, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets - Improvements', 'Accumulated Impairment Losses - Leased Assets Improvements, Land', 10609012, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets - Improvements', 'Leased Assets Improvements, Buildings', 10609020, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets - Improvements', 'Accumulated Depreciation - Leased Assets Improvements, Buildings', 10609021, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets - Improvements', 'Accumulated Impairment Losses - Leased Assets Improvements, Buildings', 10609022, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets - Improvements', 'Other Leased Assets Improvements', 10609990, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets - Improvements', 'Accumulated Depreciation - Other Leased Assets Improvements', 10609991, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Leased Assets - Improvements', 'Accumulated Impairment Losses - Other Leased Assets Improvements', 10609992, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Construction in Progress', '', 10610000, '', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Construction in Progress', 'Construction in Progress - Land Improvements', 10610010, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Construction in Progress', 'Construction in Progress - Infrastructure Assets', 10610020, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Construction in Progress', 'Construction in Progress - Buildings and Other Structures', 10610030, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Construction in Progress', 'Construction in Progress - Leased Assets', 10610040, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Construction in Progress', 'Construction in Progress - Leased Assets Improvements', 10610050, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Heritage Assets', '', 10611000, '', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Heritage Assets', 'Historical Buildings', 10611010, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Heritage Assets', 'Accumulated Depreciation - Historical Buildings', 10611011, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Heritage Assets', 'Accumulated Impairment Losses - Historical Buildings', 10611012, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Heritage Assets', 'Works of Arts and Archeological Specimens', 10611020, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Heritage Assets', 'Accumulated Depreciation - Works of Arts and Archeological Specimens', 10611021, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Heritage Assets', 'Accumulated Impairment Losses - Works of Arts and Archeological Specimens', 10611022, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Heritage Assets', 'Other Heritage Assets', 10611990, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Heritage Assets', 'Accumulated Depreciation - Other Heritage Assets', 10611991, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Heritage Assets', 'Accumulated Impairment Losses - Other Heritage Assets', 10611992, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', '', 10612000, '', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Service Concession- Road Networks', 10612010, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Accumulated Depreciation - Service Concession- Road Networks', 10612011, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Accumulated Impairment Losses -Service Concession - Road Networks', 10612012, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Service Concession - Flood Control Systems', 10612020, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Accumulated Depreciation - Service Concession - Flood Control Systems', 10612021, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Accumulated Impairment Losses - Service Concession - Flood Control Systems', 10612022, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Service Concession - Sewer Systems', 10612030, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Accumulated Depreciation - Service Concession - Sewer Systems', 10612031, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Accumulated Impairment Losses - Service Concession - Sewer Systems', 10612032, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Service Concession - Water Supply Systems', 10612040, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Accumulated Depreciation - Service Concession - Water Supply Systems', 10612041, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Accumulated Impairment Losses - Service Concession - Water Supply Systems', 10612042, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Service Concession - Power Supply Systems', 10612050, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Accumulated Depreciation - Service Concession - Power Supply Systems', 10612051, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Accumulated Impairment Losses - Service Concession - Power Supply Systems', 10612052, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Service Concession - Communication Networks', 10612060, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Accumulated Depreciation - Service Concession - Communication Networks', 10612061, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Accumulated Impairment Losses - Service Concession - Communication Networks', 10612062, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Service Concession - Seaport Systems', 10612070, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Accumulated Depreciation - Service Concession - Seaport Systems', 10612071, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Accumulated Impairment Losses - Service Concession - Seaport Systems', 10612072, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Service Concession - Airport Systems', 10612080, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Accumulated Depreciation - Service Concession - Airport Systems', 10612081, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Accumulated Impairment Losses - Service Concession - Airport Systems', 10612082, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Service Concession - Parks, Plazas and Monuments', 10612090, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Accumulated Depreciation - Service Concession - Parks, Plazas and Monuments', 10612091, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Accumulated Impairment Losses - Service Concession - Parks, Plazas and Monuments', 10612092, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Other Service Concession Assets', 10612990, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Accumulated Depreciation - Other Service Concession Assets', 10612991, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Service Concession Assets', 'Accumulated Impairment Losses - Other Service Concession Assets', 10612992, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Other Property, Plant and Equipment', '', 10699000, '', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Other Property, Plant and Equipment', 'Work/Zoo Animals', 10699010, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Other Property, Plant and Equipment', 'Accumulated Depreciation - Work/Zoo Animals', 10699011, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Other Property, Plant and Equipment', 'Accumulated Impairment Losses - Work/Zoo Animals', 10699012, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Other Property, Plant and Equipment', 'Other Property, Plant and Equipment', 10699990, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Other Property, Plant and Equipment', 'Accumulated Depreciation - Other Property, Plant and Equipment', 10699991, 'Active', NULL, 'no'),
('Asset', 'Property, Plant and Equipment', 'Other Property, Plant and Equipment', 'Accumulated Impairment Losses - Other Property, Plant and Equipment', 10699992, 'Active', NULL, 'no'),
('Asset', 'Biological Assets', '', '', 10700000, '', NULL, 'no'),
('Asset', 'Biological Assets', 'Bearer Biological Assets', '', 10701000, '', NULL, 'no'),
('Asset', 'Biological Assets', 'Bearer Biological Assets', 'Breeding Stocks', 10701010, 'Active', NULL, 'no'),
('Asset', 'Biological Assets', 'Bearer Biological Assets', 'Accumulated Impairment Losses - Breeding Stocks', 10701011, 'Active', NULL, 'no'),
('Asset', 'Biological Assets', 'Bearer Biological Assets', 'Livestock', 10701020, 'Active', NULL, 'no'),
('Asset', 'Biological Assets', 'Bearer Biological Assets', 'Accumulated Impairment Losses - Livestock', 10701021, 'Active', NULL, 'no'),
('Asset', 'Biological Assets', 'Bearer Biological Assets', 'Trees, Plants and Crops', 10701030, 'Active', NULL, 'no'),
('Asset', 'Biological Assets', 'Bearer Biological Assets', 'Accumulated Impairment Losses - Trees, Plants and Crops', 10701031, 'Active', NULL, 'no'),
('Asset', 'Biological Assets', 'Bearer Biological Assets', 'Aquaculture', 10701040, 'Active', NULL, 'no'),
('Asset', 'Biological Assets', 'Bearer Biological Assets', 'Accumulated Impairment Losses - Aquaculture', 10701041, 'Active', NULL, 'no'),
('Asset', 'Biological Assets', 'Bearer Biological Assets', 'Other Bearer Biological Assets', 10701990, 'Active', NULL, 'no'),
('Asset', 'Biological Assets', 'Bearer Biological Assets', 'Accumulated Impairment Losses - Other Bearer Biological Assets', 10701991, 'Active', NULL, 'no'),
('Asset', 'Biological Assets', 'Consumable Biological Assets', '', 10702000, '', NULL, 'no'),
('Asset', 'Biological Assets', 'Consumable Biological Assets', 'Livestock Held for Consumption/Sale/Distribution', 10702010, 'Active', NULL, 'no'),
('Asset', 'Biological Assets', 'Consumable Biological Assets', 'Accumulated Impairment Losses - Livestock Held for Consumption/Sale/Distribution', 10702011, 'Active', NULL, 'no'),
('Asset', 'Biological Assets', 'Consumable Biological Assets', 'Trees, Plants and Crops Held for Consumption/Sale/Distribution', 10702020, 'Active', NULL, 'no'),
('Asset', 'Biological Assets', 'Consumable Biological Assets', 'Accumulated Impairment Losses - Trees, Plants and Crops Held for Consumption/Sale/Distribution', 10702021, 'Active', NULL, 'no'),
('Asset', 'Biological Assets', 'Consumable Biological Assets', 'Agricultural Produce Held for Consumption/Sale/Distribution', 10702030, 'Active', NULL, 'no');
INSERT INTO `uacs` (`classification`, `sub_class`, `grouping`, `object_code`, `uacs`, `status`, `isEnabled`, `payment_account`) VALUES
('Asset', 'Biological Assets', 'Consumable Biological Assets', 'Accumulated Impairment Losses - Agricultural Produce Held for Consumption/Sale/Distribution', 10702031, 'Active', NULL, 'no'),
('Asset', 'Biological Assets', 'Consumable Biological Assets', 'Aquaculture', 10702040, 'Active', NULL, 'no'),
('Asset', 'Biological Assets', 'Consumable Biological Assets', 'Accumulated Impairment Losses - Aquaculture', 10702041, 'Active', NULL, 'no'),
('Asset', 'Biological Assets', 'Consumable Biological Assets', 'Other Consumable Biological Assets', 10702990, 'Active', NULL, 'no'),
('Asset', 'Biological Assets', 'Consumable Biological Assets', 'Accumulated Impairment Losses- Other Consumable Biological Assets', 10702991, 'Active', NULL, 'no'),
('Asset', 'Intangible Assets', '', '', 10800000, '', NULL, 'no'),
('Asset', 'Intangible Assets', 'Intangible Assets', '', 10801000, '', NULL, 'no'),
('Asset', 'Intangible Assets', 'Intangible Assets', 'Patents/Copyrights', 10801010, 'Active', NULL, 'no'),
('Asset', 'Intangible Assets', 'Intangible Assets', 'Accumulated Amortization - Patents/Copyrights', 10801011, 'Active', NULL, 'no'),
('Asset', 'Intangible Assets', 'Intangible Assets', 'Computer Software', 10801020, 'Active', NULL, 'no'),
('Asset', 'Intangible Assets', 'Intangible Assets', 'Accumulated Amortization - Computer Software', 10801021, 'Active', NULL, 'no'),
('Asset', 'Intangible Assets', 'Intangible Assets', 'Other Intangible Assets', 10801990, 'Active', NULL, 'no'),
('Asset', 'Intangible Assets', 'Intangible Assets', 'Accumulated Amortization - Other Intangible Assets', 10801991, 'Active', NULL, 'no'),
('Asset', 'Other Assets', '', '', 10900000, '', NULL, 'no'),
('Asset', 'Other Assets', 'Advances', '', 19901000, '', NULL, 'no'),
('Asset', 'Other Assets', 'Advances', 'Advances for Operation Expenses', 19901010, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Advances', 'Advances for Payroll', 19901020, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Advances', 'Advances to Special Disbursing Officer', 19901030, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Advances', 'Advances to Officers and Employees', 19901040, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Prepayments', '', 19902000, '', NULL, 'no'),
('Asset', 'Other Assets', 'Prepayments', 'Advances to Contractors', 19902010, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Prepayments', 'Prepaid Rent', 19902020, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Prepayments', 'Prepaid Registration', 19902030, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Prepayments', 'Prepaid Interest', 19902040, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Prepayments', 'Prepaid Insurance', 19902050, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Prepayments', 'Other Prepayments', 19902990, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Deposits', '', 19903000, '', NULL, 'no'),
('Asset', 'Other Assets', 'Deposits', 'Deposits on Letters of Credits', 19903010, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Deposits', 'Guaranty Deposits', 19903020, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Deposits', 'Other Deposits', 19903990, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Other Assets', '', 19999000, '', NULL, 'no'),
('Asset', 'Other Assets', 'Other Assets', 'Acquired Assets', 19999010, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Other Assets', 'Accumulated Impairment Losses - Acquired Assets', 19999011, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Other Assets', 'Foreclosed Property/Assets', 19999020, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Other Assets', 'Accumulated Impairment Losses - Foreclosed Property/Assets', 19999021, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Other Assets', 'Forfeited Property/Assets', 19999030, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Other Assets', 'Accumulated Impairment Losses - Forfeited Property/Assets', 19999031, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Other Assets', 'Confiscated Property/Assets', 19999040, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Other Assets', 'Accumulated Impairment Losses - Confiscated Property/Assets', 19999041, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Other Assets', 'Abandoned Property/Assets', 19999050, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Other Assets', 'Accumulated Impairment Losses - Abandoned Property Assets', 19999051, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Other Assets', 'Other Assets', 19999990, 'Active', NULL, 'no'),
('Asset', 'Other Assets', 'Other Assets', 'Accumulated Impairment Losses - Other Assets', 19999991, 'Active', NULL, 'no'),
('Liabilities', '', '', '', 20000000, '', NULL, 'no'),
('Liabilities', 'Dividends Payable', 'Dividends Payable', 'Dividends Payable', 20000001, 'Active', '1', 'no'),
('Liabilities', 'Financial Liabilities', '', '', 20100000, '', NULL, 'no'),
('Liabilities', 'Financial Liabilities', 'Payables', '', 20101000, '', NULL, 'no'),
('Liabilities', 'Financial Liabilities', 'Payables', 'Accounts Payable', 20101010, 'Active', '1', 'no'),
('Liabilities', 'Financial Liabilities', 'Payables', 'Due to Officers and Employees', 20101020, 'Active', NULL, 'no'),
('Liabilities', 'Financial Liabilities', 'Payables', 'Internal Revenue Allotment Payable', 20101030, 'Active', NULL, 'no'),
('Liabilities', 'Financial Liabilities', 'Payables', 'Notes Payable', 20101040, 'Active', NULL, 'no'),
('Liabilities', 'Financial Liabilities', 'Payables', 'Interest Payable', 20101050, 'Active', NULL, 'no'),
('Liabilities', 'Financial Liabilities', 'Payables', 'Operating Lease Payable', 20101060, 'Active', NULL, 'no'),
('Liabilities', 'Financial Liabilities', 'Payables', 'Finance Lease Payable', 20101070, 'Active', NULL, 'no'),
('Liabilities', 'Financial Liabilities', 'Payables', 'Awards and Rewards Payable', 20101080, 'Active', NULL, 'no'),
('Liabilities', 'Financial Liabilities', 'Payables', 'Service Concession Arrangements Payable', 20101090, 'Active', NULL, 'no'),
('Liabilities', 'Financial Liabilities', 'Bills/Bonds/Loans Payable', '', 20102000, '', NULL, 'no'),
('Liabilities', 'Financial Liabilities', 'Bills/Bonds/Loans Payable', 'Treasury Bills Payable ', 20102010, 'Active', NULL, 'no'),
('Liabilities', 'Financial Liabilities', 'Bills/Bonds/Loans Payable', 'Bonds Payable - Domestic', 20102020, 'Active', NULL, 'no'),
('Liabilities', 'Financial Liabilities', 'Bills/Bonds/Loans Payable', 'Discount on Bonds Payable - Domestic', 20102021, 'Active', NULL, 'no'),
('Liabilities', 'Financial Liabilities', 'Bills/Bonds/Loans Payable', 'Premium on Bonds Payable - Domestic', 20102022, 'Active', NULL, 'no'),
('Liabilities', 'Financial Liabilities', 'Bills/Bonds/Loans Payable', 'Bonds Payable - Foreign', 20102030, 'Active', NULL, 'no'),
('Liabilities', 'Financial Liabilities', 'Bills/Bonds/Loans Payable', 'Discount on Bonds Payable - Foreign', 20102031, 'Active', NULL, 'no'),
('Liabilities', 'Financial Liabilities', 'Bills/Bonds/Loans Payable', 'Premium on Bonds Payable - Foreign', 20102032, 'Active', NULL, 'no'),
('Liabilities', 'Financial Liabilities', 'Bills/Bonds/Loans Payable', 'Loans Payable - Domestic', 20102040, 'Active', NULL, 'no'),
('Liabilities', 'Financial Liabilities', 'Bills/Bonds/Loans Payable', 'Loans Payable - Foreign ', 20102050, 'Active', NULL, 'no'),
('Liabilities', 'Inter-Agency Payables', '', '', 20200000, '', NULL, 'no'),
('Liabilities', 'Inter-Agency Payables', 'Inter-Agency Payables', '', 20201000, '', NULL, 'no'),
('Liabilities', 'Inter-Agency Payables', 'Inter-Agency Payables', 'Due to BIR', 20201010, 'Active', NULL, 'no'),
('Liabilities', 'Inter-Agency Payables', 'Inter-Agency Payables', 'Due to GSIS', 20201020, 'Active', NULL, 'no'),
('Liabilities', 'Inter-Agency Payables', 'Inter-Agency Payables', 'Due to Pag-IBIG', 20201030, 'Active', NULL, 'no'),
('Liabilities', 'Inter-Agency Payables', 'Inter-Agency Payables', 'Due to PhilHealth', 20201040, 'Active', NULL, 'no'),
('Liabilities', 'Inter-Agency Payables', 'Inter-Agency Payables', 'Due to NGAs', 20201050, 'Active', NULL, 'no'),
('Liabilities', 'Inter-Agency Payables', 'Inter-Agency Payables', 'Due to GOCCs', 20201060, 'Active', NULL, 'no'),
('Liabilities', 'Inter-Agency Payables', 'Inter-Agency Payables', 'Due to LGUs', 20201070, 'Active', NULL, 'no'),
('Liabilities', 'Inter-Agency Payables', 'Inter-Agency Payables', 'Due to Joint Venture', 20201080, 'Active', NULL, 'no'),
('Liabilities', 'Intra-Agency Payables', '', '', 20300000, '', NULL, 'no'),
('Liabilities', 'Intra-Agency Payables', 'Intra-Agency Payables', '', 20301000, '', NULL, 'no'),
('Liabilities', 'Intra-Agency Payables', 'Intra-Agency Payables', 'Due to Central Office', 20301010, 'Active', NULL, 'no'),
('Liabilities', 'Intra-Agency Payables', 'Intra-Agency Payables', 'Due to Bureaus', 20301020, 'Active', NULL, 'no'),
('Liabilities', 'Intra-Agency Payables', 'Intra-Agency Payables', 'Due to Regional Offices', 20301030, 'Active', NULL, 'no'),
('Liabilities', 'Intra-Agency Payables', 'Intra-Agency Payables', 'Due to Operating Units', 20301040, 'Active', NULL, 'no'),
('Liabilities', 'Intra-Agency Payables', 'Intra-Agency Payables', 'Due to Other Funds', 20301050, 'Active', NULL, 'no'),
('Liabilities', 'Trust Liabilities', '', '', 20400000, '', NULL, 'no'),
('Liabilities', 'Trust Liabilities', 'Trust Liabilities', '', 20401000, '', NULL, 'no'),
('Liabilities', 'Trust Liabilities', 'Trust Liabilities', 'Trust Liabilities', 20401010, 'Active', NULL, 'no'),
('Liabilities', 'Trust Liabilities', 'Trust Liabilities', 'Trust Liabilities - Disaster Risk Reduction And Management Fund', 20401020, 'Active', NULL, 'no'),
('Liabilities', 'Trust Liabilities', 'Trust Liabilities', 'Bail Bonds Payable ', 20401030, 'Active', NULL, 'no'),
('Liabilities', 'Trust Liabilities', 'Trust Liabilities', 'Guaranty/Security Deposits Payable', 20401040, 'Active', NULL, 'no'),
('Liabilities', 'Trust Liabilities', 'Trust Liabilities', 'Customers'' Deposits Payable', 20401050, 'Active', NULL, 'no'),
('Liabilities', 'Deffered Credits/Unearned Income', '', '', 20500000, '', NULL, 'no'),
('Liabilities', 'Deffered Credits/Unearned Income', 'Deferred Credits', '', 20501000, '', NULL, 'no'),
('Liabilities', 'Deffered Credits/Unearned Income', 'Deferred Credits', 'Deferred Finance Lease Revenue', 20501010, 'Active', NULL, 'no'),
('Liabilities', 'Deffered Credits/Unearned Income', 'Deferred Credits', 'Deferred Service Concession Revenue', 20501020, 'Active', NULL, 'no'),
('Liabilities', 'Deffered Credits/Unearned Income', 'Deferred Credits', 'Other Deferred Credits', 20501990, 'Active', NULL, 'no'),
('Liabilities', 'Deffered Credits/Unearned Income', 'Unearned Revenue', '', 20502000, '', NULL, 'no'),
('Liabilities', 'Deffered Credits/Unearned Income', 'Unearned Revenue', 'Unearned Revenue - Investment Property', 20502010, 'Active', NULL, 'no'),
('Liabilities', 'Deffered Credits/Unearned Income', 'Unearned Revenue', 'Other Unearned Revenue', 20502990, 'Active', NULL, 'no'),
('Liabilities', 'Provisions', '', '', 20600000, '', NULL, 'no'),
('Liabilities', 'Provisions', 'Provisions', '', 20601000, '', NULL, 'no'),
('Liabilities', 'Provisions', 'Provisions', 'Pension Benefits Payable', 20601010, 'Active', NULL, 'no'),
('Liabilities', 'Provisions', 'Provisions', 'Leave Benefits Payable', 20601020, 'Active', NULL, 'no'),
('Liabilities', 'Provisions', 'Provisions', 'Retirement Gratuity Payable', 20601030, 'Active', NULL, 'no'),
('Liabilities', 'Provisions', 'Provisions', 'Other Provisions', 20601990, 'Active', NULL, 'no'),
('Liabilities', 'Other Payables', 'Other Payables', 'Other Payables', 29999990, 'Active', NULL, 'no'),
('Equity', '', '', '', 30000000, '', NULL, 'no'),
('Equity', 'Retained Earnings', 'Retained Earnings', 'Retained Earnings', 30000001, 'Active', '1', 'no'),
('Equity', 'Government Equity', '', '', 30100000, '', NULL, 'no'),
('Equity', 'Donation', 'Donation', 'Donated Capital', 30100001, 'Active', '1', 'no'),
('Equity', 'Government Equity', 'Government Equity', '', 30101000, '', NULL, 'no'),
('Equity', 'Government Equity', 'Government Equity', 'Government Equity', 30101010, 'Active', NULL, 'no'),
('Equity', 'Government Equity', 'Government Equity', 'Contributed Capital', 30101030, 'Active', '1', 'no'),
('Equity', 'Revaluation Surplus', 'Revaluation Surplus', 'Revaluation Surplus', 30201010, 'Active', NULL, 'no'),
('Equity', 'Intermediate Accounts', '', '', 30300000, '', NULL, 'no'),
('Equity', 'Intermediate Accounts', 'Intermediate Accounts', '', 30301000, '', NULL, 'no'),
('Equity', 'Intermediate Accounts', 'Intermediate Accounts', 'Income and Expense Summary', 30301010, 'Active', NULL, 'no'),
('Equity', 'Equity in Joint Venture', 'Equity in Joint Venture', 'Equity in Joint Venture', 30401010, 'Active', NULL, 'no'),
('Income', '', '', '', 40000000, '', NULL, 'no'),
('Income', '', 'Sales', 'Sales - Fruits & Vegetables', 40000001, 'Active', '1', 'no'),
('Income', 'Tax Revenue', '', '', 40100000, '', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Individual and Corporation', '', 40101000, '', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Individual and Corporation', 'Income Tax', 40101010, 'Active', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Individual and Corporation', 'Professional Tax', 40101020, 'Active', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Individual and Corporation', 'Travel Tax', 40101030, 'Active', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Individual and Corporation', 'Immigration Tax', 40101040, 'Active', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Property', '', 40102000, '', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Property', 'Estate Tax', 40102010, 'Active', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Property', 'Donors Tax', 40102020, 'Active', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Property', 'Capital Gains Tax', 40102030, 'Active', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Goods and Services', '', 40103000, '', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Goods and Services', 'Import Duties', 40103010, 'Active', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Goods and Services', 'Excise Tax', 40103020, 'Active', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Goods and Services', 'Business Tax', 40103030, 'Active', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Goods and Services', 'Tax on Sand, Gravel and Other Quarry Products', 40103040, 'Active', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Goods and Services', 'Tax on Delivery Vans and Trucks', 40103050, 'Active', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Goods and Services', 'Tax on Forest Products', 40103060, 'Active', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Others', '', 40104000, '', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Others', 'Documentary Stamp Tax', 40104010, 'Active', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Others', 'Motor Vehicles Users'' Charge ', 40104020, 'Active', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Others', 'Other Taxes', 40104990, 'Active', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Fines and Penalties', '', 40105000, '', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Fines and Penalties', 'Tax Revenue - Fines and Penalties - Taxes on Individual and Corporation', 40105010, 'Active', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Fines and Penalties', 'Tax Revenue - Fines and Penalties - Property Taxes', 40105020, 'Active', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Fines and Penalties', 'Tax Revenue - Fines and Penalties - Taxes on Goods and Services', 40105030, 'Active', NULL, 'no'),
('Income', 'Tax Revenue', 'Tax Revenue - Fines and Penalties', 'Tax Revenue - Fines and Penalties - Other Taxes', 40105040, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', '', '', 40200000, '', NULL, 'no'),
('Income', 'Service and Business Income', 'Service Income', '', 40201000, '', NULL, 'no'),
('Income', 'Service and Business Income', 'Service Income', 'Permit Fees', 40201010, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Service Income', 'Registration Fees', 40201020, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Service Income', 'Registration Plates, Tags and Stickers Fees', 40201030, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Service Income', 'Clearance and Certification Fees', 40201040, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Service Income', 'Franchising Fees', 40201050, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Service Income', 'Licensing Fees', 40201060, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Service Income', 'Supervision and Regulation Enforcement Fees', 40201070, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Service Income', 'Spectrum Usage Fees', 40201080, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Service Income', 'Legal Fees', 40201090, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Service Income', 'Inspection Fees', 40201100, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Service Income', 'Verification and Authentication Fees', 40201110, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Service Income', 'Passport and Visa Fees', 40201120, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Service Income', 'Processing Fees', 40201130, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Service Income', 'Fines and Penalties - Service Income', 40201140, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Service Income', 'Other Service Income', 40201990, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', '', 40202000, '', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'School Fees', 40202010, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Affiliation Fees', 40202020, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Examination Fees', 40202030, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Seminar/Training Fees', 40202040, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Rent/Lease Income', 40202050, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Communication Network Fees', 40202060, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Transportation System Fees', 40202070, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Road Network Fees', 40202080, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Waterworks System Fees', 40202090, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Power Supply System Fees', 40202100, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Seaport System Fees', 40202110, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Landing and Parking Fees', 40202120, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Income from Hostels/Dormitories and Other Like Facilities', 40202130, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Slaughterhouse Operation', 40202140, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Income from Printing and Publication', 40202150, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Sales Revenue', 40202160, 'Active', '1', 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Sales Discounts', 40202161, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Hospital Fees', 40202170, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Guarantee Income', 40202180, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Fidelity Insurance Income', 40202190, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Dividend Income', 40202200, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Interest Income', 40202210, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Share in the Profit of Joint Venture', 40202220, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Fines and Penalties - Business Income', 40202230, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Service Concession Revenue', 40202240, 'Active', NULL, 'no'),
('Income', 'Service and Business Income', 'Business Income', 'Other Business Income', 40202990, 'Active', NULL, 'no'),
('Income', 'Assistance and Subsidy', '', '', 40300000, '', NULL, 'no'),
('Income', 'Assistance and Subsidy', 'Assistance and Subsidy', '', 40301000, '', NULL, 'no'),
('Income', 'Assistance and Subsidy', 'Assistance and Subsidy', 'Subsidy from National Government', 40301010, 'Active', NULL, 'no'),
('Income', 'Assistance and Subsidy', 'Assistance and Subsidy', 'Subsidy from Other National Government Agencies', 40301020, 'Active', NULL, 'no'),
('Income', 'Assistance and Subsidy', 'Assistance and Subsidy', 'Assistance from Local Government Units', 40301030, 'Active', NULL, 'no'),
('Income', 'Assistance and Subsidy', 'Assistance and Subsidy', 'Assistance from Government-Owned and/or Controlled Corporations', 40301040, 'Active', NULL, 'no'),
('Income', 'Assistance and Subsidy', 'Assistance and Subsidy', 'Subsidy from Other Funds', 40301050, 'Active', NULL, 'no'),
('Income', 'Shares, Grants and Donations', '', '', 40400000, '', NULL, 'no'),
('Income', 'Shares, Grants and Donations', 'Shares', '', 40401000, '', NULL, 'no'),
('Income', 'Shares, Grants and Donations', 'Shares', 'Share from National Wealth', 40401010, 'Active', NULL, 'no'),
('Income', 'Shares, Grants and Donations', 'Shares', 'Share from PAGCOR/PCSO', 40401020, 'Active', NULL, 'no'),
('Income', 'Shares, Grants and Donations', 'Shares', 'Share from Earnings of GOCCs', 40401030, 'Active', NULL, 'no'),
('Income', 'Shares, Grants and Donations', 'Grants and Donations', '', 40402000, '', NULL, 'no'),
('Income', 'Shares, Grants and Donations', 'Grants and Donations', 'Income from Grants and Donations in Cash', 40402010, 'Active', NULL, 'no'),
('Income', 'Donations', 'Donations', 'Income from Donations in Kind', 40402020, 'Active', '1', 'no'),
('Income', 'Gains', '', '', 40500000, '', NULL, 'no'),
('Income', 'Gains', 'Gains', '', 40501000, '', NULL, 'no'),
('Income', 'Gains', 'Gains', 'Gain on Foreign Exchange (FOREX)', 40501010, 'Active', NULL, 'no'),
('Income', 'Gains', 'Gains', 'Gain on Sale of Investments', 40501020, 'Active', NULL, 'no'),
('Income', 'Gains', 'Gains', 'Gain on Sale of Investment Property', 40501030, 'Active', NULL, 'no'),
('Income', 'Gains', 'Gains', 'Gain on Sale of Property, Plant and Equipment', 40501040, 'Active', '1', 'no'),
('Income', 'Gains', 'Gains', 'Gain on Initial Recognition of Biological Assets', 40501050, 'Active', NULL, 'no'),
('Income', 'Gains', 'Gains', 'Gain on Sale of Biological Assets', 40501060, 'Active', NULL, 'no'),
('Income', 'Gains', 'Gains', 'Gain from Changes in Fair Value Less Cost to Sell of Biological Assets Due to Physical Change', 40501070, 'Active', NULL, 'no'),
('Income', 'Gains', 'Gains', 'Gain from Changes in Fair Value Less Cost to Sell of Biological Assets Due to Price Change', 40501080, 'Active', NULL, 'no'),
('Income', 'Gains', 'Gains', 'Gain on Sale of Agricultural Produce', 40501090, 'Active', NULL, 'no'),
('Income', 'Gains', 'Gains', 'Gain on Sale of Intangible Assets', 40501100, 'Active', NULL, 'no'),
('Income', 'Gains', 'Gains', 'Other Gains', 40501990, 'Active', NULL, 'no'),
('Income', 'Other Non-Operating Income', '', '', 40600000, '', NULL, 'no'),
('Income', 'Other Non-Operating Income', 'Sale of Assets', '', 40601000, '', NULL, 'no'),
('Income', 'Other Non-Operating Income', 'Sale of Assets', 'Sale of Garnished/Confiscated/Abandoned/Seized Goods and Properties', 40601010, 'Active', NULL, 'no'),
('Income', 'Other Non-Operating Income', 'Sale of Assets', 'Sale of Unserviceable Property', 40601020, 'Active', NULL, 'no'),
('Income', 'Other Non-Operating Income', 'Reversal of Impairment Loss', 'Reversal of Impairment Loss', 40602010, 'Active', NULL, 'no'),
('Income', 'Other Non-Operating Income', 'Miscellaneous Income', '', 40609000, '', NULL, 'no'),
('Income', 'Other Non-Operating Income', 'Miscellaneous Income', 'Proceeds from Insurance/Indemnities', 40609010, 'Active', NULL, 'no'),
('Income', 'Other Non-Operating Income', 'Miscellaneous Income', 'Miscellaneous Income', 40609990, 'Active', NULL, 'no'),
('Expenses', '', '', '', 50000000, '', NULL, 'no'),
('Expenses', '', 'Purchases', 'Purchases - Fruits & Vegetables', 50000001, 'Active', '1', 'no'),
('Expenses', 'Personnel Services', '', '', 50100000, '', NULL, 'no'),
('Expenses', 'Personnel Services', 'Salaries and Wages', '', 50101000, '', NULL, 'no'),
('Expenses', 'Personnel Services', 'Salaries and Wages', 'Salaries and Wages', 50101010, 'Active', '1', 'no'),
('Expenses', 'Personnel Services', 'Salaries and Wages', 'Salaries and Wages - Casual/Contractual', 50101020, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Compensation', '', 50102000, '', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Compensation', 'Personal Economic Relief Allowance (PERA)', 50102010, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Compensation', 'Representation Allowance (RA)', 50102020, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Compensation', 'Transportation Allowance (TA)', 50102030, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Compensation', 'Clothing/Uniform Allowance', 50102040, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Compensation', 'Subsistence Allowance (SA)', 50102050, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Compensation', 'Laundry Allowance ( LA )', 50102060, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Compensation', 'Quarters Allowance ( QA )', 50102070, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Compensation', 'Productivity Incentive Allowance (PIA)', 50102080, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Compensation', 'Overseas Allowance (OA)', 50102090, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Compensation', 'Honoraria', 50102100, 'Active', '1', 'no'),
('Expenses', 'Personnel Services', 'Other Compensation', 'Hazard Pay ( HP )', 50102110, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Compensation', 'Longevity Pay ( LP )', 50102120, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Compensation', 'Overtime and Night Pay', 50102130, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Compensation', 'Year End Bonus', 50102140, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Compensation', 'Cash Gift', 50102150, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Compensation', 'Other Bonuses and Allowances', 50102990, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Personnel Benefit Contributions', '', 50103000, '', NULL, 'no'),
('Expenses', 'Personnel Services', 'Personnel Benefit Contributions', 'Retirement and Life Insurance Premiums', 50103010, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Personnel Benefit Contributions', 'Pag-IBIG Contributions ', 50103020, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Personnel Benefit Contributions', 'PhilHealth Contributions', 50103030, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Personnel Benefit Contributions', 'Employees Compensation Insurance Premiums (ECIP)', 50103040, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Personnel Benefit Contributions', 'Provident/Welfare Fund Contributions', 50103050, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Personnel Benefits', '', 50104000, '', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Personnel Benefits', 'Pension Benefits', 50104010, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Personnel Benefits', 'Retirement Gratuity', 50104020, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Personnel Benefits', 'Terminal Leave Benefits ', 50104030, 'Active', NULL, 'no'),
('Expenses', 'Personnel Services', 'Other Personnel Benefits', 'Other Personnel Benefits', 50104990, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', '', '', 50200000, '', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Traveling Expenses', '', 50201000, '', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Traveling Expenses', 'Traveling Expenses - Local', 50201010, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Traveling Expenses', 'Traveling Expenses - Foreign', 50201020, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Training and Scholarship Expenses', '', 50202000, '', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Training and Scholarship Expenses', 'Training Expenses', 50202010, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Training and Scholarship Expenses', 'Scholarship Grants/Expenses', 50202020, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Supplies and Materials Expenses', '', 50203000, '', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Supplies and Materials Expenses', 'Office Supplies Expenses', 50203010, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Supplies and Materials Expenses', 'Accountable Forms Expenses', 50203020, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Supplies and Materials Expenses', 'Non-Accountable Forms Expenses', 50203030, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Supplies and Materials Expenses', 'Animal/Zoological Supplies Expenses', 50203040, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Supplies and Materials Expenses', 'Food Supplies Expenses', 50203050, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Supplies and Materials Expenses', 'Welfare Goods Expense', 50203060, 'Active', '1', 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Supplies and Materials Expenses', 'Drugs and Medicines Expenses', 50203070, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Supplies and Materials Expenses', 'Medical, Dental and Laboratory Supplies Expenses', 50203080, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Supplies and Materials Expenses', 'Fuel, Oil and Lubricants Expenses', 50203090, 'Active', '1', 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Supplies and Materials Expenses', 'Agricultural and Marine Supplies Expenses', 50203100, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Supplies and Materials Expenses', 'Textbooks and Instructional Materials Expenses', 50203110, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Supplies and Materials Expenses', 'Military, Police and Traffic Supplies Expenses', 50203120, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Supplies and Materials Expenses', 'Chemical and Filtering Supplies Expenses', 50203130, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Supplies and Materials Expenses', 'Semi-Expendable Machinery and Equipment Expenses', 50203210, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Supplies and Materials Expenses', 'Semi-Expendable Furniture, Fixtures and Books Expenses', 50203220, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Supplies and Materials Expenses', 'Other Supplies and Materials Expenses', 50203990, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Supplies and Materials Expenses', 'Salt Expense', 50203991, 'Active', '1', 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Utility Expenses', '', 50204000, '', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Utility Expenses', 'Water Expenses', 50204010, 'Active', '1', 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Utility Expenses', 'Electricity Expenses', 50204020, 'Active', '1', 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Utility Expenses', 'Gas/Heating Expenses', 50204030, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Communication Expenses', '', 50205000, '', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Communication Expenses', 'Postage and Courier Services', 50205010, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Communication Expenses', 'Telephone Expenses', 50205020, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Communication Expenses', 'Internet Subscription Expenses', 50205030, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Communication Expenses', 'Cable, Satellite, Telegraph and Radio Expenses', 50205040, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Awards/Rewards and Prizes', '', 50206000, '', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Awards/Rewards and Prizes', 'Awards/Rewards Expenses', 50206010, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Awards/Rewards and Prizes', 'Prizes', 50206020, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Survey, Research, Exploration and Development Expenses', '', 50207000, '', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Survey, Research, Exploration and Development Expenses', 'Survey Expenses', 50207010, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Survey, Research, Exploration and Development Expenses', 'Research, Exploration and Development Expenses', 50207020, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Demolition/Relocation and Desilting/Dredging Expenses', '', 50208000, '', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Demolition/Relocation and Desilting/Dredging Expenses', 'Demolition and Relocation Expenses', 50208010, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Demolition/Relocation and Desilting/Dredging Expenses', 'Desilting and Dredging Expenses', 50208020, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Generation, Transmission and Distribution Expenses', 'Generation, Transmission and Distribution Expenses', 50209010, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Confidential, Intelligence and Extraordinary Expenses', '', 50210000, '', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Confidential, Intelligence and Extraordinary Expenses', 'Confidential Expenses', 50210010, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Confidential, Intelligence and Extraordinary Expenses', 'Intelligence Expenses', 50210020, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Confidential, Intelligence and Extraordinary Expenses', 'Extraordinary and Miscellaneous Expenses', 50210030, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Professional Services', '', 50211000, '', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Professional Services', 'Legal Services ', 50211010, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Professional Services', 'Auditing Services', 50211020, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Professional Services', 'Consultancy Services', 50211030, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Professional Services', 'Other Professional Services', 50211990, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'General Services', '', 50212000, '', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'General Services', 'Environment/Sanitary Services', 50212010, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'General Services', 'Janitorial Services', 50212020, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'General Services', 'Security Services', 50212030, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'General Services', 'Other General Services', 50212990, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Repairs and Maintenance', '', 50213000, '', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Repairs and Maintenance', 'Repairs and Maintenance - Investment Property', 50213010, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Repairs and Maintenance', 'Repairs and Maintenance - Land Improvements', 50213020, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Repairs and Maintenance', 'Repairs and Maintenance - Infrastructure Assets', 50213030, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Repairs and Maintenance', 'Repairs and Maintenance - Buildings and Other Structures', 50213040, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Repairs and Maintenance', 'Repairs and Maintenance - Machinery and Equipment', 50213050, 'Active', '1', 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Repairs and Maintenance', 'Repairs and Maintenance - Transportation Equipment', 50213060, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Repairs and Maintenance', 'Repairs and Maintenance - Furniture and Fixtures', 50213070, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Repairs and Maintenance', 'Repairs and Maintenance - Leased Assets', 50213080, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Repairs and Maintenance', 'Repairs and Maintenance - Leased Assets Improvements', 50213090, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Repairs and Maintenance', 'Restoration and Maintenance - Heritage Assets', 50213100, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Repairs and Maintenance', 'Repairs and Maintenance - Semi-Expendable Machinery and Equipment', 50213210, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Repairs and Maintenance', 'Repairs and Maintenance - Semi-Expendable Furniture, Fixtures and Books', 50213220, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Repairs and Maintenance', 'Repairs and Maintenance - Other Property, Plant and Equipment', 50213990, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Financial Assistance/Subsidy', '', 50214000, '', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Financial Assistance/Subsidy', 'Subsidy to NGAs', 50214010, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Financial Assistance/Subsidy', 'Financial Assistance to NGAs', 50214020, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Financial Assistance/Subsidy', 'Financial Assistance to Local Government Units', 50214030, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Financial Assistance/Subsidy', 'Budgetary Support to Government-Owned and/or Controlled Corporations', 50214040, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Financial Assistance/Subsidy', 'Financial Assistance to NGOs/POs', 50214050, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Financial Assistance/Subsidy', 'Internal Revenue Allotment', 50214060, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Financial Assistance/Subsidy', 'Subsidy to Regional Offices/Staff Bureaus', 50214070, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Financial Assistance/Subsidy', 'Subsidy to Operating Units', 50214080, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Financial Assistance/Subsidy', 'Subsidy to Other Funds', 50214090, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Financial Assistance/Subsidy', 'Subsidies - Others', 50214990, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Taxes, Insurance Premiums and Other Fees', '', 50215000, '', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Taxes, Insurance Premiums and Other Fees', 'Taxes, Duties and Licenses', 50215010, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Taxes, Insurance Premiums and Other Fees', 'Fidelity Bond Premiums', 50215020, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Taxes, Insurance Premiums and Other Fees', 'Insurance Expenses', 50215030, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Labor and Wages', 'Labor and Wages', 50216010, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Other Maintenance and Operating Expenses', '', 50299000, '', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Other Maintenance and Operating Expenses', 'Advertising Expenses', 50299010, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Other Maintenance and Operating Expenses', 'Printing and Publication Expenses', 50299020, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Other Maintenance and Operating Expenses', 'Representation Expenses', 50299030, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Other Maintenance and Operating Expenses', 'Transportation and Delivery Expenses', 50299040, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Other Maintenance and Operating Expenses', 'Rent/Lease Expenses', 50299050, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Other Maintenance and Operating Expenses', 'Membership Dues and Contributions to Organizations', 50299060, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Other Maintenance and Operating Expenses', 'Subscription Expenses', 50299070, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Other Maintenance and Operating Expenses', 'Donations', 50299080, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Other Maintenance and Operating Expenses', 'Litigation/Acquired Assets Expenses', 50299090, 'Active', NULL, 'no'),
('Expenses', 'Maintenance and Other Operating Expenses', 'Other Maintenance and Operating Expenses', 'Other Maintenance and Operating Expenses', 50299990, 'Active', NULL, 'no'),
('Expenses', 'Financial Expenses', '', '', 50300000, '', NULL, 'no'),
('Expenses', 'Financial Expenses', 'Financial Expenses', '', 50301000, '', NULL, 'no'),
('Expenses', 'Financial Expenses', 'Financial Expenses', 'Management Supervision/Trusteeship Fees', 50301010, 'Active', NULL, 'no'),
('Expenses', 'Financial Expenses', 'Financial Expenses', 'Interest Expenses', 50301020, 'Active', NULL, 'no'),
('Expenses', 'Financial Expenses', 'Financial Expenses', 'Guarantee Fees', 50301030, 'Active', NULL, 'no'),
('Expenses', 'Financial Expenses', 'Financial Expenses', 'Bank Charges', 50301040, 'Active', NULL, 'no'),
('Expenses', 'Financial Expenses', 'Financial Expenses', 'Commitment Fees', 50301050, 'Active', NULL, 'no'),
('Expenses', 'Financial Expenses', 'Financial Expenses', 'Other Financial Charges', 50301990, 'Active', NULL, 'no'),
('Expenses', 'Direct Costs', '', '', 50400000, '', NULL, 'no'),
('Expenses', 'Direct Costs', 'Cost of Goods Manufactured', '', 50401000, '', NULL, 'no'),
('Expenses', 'Direct Costs', 'Cost of Goods Manufactured', 'Direct Labor', 50401010, 'Active', NULL, 'no'),
('Expenses', 'Direct Costs', 'Cost of Goods Manufactured', 'Manufacturing Overhead', 50401020, 'Active', NULL, 'no'),
('Expenses', 'Direct Costs', 'Cost of Sales', 'Cost of Sales', 50402010, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', '', '', 50500000, '', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Depreciation', '', 50501000, '', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Depreciation', 'Depreciation - Investment Property', 50501010, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Depreciation', 'Depreciation - Land Improvements', 50501020, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Depreciation', 'Depreciation - Infrastructure Assets', 50501030, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Depreciation', 'Depreciation - Buildings and Other Structures', 50501040, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Depreciation', 'Depreciation - Machinery and Equipment', 50501050, 'Active', '1', 'no'),
('Expenses', 'Non-Cash Expenses', 'Depreciation', 'Depreciation - Transportation Equipment', 50501060, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Depreciation', 'Depreciation - Furniture, Fixtures and Books', 50501070, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Depreciation', 'Depreciation - Leased Assets', 50501080, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Depreciation', 'Depreciation - Leased Assets Improvements', 50501090, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Depreciation', 'Depreciation - Heritage Assets', 50501100, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Depreciation', 'Depreciation - Service Concession Asset', 50501110, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Depreciation', 'Depreciation - Other Property, Plant and Equipment', 50501990, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Amortization', '', 50502000, '', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Amortization', 'Amortization - Intangible Assets', 50502010, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Impairment Loss', '', 50503000, '', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Impairment Loss', 'Impairment Loss - Financial Assets Held to Maturity', 50503010, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Impairment Loss', 'Impairment Loss - Loans and Receivables', 50503020, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Impairment Loss', 'Impairment Loss - Lease Receivables', 50503030, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Impairment Loss', 'Impairment Loss - Investments in GOCCs', 50503040, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Impairment Loss', 'Impairment Loss - Investment in Joint Venture', 50503050, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Impairment Loss', 'Impairment Loss - Other Receivables', 50503060, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Impairment Loss', 'Impairment Loss - Inventories', 50503070, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Impairment Loss', 'Impairment Loss - Investment Property', 50503080, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Impairment Loss', 'Impairment Loss - Property, Plant and Equipment', 50503090, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Impairment Loss', 'Impairment Loss - Biological Assets', 50503100, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Impairment Loss', 'Impairment Loss - Intangible Assets', 50503110, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Impairment Loss', 'Impairment Loss - Investments in Associates', 50503120, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Impairment Loss', 'Impairment Loss - Other Assets', 50503139, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Losses', '', 50504000, '', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Losses', 'Loss on Foreign Exchange (FOREX)', 50504010, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Losses', 'Loss on Sale of Investments', 50504020, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Losses', 'Loss on Sale of Investment Property', 50504030, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Losses', 'Loss on Sale of Property, Plant and Equipment', 50504040, 'Active', '1', 'no'),
('Expenses', 'Non-Cash Expenses', 'Losses', 'Loss on Sale of Biological Assets', 50504050, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Losses', 'Loss on Sale of Agricultural Produce ', 50504060, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Losses', 'Loss on Sale of Intangible Assets', 50504070, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Losses', 'Loss on Sale of Assets', 50504080, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Losses', 'Loss of Assets', 50504090, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Losses', 'Loss on Guaranty', 50504100, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Losses', 'Loss on Initial Recognition of Biological Assets', 50504110, 'Active', NULL, 'no'),
('Expenses', 'Non-Cash Expenses', 'Losses', 'Other Losses', 50504990, 'Active', NULL, 'no'),
('Expenses', 'Capital Outlays', '', '', 50600000, '', NULL, 'no');
INSERT INTO `uacs` (`classification`, `sub_class`, `grouping`, `object_code`, `uacs`, `status`, `isEnabled`, `payment_account`) VALUES
('Expenses', 'Capital Outlays', 'Investment Outlay', '', 50601000, '', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Investment Outlay', 'Investment in Government-Owned and/or Controlled Corporations', 50601010, 'Active', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Investment Outlay', 'Investment in Associates', 50601020, 'Active', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Loans Outlay', '', 50602000, '', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Loans Outlay', 'Loans Outlay - Government-Owned and/or Controlled Corporations', 50602010, 'Active', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Loans Outlay', 'Loans Outlay - Local Government Units', 50602020, 'Active', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Loans Outlay', 'Loans Outlay - Others', 50602990, 'Active', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Investment Property Outlay', '', 50603000, '', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Investment Property Outlay', 'Land and Buildings Outlay', 50603010, 'Active', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Property, Plant and Equipment Outlay', '', 50604000, '', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Property, Plant and Equipment Outlay', 'Land Outlay', 50604010, 'Active', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Property, Plant and Equipment Outlay', 'Land Improvements Outlay', 50604020, 'Active', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Property, Plant and Equipment Outlay', 'Infrastructure Outlay', 50604030, 'Active', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Property, Plant and Equipment Outlay', 'Buildings and Other Structures', 50604040, 'Active', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Property, Plant and Equipment Outlay', 'Machinery and Equipment Outlay', 50604050, 'Active', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Property, Plant and Equipment Outlay', 'Transportation Equipment Outlay', 50604060, 'Active', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Property, Plant and Equipment Outlay', 'Furniture, Fixtures and Books Outlay', 50604070, 'Active', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Property, Plant and Equipment Outlay', 'Heritage Assets', 50604080, 'Active', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Property, Plant and Equipment Outlay', 'Other Property Plant and Equipment Outlay', 50604090, 'Active', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Biological Assets Outlay', '', 50605000, '', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Biological Assets Outlay', 'Bearer Biological Assets Outlay', 50605010, 'Active', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Intangible Assets Outlay', '', 50606000, '', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Intangible Assets Outlay', 'Patents/Copyrights', 50606010, 'Active', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Intangible Assets Outlay', 'Computer Software', 50606020, 'Active', NULL, 'no'),
('Expenses', 'Capital Outlays', 'Intangible Assets Outlay', 'Other Intangible Assets', 50606990, 'Active', NULL, 'no');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`, `employee_id`) VALUES
(1, 'admin', 'yqp8OAKFWgco5DUGnvyujWdTyX0S0sxb', '$2y$13$FK0B9JHNgtRY1/BdkqjbyucypacTEvqH.i7H6heeANfdkzfty2KAq', NULL, 'jundeybrier@gmail.com', 10, 1525683174, 1551407132, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts_payable_invoice`
--
ALTER TABLE `accounts_payable_invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier` (`supplier`),
  ADD KEY `type_of_expense` (`type_of_expense`),
  ADD KEY `jev` (`jev`);

--
-- Indexes for table `accounts_payable_payment`
--
ALTER TABLE `accounts_payable_payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ap_id` (`ap_id`),
  ADD KEY `dv_number` (`dv_number`),
  ADD KEY `pcv_number` (`pcv_number`);

--
-- Indexes for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Indexes for table `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `capitals`
--
ALTER TABLE `capitals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `membersId` (`membersId`),
  ADD KEY `jev` (`jev`);

--
-- Indexes for table `cash_dec`
--
ALTER TABLE `cash_dec`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `date_posted` (`date_posted`);

--
-- Indexes for table `cash_dec_det`
--
ALTER TABLE `cash_dec_det`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_dec` (`cash_dec`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lastname` (`lastname`,`firstname`);

--
-- Indexes for table `dv_tracking`
--
ALTER TABLE `dv_tracking`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dv_number` (`dv_number`),
  ADD KEY `payee` (`payee`),
  ADD KEY `jev` (`jev`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lastname` (`lastname`,`firstname`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `supplier` (`supplier`),
  ADD KEY `jev` (`jev`),
  ADD KEY `dv` (`dv`);

--
-- Indexes for table `expenses_payments`
--
ALTER TABLE `expenses_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expense_id` (`expense_id`);

--
-- Indexes for table `expenses_payment_payable`
--
ALTER TABLE `expenses_payment_payable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expense_id` (`expense_id`);

--
-- Indexes for table `expense_types`
--
ALTER TABLE `expense_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_loss`
--
ALTER TABLE `inventory_loss`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pID` (`pID`);

--
-- Indexes for table `jev_entries`
--
ALTER TABLE `jev_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jev` (`jev`),
  ADD KEY `accounting_code` (`accounting_code`);

--
-- Indexes for table `jev_tracking`
--
ALTER TABLE `jev_tracking`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jev` (`jev`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `monthly_dues`
--
ALTER TABLE `monthly_dues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mID` (`mID`),
  ADD KEY `jev` (`jev`);

--
-- Indexes for table `officers`
--
ALTER TABLE `officers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pID` (`pID`),
  ADD KEY `mID` (`mID`);

--
-- Indexes for table `officers_positions`
--
ALTER TABLE `officers_positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `or_ar_tracking`
--
ALTER TABLE `or_ar_tracking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments_receivable`
--
ALTER TABLE `payments_receivable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_id` (`sales_id`),
  ADD KEY `orNo` (`orNo`),
  ADD KEY `jev` (`jev`);

--
-- Indexes for table `payment_voucher`
--
ALTER TABLE `payment_voucher`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_id` (`emp_id`),
  ADD KEY `dv` (`dv`),
  ADD KEY `jev` (`jev`),
  ADD KEY `pcv` (`pcv`);

--
-- Indexes for table `payroll_deductions`
--
ALTER TABLE `payroll_deductions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pID` (`pID`),
  ADD KEY `dID` (`dID`);

--
-- Indexes for table `payroll_deductions_honorarium`
--
ALTER TABLE `payroll_deductions_honorarium`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pID` (`pID`),
  ADD KEY `dID` (`dID`);

--
-- Indexes for table `payroll_dividends`
--
ALTER TABLE `payroll_dividends`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_id` (`emp_id`),
  ADD KEY `dv` (`dv`),
  ADD KEY `jev` (`jev`),
  ADD KEY `pcv` (`pcv`),
  ADD KEY `dividends_payable_jev` (`dividends_payable_jev`);

--
-- Indexes for table `payroll_honorarium`
--
ALTER TABLE `payroll_honorarium`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_id` (`emp_id`),
  ADD KEY `dv` (`dv`),
  ADD KEY `jev` (`jev`),
  ADD KEY `pcv` (`pcv`);

--
-- Indexes for table `payslip_deduction_types`
--
ALTER TABLE `payslip_deduction_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pcv_tracking`
--
ALTER TABLE `pcv_tracking`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pcv_number` (`pcv_number`),
  ADD KEY `payee` (`payee`),
  ADD KEY `jev` (`jev`),
  ADD KEY `debit` (`debit`),
  ADD KEY `credit` (`credit`);

--
-- Indexes for table `petty_cash`
--
ALTER TABLE `petty_cash`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `supplier` (`supplier`),
  ADD KEY `jev` (`jev`),
  ADD KEY `dv` (`dv`);

--
-- Indexes for table `ppe`
--
ALTER TABLE `ppe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uacs` (`uacs`),
  ADD KEY `jev` (`jev`);

--
-- Indexes for table `ppe_depreciation`
--
ALTER TABLE `ppe_depreciation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ppeID` (`ppeID`),
  ADD KEY `jev1` (`jev1`),
  ADD KEY `jev2` (`jev2`);

--
-- Indexes for table `pricelist`
--
ALTER TABLE `pricelist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pId` (`pId`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `product_name` (`product_name`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salesAccount` (`salesAccount`),
  ADD KEY `purchaseAccount` (`purchaseAccount`);

--
-- Indexes for table `revenues`
--
ALTER TABLE `revenues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `revenue_payments_receivable`
--
ALTER TABLE `revenue_payments_receivable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `sales_id` (`revenue_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `jev` (`jev`),
  ADD KEY `orNo` (`orNo`),
  ADD KEY `jev_2` (`jev`);

--
-- Indexes for table `sales_details`
--
ALTER TABLE `sales_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `sales_id` (`sales_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD UNIQUE KEY `attribute` (`attribute`);

--
-- Indexes for table `stock_card`
--
ALTER TABLE `stock_card`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sku` (`sku`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uacs`
--
ALTER TABLE `uacs`
  ADD PRIMARY KEY (`uacs`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `employee_id` (`employee_id`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`),
  ADD KEY `employee_id_2` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts_payable_invoice`
--
ALTER TABLE `accounts_payable_invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `accounts_payable_payment`
--
ALTER TABLE `accounts_payable_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `capitals`
--
ALTER TABLE `capitals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cash_dec`
--
ALTER TABLE `cash_dec`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cash_dec_det`
--
ALTER TABLE `cash_dec_det`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `dv_tracking`
--
ALTER TABLE `dv_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `expenses_payments`
--
ALTER TABLE `expenses_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `expenses_payment_payable`
--
ALTER TABLE `expenses_payment_payable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `expense_types`
--
ALTER TABLE `expense_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inventory_loss`
--
ALTER TABLE `inventory_loss`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jev_entries`
--
ALTER TABLE `jev_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jev_tracking`
--
ALTER TABLE `jev_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `monthly_dues`
--
ALTER TABLE `monthly_dues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `officers`
--
ALTER TABLE `officers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `officers_positions`
--
ALTER TABLE `officers_positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `or_ar_tracking`
--
ALTER TABLE `or_ar_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payments_receivable`
--
ALTER TABLE `payments_receivable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_voucher`
--
ALTER TABLE `payment_voucher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payroll_deductions`
--
ALTER TABLE `payroll_deductions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payroll_deductions_honorarium`
--
ALTER TABLE `payroll_deductions_honorarium`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payroll_dividends`
--
ALTER TABLE `payroll_dividends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payroll_honorarium`
--
ALTER TABLE `payroll_honorarium`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payslip_deduction_types`
--
ALTER TABLE `payslip_deduction_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `pcv_tracking`
--
ALTER TABLE `pcv_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `petty_cash`
--
ALTER TABLE `petty_cash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ppe`
--
ALTER TABLE `ppe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ppe_depreciation`
--
ALTER TABLE `ppe_depreciation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pricelist`
--
ALTER TABLE `pricelist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `revenues`
--
ALTER TABLE `revenues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `revenue_payments_receivable`
--
ALTER TABLE `revenue_payments_receivable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sales_details`
--
ALTER TABLE `sales_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stock_card`
--
ALTER TABLE `stock_card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts_payable_invoice`
--
ALTER TABLE `accounts_payable_invoice`
  ADD CONSTRAINT `accounts_payable_invoice_ibfk_1` FOREIGN KEY (`type_of_expense`) REFERENCES `uacs` (`uacs`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `accounts_payable_invoice_ibfk_2` FOREIGN KEY (`supplier`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `accounts_payable_invoice_ibfk_3` FOREIGN KEY (`jev`) REFERENCES `jev_tracking` (`jev`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `accounts_payable_payment`
--
ALTER TABLE `accounts_payable_payment`
  ADD CONSTRAINT `accounts_payable_payment_ibfk_1` FOREIGN KEY (`ap_id`) REFERENCES `accounts_payable_invoice` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `accounts_payable_payment_ibfk_4` FOREIGN KEY (`dv_number`) REFERENCES `dv_tracking` (`dv_number`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `accounts_payable_payment_ibfk_5` FOREIGN KEY (`pcv_number`) REFERENCES `pcv_tracking` (`pcv_number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_assignment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `capitals`
--
ALTER TABLE `capitals`
  ADD CONSTRAINT `capitals_ibfk_1` FOREIGN KEY (`membersId`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `capitals_ibfk_3` FOREIGN KEY (`jev`) REFERENCES `jev_tracking` (`jev`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cash_dec_det`
--
ALTER TABLE `cash_dec_det`
  ADD CONSTRAINT `cash_dec_det_ibfk_1` FOREIGN KEY (`cash_dec`) REFERENCES `cash_dec` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dv_tracking`
--
ALTER TABLE `dv_tracking`
  ADD CONSTRAINT `dv_tracking_ibfk_1` FOREIGN KEY (`jev`) REFERENCES `jev_tracking` (`jev`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`type`) REFERENCES `uacs` (`uacs`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `expenses_ibfk_2` FOREIGN KEY (`supplier`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `expenses_ibfk_3` FOREIGN KEY (`jev`) REFERENCES `jev_tracking` (`jev`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `expenses_ibfk_4` FOREIGN KEY (`dv`) REFERENCES `dv_tracking` (`dv_number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `expenses_payments`
--
ALTER TABLE `expenses_payments`
  ADD CONSTRAINT `expenses_payments_ibfk_1` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `expenses_payment_payable`
--
ALTER TABLE `expenses_payment_payable`
  ADD CONSTRAINT `expenses_payment_payable_ibfk_1` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inventory_loss`
--
ALTER TABLE `inventory_loss`
  ADD CONSTRAINT `inventory_loss_ibfk_1` FOREIGN KEY (`pID`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jev_entries`
--
ALTER TABLE `jev_entries`
  ADD CONSTRAINT `jev_entries_ibfk_1` FOREIGN KEY (`jev`) REFERENCES `jev_tracking` (`jev`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jev_entries_ibfk_2` FOREIGN KEY (`accounting_code`) REFERENCES `uacs` (`uacs`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `monthly_dues`
--
ALTER TABLE `monthly_dues`
  ADD CONSTRAINT `monthly_dues_ibfk_1` FOREIGN KEY (`mID`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `monthly_dues_ibfk_2` FOREIGN KEY (`jev`) REFERENCES `jev_tracking` (`jev`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `officers`
--
ALTER TABLE `officers`
  ADD CONSTRAINT `officers_ibfk_1` FOREIGN KEY (`pID`) REFERENCES `officers_positions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `officers_ibfk_2` FOREIGN KEY (`mID`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments_receivable`
--
ALTER TABLE `payments_receivable`
  ADD CONSTRAINT `payments_receivable_ibfk_1` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_receivable_ibfk_2` FOREIGN KEY (`jev`) REFERENCES `jev_tracking` (`jev`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_receivable_ibfk_3` FOREIGN KEY (`orNo`) REFERENCES `or_ar_tracking` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `payroll_ibfk_1` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payroll_ibfk_2` FOREIGN KEY (`dv`) REFERENCES `dv_tracking` (`dv_number`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `payroll_ibfk_3` FOREIGN KEY (`jev`) REFERENCES `jev_tracking` (`jev`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `payroll_ibfk_4` FOREIGN KEY (`pcv`) REFERENCES `pcv_tracking` (`pcv_number`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `payroll_deductions`
--
ALTER TABLE `payroll_deductions`
  ADD CONSTRAINT `payroll_deductions_ibfk_1` FOREIGN KEY (`pID`) REFERENCES `payroll` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payroll_deductions_ibfk_2` FOREIGN KEY (`dID`) REFERENCES `payslip_deduction_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payroll_deductions_honorarium`
--
ALTER TABLE `payroll_deductions_honorarium`
  ADD CONSTRAINT `payroll_deductions_honorarium_ibfk_1` FOREIGN KEY (`pID`) REFERENCES `payroll_honorarium` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payroll_deductions_honorarium_ibfk_2` FOREIGN KEY (`dID`) REFERENCES `payslip_deduction_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payroll_dividends`
--
ALTER TABLE `payroll_dividends`
  ADD CONSTRAINT `payroll_dividends_ibfk_1` FOREIGN KEY (`emp_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payroll_dividends_ibfk_2` FOREIGN KEY (`dv`) REFERENCES `dv_tracking` (`dv_number`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `payroll_dividends_ibfk_3` FOREIGN KEY (`pcv`) REFERENCES `pcv_tracking` (`pcv_number`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `payroll_dividends_ibfk_4` FOREIGN KEY (`jev`) REFERENCES `jev_tracking` (`jev`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `payroll_honorarium`
--
ALTER TABLE `payroll_honorarium`
  ADD CONSTRAINT `payroll_honorarium_ibfk_1` FOREIGN KEY (`emp_id`) REFERENCES `officers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payroll_honorarium_ibfk_2` FOREIGN KEY (`dv`) REFERENCES `dv_tracking` (`dv_number`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `payroll_honorarium_ibfk_3` FOREIGN KEY (`jev`) REFERENCES `jev_tracking` (`jev`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `payroll_honorarium_ibfk_4` FOREIGN KEY (`pcv`) REFERENCES `pcv_tracking` (`pcv_number`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pcv_tracking`
--
ALTER TABLE `pcv_tracking`
  ADD CONSTRAINT `pcv_tracking_ibfk_1` FOREIGN KEY (`jev`) REFERENCES `jev_tracking` (`jev`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pcv_tracking_ibfk_2` FOREIGN KEY (`debit`) REFERENCES `uacs` (`uacs`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pcv_tracking_ibfk_3` FOREIGN KEY (`credit`) REFERENCES `uacs` (`uacs`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ppe`
--
ALTER TABLE `ppe`
  ADD CONSTRAINT `ppe_ibfk_1` FOREIGN KEY (`jev`) REFERENCES `jev_tracking` (`jev`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ppe_depreciation`
--
ALTER TABLE `ppe_depreciation`
  ADD CONSTRAINT `ppe_depreciation_ibfk_1` FOREIGN KEY (`ppeID`) REFERENCES `ppe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ppe_depreciation_ibfk_2` FOREIGN KEY (`jev1`) REFERENCES `jev_tracking` (`jev`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ppe_depreciation_ibfk_3` FOREIGN KEY (`jev2`) REFERENCES `jev_tracking` (`jev`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pricelist`
--
ALTER TABLE `pricelist`
  ADD CONSTRAINT `pricelist_ibfk_1` FOREIGN KEY (`pId`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category`) REFERENCES `product_categories` (`id`);

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_ibfk_1` FOREIGN KEY (`salesAccount`) REFERENCES `uacs` (`uacs`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_categories_ibfk_2` FOREIGN KEY (`purchaseAccount`) REFERENCES `uacs` (`uacs`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `revenue_payments_receivable`
--
ALTER TABLE `revenue_payments_receivable`
  ADD CONSTRAINT `revenue_payments_receivable_ibfk_1` FOREIGN KEY (`revenue_id`) REFERENCES `revenues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`jev`) REFERENCES `jev_tracking` (`jev`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sales_ibfk_3` FOREIGN KEY (`orNo`) REFERENCES `or_ar_tracking` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales_details`
--
ALTER TABLE `sales_details`
  ADD CONSTRAINT `sales_details_ibfk_1` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sales_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `stock_card`
--
ALTER TABLE `stock_card`
  ADD CONSTRAINT `stock_card_ibfk_1` FOREIGN KEY (`sku`) REFERENCES `products` (`sku`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
