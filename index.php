<?php
require_once "checkin.php";
if(isset($_GET['logout'])){
    unset($_SESSION['login']);
    session_destroy();
    header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project List</title>
    <!-- library css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    
</head>
<body class="bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <br>
                <h3 class="titulo-tabla">Project List <a href="index.php?logout=1" class="btn btn-danger pull-right">Logout</a></h3>
                <hr class="bg-dark">
                    <?php
                        // Include config file
                        require_once "config.php";
                        
                        // Attempt select query execution
                        $sql = "SELECT * FROM projects";
                    ?>
                    <?php
                    if($result = mysqli_query($connection, $sql))
                    {
                        if(mysqli_num_rows($result) > 0)
                        {
                    ?>
                            <table id="projects" class="table table-striped table-bordered border-dark" style="width:100%">
                                <thead class="bg-primary table-bordered border-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Log Date</th>
                                        <th>Log Login</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        while($row = mysqli_fetch_array($result))
                                        {
                                        ?>
                                        <tr>
                                            <td><?= $row['id'] ;?></td>
                                            <td><?= $row['title'] ;?></td>
                                            <td><?= $row['description']; ?></td>
                                            <td><?php switch($row['status']){
                                                    case "In progress":
                                                        echo "<span class='badge bg-warning'>".$row['status']."</span>";
                                                        break;
                                                    case "In validation":
                                                        echo "<span class='badge bg-success'>".$row['status']."</span>";
                                                        break;
                                                    case "Closed":
                                                        echo "<span class='badge bg-danger'>".$row['status']."</span>";
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td><?= $row['log_date']; ?></td>
                                            <td><?= $row['log_login'] ;?></td>
                                            <td>
                                                <?php
                                                echo "<a href='read.php?id=". $row['id'] ."' title='View Project' data-toggle='tooltip'> <i class='material-icons' aria-hidden='true'>description</i></a>";
                                                echo "<a href='update.php?id=". $row['id'] ."' title='Update Project' data-toggle='tooltip'> <i class='material-icons' aria-hidden='true' style='color:#3ca23c;'>create</i></a>";
                                                echo "<a href='delete.php?id=". $row['id'] ."' title='Delete Project' data-toggle='tooltip'> <i class='material-icons' aria-hidden='true' style='color:crimson;'>delete_sweep</i></a>";
                                                ?>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>                          
                            </table>
                        <?php
                            // Free result set
                            mysqli_free_result($result);
                        }
                        else
                        {
                            echo "<p class='lead'><em>No projects were found.</em></p>";
                        }
                    }
                    else
                    {
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($connection);
                    }
 
                    // Close connection
                    mysqli_close($connection);
                    ?>
                <a href="create.php" class="btn btn-success pull-left">Add New Project</a>

            </div>
        </div>
    </div>
        <!-- library js -->
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.bootstrap4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.colVis.min.js"></script>
        
       
        <!-- internal script -->
        <script src="js/export.js"></script>

</body>
</html>