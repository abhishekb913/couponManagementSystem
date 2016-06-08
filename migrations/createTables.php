<?php
$user = 'root';
$password = 'qwerty';
$server = 'localhost';

$con = new mysqli ($server, $user, $password);
if ($con->connect_error) {
	echo ("Connection failed: " . mysqli_connect_error());
}
else {
	if (mysqli_query($con, "CREATE DATABASE CouponManagementSystem")) {
		echo "DB CouponManagementSystem created";
		mysqli_select_db($con, "CouponManagementSystem");
		$couponQuery = "CREATE TABLE `Coupon` (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`couponCode` varchar(10) DEFAULT NULL,
			`couponType` int(11) DEFAULT NULL,
			`validUpto` timestamp NULL DEFAULT NULL,
			`createdOn` timestamp NULL DEFAULT NULL,
			`updatedOn` timestamp NULL DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		mysqli_query($con, $couponQuery);
		$userQuery = "CREATE TABLE `User` (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`auth` varchar(10) DEFAULT NULL,
			`name` varchar(10) DEFAULT NULL,
			`email` varchar(10) DEFAULT NULL,
			`createdOn` timestamp NULL DEFAULT NULL,
			`updatedOn` timestamp NULL DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		mysqli_query($con, $userQuery);
		$transactionQuery = "CREATE TABLE `CouponTransaction` (
		    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		    `userID` int(11) unsigned NOT NULL,
		    `couponID` int(11) unsigned NOT NULL,
		    `transactionOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		    PRIMARY KEY (`id`),
		    CONSTRAINT `coupon_u` FOREIGN KEY (`couponID`) REFERENCES `Coupon` (`id`),
		    CONSTRAINT `user_u` FOREIGN KEY (`userID`) REFERENCES `User` (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		mysqli_query($con, $transactionQuery);
		$multiUseLog = "CREATE TABLE `MultiUseCouponLog` (
		    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		    `couponID` int(11) unsigned NOT NULL,
		    `redemptionsAllowed` int(11) NOT NULL,
		    `redemptionsLeft` int(11) NOT NULL,
		    PRIMARY KEY (`id`),
		    CONSTRAINT `multi_u` FOREIGN KEY (`couponID`) REFERENCES `Coupon` (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		mysqli_query($con, $multiUseLog);
	}
	else {
		echo mysqli_error($con).PHP_EOL;
	}
}
mysqli_close($con);
?>