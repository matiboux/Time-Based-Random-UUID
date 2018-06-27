<?php
header('Content-Type: text/plain');
include 'lib.php';

echo 'Fixed Date (2018-06-27 22:40:00): '; var_dump(uuid(strtotime('2018-06-27 22:25:00')));
echo 'Right now: '; var_dump(uuid());
?>