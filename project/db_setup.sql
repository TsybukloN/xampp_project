CREATE DATABASE CryptocurrencyMarket;

CREATE TABLE `Exchanges` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `country` varchar(255),
  `trading_volume` float default 0
);

CREATE TABLE `Cryptocurrencies` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `symbol` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `market_cap` decimal,
  `circulating_supply` decimal,
  `max_supply` decimal,
  `description` text
);

CREATE TABLE `Prices` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `cryptocurrency_id` int,
  `exchange_id` int,
  `price_usd` decimal NOT NULL,
  `volume_24h` decimal,
  `timestamp` timestamp default CURRENT_TIMESTAMP
);

CREATE TABLE `Users` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_time` datetime default CURRENT_DATE
);

CREATE TABLE `Portfolios` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `name` varchar(255) NOT NULL
);

CREATE TABLE `Transactions` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `portfolio_id` int,
  `cryptocurrency_id` int,
  `transaction_type` varchar(255) NOT NULL,
  `amount` decimal NOT NULL,
  `price_usd` decimal NOT NULL,
  `timestamp` timestamp default CURRENT_TIMESTAMP
);

ALTER TABLE `Prices` ADD FOREIGN KEY (`cryptocurrency_id`) REFERENCES `Cryptocurrencies` (`id`);

ALTER TABLE `Prices` ADD FOREIGN KEY (`exchange_id`) REFERENCES `Exchanges` (`id`);

ALTER TABLE `Portfolios` ADD FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `Transactions` ADD FOREIGN KEY (`portfolio_id`) REFERENCES `Portfolios` (`id`);

ALTER TABLE `Transactions` ADD FOREIGN KEY (`cryptocurrency_id`) REFERENCES `Cryptocurrencies` (`id`);
