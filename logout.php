<?php

include "includes/functions.php";

// Logout user
session_start();
session_unset();
session_destroy();

redirect('index');
exit();
