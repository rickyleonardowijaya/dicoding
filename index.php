<html>
 <head>
 <Title>Registration Form</Title>
 <style type="text/css">
 	body { background-color: #fff; border-top: solid 10px #000;
 	    color: #333; font-size: .85em; margin: 20; padding: 20;
 	    font-family: "Segoe UI", Verdana, Helvetica, Sans-Serif;
 	}
 	h1, h2, h3,{ color: #000; margin-bottom: 0; padding-bottom: 0; }
 	h1 { font-size: 2em; }
 	h2 { font-size: 1.75em; }
 	h3 { font-size: 1.2em; }
 	table { margin-top: 0.75em; }
 	th { font-size: 1.2em; text-align: left; border: none; padding-left: 0; }
 	td { padding: 0.25em 2em 0.25em 0em; border: 0 none; }
 </style>
 </head>
 <body>
 <h1>Register here!</h1>
 <p>Fill in your name and email address, then click <strong>Submit</strong> to register.</p>
 <form method="post" action="index.php" enctype="multipart/form-data" >
       Nama  <input type="text" name="nama" id="nama"/></br></br>
       Genre <input type="text" name="genre" id="genre"/></br></br>
       Deskripsi <textarea name="deskripsi" id="deskripsi"></textarea></br></br>
       <input type="submit" name="simpan" value="Simpan" />
       <input type="submit" name="tampilData" value="Tampil Data" />
 </form>
 <?php
    $host = "tcp:dicodingappservertest.database.windows.net,1433";
    $user = "rickyleonardowijaya";
    $pass = "Ricky1234%";
    $db = "dicodingdb";
    try {
        $conn = new PDO("sqlsrv:server = $host; Database = $db", $user, $pass);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    } catch(Exception $e) {
        echo "Failed: " . $e;
    }
    if (isset($_POST['simpan'])) {
        try {
            $name = $_POST['nama'];
            $genre = $_POST['genre'];
            $deskripsi = $_POST['deskripsi'];
            // Insert data
            $sql_insert = "INSERT INTO [dbo].[tbl_daftar_game] (nama,genre,deskripsi) VALUES 
            ('".$name."','".$genre."','".$deskripsi."')";
            echo ($sql_insert);
            $stmt = $conn->prepare($sql_insert);
            $stmt->bindValue(1, $name);
            $stmt->bindValue(2, $genre);
            $stmt->bindValue(3, $deskripsi);
            $stmt->execute();
        } catch(Exception $e) {
            echo "Failed: " . $e;
            echo "Data yang dimasukkan tidak valid, Silahkan masukkan ulang dengan lengkap";
        }
        echo "<h3>Your're registered!</h3>";
     } else if (isset($_POST['tampilData'])) {
        try {
            $sql_select = "SELECT * FROM [dbo].[tbl_daftar_game]";
            $stmt = $conn->query($sql_select);
            $registrants = $stmt->fetchAll(); 
            if(count($registrants) > 0) {
                echo "<h2>People who are registered:</h2>";
                echo "<table style='border 1px solid black'>";
                echo "<tr><th>Nomor</th>";
                echo "<th>Nama</th>";
                echo "<th>Genre</th>";
                echo "<th>Deksripsi</th></tr>";
                foreach($registrants as $registrant) {
                    echo "<tr><td>".$registrant['id']."</td>";
                    echo "<td>".$registrant['nama']."</td>";
                    echo "<td>".$registrant['genre']."</td>";
                    echo "<td>".$registrant['deskripsi']."</td></tr>";
                }
                echo "</table>";
            } else {
                echo "<h3>No one is currently registered.</h3>";
            }
        } catch(Exception $e) {
            echo "Failed: " . $e;
        }
    }
 ?>
 </body>
 </html>
