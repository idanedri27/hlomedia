-- =====================================================================
-- 001_attribution.sql
--
-- Creates the `leads` table that captures every contact-form submission
-- with full attribution data (landing page, UTM, referrer, search query).
--
-- The legacy `customers` table is kept untouched - new code writes to
-- `leads`, old data stays where it is. If you ever want to merge:
--
--   INSERT INTO leads (lead_type, email, location, created_at)
--   SELECT 'legacy', email, location, date FROM customers;
--
-- Run on production with:
--   mysql -u root -p hlomedia < server/migrations/001_attribution.sql
-- =====================================================================

CREATE TABLE IF NOT EXISTS leads (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    lead_type         VARCHAR(32)  NOT NULL DEFAULT 'contact', -- contact | quotation | legacy
    name              VARCHAR(255) NULL,
    email             VARCHAR(255) NULL,
    phone             VARCHAR(50)  NULL,
    subject           VARCHAR(255) NULL,
    message           TEXT         NULL,

    -- Attribution
    landing_page      VARCHAR(500) NULL,
    original_referrer VARCHAR(500) NULL,
    utm_source        VARCHAR(150) NULL,
    utm_medium        VARCHAR(150) NULL,
    utm_campaign      VARCHAR(150) NULL,
    utm_term          VARCHAR(150) NULL,
    utm_content       VARCHAR(150) NULL,
    search_query      VARCHAR(255) NULL,

    -- Environment
    location          VARCHAR(100) NULL,        -- IP-derived city/country
    user_agent        VARCHAR(500) NULL,
    ip_address        VARCHAR(64)  NULL,
    is_mobile         TINYINT(1)   NOT NULL DEFAULT 0,

    -- Lifecycle
    status            VARCHAR(32)  NOT NULL DEFAULT 'new',     -- new | contacted | qualified | closed | lost
    notes             TEXT         NULL,
    created_at        DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Indexes optimized for the SEO agent's analytics queries.
    INDEX idx_created (created_at),
    INDEX idx_landing (landing_page(191)),
    INDEX idx_source  (utm_source),
    INDEX idx_status  (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
