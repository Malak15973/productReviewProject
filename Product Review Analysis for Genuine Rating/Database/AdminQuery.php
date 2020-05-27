<?php
include_once "connect.php";
include_once '../Model/Product.php';
include_once '../Model/category.php';
include_once '../Model/User.php';
$db_connection = Connect::getInstance()->getConnection();
function AddProduct($product)
{
    global $db_connection;
    $serial_number = $product->getSerialNumber();
    $name = $product->getName();
    $rate = $product->getAverageRating();
    $price = $product->getPrice();
    $picture = $product->getPicture();
    $details = $product->getOtherInfo();
    $category_id = $product->getCategoryID();
    $query = "INSERT INTO `product` (`SerialNumber`, `Name`, `Rate`, `Price`, `Picture`, `Details`, `CategoryId`)
        VALUES (?, ?, ?, ?, ?, ?, ?);";

    $prepared_statement = mysqli_prepare($db_connection, $query);
    mysqli_stmt_bind_param($prepared_statement, "isddssi", $serial_number, $name, $rate, $price
        , $picture, $details, $category_id);
    if ($result = mysqli_stmt_execute($prepared_statement)) {
        return 1;
    } else {
        echo $db_connection->error;
        return 0;
    }
}
function EditProduct($categoryId, $serialNumber, $product)
{
    global $db_connection;
    $name = $product->getName();
    $rate = $product->getAverageRating();
    $price = $product->getPrice();
    $picture = $product->getPicture();
    $details = $product->getOtherInfo();
    $query = "UPDATE product set Name='" . $name . "' , Rate='" . $rate . "' , Price='" . $price . "' , Picture='" . $picture . "' , Details='" . $details . "' Where CategoryId='" . $categoryId . "' and SerialNumber='" . $serialNumber . "' ";
    if ($result = $db_connection->query($query)) {
        return 1;
    } else {
        echo $db_connection->error;
        return 0;
    }
}
function AddCategory($category)
{
    global $db_connection;
    $name = $category->getCategoryName();
    $query = "INSERT INTO `category` (`CategoryName`) VALUES (?);";
    $prepared_statement = mysqli_prepare($db_connection, $query);
    mysqli_stmt_bind_param($prepared_statement, "s", $name);
    if ($result = mysqli_stmt_execute($prepared_statement)) {
        return 1;
    } else {
        return 0;
    }
}
function DeleteCategory($category_id)
{
    global $db_connection;
    $query = "DELETE FROM category WHERE CategoryId = " . $category_id;
    if ($result = $db_connection->query($query)) {
        return 1;
    } else {
        return 0;
    }
}
function DeleteProduct($product_serial_number, $categoryId)
{
    global $db_connection;
    $query = "DELETE from product
    WHERE SerialNumber = " . $product_serial_number . "." . "and CategoryId= " . $categoryId . ".";
    if ($result = $db_connection->query($query)) {
        return 1;
    } else {
        return 0;
    }
}
function ViewUsers()
{
    global $db_connection;
    $all_users = array();
    $query = "SELECT UserName,Id,Email,Feedback FROM users WHERE UserTypeId <> 1";
    if ($result = $db_connection->query($query)) {
        if ($result->num_rows > 0) {
            $counter = 0;
            while ($row = $result->fetch_assoc()) {
                $user = new User();
                $user->setName($row['UserName']);
                $user->setID($row['Id']);
                $user->setEmail($row['Email']);
                $user->setFeedback($row['Feedback']);
                $all_users[$counter++] = $user;
            }
            return $all_users;
        }
    } else {
        return 0;
    }
}
function DeleteFeedback($userId)
{
    global $db_connection;
    $query = "UPDATE users SET Feedback= '' WHERE Id='" . $userId . "' ;";
    if ($result = $db_connection->query($query)) {
        return 1;
    } else {
        return 0;
    }

}
