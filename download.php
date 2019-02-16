<?php

$filename = $_GET['filename'];

header('content-disposition:attachment;filename='.basename($filename));
header('content-type:'.pathinfo($filename,PATHINFO_EXTENSION));
header('content-length:'.filesize($filename));

readfile($filename);