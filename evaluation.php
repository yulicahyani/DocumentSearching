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
        <nav class="nav nav-pills flex-column flex-sm-row">
            <a class="flex-sm-fill text-sm-center nav-link" href="index.php">Search</a>
            <a class="flex-sm-fill text-sm-center nav-link" href="machine.php">Machine</a>
            <a class="flex-sm-fill text-sm-center nav-link active" href="evaluation.php">Evaluation</a>
            <a class="flex-sm-fill text-sm-center nav-link" href="collection.php">Collection</a>
        </nav>
        <h3 class ="mt-4 mb-2">Pengujian Akurasi</h3>
        <div>
    <?php 
        $folder = "upload/"; //Sesuaikan Folder nya
        if(!($buka_folder = opendir($folder))) die ("eRorr... Tidak bisa membuka Folder");
    
        $file_array = array();
            while($baca_folder = readdir($buka_folder)){
                if(substr($baca_folder,0,1) != '.'){
                $file_array[] =  $baca_folder;
                            
                }
            }             
    ?>
    <p class="text-info">Jumlah koleksi dokumen : <?php echo count($file_array)?></p>
</div>
        <form action="" method ="post">
        <p>
                Masukan dokumen yang akan digunakan :
                <div class="form-row align-items-center">
                    <div class="col-auto">
                        doc ke-
                    </div>
                    <div class="col-auto">
                    <label class="sr-only" for="inlineFormInput">doc ke</label>
                    <input type="text" class="form-control mb-2" id="inlineFormInput" name="awal" value="<?=isset($_POST['awal']) ? $_POST['awal'] : ''?>">
                    </div>
                    <div class="col-auto">
                        sampai dengan doc ke-
                    </div>
                    <div class="col-auto">
                    <label class="sr-only" for="inlineFormInputGroup">sampai doc ke</label>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" id="inlineFormInputGroup" name="akhir" value="<?=isset($_POST['akhir']) ? $_POST['akhir'] : ''?>">
                    </div>
                    </div>
                </div>
            </p>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Cari..." name="keys" value="<?=isset($_POST['keys']) ? $_POST['keys'] : ''?>">
                <div class="input-group-append">
                    <input class="btn btn-primary" type="submit" name="submit" value="Cari"/>
                </div>
            </div>
        </form>
        
        <?php
            $awal_w = microtime(true);
            if (isset($_POST['submit'])){
                //Inputan keywords
                $keywords = $_POST['keys'];

                //menentukan jumlah state
                //1. split string masukan keyword menjadi array
                $key = explode(" ", $keywords);
                //2. gabungkan string array untuk menentukan jumlah Q
                $keygabung = "";
                foreach($key as $val){
                    $keygabung = $keygabung.$val;
                }
                $n_state = strlen($keygabung);


                //mennetukan state
                $state = array();
                for($i=0; $i <= $n_state; $i++){
                    $state[] = 'q'.$i;
                }


                //mennetukan start state
                $start = 'q0';

                //menentukan final state
                $final_state = array();
                $j = 0;
                for($i=0; $i < count($key); $i++){
                    $j = $j + strlen($key[$i]);
                    $final_state[] = $state[$j];
                }



                //menentukan sigma atau inputan simbol pada fna
                $sigma = array();
                for ($i=0; $i <=183 ; $i++) {
                    $sigma[] = chr($i);
                }


                //menetukan sigma untuk percabangaan berdasarkan keywords
                //1. huruf kecil
                $sigma_cabang_kecil = array();
                for($i = 0; $i < strlen($keygabung); $i++){
                    $sigma_cabang_kecil [] = substr($keygabung,$i,1);
                }
                //2. huruf kapital
                $sigma_cabang_kapital = array();
                for($i = 0; $i < strlen($keygabung); $i++){
                    $sigma_cabang_kapital [] = substr(strtoupper($keygabung),$i,1);
                }


                //mennetukan delta atau transisi
                $delta = array();
                //1. menentukan transisi untuk pada awal inputan untuk q0(start state)
                for($i=0; $i < count($sigma); $i++){
                    $delta[] = [$start,$start,$sigma[$i]];
                }
                //2. mennetukan transisi untuk state percabangan --> ini untuk huruf kecil
                $k = 0;
                for($i=1; $i < count($state); $i++){
                    $delta[] = [$state[$k],$state[$i],$sigma_cabang_kecil[($i-1)]];
                    $k=$i;
                    foreach($final_state as $q){
                        if($state[$i] == $q){
                            $k=0;
                        }
                    }
                }
                //3. mennetukan transisi untuk state percabangan --> ini untuk huruf kapital
                $k = 0;
                for($i=1; $i < count($state); $i++){
                    $delta[] = [$state[$k],$state[$i],$sigma_cabang_kapital[($i-1)]];
                    $k=$i;
                    foreach($final_state as $q){
                        if($state[$i] == $q){
                            $k=0;
                        }
                    }
                }
        ?>

        <?php
                //implementasi text search        
                //membuka folder
                $folder = "upload/"; //Sesuaikan Folder nya
                if(!($buka_folder = opendir($folder))) die ("eRorr... Tidak bisa membuka Folder");

                $file_array = array();
                while($baca_folder = readdir($buka_folder)){
                    if(substr($baca_folder,0,1) != '.'){
                        $file_array[] =  $baca_folder;
                        
                    }
                }

                //membuka file
                $awal = $_POST['awal']-1;
                $akhir = $_POST['akhir']-1;
                $nomor = 0;
                $h_pencarian = array();
                $text = array();
    
                for($m=$awal; $m <= $akhir; $m++){
                    $nama_file = $file_array[$m];
                    
                    $data = "upload/$nama_file";
                    $bukafile = fopen($data, "r");
    
                    if (!$bukafile){
                        echo "File $data gagal dibuka ! ";
                    }
        
                    $input = "";
                    while (!feof($bukafile)){
                        $ch = fgetc($bukafile);
                        $input = $input.$ch;
                    }
                    //echo $input;
                    //echo "<br>";
    
                    //proses pengecekan dengan NFA
                    $current_state = [$start];
                    $s_q = array();
                    $accepted = FALSE;
        
                    for($i=0; $i < strlen($input); $i++){
                        $c = substr($input,$i,1);
                        for($k=0; $k < count($current_state); $k++){
                            for ($j= 0; $j < count($delta); $j++){
                                if($delta[$j][0] == $current_state[$k] AND $delta[$j][2] == $c){
                                    $s_q [] = $delta[$j][1];
                                    //echo "<br>".print_r($delta[$j]);
        
                                    foreach($final_state as $fq){
                                        if($delta[$j][1] == $fq && (substr($input,($i+1),1) == " " || $i == strlen($input) - 2 || substr($input,($i+1),1) == "." || substr($input,($i+1),1) == "," || substr($input,($i+1),1) == "-" || substr($input,($i+1),1) == chr(10))){
                                            $accepted = TRUE;
                                        }
                                    }
                                }
        
                            }
                        }
                        $current_state = $s_q;
                        $s_q = [];
                        //mencetak nama document yang accepted atau mengandung keywords
                        if ($accepted == TRUE){
                            //echo "accepted";
                            $nomor = $nomor + 1;
                            // echo "<br>$nomor. <a href='DokumenBasaBali/$nama_file'>$nama_file</a><br/>";
    
                            $text [$nomor-1] = $input ;
                            $h_pencarian [$nomor-1] = $nama_file;
                        break;
                        }
                        
                    }
    
                    fclose($bukafile);
     
                }
    
                closedir($buka_folder);
                
                //menghitung lama waktu pencarian
                $akhir_w = microtime(true);
                $lama = $akhir_w - $awal_w;
        ?>

        

        <div class="mt-4 text-info mb-2">
            <h5>Sekitar <?php echo $nomor?> hasil (<?php echo $lama?> detik)</h5>
        </div>
        <?php
                //mencetak nama document yang accepted atau mengandung keywords
                for($i=0; $i < count($h_pencarian); $i++){
        ?>

            <div class="mt-4 border-bottom">
                <a href='upload/<?php echo $h_pencarian[$i] ?>'><?php echo $h_pencarian[$i] ?></a><br>
                
                <div  class="text-secondary">
                <?php
                            for($p=0; $p < strlen($text[$i])/8; $p++){
                                echo substr($text[$i],$p,1);
                            }
                ?>
                </div>
            </div>
            
        <?php
                    }

        ?>
        <h3 class ="mt-5 mb-3">Hasil Pengujian Akurasi</h3>
        <form onsubmit="return false" class="form-inline">
            <div class="form-group mb-2">
                <label class="mr-2">Jumlah doc hasil pencarian</label>
                <input type="text" class="form-control" id="jml1" value="<?php echo $nomor ?>">
            </div>
            <div class="form-group mx-sm-3 mb-2">
                <label class="ml-4 mr-2">Jumlah doc mengandung keyword</label>
                <input type="text" class="form-control" id="jml2">
            </div>
            <button type="submit" class="btn btn-primary mb-2" id="hitung">hitung</button>
        </form>
        
        <div>
            <form onsubmit="return false" class="mt-2 form-inline">
                <div class="form-group mb-2 display-4 px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
                        <label for="inputPassword2" class="sr-only">Hasil pengujian akurasi</label>
                        <input style="height:100px; width:300px" type="text" class="form-control text-center" id="hasil" placeholder="Hasil Uji">
                </div>
            </form>
        </div>



        <!-- script untuk menghitung hasil uji -->
        <script>
            document.getElementById("hitung").
            addEventListener("click", hitung_hasil_uji);

            function hitung_hasil_uji(){
                var x = parseFloat(document.getElementById("jml1").value);
                var y = parseFloat(document.getElementById("jml2").value);
                var hasil_uji = y/x*100;
                document.getElementById("hasil").value=hasil_uji.toFixed(2) + " %";
            }
        
        </script>
       
        <?php
        }
        ?>
         

        </div> 
    </body>
</html>
