<?php
require_once dirname(__FILE__) . "/../config.php";
require_once dirname(__FILE__) . "/../helpers/database.php";
$handle = new Database();
$handle->doQuery("
	INSERT INTO `User` (`auth`, `name`, `email`, `createdOn`, `updatedOn`)
	VALUES
		('qwertyuiop', 'Abhishek', 'abc@gmail.com', '2016-06-10 00:08:40', NULL),
		('asdfghjkl', 'User 2', 'user2@gmail.com', '2016-06-10 00:09:20', NULL),
		('zxcvbnm', 'User 3', 'user3@gmail.com', '2016-06-10 00:09:20', NULL),
		('user4', 'User 4', 'user4@gmail.com', '2016-06-10 00:09:20', NULL),
		('user5', 'User 5', 'user5@gmail.com', '2016-06-10 00:09:20', NULL),
		('user6', 'User 6', 'user6@gmail.com', '2016-06-10 00:09:20', NULL)
");
$handle->close();
?>