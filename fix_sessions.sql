-- Arreglar tabla sessions
DROP TABLE IF EXISTS sessions;

CREATE TABLE sessions (
  id varchar(255) NOT NULL,
  id_usuario bigint unsigned DEFAULT NULL,
  ip_address varchar(45) DEFAULT NULL,
  user_agent text,
  payload longtext NOT NULL,
  last_activity int NOT NULL,
  PRIMARY KEY (id),
  KEY sessions_id_usuario_index (id_usuario),
  KEY sessions_last_activity_index (last_activity),
  CONSTRAINT sessions_id_usuario_foreign FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
