<?php
session_start();
session_destroy();
?>

<script type="text/javascript">
alert("Logout Completed.");
window.location.href = 'Login&Register.php';
</script>
