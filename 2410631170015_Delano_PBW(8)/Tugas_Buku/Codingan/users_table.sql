-- ============================================
-- TAMBAHAN: Tabel Users untuk Autentikasi
-- Database: pemrograman_web_contoh
-- ============================================

USE pemrograman_web_contoh;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id           INT(11)       NOT NULL AUTO_INCREMENT,
    username     VARCHAR(50)   NOT NULL UNIQUE,
    password     VARCHAR(255)  NOT NULL,
    nama_lengkap VARCHAR(100)  NOT NULL DEFAULT '',
    created_at   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User default: admin / admin123
-- Hash dibuat dengan: password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO users (username, password, nama_lengkap) VALUES
('admin', '$2y$10$nC3255TwnDyCh7JH.80Kj.9kIf6HN.1xxQgIgHPuUnKAWAU1HVXK6', 'Administrator');

