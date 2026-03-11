<?php

include 'config/database.php';

$nama = "Super Admin";
$email = "admin@sima.com";
$password_asli = "admin123"; 

$password_hash = password_hash($password_asli, PASSWORD_DEFAULT);

$check = mysqli_query($conn, "SELECT * FROM pengelola WHERE email = '$email'");
if(mysqli_num_rows($check) > 0){

    $query = "UPDATE pengelola SET password = '$password_hash' WHERE email = '$email'";
    $action = "di-update";
} else {

    $query = "INSERT INTO pengelola (nama_lengkap, email, password, role) 
              VALUES ('$nama', '$email', '$password_hash', 'admin')";
    $action = "dibuat";
}

if(mysqli_query($conn, $query)){
    echo "<h1>SUKSES! </h1>";
    echo "<p>Akun Admin berhasil $action.</p>";
    echo "<ul>";
    echo "<li>Email: <b>$email</b></li>";
    echo "<li>Password: <b>$password_asli</b></li>";
    echo "</ul>";
    echo "<a href='login.php'>Klik disini untuk Login</a>";
} else {
    echo "<h1>GAGAL </h1>";
    echo "Error: " . mysqli_error($conn);
}
?>