<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Serverside Rest API</title>
        <link href="css/normalize.css" rel="stylesheet" />
        <link href="css/stylesheet.css" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;700&display=swap" rel="stylesheet">
        <script src="js/api.js"></script>
    </head>
    <body>
    <div class="container">
        <!-- Header start -->
        <div class="headerGrid">
            <header>
                <nav>
                    <a href="index.html" class="nav">HOME</a>
                    <div class="floatRight">
                        <a href="api.php" class="headerNav">REST API</a>
                        <h6 class="navBreak">.</h6>
                        <a href="contact.html" class="headerNav">CONTACT</a>
                        <div class="socialBlock">
                            <div class="dropdown">
                                <img class="navMenu" src="images/icons/menu-icon.png" alt="Menu Icon" />
                                <div class="dropdown-content">
                                    <a href="api.php">REST API</a>
                                    <br />
                                    <a href="contact.html">Contact</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
        </div>
        <!-- Header end -->
        <!-- Left start -->
        <div class="leftGrid">
        </div>
        <!-- Left end -->
        <!-- right start -->
        <div class="rightGrid">
        </div>
        <!-- right end -->
        <!-- Body start -->
        <div class="bodyGrid">
            <div id="wrapper">
                <h1>REST API</h1>
            </div>
            <div class="formContainer">
                <div id="ok">
                    <p>Successfully Submitted!</p>
                </div>
                <div id="loading">
                    <img src="images/loading.gif" alt="loading gif">
                </div>
                <form id="form" name="form" action="" method="POST">
                    <h2>REST API</h2>
                    <fieldset>
                        <legend>
                            <label for="fullName">Name</label>
                        </legend>
                        <input placeholder="Your name" type="text" name="fullName" id="fullName" autofocus>
                        <span class="name_error"></span>
                    </fieldset>
                    <fieldset>
                        <legend>
                            <label for="query">Message</label>
                        </legend>
                        <textarea placeholder="Type your Message Here...." name="query" id="query"></textarea>
                        <span class="message_error"></span>
                    </fieldset>
                    <input name="btnSubmit" type="submit" id="api-submit" value="Submit" />
                </form>
            </div>
            <div class="getResults">
                <?php
                    // if the jason variable has any content from the database, then display here on the page
                    if (!empty($jsonGetResponse)){
                        echo $jsonGetResponse;
                    }
                    if (!empty($jsonPostResponse)){
                        echo $jsonPostResponse;
                    }
                ?>
            </div>
        </div>
        <!-- Body end -->
        <!-- Footer start -->
        <div class="footerGrid">
            <footer>
                <a href="index.html">Home</a>
                <a href="api.php">REST API</a>
                <a href="contact.html">Contact</a>
                <p>2021 Tim Amis</p>
            </footer>
        </div>
        <!-- Footer end -->
    </div>
    </body>
</html>
