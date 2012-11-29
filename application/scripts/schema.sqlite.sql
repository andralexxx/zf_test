CREATE TABLE guestbook (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    email VARCHAR(32) NOT NULL DEFAULT 'noemail@test.com',
    firstname TEXT NOT NULL,
    lastname TEXT NOT NULL,
    phone TEXT NOT NULL,
    birthday DATETIME NOT NULL,
    photo TEXT NULL,
    created DATETIME NOT NULL
);
 
CREATE INDEX "id" ON "guestbook" ("id");