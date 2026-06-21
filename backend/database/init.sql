USE developmentdb;

DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS mixes;

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
    submitted_date DATE NOT NULL,
    description TEXT,
    status VARCHAR(50) NOT NULL DEFAULT 'published',
    featured TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
    submitted_date,
    description,
    status,
    featured
) VALUES
(
    1,
    'Lagos Sunset Groove',
    'DJ Kemi',
    'Afrobeat',
    'SoundCloud',
    'https://soundcloud.com/skproductionhub/lagos-sunset-groove',
    'https://images.unsplash.com/photo-1514525253161-7a46d19cd819',
    '42:18',
    'Sofia Martins',
    '2026-05-04',
    'A warm Afrobeat session with bright percussion, smooth horn lines, and late-evening party energy.',
    'published',
    1
),
(
    2,
    'Warehouse Pulse 132',
    'Milo Voss',
    'Techno',
    'Mixcloud',
    'https://www.mixcloud.com/skproductionhub/warehouse-pulse-132',
    'https://images.unsplash.com/photo-1571266028243-d220c9c3b04d',
    '58:44',
    'Noah Vermeer',
    '2026-05-07',
    'A driving techno mix built around hypnotic drums, dark synth stabs, and steady peak-time momentum.',
    'published',
    0
),
(
    3,
    'Rooftop House Session',
    'Lena Cole',
    'House',
    'SoundCloud',
    'https://soundcloud.com/skproductionhub/rooftop-house-session',
    'https://images.unsplash.com/photo-1501386761578-eac5c94b800a',
    '46:09',
    'Maya Brooks',
    '2026-05-12',
    'Uplifting house selections with soulful vocals, crisp claps, and a relaxed rooftop feel.',
    'published',
    1
),
(
    4,
    'Late Night Cypher Tape',
    'Northside Kai',
    'Hip-Hop',
    'YouTube',
    'https://www.youtube.com/watch?v=skproductionhub-cypher',
    'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f',
    '35:27',
    'Jordan Price',
    '2026-05-15',
    'Boom bap drums, mellow keys, and sharp underground verses stitched into a late-night hip-hop blend.',
    'published',
    0
),
(
    5,
    'Pretoria Balcony Keys',
    'Thabo Nine',
    'Amapiano',
    'Mixcloud',
    'https://www.mixcloud.com/skproductionhub/pretoria-balcony-keys',
    'https://images.unsplash.com/photo-1524368535928-5b5e00ddc76b',
    '51:33',
    'Aisha Khan',
    '2026-05-19',
    'Amapiano grooves with rolling log drums, airy pads, and smooth weekend balcony energy.',
    'published',
    1
),
(
    6,
    'Velvet Room Slow Jams',
    'Nia Vale',
    'R&B',
    'SoundCloud',
    'https://soundcloud.com/skproductionhub/velvet-room-slow-jams',
    'https://images.unsplash.com/photo-1516280440614-37939bbacd81',
    '39:52',
    'Elena Rossi',
    '2026-05-22',
    'A smooth R&B set with glossy vocals, soft basslines, and slow grooves for after-hours listening.',
    'published',
    0
),
(
    7,
    'Kingston Heatwave',
    'Selecta Rowan',
    'Dancehall',
    'YouTube',
    'https://www.youtube.com/watch?v=skproductionhub-kingston',
    'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3',
    '44:06',
    'Dante Lewis',
    '2026-05-25',
    'Dancehall rhythms with bold hooks, quick transitions, and sunny sound-system energy.',
    'published',
    0
),
(
    8,
    'Night Bus Breaks',
    'Rue Signal',
    'Drum and Bass',
    'Mixcloud',
    'https://www.mixcloud.com/skproductionhub/night-bus-breaks',
    'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee',
    '55:41',
    'Sam Taylor',
    '2026-05-29',
    'Fast breaks, deep subs, and atmospheric pads shaped for midnight headphones and city rides.',
    'published',
    1
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
