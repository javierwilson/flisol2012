<?php
#
# manejador de sesiones, Django y PostgreSQL usan la misma tabla
# solo tiene utilidad pedagógica
#
#$sessiondb = pg_connect('dbname=sessionss user=sbob password=secret');
$sessiondb = pg_connect('dbname=sessions user=bob password=secret host=localhost');

function on_session_start($save_path, $session_name) {
  error_log("on_session_start: " . $session_name . " ". session_id());
}

function on_session_end() {
  global $sessiondb;
  pg_close($sessiondb);
}

function on_session_read($key) {
  global $sessiondb;
  #error_log("on_session_read: " . $key);
  $stmt = "SELECT session_data FROM django_session WHERE session_key ='$key' AND CURRENT_TIMESTAMP < expire_date";
  $sth = pg_query($sessiondb, $stmt);

  if($sth) {
    $row = pg_fetch_array($sth);
    return($row['session_data']);
  } else {
    return $sth;
  }
}

function on_session_write($key, $val) {
  global $sessiondb;
  #error_log("on_session_write $key = $val");
  $val = pg_escape_string($val);
  $result = pg_query($sessiondb, "SELECT session_key FROM django_session WHERE session_key='$key'");
  $existe = pg_num_rows($result);
  if ($existe === 1)
    $sql  = "UPDATE django_session SET session_data ='$val', expire_date = CURRENT_TIMESTAMP + interval '1 week' WHERE session_key ='$key'";
  else
    $sql  = "INSERT INTO django_session VALUES ('$key', '$val',CURRENT_TIMESTAMP + interval '1 week')";
  pg_query($sessiondb, $sql);
}

function on_session_destroy($key) {
  global $sessiondb;
  pg_query($sessiondb, "DELETE FROM django_session WHERE session_key = '$key'");
}

function on_session_gc($max_lifetime) {
  global $sessiondb;
  pg_query($sessiondb, "DELETE FROM django_session WHERE expire_date > CURRENT_TIMESTAMP");
}


# Set the save handlers
session_set_save_handler("on_session_start", "on_session_end", "on_session_read", "on_session_write", "on_session_destroy", "on_session_gc");
session_start();
