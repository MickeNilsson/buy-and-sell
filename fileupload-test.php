<?php

$filename = $_FILES['file']['name'];
if($_FILES['file']['error'] == UPLOAD_ERR_OK && $_FILES['file']['size'] < 2000000){
    if(move_uploaded_file($_FILES['file']['tmp_name'], './' . $filename)) {
        echo 'filen laddades upp ' . $sentname;
    } else {
        echo 'filen laddades inte upp';
    }
}
else {
    echo 'filen laddades inte upp';
}

?>