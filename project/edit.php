<?php
global $connection;
include('db.php');

$table = $_GET['table'] ?? '';

function generate_form($table) {
    switch ($table) {
        case 'Users':
            echo "
                <form method='POST' action='insert.php?table=Users'>
                    <label for='username'>Username:</label><br>
                    <input type='text' id='username' name='username' required><br><br>
                    
                    <label for='email'>Email:</label><br>
                    <input type='email' id='email' name='email' required><br><br>
                    
                    <label for='password'>Password:</label><br>
                    <input type='password' id='password' name='password' required><br><br>

                    <input type='submit' name='add' value='Add User'>
                </form>
            ";
            break;
        
        case 'Exchanges':
            echo "
                <form method='POST' action='insert.php?table=Exchanges'>
                    <label for='name'>Exchange Name:</label><br>
                    <input type='text' id='name' name='name' required><br><br>
                    
                    <label for='url'>URL:</label><br>
                    <input type='text' id='url' name='url' required><br><br>
                    
                    <label for='country'>Country:</label><br>
                    <input type='text' id='country' name='country'><br><br>

                    <label for='trading_volume'>Trading Volume:</label><br>
                    <input type='number' id='trading_volume' name='trading_volume' step='0.01'><br><br>

                    <input type='submit' name='add' value='Add Exchange'>
                </form>
            ";
            break;
        
        case 'Cryptocurrencies':
            echo "
                <form method='POST' action='insert.php?table=Cryptocurrencies'>
                    <label for='symbol'>Symbol:</label><br>
                    <input type='text' id='symbol' name='symbol' required><br><br>

                    <label for='name'>Name:</label><br>
                    <input type='text' id='name' name='name' required><br><br>

                    <label for='market_cap'>Market Cap:</label><br>
                    <input type='number' id='market_cap' name='market_cap' step='0.01' required><br><br>

                    <label for='circulating_supply'>Circulating Supply:</label><br>
                    <input type='number' id='circulating_supply' name='circulating_supply' step='0.01' required><br><br>

                    <label for='max_supply'>Max Supply:</label><br>
                    <input type='number' id='max_supply' name='max_supply' step='0.01'><br><br>

                    <label for='description'>Description:</label><br>
                    <textarea id='description' name='description'></textarea><br><br>

                    <input type='submit' name='add' value='Add Cryptocurrency'>
                </form>
            ";
            break;

        case 'Prices':
            echo "
                <form method='POST' action='insert.php?table=Prices'>
                    <label for='cryptocurrency_id'>Cryptocurrency ID:</label><br>
                    <input type='number' id='cryptocurrency_id' name='cryptocurrency_id' required><br><br>

                    <label for='exchange_id'>Exchange ID:</label><br>
                    <input type='number' id='exchange_id' name='exchange_id' required><br><br>

                    <label for='price_usd'>Price (USD):</label><br>
                    <input type='number' id='price_usd' name='price_usd' step='0.01' required><br><br>

                    <label for='volume_24h'>Volume (24h):</label><br>
                    <input type='number' id='volume_24h' name='volume_24h' step='0.01'><br><br>

                    <input type='submit' name='add' value='Add Price'>
                </form>
            ";
            break;

        case 'Portfolios':
            echo "
                <form method='POST' action='insert.php?table=Portfolios'>
                    <label for='user_id'>User ID:</label><br>
                    <input type='number' id='user_id' name='user_id' required><br><br>

                    <label for='name'>Portfolio Name:</label><br>
                    <input type='text' id='name' name='name' required><br><br>

                    <input type='submit' name='add' value='Add Portfolio'>
                </form>
            ";
            break;

        case 'Transactions':
            echo "
                <form method='POST' action='insert.php?table=Transactions'>
                    <label for='portfolio_id'>Portfolio ID:</label><br>
                    <input type='number' id='portfolio_id' name='portfolio_id' required><br><br>

                    <label for='cryptocurrency_id'>Cryptocurrency ID:</label><br>
                    <input type='number' id='cryptocurrency_id' name='cryptocurrency_id' required><br><br>

                    <label for='transaction_type'>Transaction Type:</label><br>
                    <select id='transaction_type' name='transaction_type'>
                        <option value='BUY'>BUY</option>
                        <option value='SELL'>SELL</option>
                    </select><br><br>

                    <label for='amount'>Amount:</label><br>
                    <input type='number' id='amount' name='amount' step='0.01' required><br><br>

                    <label for='price_usd'>Price (USD):</label><br>
                    <input type='number' id='price_usd' name='price_usd' step='0.01' required><br><br>

                    <input type='submit' name='add' value='Add Transaction'>
                </form>
            ";
            break;

        default:
            echo "Invalid table.";
            break;
    }
}

