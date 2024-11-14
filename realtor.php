<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="realtorhomepage.js"></script>

<style>


    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        background-color: #eaeaea;
        color: #333;
    }

    .header-container {
        background-color: #333;
        color: #fff;
        text-align: left;
        padding: 20px;
        border-radius: 6px;
        margin: 15px;
    }

    header h1 {
        margin: 0;
        font-size: 24px;
    }

    .container {
        width: 95%;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
    }

    nav {
        width: 95%;
        text-align: right;
        background-color: #333;
        color: #fff;
        padding: 20px;
        border-radius: 0 0 8px 0;
    }

    nav ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        justify-content: flex-end;
    }

    nav li {
        margin-right: 20px;
    }

    nav a {
        text-decoration: none;
        color: #fff;
        font-weight: bold;
    }

    .property-list {
        margin-bottom: 20px;
    }

    .property {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    .property img {
        max-width: 100px;
        max-height: 100px;
        margin-right: 10px;
    }

    .property button {
        background-color: #4caf50;
        color: #fff;
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        margin-right: 10px;
    }

    .property button:hover {
        background-color: #45a049;
    }

    .menu button {
        background-color: #4caf50;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .menu button:hover {
        background-color: #45a049;
    }

    footer {
        background-color: #333;
        color: #fff;
        text-align: center;
        padding: 10px;
        border-radius: 4px;
        margin: 15px;
    }
</style>

<body>

<div class="header-container">
    <header>
        <div id="welcomeMessage"></div>
        <nav>
            <ul>
                <li><a href="realtor.php">Home</a></li>
                <li><a href="myprofile.html">My Profile</a></li>
                <li><a href="addproperty.html">Add Property</a></li>
                <li><a href="Login.html">Logout</a></li>
            </ul>
        </nav>
    </header>
</div>

<div class="container">
    <div class="property-list" id="propertyList">

    </div>
</div>

<footer>
    <p>&copy; 2023 Real Estate System Team 4</p>
</footer>

</body>

<?php
extracted();

