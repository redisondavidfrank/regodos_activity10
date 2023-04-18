<?php
require('config/config.php');
require('config/db.php');

// Start session
session_start();

// Check for submit
if(isset($_POST['submit'])){
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  $result = mysqli_query($conn, $query);

  if(mysqli_num_rows($result) == 1){
    // User authenticated
    $_SESSION['username'] = $username;
  } else {
    // User not authenticated
    echo "Invalid username or password";
  }
  
  // Free result
  mysqli_free_result($result);

  // Close connection
  mysqli_close($conn);

  // Redirect to login page if user is not authenticated
  if(!isset($_SESSION['username'])){
    header('Location: guestbook-login.php');
    exit();
  }
} 

// Fetch data
$query = 'SELECT * FROM person ORDER BY id ASC LIMIT 25';
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 1){
  $persons = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Free result
mysqli_free_result($result);

// Close connection
mysqli_close($conn);
?>

<?php include('inc/header.php'); ?>
  <div class="container">
    <br/>
    <h2>Person's Log</h2>
    <?php if(isset($persons) && count($persons) > 0) { ?>
      <table class="table">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Id</th>
            <th scope="col">Firstname</th>
            <th scope="col">Lastname</th>
            <th scope="col">Address</th>
            <th scope="col">Log Date and Time</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($persons as $person) : ?>
            <tr>
              <th scope="person"><?php echo $person['id'];?></th>
              <td><?php echo $person['lastname'];?></td>
              <td><?php echo $person['firstname'];?></td>
              <td><?php echo $person['address'];?></td>
              <td><?php echo $person['logdate'];?></td>
            </tr>
          <?php endforeach; ?>   
        </tbody>
      </table>
      <br/>
    <?php } else { ?>
      <p>No data available</p>
    <?php } ?>
    <button type="button" class="btn btn-dark btn-sm" onclick="document.location='guestbook-login.php'">Logout</button>
  </div>
<?php include('inc/footer.php'); ?>