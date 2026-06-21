USE developmentdb;

DROP TABLE IF EXISTS votes;
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS mixes;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE mixes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    artist VARCHAR(255) NOT NULL,
    genre VARCHAR(100) NOT NULL,
    platform VARCHAR(100) NOT NULL,
    mix_url VARCHAR(500) NOT NULL,
    cover_image_url VARCHAR(500),
    duration VARCHAR(20),
    submitted_by VARCHAR(255) NOT NULL,
    submitted_by_user_id INT NULL,
    submitted_date DATE NOT NULL,
    description TEXT,
    status VARCHAR(50) NOT NULL DEFAULT 'published',
    featured TINYINT(1) NOT NULL DEFAULT 0,
    review_note TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_mixes_submitted_user
        FOREIGN KEY (submitted_by_user_id) REFERENCES users(id)
        ON DELETE SET NULL
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mix_id INT NOT NULL,
    user_id INT NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_comments_mix
        FOREIGN KEY (mix_id) REFERENCES mixes(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_comments_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);

CREATE TABLE votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mix_id INT NOT NULL,
    user_id INT NOT NULL,
    vote_type VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_mix_user_vote (mix_id, user_id),
    CONSTRAINT chk_vote_type CHECK (vote_type IN ('like', 'dislike')),
    CONSTRAINT fk_votes_mix
        FOREIGN KEY (mix_id) REFERENCES mixes(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_votes_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);

INSERT INTO mixes (
    id,
    title,
    artist,
    genre,
    platform,
    mix_url,
    cover_image_url,
    duration,
    submitted_by,
    submitted_by_user_id,
    submitted_date,
    description,
    status,
    featured,
    review_note
) VALUES
(
    1,
    'Lagos Sunset Groove',
    'DJ Kemi',
    'Afrobeat',
    'YouTube',
    'https://www.youtube.com/watch?v=bk6Xst6euQk',
    'https://images.unsplash.com/photo-1514525253161-7a46d19cd819',
    '42:18',
    'Sofia Martins',
    NULL,
    '2026-05-04',
    'A warm Afrobeat session with bright percussion, smooth horn lines, and late-evening party energy.',
    'published',
    1,
    NULL
),
(
    2,
    'Warehouse Pulse 132',
    'Milo Voss',
    'Techno',
    'YouTube',
    'https://www.youtube.com/watch?v=23Oh1LHavuE',
    'https://images.unsplash.com/photo-1571266028243-d220c9c3b04d',
    '58:44',
    'Noah Vermeer',
    NULL,
    '2026-05-07',
    'A driving techno mix built around hypnotic drums, dark synth stabs, and steady peak-time momentum.',
    'published',
    0,
    NULL
),
(
    3,
    'Rooftop House Session',
    'Lena Cole',
    'House',
    'YouTube',
    'https://www.youtube.com/watch?v=mRfwdJx0NDE',
    'https://images.unsplash.com/photo-1501386761578-eac5c94b800a',
    '46:09',
    'Maya Brooks',
    NULL,
    '2026-05-12',
    'Uplifting house selections with soulful vocals, crisp claps, and a relaxed rooftop feel.',
    'published',
    1,
    NULL
),
(
    4,
    'Late Night Cypher Tape',
    'Northside Kai',
    'Hip-Hop',
    'YouTube',
    'https://www.youtube.com/watch?v=-5EQIiabJvk',
    'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f',
    '35:27',
    'Jordan Price',
    NULL,
    '2026-05-15',
    'Boom bap drums, mellow keys, and sharp underground verses stitched into a late-night hip-hop blend.',
    'published',
    0,
    NULL
),
(
    5,
    'Pretoria Balcony Keys',
    'Thabo Nine',
    'Amapiano',
    'YouTube',
    'https://www.youtube.com/watch?v=vy-k0FopsmY',
    'https://images.unsplash.com/photo-1524368535928-5b5e00ddc76b',
    '51:33',
    'Aisha Khan',
    NULL,
    '2026-05-19',
    'Amapiano grooves with rolling log drums, airy pads, and smooth weekend balcony energy.',
    'published',
    1,
    NULL
),
(
    6,
    'Velvet Room Slow Jams',
    'Nia Vale',
    'R&B',
    'YouTube',
    'https://www.youtube.com/watch?v=m_qewI-1cEs',
    'https://images.unsplash.com/photo-1516280440614-37939bbacd81',
    '39:52',
    'Elena Rossi',
    NULL,
    '2026-05-22',
    'A smooth R&B set with glossy vocals, soft basslines, and slow grooves for after-hours listening.',
    'published',
    0,
    NULL
),
(
    7,
    'Kingston Heatwave',
    'Selecta Rowan',
    'Dancehall',
    'YouTube',
    'https://www.youtube.com/watch?v=IvPdwoppGw4',
    'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3',
    '44:06',
    'Dante Lewis',
    NULL,
    '2026-05-25',
    'Dancehall rhythms with bold hooks, quick transitions, and sunny sound-system energy.',
    'published',
    0,
    NULL
),
(
    8,
    'Night Bus Breaks',
    'Rue Signal',
    'Drum and Bass',
    'YouTube',
    'https://www.youtube.com/watch?v=8v_3k-94n30',
    'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee',
    '55:41',
    'Sam Taylor',
    NULL,
    '2026-05-29',
    'Fast breaks, deep subs, and atmospheric pads shaped for midnight headphones and city rides.',
    'published',
    1,
    NULL
);

ALTER TABLE mixes AUTO_INCREMENT = 9;

INSERT INTO users (
    id,
    name,
    email,
    password_hash,
    role
) VALUES
(
    1,
    'Admin User',
    'admin@skproduction.test',
    '$2y$12$3bMlg1H6tmTqY8lLXAoST.zOFAoCksLj7DVc61sk3wJV1vI6BPUIi',
    'admin'
),
(
    2,
    'Test User',
    'user@skproduction.test',
    '$2y$12$WM7ekzGhdGT9ll3VpxYy8.7KQxYOEYjfh3nF6uABZH8xX2UrK8Ja6',
    'user'
);

ALTER TABLE users AUTO_INCREMENT = 3;
