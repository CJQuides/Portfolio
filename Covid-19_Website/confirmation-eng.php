<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$email = "";
$email_err = "";
 
if(isset($_POST["id"]) && !empty($_POST["id"])){
    $id = $_POST["id"];
    
    // Validate name 
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Please enter your email.";     
    } else{
        $email = $input_email;
    }
    
    // Check input errors before inserting in database
    if(empty($email_err)){
        //update statement
        $sql = "SELECT * FROM vaccinetbl WHERE id=$id";
        if($result = mysqli_query($link, $sql)){
            $row = mysqli_fetch_array($result);
            $emailDb = $row["email"];
            if($input_email == $emailDb){
                // $email_err = $emailDb;
                header("location:  update-eng.php?id=$id");
            }
            else {
                $email_err = "Email does not match.";   
            }
        }
    }
    
    mysqli_close($link);
} else{
    // Check if id exists
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $id =  trim($_GET["id"]);
        
        // select statement
        $sql = "SELECT * FROM vaccinetbl WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    // Fetch result row as an associative array
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // display value
                    $email = $row["email"];
                } else{
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        mysqli_stmt_close($stmt);
        
        mysqli_close($link);
    }  else{
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GRQV - PH Confirmation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <?php include 'head.php'; ?>
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <!--
      Header Bar
    -->

    <?php include 'header-eng.php'; ?>
    
    <div class="site-confirmation">
        <div class="container">
            <div class="row">
                <div class="col-md-12 p-4">
                    <div class="cconfirmation text-center">
                        <h2 class="pt-3 mt-5">Update Record</h2>
                        <p>Please input your email and submit.</p>
                    </div>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class = "cconfirmation2 p-5">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" name="email" placeholder = "Email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>">
                                <span class="invalid-feedback"><?php echo $email_err;?></span>
                            </div>
                            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                            <input type="submit" class="btn btn-success" value="Submit">
                            <a href="records-eng.php" class="btn btn-danger ml-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>