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
                <a class="flex-sm-fill text-sm-center nav-link active" href="machine.php">Machine</a>
                <a class="flex-sm-fill text-sm-center nav-link" href="evaluation.php">Evaluation</a>
                <a class="flex-sm-fill text-sm-center nav-link" href="collection.php">Collection</a>
            </nav>
            <p>
                <form action="" method ="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Cari..." name="keys" value="<?=isset($_POST['keys']) ? $_POST['keys'] : ''?>">
                        <div class="input-group-append">
                            <input class="btn btn-primary" type="submit" name="submit" value="Cari"/>
                        </div>
                    </div>
                </form>
            </p>
        <?php
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

        <!-- MENAMPILKAN DIAGRAM TRANSII-->
        <h3 class="mt-5 mb-3">Diagram Transisi</h3>
        <table>
            <tr>
                <td>
                    <div style="text-align: center; padding: 0">
                        <p>sigma</p> 
                        <img style="width:50px" src="img/uturn.png" alt=""> 
                    </div> 

                </td>
            <tr>
                <td rowspan="<?php echo count($final_state)*2 ?>">
                    <div>
                        <div style = "display: inline-block">
                        
                            <img style="width:40px;" src="img/right.png" alt="">
                        </div>
                        <div style="background-color: grey; color: white; width: 80px; height: 80px; border-radius: 50%; text-align: center; padding: 2px;display: inline-block">
                                    <p><?php echo $start ?></p>
                        </div>
                        
                    </div>
                </td>
                <td colspan="3"></td>
        <?php 
            for($i=1; $i < count($state); $i++){
        ?>
                <td>
                    <div>
                        <div style = "display: inline-block">
                            <p><?php echo $sigma_cabang_kecil[$i-1].','. $sigma_cabang_kapital[$i-1] ?></p>
                            <?php if($i == 1){
                            ?>
                                <img style="width:20px; height:20px" src="img/up-right.png" alt="">
                            <?php
                                }
                                else{
                            ?>
                                <img style="width:30px;" src="img/right.png" alt="">
                            <?php
                                } 
                            ?>
                        </div>
                        
                        <?php if($state[$i] == $final_state[0]){
                        ?>
                            <div style="background-color: red; color: white; width: 40px; height: 40px; border-radius: 50%; text-align: center; padding: 2px;display: inline-block">
                                <p><?php echo $state[$i] ?></p>
                            </div>
                        <?php
                            }
                            else{
                        ?>
                            <div style="background-color: grey; color: white; width: 40px; height: 40px; border-radius: 50%; text-align: center; padding: 2px;display: inline-block">
                                <p><?php echo $state[$i] ?></p>
                            </div>
                        <?php
                            } 
                        ?>
                        
                        
                    </div>
                </td>
        <?php
                if($state[$i] == $final_state[0]){
                    $sampe=$i+1;
                break;
                }      
            }
        ?>

            </tr>
            <tr></tr>

            
        <?php
            for($i=0; $i < count($key)-1; $i++){
        ?>
            <tr>
                <td colspan="3"></td>
            <?php 
                for($j=$sampe; $j <= strlen($key[$i+1])+($sampe-1); $j++){
            ?>
                    <td>
                        <div>
                            <div style = "display: inline-block">
                                <p><?php echo $sigma_cabang_kecil[$j-1].','. $sigma_cabang_kapital[$j-1] ?></p>
                                <?php if($j == $sampe){
                                ?>
                                    <img style="width:20px; height:20px" src="img/down-right.png" alt="">
                                <?php
                                    }
                                    else{
                                ?>
                                    <img style="width:30px;" src="img/right.png" alt="">
                                <?php
                                    } 
                                ?>
                            </div>
                            
                            
                            <?php if($j == strlen($key[$i+1])+($sampe-1)){
                            ?>
                                <div style="background-color: red; color: white; width: 40px; height: 40px; border-radius: 50%; text-align: center; padding: 2px;display: inline-block">
                                    <p><?php echo $state[$j] ?></p>
                                </div>
                            <?php
                                }
                                else{
                            ?>
                                <div style="background-color: grey; color: white; width: 40px; height: 40px; border-radius: 50%; text-align: center; padding: 2px;display: inline-block">
                                    <p><?php echo $state[$j] ?></p>
                                </div>
                            <?php
                                } 
                            ?>
                            
                            
                        </div>
                    </td>
            <?php
                    
                }
                $sampe=$j;
                
            ?>
                    
            </tr>
            <tr></tr>
        <?php 
                  
            }
        
        ?>
            

        </table>

        <!-- MENAMPILKAN DIAGRAM TRANSII-->
        <h3 class="mt-5 mb-3">Quintuple NFA</h3>
        <table class="table table-bordered">
            <tr>
                <td>Q</td>
                <td>{
                <?php
                    //menampilkan state
                    foreach($state as $q){
                        echo $q . ",";
                    }
                ?>
                }
                </td>
            </tr>
            <tr>
                <td>Start State</td>
                <td>
                <?php
                    echo $start;
                    
                ?>
                </td>
            </tr>
            <tr>
                <td>Final State</td>
                <td>{
                <?php
                     //menampilkan final state
                    foreach($final_state as $q){
                        echo $q . ",";
                    }
                ?>
                }
                </td>
            </tr>
            <tr>
                <td>Sigma</td>
                <td>{
                <?php
                    //menampilkan sigma
                    foreach($sigma as $a){
                        echo $a . ",";
                    }
                ?>
                }
                </td>
            </tr>
            <tr>
                <td>Delta</td>
                <td>
                <?php
                    //menampilkan semua transisi/delta
                    for($i=0; $i < count($delta); $i++){
                        echo $delta[$i][0]." --> ".$delta[$i][1]." --> ".$delta[$i][2]."<br>";
                    }
                ?>
                </td>
            </tr>
        </table>

        <?php 
            
            } 
            ?>
        </div>
    </body>
</html>