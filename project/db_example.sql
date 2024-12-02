USE CryptocurrencyMarket;

INSERT INTO Exchanges VALUES (1, 'Binance', 'https://www.binance.com', 'China', 1000000000);
INSERT INTO Exchanges VALUES (2, 'Coinbase', 'https://www.coinbase.com', 'USA', 500000000);
INSERT INTO Exchanges VALUES (3, 'Bitfinex', 'https://www.bitfinex.com', 'Hong Kong', 300000000);

INSERT INTO Cryptocurrencies VALUES (1, 'BTC', 'Bitcoin', 100000000000, 17000000, 21000000, 'The first cryptocurrency');
INSERT INTO Cryptocurrencies VALUES (2, 'ETH', 'Ethereum', 50000000000, 100000000, NULL, 'The second cryptocurrency');
INSERT INTO Cryptocurrencies VALUES (3, 'XRP', 'Ripple', 30000000000, 40000000000, 100000000000, 'The third cryptocurrency');

INSERT INTO Prices VALUES (1, 1, 1, 10000, 1000000, '2018-01-01');
INSERT INTO Prices VALUES (2, 1, 2, 10000, 1000000, '2018-01-01');
INSERT INTO Prices VALUES (3, 1, 3, 10000, 1000000, '2018-01-01');
INSERT INTO Prices VALUES (4, 2, 1, 1000, 100000, '2018-01-01');
INSERT INTO Prices VALUES (5, 2, 2, 1000, 100000, '2018-01-01');
INSERT INTO Prices VALUES (6, 2, 3, 1000, 100000, '2018-01-01');

INSERT INTO portfolios VALUES (1, 1, 'My Portfolio');
INSERT INTO portfolios VALUES (2, 2, 'My Portfolio');

INSERT INTO transactions VALUES (1, 1, 1, 'BUY', 1, 10000, '2018-01-01');
INSERT INTO transactions VALUES (2, 1, 2, 'SELL', 1, 1000, '2018-01-01');
INSERT INTO transactions VALUES (3, 2, 1, 'BUY', 1, 10000, '2018-01-01');
INSERT INTO transactions VALUES (4, 2, 2, 'SELL', 1, 1000, '2018-01-01');
