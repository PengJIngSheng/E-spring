<?php
$conn = mysqli_connect('127.0.0.1:3307', 'root', 'root', 'cust') or die("connection failed:" . mysqli_connect_error());
if (!$conn) {
    echo '<p>连接失败</p>';
    trigger_error('Could not connect to MySQL: ' . mysqli_connect_error());
} else { // Otherwise, set the encoding
    mysqli_set_charset($conn, 'utf8');
}
