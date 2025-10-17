<?php
if (function_exists('mysqli_init') && !extension_loaded("mysqli")) {
    echo "We don't have mysqli!!!";
} else {
    echo "mysqli is installed";
}
phpinfo();
?>
