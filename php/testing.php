<?php

include('sessions.inc.php');
$_SESSION['nombre'] = 'Javier Wilson';
$_SESSION['email'] = 'javier@guegue.net';
$_SESSION['contador']++;
$data = print_r($_SESSION, 1);
?>
PHP dice...
<pre>
<?=$data?>
</pre>
