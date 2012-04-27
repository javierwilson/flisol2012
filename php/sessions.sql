--- Crea tabla para manejo de sesiones PHP en PostgreSQL
CREATE TABLE sessions (session_id varchar(32) PRIMARY KEY, session_data text, session_expiration timestamp);
