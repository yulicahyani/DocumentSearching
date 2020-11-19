<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title>Web Searching</title>
</head>
<body>

    <div class="container">
        <h1 class="display-4 px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">Document Searching</h1>
        <form action="" method ="post">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Cari..." name="keys" value="<?=isset($_POST['keys']) ? $_POST['keys'] : ''?>">
                <div class="input-group-append">
                    <input class="btn btn-primary" type="submit" name="submit" value="Cari"/>
                </div>
            </div>
        </form>
        <nav class="nav nav-pills flex-column flex-sm-row">
            <a class="flex-sm-fill text-sm-center nav-link" href="index_search.php">Search</a>
            <a class="flex-sm-fill text-sm-center nav-link" href="machine.php">Machine</a>
            <a class="flex-sm-fill text-sm-center nav-link" href="evaluation.php">Evaluation</a>
            <a class="flex-sm-fill text-sm-center nav-link active" href="collection.php">Collection</a>
        </nav>
        <div style="background-color: whitesmoke">
            <div class="mt-5  px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="file" id="file" name="files[]" multiple="multiple" />
                    <input type="submit" value="Upload">
                </form>
            </div>
            <?php
                $format_file = array("txt");
                $max_file_size = 10 * 1024 * 1024; //maksimal 10 mb
                $path = "upload/"; // Lokasi folder untuk menampung file
                $count = 0;

                if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
                    // Loop $_FILES to exeicute all files
                    foreach ($_FILES['files']['name'] as $f => $name) {     
                        if ($_FILES['files']['error'][$f] == 4) {
                            continue; // Skip file if any error found
                        }	       
                        if ($_FILES['files']['error'][$f] == 0) {	           
                            if ($_FILES['files']['size'][$f] > $max_file_size) {
                                $message[] = "$name is too large!.";
                                continue; // Skip large files
                            }
                            elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $format_file) ){
                                $message[] = "$name is not a valid format";
                                continue; // Skip invalid file formats
                            }
                            else{ // No error found! Move uploaded files 
                                if(move_uploaded_file($_FILES["files"]["tmp_name"][$f], $path.$name))
                                $count++; // Number of successfully uploaded file
                            }
                        }
                    }
            ?>
            <p class="mt-3  px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center"><?php echo 'berhasil upload '.$count.' files';?></p> 
            <?php
                    
                }
            ?>
            <br>
            <div class="mt-2  px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
                <button class="btn btn-secondary" onclick="window.location.href='upload/'">Lihat Koleksi</button>
                <form style="margin-top:30px" action="hapus.php">
                    <button class="btn btn-secondary">Hapus Koleksi</button>
                </form>
            </div>


        </div>
        
    </div>
 
</body>
</html>

