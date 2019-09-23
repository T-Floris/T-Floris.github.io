<?php

  //MySQL Host
  $host = "mysql35.unoeuro.com";

  //MySQL Bruger
  $bruger = "tinofloris_me";

  //MySQL Password
  $password = "a9xpmwctd3";

  // MySQL Database
  $database = "tinofloris_me_db";

  // Create database connection
  $conn = mysqli_connect($host, $bruger, $password, $database);

  function createNewFolder($conn, $genre, $image, $maxfile) {
    $NumberOfImagesInFolder = 25;
    
    $imagesGenre = "images/" . $genre;
    //Check if the directory with the name already exists
    if (!is_dir($imagesGenre)) {
    //Create our directory if it does not exist
    mkdir($imagesGenre);
    }
        //SELECT COUNT(images.folder) AS folders from images where folder = (select max(folder) from images) and folderName='Concept art';
    $foldernumber = "SELECT COUNT(images.folder) AS folders from images where folder = (select max(folder) from images) and folderName='$genre'";
    $hvad = mysqli_query($conn, $foldernumber);
    $result = $hvad->fetch_assoc();
    $foldernumber = $result['folders'];
    

    $newfolder = "select max(folder) as max from images where folderName='$genre'";
    $hvad = mysqli_query($conn, $newfolder);
    $result = $hvad->fetch_assoc();
    $newfolder = $result['max'];    


    $imagesFolder = $imagesGenre . "/" . $newfolder;
    if ($foldernumber == $NumberOfImagesInFolder) {
      // code...
    }

    if (!is_dir($imagesFolder)) {
      mkdir($imagesFolder);
    }
    // Get image name
  	$extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
  	// Get text
    $name = $_POST["imageTitel"];   

    RETURN $imagesFolder . "/" . $name . "." .$extension;
  }

  	// If Update is clicked...
	if (isset($_POST['Update'])) {

        $query = "SELECT MAX(images.id) AS maximum from images";

        $sqlquery = mysqli_query($conn, $query);

        $result = $sqlquery->fetch_assoc();

        $maxid = $result['maximum'];



    for ($i=1; $i <= $maxid ; $i++) {

      $query = "UPDATE images SET images.allowToBeShown =  where images.id = $i";
    }     
  }

// If upload button is clicked ...
  if (isset($_POST['upload'])) {

    $query = "SELECT COUNT(images.folder) AS maximum from images where folder = (select max(images.folder) from images)";
    $hvad = mysqli_query($conn, $query);
    $result = $hvad->fetch_assoc();
    $numberOfFile = $result['maximum'];
    //echo $numberOfFile . "<br/>"; //print result on screen
    //if table is empty
    //echo $numberOfFile;
    if ($numberOfFile == 0) {
      $numberOfFile = 1;
    }
    //echo $numberOfFile;


    //echo $numberOfFile . "<br/>"; //print result on screen

    $query = "SELECT max(images.folder) as maxfolder from images";
    $hvad = mysqli_query($conn, $query);
    $result = $hvad->fetch_assoc();
    $foldernumber = $result['maxfolder'];
  //  echo $foldernumber;

    if ($foldernumber == 0) {
      $foldernumber += 1;
    }

    // Create new button if nedet
    if ($numberOfFile == 10) {
      $foldernumber += 1;
    }




    
    
	
    $imageTitel = mysqli_real_escape_string($conn, $_POST['imageTitel']);
  	$image_text = mysqli_real_escape_string($conn, $_POST['image_text']);
    $imageCreator = mysqli_real_escape_string($conn, $_POST['imageCreator']);
    $imageLink = mysqli_real_escape_string($conn, $_POST['imageLink']);
    //$allowToBeShown = mysqli_real_escape_string($conn,$_POST['isAllowToBeShown']);
    $genre = $_POST['genres'];
    $allowToBeShown = (isset($_POST['isAllowToBeShown'])) ? 1 : 0;
    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

  //  echo $allowToBeShown;


  	// image file directory
  	$target = "images/" . $genre ."/" . $foldernumber . "/" . basename($image);

  	$sql = "INSERT INTO images (folderName, folder, imagetypes, imageTitel, image_text, imageCreator, imageLink, allowToBeShown) VALUES ('$genre', '$foldernumber','$extension', '$imageTitel', '$image_text', '$imageCreator', '$imageLink', '$allowToBeShown')";
  	// execute query
  	mysqli_query($conn, $sql);

    //echo "test1" . "<br/>";
    $max = 100;
    //echo $foldernumber + 1;
    
	//tmp_name
  	if (move_uploaded_file($_FILES['image']['tmp_name'], createNewFolder($conn, $genre, $image, $max))) {
  		$msg = "Image uploaded successfully";
  	}else{
  		$msg = "Failed to upload image";
  	}

    //wp_redirect("http://localhost:8080/Unity-Project/create%20folder/index2.php");

  }
  $result = mysqli_query($conn, "SELECT * FROM images");
  //echo "test2" . "<br/>";


  if (isset($_POST['delete'])) {
    // code...
  }



  mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="script\css\index3.css">
    <title>Image Upload</title>
