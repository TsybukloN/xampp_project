CREATE DATABASE CryptocurrencyMarket;

USE CryptocurrencyMarket;

CREATE TABLE `Exchanges` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `country` VARCHAR(255),
  `trading_volume` DECIMAL(20,2) DEFAULT 0
);

CREATE TABLE `Cryptocurrencies` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `symbol` VARCHAR(50) NOT NULL UNIQUE,
  `name` VARCHAR(255) NOT NULL,
  `market_cap` DECIMAL(20,2),
  `circulating_supply` DECIMAL(20,8),
  `max_supply` DECIMAL(20,8),
  `description` TEXT
);

CREATE TABLE `Prices` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `cryptocurrency_id` INT NOT NULL,
  `exchange_id` INT NOT NULL,
  `price_usd` DECIMAL(20,8) NOT NULL,
  `volume_24h` DECIMAL(20,2),
  `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`cryptocurrency_id`) REFERENCES `Cryptocurrencies`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`exchange_id`) REFERENCES `Exchanges`(`id`) ON DELETE CASCADE
);

CREATE TABLE `Users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL UNIQUE,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_time` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `Portfolios` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `Users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `Transactions` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `portfolio_id` INT NOT NULL,
  `cryptocurrency_id` INT NOT NULL,
  `transaction_type` ENUM('BUY', 'SELL') NOT NULL,
  `amount` DECIMAL(20,8) NOT NULL,
  `price_usd` DECIMAL(20,8) NOT NULL,
  `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`portfolio_id`) REFERENCES `Portfolios`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`cryptocurrency_id`) REFERENCES `Cryptocurrencies`(`id`) ON DELETE CASCADE
);

CREATE INDEX idx_price_crypto ON Prices(cryptocurrency_id);
CREATE INDEX idx_price_exchange ON Prices(exchange_id);
CREATE INDEX idx_transaction_portfolio ON Transactions(portfolio_id);
CREATE INDEX idx_transaction_crypto ON Transactions(cryptocurrency_id);
