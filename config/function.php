<?php
    function redirect_with_alert($message, $location) {
        echo "<script>alert('$message'); window.location='$location';</script>";
    }