<?php

require_once('./DbConnection.php');

// try{
//     $users = "Create table users
//     (
//         UserID int PRIMARY KEY NOT NULL AUTO_INCREMENT,
//         FullName varchar(50),
//         Email varchar(50),
//         Phone varchar(30),
//         Address varchar(50),
//         ProfileImage varchar(255),
//         Role varchar(15),
//         Password varchar(255)
//     )";

//     $connection->exec($users);
//     echo "Users Table Created Successfully!";
// }
// catch(PDOException $error){
//     echo "Error=>".$error->getMessage();
// }

// try{
//     $destinations = "Create table destinations
//     (
//         DestinationID int PRIMARY KEY NOT NULL AUTO_INCREMENT,
//         Destination varchar(50),
//         Image varchar(255)
//     )";

//     $connection->exec($destinations);
//     echo "Destinations Table Created Successfully!";
// }
// catch(PDOException $error){
//     echo "Error=>".$error->getMessage();
// }

// try{
//     $tourGuides = "Create table tour_guides
//     (
//         TourGuideID int PRIMARY KEY NOT NULL AUTO_INCREMENT,
//         GuideName varchar(50),
//         Email varchar(50),
//         Phone varchar(30),
//         Languages text
//     )";

//     $connection->exec($tourGuides);
//     echo "TourGuides Table Created Successfully!";
// }
// catch(PDOException $error){
//     echo "Error=>".$error->getMessage();
// }

// try{
//     $packages = "Create table packages
//     (
//         PackageID int PRIMARY KEY NOT NULL AUTO_INCREMENT,
//         DestinationID int,
//         TourGuideID int,
//         Title text,
//         Description text,
//         Duration varchar(30),
//         Languages text,
//         Size int,
//         Price int,
//         Highlight1 text,
//         Highlight2 text,
//         Highlight3 text,
//         Highlight4 text,
//         IncludedThings text,
//         ExcludedThings text,
//         Info text,
//         Image1 varchar(255),
//         Image2 varchar(255),
//         Image3 varchar(255),
//         Map text,
//         CreatedAt timestamp DEFAULT CURRENT_TIMESTAMP,
//         Active booleam
//     )";

//     $connection->exec($packages);
//     echo "Packages Table Created Successfully!";
// }
// catch(PDOException $error){
//     echo "Error=>".$error->getMessage();
// }

// try{
//     $reviews = "Create table reviews
//     (
//         ReviewID int PRIMARY KEY NOT NULL AUTO_INCREMENT,
//         UserID int,
//         PackageID int,
//         Rating int,
//         Comment text, 
//         CreatedAt timestamp DEFAULT CURRENT_TIMESTAMP,
//         FOREIGN KEY(UserID) REFERENCES users(UserID),
//         FOREIGN KEY(PackageID) REFERENCES packages(PackageID)
//     )";

//     $connection->exec($reviews);
//     echo "Reviews Table Created Successfully!";
// }
// catch(PDOException $error){
//     echo "Error=>".$error->getMessage();
// }

// try{
//     $itineraries = "Create table itineraries
//     (
//         ItineraryID int PRIMARY KEY NOT NULL AUTO_INCREMENT,
//         PackageID int,
//         Name varchar(60),
//         Day int, 
//         Activity text,
//         FOREIGN KEY(PackageID) REFERENCES packages(PackageID)
//     )";

//     $connection->exec($itineraries);
//     echo "Itineraries Table Created Successfully!";
// }
// catch(PDOException $error){
//     echo "Error=>".$error->getMessage();
// }

// try{
//     $availability = "Create table availability
//     (
//         AvailabilityID int PRIMARY KEY NOT NULL AUTO_INCREMENT,
//         PackageID int,
//         StartDate date,
//         EndDate date,
//         Price int,
//         FOREIGN KEY(PackageID) REFERENCES packages(PackageID)
//     )";

//     $connection->exec($availability);
//     echo "Availability Table Created Successfully!";
// }
// catch(PDOException $error){
//     echo "Error=>".$error->getMessage();
// }

// try{
//     $bookings = "Create table bookings
//     (
//         BookingID int PRIMARY KEY NOT NULL AUTO_INCREMENT,
//         UserID int,
//         AvailabilityID int,
//         BookingCode varchar(50),
//         FullName varchar(50),
//         Email varchar(50),
//         Phone varchar(30),
//         DOB date,
//         Gender varchar(10),
//         TotalTraveller int,
//         BookingStatus varchar(20),
//         CreatedAt timestamp DEFAULT CURRENT_TIMESTAMP,
//         FOREIGN KEY(UserID) REFERENCES users(UserID),
//         FOREIGN KEY(AvailabilityID) REFERENCES availability(AvailabilityID)
//     )";

//     $connection->exec($bookings);
//     echo "Bookings Table Created Successfully!";
// }
// catch(PDOException $error){
//     echo "Error=>".$error->getMessage();
// }

// try{
//     $paymentTypes = "Create table payment_types
//     (
//         PaymentTypeID int PRIMARY KEY NOT NULL AUTO_INCREMENT,
//         TypeName varchar(30),
//         AccountName varchar(60),
//         AccountNumber int
//     )";

//     $connection->exec($paymentTypes);
//     echo "PaymentTypes Table Created Successfully!";
// }
// catch(PDOException $error){
//     echo "Error=>".$error->getMessage();
// }

// try{
//     $payments = "Create table payments
//     (
//         PaymentID int PRIMARY KEY NOT NULL AUTO_INCREMENT,
//         BookingID int,
//         PaymentTypeID int,
//         TotalPrice int,
//         Screenshot varchar(255),
//         PaymentStatus varchar(20),
//         CreatedAt timestamp DEFAULT CURRENT_TIMESTAMP,
//         FOREIGN KEY(BookingID) REFERENCES bookings(BookingID),
//         FOREIGN KEY(PaymentTypeID) REFERENCES payment_types(PaymentTypeID)
//     )";

//     $connection->exec($payments);
//     echo "Payments Table Created Successfully!";
// }
// catch(PDOException $error){
//     echo "Error=>".$error->getMessage();
// }

// try{
//     $contacts = "Create table contacts
//     (
//         ContactID int PRIMARY KEY NOT NULL AUTO_INCREMENT,
//         Name varchar(50),
//         Email varchar(60),
//         Message text
//     )";

//     $connection->exec($contacts);
//     echo "Contacts Table Created Successfully!";
// }
// catch(PDOException $error){
//     echo "Error=>".$error->getMessage();
// }
?>