-- PHP-Auth (https://github.com/delight-im/PHP-Auth)
-- Copyright (c) delight.im (https://www.delight.im/)
-- Licensed under the MIT License (https://opensource.org/licenses/MIT)

PRAGMA foreign_keys = OFF;



-- BUILD USER TABLE
-- -----------------------------------
CREATE TABLE IF NOT EXISTS "users" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL CHECK ("id" >= 0),
    "email" VARCHAR(249) NOT NULL,
    "password" VARCHAR(255) NOT NULL,
    "username" VARCHAR(100) DEFAULT NULL,
    "status" INTEGER NOT NULL CHECK ("status" >= 0) DEFAULT "0",
    "verified" INTEGER NOT NULL CHECK ("verified" >= 0) DEFAULT "0",
    "resettable" INTEGER NOT NULL CHECK ("resettable" >= 0) DEFAULT "1",
    "roles_mask" INTEGER NOT NULL CHECK ("roles_mask" >= 0) DEFAULT "0",
    "registered" INTEGER NOT NULL CHECK ("registered" >= 0),
    "last_login" INTEGER CHECK ("last_login" >= 0) DEFAULT NULL,
    "force_logout" INTEGER NOT NULL CHECK ("force_logout" >= 0) DEFAULT "0",
    CONSTRAINT "email" UNIQUE ("email")
);



-- BUILD USERS CONFIRMATION TABLE
-- -----------------------------------
CREATE TABLE IF NOT EXISTS "users_confirmations" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL CHECK ("id" >= 0),
    "user_id" INTEGER NOT NULL CHECK ("user_id" >= 0),
    "email" VARCHAR(249) NOT NULL,
    "selector" VARCHAR(16) NOT NULL,
    "token" VARCHAR(255) NOT NULL,
    "expires" INTEGER NOT NULL CHECK ("expires" >= 0),
    CONSTRAINT "selector" UNIQUE ("selector")
);
CREATE INDEX IF NOT EXISTS "users_confirmations.email_expires" ON "users_confirmations" ("email", "expires");
CREATE INDEX IF NOT EXISTS "users_confirmations.user_id" ON "users_confirmations" ("user_id");



-- BUILD USERS REMEMBERED TABLE
-- -----------------------------------
CREATE TABLE IF NOT EXISTS "users_remembered" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL CHECK ("id" >= 0),
    "user" INTEGER NOT NULL CHECK ("user" >= 0),
    "selector" VARCHAR(24) NOT NULL,
    "token" VARCHAR(255) NOT NULL,
    "expires" INTEGER NOT NULL CHECK ("expires" >= 0),
    CONSTRAINT "selector" UNIQUE ("selector")
);
CREATE INDEX IF NOT EXISTS "users_remembered.user" ON "users_remembered" ("user");



-- BUILD USERS RESETS TABLE
-- -----------------------------------
CREATE TABLE IF NOT EXISTS "users_resets" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL CHECK ("id" >= 0),
    "user" INTEGER NOT NULL CHECK ("user" >= 0),
    "selector" VARCHAR(20) NOT NULL,
    "token" VARCHAR(255) NOT NULL,
    "expires" INTEGER NOT NULL CHECK ("expires" >= 0),
    CONSTRAINT "selector" UNIQUE ("selector")
);
CREATE INDEX IF NOT EXISTS "users_resets.user_expires" ON "users_resets" ("user", "expires");



-- BUILD USERS THROTTLING TABLE
-- -----------------------------------
CREATE TABLE IF NOT EXISTS "users_throttling" (
    "bucket" VARCHAR(44) PRIMARY KEY NOT NULL,
    "tokens" REAL NOT NULL CHECK ("tokens" >= 0),
    "replenished_at" INTEGER NOT NULL CHECK ("replenished_at" >= 0),
    "expires_at" INTEGER NOT NULL CHECK ("expires_at" >= 0)
);
CREATE INDEX IF NOT EXISTS "users_throttling.expires_at" ON "users_throttling" ("expires_at");



-- BUILD USER PROFILES TABLE
-- -----------------------------------
CREATE TABLE IF NOT EXISTS "users_profile" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL CHECK ("id" >= 0),
    "user" INTEGER NOT NULL CHECK ("user" >= 0),
    "nickname" VARCHAR(44) PRIMARY KEY NOT NULL,
    "birthdate" INTEGER NOT NULL CHECK ("birthdate" >= -1270237152)
);
-- CREATE INDEX IF NOT EXISTS "profiles.expires_at" ON "profiles" ("expires_at");



-- BUILD USER SESSIONS TABLE
-- -----------------------------------
CREATE TABLE IF NOT EXISTS "users_sessions" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL CHECK ("id" >= 0),
    "user_id" INTEGER NOT NULL CHECK ("user_id" >= 0),
    "authtoken" VARCHAR(44) PRIMARY KEY NOT NULL,
    "expires_at" INTEGER NOT NULL CHECK ("expires_at" >= 0)
    CONSTRAINT "email" UNIQUE ("email")
);
CREATE INDEX IF NOT EXISTS "session.expires_at" ON "users_sessions" ("expires_at");
CREATE INDEX IF NOT EXISTS "session.user_id" ON "users_sessions" ("user_id");
