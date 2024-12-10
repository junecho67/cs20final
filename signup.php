<?php 
    session_start();

    //retrieve info from signup
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['username']) && isset($_GET['password'])){
        extract($_GET);
        //establish connection info
        $server = "localhost";
        $userid = "uxnrrh8y1k0sy";
        $pw = "e%N#rh<#t2~1";
        $db = "dbwl3llwukutmw";

        //create connection
        $conn = new mysqli($server, $userid, $pw);

        //check connection
        if($conn->connect_error){
            die("Connection failed: " . $conn->connect_error);
        }

        //select database
        $conn->select_db($db);

        //check if the user already has an account
        $checkUserSql = "SELECT username FROM Users WHERE username='" . $username . "'";
        $userExists = $conn->query($checkUserSql);

        if($userExists->num_rows > 0){
            $_SESSION['directToLogin'] = "This user already has an account. Please <a href='login.php'>login</a>.";
        }
        else{
            //enter users info in database
            $inputUserSql = "INSERT INTO Users (username, password) VALUES ('" . $username . "', '" . $password . "');";
            $result = $conn->query($inputUserSql);
        }

        // Redirect to the same page to avoid form resubmission if user refreshes browser
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup - PawfectMatch</title>
  <link rel="stylesheet" href="home.css">
  <script> 
    const validate = () => {
        const isLetter = (character) => {
            return ('A'.charCodeAt(0) <= character.charCodeAt(0) &&  character.charCodeAt(0) <= 'Z'.charCodeAt(0)) || 
                   ('a'.charCodeAt(0) <= character.charCodeAt(0) && character.charCodeAt(0) <= 'z'.charCodeAt(0));
        }

        let username = document.querySelector("#username").value;
        let password = document.querySelector("#password").value;

        if(username.indexOf(' ') >= 0){
            document.querySelector("#user_error").innerHTML = "Your username isn't allowed to have whitespace. Please properly enter a username";
            return false;
        }
        else{
            document.querySelector("#user_error").innerHTML = "";
            if(password.indexOf(' ') >= 0){
                document.querySelector("#password_error").innerHTML = "Your password isn't allowed to have whitespace. Please properly enter a password";
                return false;
            }
            else{
                document.querySelector("#password_error").innerHTML = "";
                if(!isLetter(username[0])){
                    document.querySelector("#user_error").innerHTML = "Your username must start with a letter. Please properly enter a username";
                    return false;
                }
                else{
                    document.querySelector("#user_error").innerHTML = "";
                    if(!isLetter(password[0])){
                        document.querySelector("#password_error").innerHTML = "Your password must start with a letter. Please properly enter a password";
                        return false;
                    }
                    else{
                        document.querySelector("#password_error").innerHTML = "";
                    }
                }
            }
        }

        return true;
    }
  </script>
</head>
<body>

  <!-- Navigation Bar -->
  <nav class="navbar">
    <div class="nav-left">
      <a href="home.html">
        <img src="logo.png" alt="PawfectMatch Logo" class="logo">
      </a>
    </div>
    <div class="nav-right">
      <a href="get-involved.html">Get Involved</a>
      <a href="login.php">Log In</a>
      <a href="matchmaking-survey.html" class="survey-btn">Matchmaking Survey</a>
    </div>
  </nav>

  <div class="content-container">
    <h1>Signup Page</h1>
    <form onsubmit='return validate()' id="signup" action="" method="get">
      <label for="username">Username: </label>
      <input type="text" name="username" id="username" autocomplete="on" autofocus required>
      <p id="user_error"></p>

      <label for="password">Password: </label>
      <input type="password" name="password" id="password" autocomplete="on" required>
      <p id="password_error"></p>

      <button type="submit" class="survey-btn">Signup</button>
      <br>
    </form>

    <?php 
      if(isset($_SESSION['directToLogin'])){
        echo '<p class="error-message">' . $_SESSION['directToLogin'] . "</p>";
        unset($_SESSION['directToLogin']);
      }
    ?>
  </div>

  <!-- Footer Section -->
  <footer>
    <div class="footer-content">
      <div class="footer-section socials">
        <a href="https://www.facebook.com/" target="_blank">Facebook</a> |
        <a href="https://www.instagram.com/" target="_blank">Instagram</a> |
        <a href="https://www.twitter.com/" target="_blank">Twitter</a>
      </div>

      <div class="footer-section links">
        <a href="contact.html">Contact Us</a> |
        <a href="support.html">Customer Support</a>
      </div>

      <div class="footer-section newsletter">
        <h3>Subscribe to PawfectMatch</h3>
        <form action="#" method="post">
          <input type="email" placeholder="Enter your email" required>
          <button type="submit">Submit</button>
        </form>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; 2024 PawfectMatch. All rights reserved.</p>
    </div>
  </footer>

  <script src="signup.js"></script>
</body>
</html>
