<?php

function upload($file){

    $nama = $file['name'];
    $tmp = $file['tmp_name'];

    move_uploaded_file(
        $tmp,
        "../assets/uploads/" . $nama
    );

    return $nama;
}