Installationsguide:

- Starta en ny terminal (New Terminal, Ctrl + Shift + ö) i VS-Code.
- Kör php seed.php

    Seeddata skapad.

- Öppna webbläsaren och klistra in http://localhost/NKBankomatApp/public/
- Logga in med något av: 

    card_number" => "1234", "pin" => "1111", "name" => "Anders Andersson", "role" => "user",
    card_number" => "5678", "pin" => "2222", "name" => "Bosse Bossesson", "role" => "user",
    card_number" => "6464", "pin" => "3333", "name" => "David Davidsson", "role" => "user",

    card_number" => "9999", "pin" => "0000", "name" => "Admin Adminsson", "role" => "admin",

- När inloggad som Admin (Role) visas Admin Panel med Användarlista, Kontolista, Transaktionslista enligt kriterier för betyg (G).






Databasschema enligt nedan:

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