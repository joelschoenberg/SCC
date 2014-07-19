-- scripts/schema.sqlite.sql
--
-- You will need load your database schema with this SQL.

CREATE TABLE kcpanel (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    user TEXT NOT NULL UNIQUE,
    state TEXT NOT NULL
);

CREATE INDEX "id" ON "kcpanel" ("id");
