CREATE TABLE `Exchanges` (
  `id` int PRIMARY KEY,
  `name` varchar(255),
  `url` varchar(255),
  `country` varchar(255),
  `trading_volume` float
);

CREATE TABLE `Cryptocurrencies` (
  `id` int PRIMARY KEY,
  `symbol` varchar(255),
  `name` varchar(255),
  `market_cap` decimal,
  `circulating_supply` decimal,
  `max_supply` decimal,
  `description` text
);

CREATE TABLE `Prices` (
  `id` int PRIMARY KEY,
  `cryptocurrency_id` int,
  `exchange_id` int,
  `price_usd` decimal,
  `volume_24h` decimal,
  `timestamp` datetime
);

CREATE TABLE `Users` (
  `id` int PRIMARY KEY,
  `username` varchar(255),
  `email` varchar(255),
  `password_hash` varchar(255),
  `created_time` datetime
);

CREATE TABLE `Portfolios` (
  `id` int PRIMARY KEY,
  `user_id` int,
  `name` varchar(255)
);

CREATE TABLE `Transactions` (
  `id` int PRIMARY KEY,
  `portfolio_id` int,
  `user_id` int,
  `cryptocurrency_id` int,
  `transaction_type` varchar(255),
  `amount` decimal,
  `price_usd` decimal,
  `timestamp` datetime
);

ALTER TABLE `Prices` ADD FOREIGN KEY (`cryptocurrency_id`) REFERENCES `Cryptocurrencies` (`id`);

ALTER TABLE `Prices` ADD FOREIGN KEY (`exchange_id`) REFERENCES `Exchanges` (`id`);

ALTER TABLE `Portfolios` ADD FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`);

ALTER TABLE `Transactions` ADD FOREIGN KEY (`portfolio_id`) REFERENCES `Portfolios` (`id`);

ALTER TABLE `Transactions` ADD FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`);

ALTER TABLE `Transactions` ADD FOREIGN KEY (`cryptocurrency_id`) REFERENCES `Cryptocurrencies` (`id`);
