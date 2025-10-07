-- schema.sql (nammcare prototype simplified)
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150),
  email VARCHAR(150) UNIQUE,
  phone VARCHAR(32),
  password VARCHAR(255),
  role ENUM('user','agency','admin') DEFAULT 'user',
  profile JSON NULL,
  otp_code VARCHAR(10) NULL,
  otp_expires DATETIME NULL,
  reset_token VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS agencies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  agency_name VARCHAR(200),
  city VARCHAR(100),
  rating DECIMAL(2,1) DEFAULT 0,
  services JSON,
  price_range VARCHAR(50),
  about TEXT,
  is_verified TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS caretakers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  agency_id INT NOT NULL,
  name VARCHAR(150),
  experience_years INT DEFAULT 0,
  specializations JSON,
  languages JSON,
  hourly_rate DECIMAL(8,2) DEFAULT 0,
  rating DECIMAL(2,1) DEFAULT 0,
  availability JSON NULL, -- e.g. {"2025-10-10":["09:00","14:00"]}
  about TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (agency_id) REFERENCES agencies(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sender_id INT,
  receiver_id INT,
  message TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  booking_code VARCHAR(30) UNIQUE,
  user_id INT,
  agency_id INT,
  caretaker_id INT,
  booking_date DATE,
  start_time TIME,
  end_time TIME,
  duration_hours INT,
  status ENUM('pending','confirmed','in_progress','completed','cancelled') DEFAULT 'pending',
  amount DECIMAL(10,2) DEFAULT 0,
  payment_status ENUM('none','mock_paid') DEFAULT 'none',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  booking_id INT NULL,
  user_id INT,
  agency_id INT NULL,
  caretaker_id INT NULL,
  rating INT,
  comment TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
