# Database Structure Documentation

This document describes the database structure for the wedding invitation application.

## Tables

### 1. clients (Account owners)
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- client_name (VARCHAR(100), NOT NULL)
- address (VARCHAR(100))
- nik (VARCHAR(50)) - National Identity Card number
- phone (VARCHAR(50))
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)

### 2. couples (The invited couple)
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- client_id (INT, NOT NULL, FOREIGN KEY references clients.id ON DELETE CASCADE)
- groom_name (VARCHAR(100), NOT NULL)
- bride_name (VARCHAR(100), NOT NULL)
- wedding_date (DATE, NOT NULL)
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)

### 3. people (Groom/Bride details)
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- couple_id (INT, NOT NULL, FOREIGN KEY references couples.id ON DELETE CASCADE)
- role (ENUM('groom','bride'), NOT NULL)
- full_name (VARCHAR(100), NOT NULL)
- image_url (VARCHAR(255))
- additional_info (TEXT)
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)

### 4. person_parents (Parents of bride/groom)
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- person_id (INT, NOT NULL, FOREIGN KEY references people.id ON DELETE CASCADE)
- father_name (VARCHAR(100))
- father_status (ENUM('alive','deceased'), DEFAULT 'alive')
- mother_name (VARCHAR(100))
- mother_status (ENUM('alive','deceased'), DEFAULT 'alive')
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)

### 5. wedding_events (Events)
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- couple_id (INT, NOT NULL, FOREIGN KEY references couples.id ON DELETE CASCADE)
- event_name (VARCHAR(100), NOT NULL)
- event_date (DATE, NOT NULL)
- event_time (TIME)
- end_time (TIME)
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)

### 6. locations (Venues)
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- wedding_event_id (INT, NOT NULL, FOREIGN KEY references wedding_events.id ON DELETE CASCADE)
- venue_name (VARCHAR(150), NOT NULL)
- address (TEXT, NOT NULL)
- map_embed_url (TEXT)
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)

### 7. gallery_images (Photos)
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- wedding_event_id (INT, NOT NULL, FOREIGN KEY references wedding_events.id ON DELETE CASCADE)
- image_url (VARCHAR(255), NOT NULL)
- thumbnail_url (VARCHAR(255))
- description (TEXT)
- sort_order (INT, DEFAULT 0)
- is_background (VARCHAR(1), NOT NULL)
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)

### 8. timeline_events (Journey story)
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- couple_id (INT, NOT NULL, FOREIGN KEY references couples.id ON DELETE CASCADE)
- title (VARCHAR(100))
- event_date (DATE)
- description (TEXT)
- image_url (VARCHAR(255))
- sort_order (INT, DEFAULT 0)
- is_inverted (BOOLEAN, DEFAULT FALSE)
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)

### 9. bank_accounts (Gift accounts)
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- wedding_event_id (INT, NOT NULL, FOREIGN KEY references wedding_events.id ON DELETE CASCADE)
- bank_name (VARCHAR(100), NOT NULL)
- account_number (VARCHAR(50), NOT NULL)
- account_holder_name (VARCHAR(100), NOT NULL)
- is_active (BOOLEAN, DEFAULT TRUE)
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)

### 10. guests
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- name (VARCHAR(100), NOT NULL)
- email (VARCHAR(150))
- phone (VARCHAR(20))
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)

### 11. invitations
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- guest_id (INT, NOT NULL, FOREIGN KEY references guests.id ON DELETE CASCADE)
- wedding_event_id (INT, NOT NULL, FOREIGN KEY references wedding_events.id ON DELETE CASCADE)
- invitation_code (VARCHAR(50), UNIQUE, NOT NULL)
- is_attending (BOOLEAN, DEFAULT NULL)
- responded_at (TIMESTAMP, NULL)
- guest_count (INT, DEFAULT 1)
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)

### 12. qr_codes
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- invitation_id (INT, NOT NULL, FOREIGN KEY references invitations.id ON DELETE CASCADE)
- qr_data (TEXT, NOT NULL)
- qr_image_url (VARCHAR(255))
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)

### 13. guest_messages (Guest messages)
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- guest_id (INT, NOT NULL, FOREIGN KEY references guests.id ON DELETE CASCADE)
- wedding_event_id (INT, NOT NULL, FOREIGN KEY references wedding_events.id ON DELETE CASCADE)
- guest_name (VARCHAR(100))
- message (TEXT, NOT NULL)
- is_approved (BOOLEAN, DEFAULT FALSE)
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)

### 14. packages (Packages)
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- name (VARCHAR(100), NOT NULL)
- description (TEXT)
- price (DECIMAL(12,2), NOT NULL)
- period (INT, NOT NULL) - active period in days
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)

