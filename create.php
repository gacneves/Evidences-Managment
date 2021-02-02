<?php

// Include config file
require_once "checkin.php";
require_once "config.php";
 
// Define variables and initialize with empty values
$title     = $description     = $status     = "";
$title_err = $description_err = $status_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Validate title
    $input_title = trim($_POST["title"]);
    if(empty($input_title))
    {
        $title_err = "Please enter a title.";
    }
    elseif(!filter_var($input_title, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/"))))
    {
        $title_err = "Please enter a valid title.";
    }
    else
    {
        $title = $input_title;
    }

    // Validate description
    $input_description = trim($_POST["description"]);
    if(empty($input_description))
    {
        $description_err = "Please enter a description.";
    }
    elseif(!filter_var($input_description, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/"))))
    {
        $description_err = "Please enter a valid description.";
    }
    else
    {
        $description = $input_description;
    }

    // Validate status
    $input_status = trim($_POST["status"]);
    if(empty($input_status))
    {
        $status_err = "Please enter the status.";     
    } 
    elseif(!filter_var($input_status, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/"))))
    {
        $description_err = "Please enter a valid description.";
    }
    else
    {
        $status = $input_status;
    }

    $log_date = date("Y-m-d H:i:s");;
    
    // Check input errors before inserting in database
    if(empty($title_err) && empty($description_err) && empty($status_err))
    {
        // Prepare an insert statement
        $sql = "INSERT INTO projects (title, description, status, log_date, log_login) VALUES (?,?,?,?,?)";
         
        if($stmt = mysqli_prepare($connection, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $title, $description, $status, $log_date, $log_login);
            
            // Set parameters
            $title         = $title;
            $description   = $description;
            $status        = $status;
            $log_date      = $log_date;
            $log_login         = $_SESSION['login'];
            echo $stmt->sqlstate;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing pstatus
                header("location: index.php");
                exit();
            }
            else
            {
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta title="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    
   <!-- add style css -->
   <link rel="stylesheet" href="css/css-create-style.css">

</head>

<body class="bg-light">
    <div class="container">
        <div class="signup-form">
            <div class="row">
                <div class="col-md-12">
                    <div class="pstatus-header">
                        <h2>Register Project</h2>
                    </div>
                    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?= (!empty($title_err)) ? 'has-error' : ''; ?>">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" value="<?= $title; ?>">
                            <span class="help-block"><?= $title_err;?></span>
                        </div>
                        <div class="form-group <?= (!empty($description_err)) ? 'has-error' : ''; ?>">
                            <label>Description</label>
                            <input type="text" name="description" class="form-control" value="<?= $description; ?>">
                            <span class="help-block"><?= $description_err;?></span>
                        </div>
                        <div class="form-group <?= (!empty($status_err)) ? 'has-error' : ''; ?>">
                            <label>Status</label>
                                <select id="status" name="status" class="form-control" value="<?= $status; ?>">
                                <option value="In progress">In progress</option>
                                <option value="In validation">In validation</option>
                                <option value="Closed">Closed</option>
                                </select>
                            <span class="help-block"><?= $status_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-danger" style="color:white   ;">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>