</head>
<body>
  <div id="content">
    <form method="POST" action="cleanup.php" enctype="multipart/form-data">
      <input type="hidden" name="size" value="1000000">
      <div>
        <?php
        ?>
        <select name="genres">
          <?php
          $conn = mysqli_connect("mysql35.unoeuro.com", "tinofloris_me", "a9xpmwctd3", "tinofloris_me_db");
          $query = $conn->query("SELECT folderName FROM folders");
          // Loop through the query results, outputing the options one by one
          while ($row = mysqli_fetch_array($query)) {
            echo '<option value="'.$row['folderName'].'">'.$row['folderName'].'</option>';
          }
          mysqli_close($conn);
          ?>
        </select>

        <br/>
      </div>
      <div>
        <label><input type="checkbox" name="isAllowToBeShown">Is the image allowed to be shown on the side</label>
      </div>
      <div>
        <input type="file" name="image">
      </div>
      <div>
        <textarea
          id="text"
          cols="40"
          rows="4"
          name="image_text"
          placeholder="Say something about this image..."></textarea>
      </div>
      <div>
        <textarea
        id="text"
        cols="40"
        rows="4"
        name="imageTitel"
        placeholder="Title on image..."></textarea>
      </div>
      <div>
        <textarea
        id="text"
        cols="40"
        rows="4"
        name="imageCreator"
        placeholder="who createde the image..."></textarea>
      </div>
      <div>
        <textarea
        id="text"
        cols="40"
        rows="2"
        name="imageLink"
        placeholder="link to creator..."></textarea>
      </div>
      <div>
        <button type="submit" name="upload">POST</button>
        <button type="submit" name="delete">Update database</button>
      </div>
    </form>
    <?php
  //  images/Game/1/1280px-Arrow_east.svg.png
      while ($row = mysqli_fetch_array($result)) {
        if ($row['allowToBeShown'] == 1) {
          echo "<div id='img_div'style='background-color:green'>";
          echo "<img src='images/".$row['folderName'] ."/" .$row['folder'] ."/" .$row['imageTitel']. "." .$row['imagetypes']."' >";
            echo "<p>".$row['image_text']."</p>";
            echo "<p>".$row['imageTitel']."</p>";
            echo "<p>".$row['imageCreator']."</p>";
            echo "<p>".$row['imageLink']."</p>";
            //echo '<input type="'checkbox'" name="'isAllowToBeShown'"value="'. $row['id']' .">';
            echo '<input type="checkbox" name="isAllowToBeShown" value="' . $row['id'] .'" checked="' . $row['allowToBeShown'] .'">';

          echo "</div>";
        }
        elseif ($row['allowToBeShown'] == 0) {
          echo "<div id='img_div'style='background-color:red'>";
          echo "<img src='images/".$row['folderName'] ."/" .$row['folder'] ."/" .$row['imageTitel']. "." .$row['imagetypes']."' >";
            echo "<p>".$row['image_text']."</p>";
            echo "<p>".$row['imageTitel']."</p>";
            echo "<p>".$row['imageCreator']."</p>";
            echo "<p>".$row['imageLink']."</p>";
            echo '<input type="checkbox" name="isAllowToBeShown" value="' . $row['id'] .'" checked="' . $row['allowToBeShown'] .'">';
          echo "</div>";
        }
      }
    ?>
    <button type="submit"></button>
  </div>
</body>
</html>