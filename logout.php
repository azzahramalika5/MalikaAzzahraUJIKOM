```php id="p4s8kd"
<?php
session_start();

// HAPUS SEMUA SESSION
session_unset();
session_destroy();

// KEMBALI KE HALAMAN LOGIN
header("Location: login.php");
exit;
?>
```
