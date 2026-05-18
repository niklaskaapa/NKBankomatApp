CREATE TABLE users 
(
id INTEGER PRIMARY KEY AUTOINCREMENT,
card_number TEXT NOT NULL UNIQUE, -- kortnummer
pin_hash TEXT NOT NULL, -- bcrypt
name TEXT NOT NULL,
role TEXT NOT NULL DEFAULT 'user', -- 'user' eller 'admin
created_at TEXT NOT NULL
);

CREATE TABLE accounts 
(
id INTEGER PRIMARY KEY AUTOINCREMENT,
user_id INTEGER NOT NULL REFERENCES users(id),
account_type TEXT NOT NULL DEFAULT 'checking', -- checking, saving
balance REAL NOT NULL DEFAULT 0.00,
created_at TEXT NOT NULL
);

CREATE TABLE transactions 
(
id INTEGER PRIMARY KEY AUTOINCREMENT,
from_account_id INTEGER REFERENCES accounts(id),
to_account_id INTEGER REFERENCES accounts(id),
type TEXT NOT NULL, -- deposit, withdrawal, transfer
amount REAL NOT NULL,
created_at TEXT NOT NULL
);