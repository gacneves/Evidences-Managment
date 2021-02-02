<?php
// Include config file
require_once "checkin.php";
require_once "config.php";

 
// Define variables and initialize with empty values
$title     = $description     = $status     = "";
$title_err = $description_err = $status_err = "";

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"]))
{
    // Get hidden input value
    $id = $_POST["id"];
    
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

   $log_date = date("Y-m-d H:i:s");
    
    // Check input errors before inserting in database
    if(empty($title_err) && empty($description_err) && empty($status_err))
    {
        // Prepare an update statement
        $sql = "UPDATE projects SET title=?, description=?, status=?, log_date=?, log_login=? WHERE id=?";

        if($stmt = mysqli_prepare($connection, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssi", $title, $description, $status, $log_date, $log_login, $param_id);
            
            // Set parameters
            $title      = $title;
            $description   = $description;
            $status        = $status;
            $log_date   = $log_date;
            $log_login     =$_SESSION['login'];
            $param_id = $id;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
            {
                // Records updated successfully. Redirect to landing page
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
else
{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"])))
    {
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM projects WHERE id = ?";
        if($stmt = mysqli_prepare($connection, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
            {
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1)
                {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $title       = $row["title"];
                    $description   = $row["description"];
                    $status     = $row["status"];
                    $log_date       = $row["log_date"];
                    $login = $row["log_login"];

                }
                else
                {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            }
            else
            {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($connection);
    }
    else
    {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    
   <!-- add style css -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body class="bg-light">
    <div class="container">
        <div class="signup-form">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Update Project</h2>
                    </div>
                    <p>Please fill this form and submit to update the project on the database.</p>
                    <form action="<?= htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
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
                        <input type="hidden" name="id" value="<?= $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default" style="color:red;">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>