### 15. transactions (Orders)
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- couple_id (INT, NOT NULL, FOREIGN KEY references couples.id ON DELETE CASCADE)
- package_id (INT, NOT NULL, FOREIGN KEY references packages.id ON DELETE CASCADE)
- reference_no (VARCHAR(13), NOT NULL, UNIQUE)
- order_date (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- status (VARCHAR(20), DEFAULT 'pending') - pending, paid, expired, cancelled
- total_amount (DECIMAL(12,2), NOT NULL)
- paid_at (TIMESTAMP, NULL)
- expired_at (TIMESTAMP, NULL)
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)

### 16. payment_methods (Payment methods)
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- payment_method_name (VARCHAR(100), NOT NULL) - e.g. bank_transfer, gopay, credit_card
- description (TEXT)
- provider_admin_fee (DECIMAL(12,2), DEFAULT 0.00) - fee from provider to admin
- provider_merchant_fee (DECIMAL(12,2), DEFAULT 0.00) - fee from provider to merchant
- admin_fee (DECIMAL(12,2), DEFAULT 0.00) - internal admin fee
- merchant_fee (DECIMAL(12,2), DEFAULT 0.00) - internal merchant fee
- m_key (VARCHAR(255)) - API/merchant key
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)

### 17. payment_transactions (Payment history)
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- transaction_id (INT, NOT NULL, FOREIGN KEY references transactions.id ON DELETE CASCADE)
- payment_method_id (INT, NOT NULL, FOREIGN KEY references payment_methods.id ON DELETE CASCADE)
- payment_method_name (VARCHAR(50), NULL) - duplication for quick logging
- provider_admin_fee (DECIMAL(12,2), DEFAULT 0.00)
- provider_merchant_fee (DECIMAL(12,2), DEFAULT 0.00)
- admin_fee (DECIMAL(12,2), DEFAULT 0.00)
- merchant_fee (DECIMAL(12,2), DEFAULT 0.00)
- status_code (VARCHAR(20), NULL) - code from payment gateway
- status_message (VARCHAR(200), NULL) - message from payment gateway
- payment_other_reff (VARCHAR(200), NULL) - reference from provider (VA, trx id, etc.)
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)

### 18. users (Authentication users)
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- client_id (INT, NULL, FOREIGN KEY references clients.id ON DELETE CASCADE)
- name (VARCHAR(255), NOT NULL)
- email (VARCHAR(255), NOT NULL, UNIQUE)
- email_verified_at (TIMESTAMP, NULL)
- password (VARCHAR(255), NOT NULL)
- role (VARCHAR(255), DEFAULT 'user')
- remember_token (VARCHAR(100))
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)

### 19. password_resets
- email (VARCHAR(255), NOT NULL)
- token (VARCHAR(255), NOT NULL)
- created_at (TIMESTAMP, NULL)

### 20. failed_jobs
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- uuid (VARCHAR(255), NOT NULL, UNIQUE)
- connection (TEXT, NOT NULL)
- queue (TEXT, NOT NULL)
- payload (LONGTEXT, NOT NULL)
- exception (LONGTEXT, NOT NULL)
- failed_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)

### 21. personal_access_tokens
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- tokenable_type (VARCHAR(255), NOT NULL)
- tokenable_id (INT, NOT NULL)
- name (VARCHAR(255), NOT NULL)
- token (VARCHAR(64), NOT NULL, UNIQUE)
- abilities (TEXT)
- last_used_at (TIMESTAMP, NULL)
- created_at (TIMESTAMP, NULL)
- updated_at (TIMESTAMP, NULL)

## Relationships

1. clients → users (One-to-One): One client can have one user account
2. clients → couples (One-to-Many): One client can have multiple couples
3. couples → people (One-to-Many): One couple can have two people (groom and bride)
4. people → person_parents (One-to-One): Each person can have parent details
5. couples → wedding_events (One-to-Many): One couple can have multiple wedding events
6. wedding_events → locations (One-to-One): Each wedding event has a location
7. wedding_events → gallery_images (One-to-Many): Each wedding event can have multiple gallery images
8. wedding_events → bank_accounts (One-to-Many): Each wedding event can have multiple bank accounts
9. wedding_events → guest_messages (One-to-Many): Each wedding event can have multiple guest messages
10. couples → timeline_events (One-to-Many): One couple can have multiple timeline events
11. guests → invitations (One-to-Many): One guest can receive multiple invitations
12. wedding_events → invitations (One-to-Many): One wedding event can have multiple invitations
13. invitations → qr_codes (One-to-One): Each invitation has a QR code
14. guests → guest_messages (One-to-Many): One guest can leave multiple messages
15. couples → transactions (One-to-Many): One couple can make multiple transactions
16. packages → transactions (One-to-Many): One package can be purchased multiple times
17. transactions → payment_transactions (One-to-Many): One transaction can have multiple payment records
18. payment_methods → payment_transactions (One-to-Many): One payment method can be used for multiple payments