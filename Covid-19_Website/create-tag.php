<?php
require_once "config.php";
 
$email = $name = $age = $gender = $fullyVac = $vaccineType = $booster = $boosterType = $sideEffects = $infection = "";
$email_err = $name_err = $age_err = $gender_err = $fullyVac_err = $vaccineType_err = $booster_err = $boosterType_err = $sideEffects_err =  $infection_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $con=mysqli_connect("localhost","root","","vaccinedb");
    $check="SELECT * FROM vaccinetbl WHERE email = '$_POST[emailTxtBx]'";
    $rs = mysqli_query($con,$check);
    $data = mysqli_fetch_array($rs, MYSQLI_NUM);
    
    $input_email = trim($_POST["emailTxtBx"]);
    if(empty($input_email)){
        $email_err = "Pakiusap ilagay ang iyong email.";
    }elseif($data > 1) {
        $email_err = "Ang email na ito ay may nag mamay-ari na.";
    } else{
        if (!filter_var($input_email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Pakiusap mag lagay ng balidong email.";
        } else {
            $email = $input_email;
        }
    }
    
    include ("txtBoxChecker-tag.php");

    if(empty($email_err) && empty($name_err) && empty($age_err) && empty($gender_err) && empty($fullyVac_err) && empty($vaccineType_err) && empty($booster_err) && empty($boosterType_err)){
        $sql = "INSERT INTO vaccinetbl (email, name, age, gender, fullyVac, vaccineType, booster, boosterType, sideEffects, infection) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, 'ssisssssss', $param_email, $param_name, $param_age, $param_gender, $param_fullyVac, $param_vaccineType, $param_booster, $param_boosterType, $param_sideEffects, $param_infection);
            
            $param_email = $email;
            $param_name = $name;
            $param_age = $age;
            $param_gender = $gender;
            $param_fullyVac = $fullyVac;
            $param_vaccineType = $vaccineType;
            $param_booster = $booster;
            $param_boosterType = $boosterType;
            $param_sideEffects = $sideEffects;
            $param_infection = $infection;
            
            if(mysqli_stmt_execute($stmt)){
                header("location: records-tag.php");
                exit();
            } else{
                echo "Sandali, May nakitang mali ang aming system, Magtry ka ulit mamaya!";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GRQV - PH Create Record</title>
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

    <?php include 'header-tag.php'; ?>
    <div class="site-create">
        <div class="container">
            <div class="row">
                <div class="col-md-12 p-4">
                    <div class="text-center crecord">
                        <h1 class = "pt-3"><b>GUMAWA NG RECORD</b></h1>
                        <h4>Mangyaring punan ang form sa ibaba.</h4>
                    </div>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    
                        <div class="d-flex crecord2 p-5">
                            <div class="w-50 mr-3">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" name="emailTxtBx" placeholder = "Email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                                    <span class="invalid-feedback"><?php echo $email_err;?></span>
                                </div>
                                <!--  -->
                                <div class="form-group">
                                    <label>Pangalan</label>
                                    <input type="text" name="nameTxtBx" placeholder = "Pangalan"   class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                                    <span class="invalid-feedback"><?php echo $name_err;?></span>
                                </div>
                                <!--  -->
                                <div class="form-group">
                                    <label>Edad</label>
                                    <input type="number"name="ageTxtBx" placeholder = "Edad"  class="form-control <?php echo (!empty($age_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $age; ?>" min="12" max="">

                                    <span class="invalid-feedback"><?php echo $age_err;?></span>
                                </div>
                                <!--  -->
                                <div class="form-group">
                                    <label for="genderTxtBx">Kasarian</label>
                                    <select name="genderTxtBx" id="gender" class="form-control <?php echo (!empty($gender_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $gender; ?>">
                                        <option value="" selected = "selected">--Choose Gender--</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $gender_err;?></span>
                                </div>
                                <!--  -->
                                <div class="form-group">
                                    <label>Fully Vaccinated</label>
                                    <input type="text" name="fullyVacTxtBx" class="form-control <?php echo (!empty($fullyVac_err)) ? 'is-invalid' : ''; ?>" value="<?php echo "Yes"; ?>" readonly>
                                    <span class="invalid-feedback"><?php echo $fullyVac_err;?></span>
                                </div>
                                <!--  -->
                                <div class="form-group">
                                    <label for="vaccineTypeTxtBx">Vaccine Type</label>
                                    <select name="vaccineTypeTxtBx" id="vaccineTypeTxtBx" class="form-control <?php echo (!empty($vaccineType_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $vaccineType; ?>">
                                        <option value="" selected = "selected">--Choose Vaccine Type--</option>
                                        <option value="Pfizer">Pfizer</option>
                                        <option value="AztraZeneca">AztraZeneca</option>
                                        <option value="Sinovac">Sinovac</option>
                                        <option value="Sputnik">Sputnik</option>
                                        <option value="J & J">J & J</option>
                                        <option value="Moderna">Moderna</option>
                                        <option value="Sinopharm">Sinopharm</option>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $vaccineType_err;?></span>
                                </div>  
                                <input type="submit" class="btn btn-success" value="Isumite">
                                <a href="index-tag.php" class="btn btn-danger ml-2">Kanselahin</a>
                            </div>
                            <div class="w-50">    
                                <!--  -->
                                <div class="form-group">
                                    <label for="boosterTxtBx">Booster</label>
                                    <select name="boosterTxtBx" id="boosterTxtBx" class="form-control <?php echo (!empty($booster_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $booster; ?>">
                                        <option value="" selected = "selected">--Choose Option--</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $booster_err;?></span>
                                </div>
                                <!--  -->
                                <div class="form-group">
                                    <label for="boosterTypeTxtBx">Booster Type</label>
                                    <select name="boosterTypeTxtBx" id="boosterTypeTxtBx" class="form-control <?php echo (!empty($boosterType_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $boosterType; ?>">
                                        <option value="" selected = "selected">--Choose Booster Type--</option>
                                        <option value="Pfizer">Pfizer</option>
                                        <option value="AztraZeneca">AztraZeneca</option>
                                        <option value="Moderna">Moderna</option>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $boosterType_err;?></span>
                                </div>
                                <!--  -->
                                <div class="form-group">
                                    <label>Mga Epekto ng Bakuna</label>
                                    <textarea name="sideEffectsTxtBx" style="height:125px;" class="form-control <?php echo (!empty($sideEffects_err)) ? 'is-invalid' : ''; ?>"><?php echo $sideEffects; ?></textarea>
                                    <span class="invalid-feedback"><?php echo $sideEffects_err;?></span>
                                </div>   
                                <!--  -->
                                <div class="form-group">
                                    <label>Impeksyon Pagkatapos Bakunahan</label>
                                    <textarea name="infectionTxtBx" style="height:125px;" class="form-control <?php echo (!empty($infection_err)) ? 'is-invalid' : ''; ?>"><?php echo $infection; ?></textarea>
                                    <span class="invalid-feedback"><?php echo $infection_err;?></span>
                                </div>                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>