if (isset($_POST['add'])) {
    switch ($table) {
        case 'Users':
            $username = mysqli_real_escape_string($connection, $_POST['username']);
            $email = mysqli_real_escape_string($connection, $_POST['email']);
            $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $query = "INSERT INTO Users (username, email, password_hash) VALUES ('$username', '$email', '$password_hash')";
            break;
        
        case 'Exchanges':
            $name = mysqli_real_escape_string($connection, $_POST['name']);
            $url = mysqli_real_escape_string($connection, $_POST['url']);
            $country = mysqli_real_escape_string($connection, $_POST['country']);
            $trading_volume = mysqli_real_escape_string($connection, $_POST['trading_volume']);
            $query = "INSERT INTO Exchanges (name, url, country, trading_volume) VALUES ('$name', '$url', '$country', '$trading_volume')";
            break;

        case 'Cryptocurrencies':
            $symbol = mysqli_real_escape_string($connection, $_POST['symbol']);
            $name = mysqli_real_escape_string($connection, $_POST['name']);
            $market_cap = mysqli_real_escape_string($connection, $_POST['market_cap']);
            $circulating_supply = mysqli_real_escape_string($connection, $_POST['circulating_supply']);
            $max_supply = mysqli_real_escape_string($connection, $_POST['max_supply']);
            $description = mysqli_real_escape_string($connection, $_POST['description']);
            $query = "INSERT INTO Cryptocurrencies (symbol, name, market_cap, circulating_supply, max_supply, description) VALUES ('$symbol', '$name', '$market_cap', '$circulating_supply', '$max_supply', '$description')";
            break;

        case 'Prices':
            $cryptocurrency_id = mysqli_real_escape_string($connection, $_POST['cryptocurrency_id']);
            $exchange_id = mysqli_real_escape_string($connection, $_POST['exchange_id']);
            $price_usd = mysqli_real_escape_string($connection, $_POST['price_usd']);
            $volume_24h = mysqli_real_escape_string($connection, $_POST['volume_24h']);
            $query = "INSERT INTO Prices (cryptocurrency_id, exchange_id, price_usd, volume_24h) VALUES ('$cryptocurrency_id', '$exchange_id', '$price_usd', '$volume_24h')";
            break;

        case 'Portfolios':
            $user_id = mysqli_real_escape_string($connection, $_POST['user_id']);
            $name = mysqli_real_escape_string($connection, $_POST['name']);
            $query = "INSERT INTO Portfolios (user_id, name) VALUES ('$user_id', '$name')";
            break;

        case 'Transactions':
            $portfolio_id = mysqli_real_escape_string($connection, $_POST['portfolio_id']);
            $cryptocurrency_id = mysqli_real_escape_string($connection, $_POST['cryptocurrency_id']);
            $transaction_type = mysqli_real_escape_string($connection, $_POST['transaction_type']);
            $amount = mysqli_real_escape_string($connection, $_POST['amount']);
            $price_usd = mysqli_real_escape_string($connection, $_POST['price_usd']);
            $query = "INSERT INTO Transactions (portfolio_id, cryptocurrency_id, transaction_type, amount, price_usd) VALUES ('$portfolio_id', '$cryptocurrency_id', '$transaction_type', '$amount', '$price_usd')";
            break;

        default:
            echo "Invalid table.";
            break;
    }

    if (mysqli_query($connection, $query)) {
        echo "Record added successfully!";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}

generate_form($table);
?>
