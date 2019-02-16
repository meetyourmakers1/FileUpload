<?php

$filename = $_FILES['myFile']['name'];
$type = $_FILES['myFile']['type'];
$tmp_name = $_FILES['myFile']['tmp_name'];
$error = $_FILES['myFile']['error'];
$size = $_FILES['myFile']['size'];


move_uploaded_file($tmp_name, "uploads/".$filename);

//copy($tmp_name,"uploads/".$filename);





