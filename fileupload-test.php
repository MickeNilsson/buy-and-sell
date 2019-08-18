<?php

$filename = $_FILES['image']['name'];
if($_FILES['image']['error'] == UPLOAD_ERR_OK && $_FILES['image']['size'] < 2000000){
    if(move_uploaded_file($_FILES['image']['tmp_name'], './' . $filename)) {
        echo 'filen laddades upp ' . $sentname;
    } else {
        echo 'filen laddades inte upp';
    }
}
else {
    echo 'filen laddades inte upp, något fel';
}

?>