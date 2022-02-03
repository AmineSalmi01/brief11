<?php
  // config
  class Tranche {
    public $borneMin;
    public $borneMax;
    public $tarif;

    function __construct($bmin, $bmax, $tar){
        $this->borneMin = $bmin;
        $this->borneMax = $bmax;
        $this->tarif = $tar;
    }

    function infos(){
        echo "Borne min: $this->borneMin. Borne max: $this->borneMax. Tarif: $this->tarif";
    }
}

$tarifs = [
    new Tranche(0, 100, 0.794), 
    new Tranche(101, 150, 0.883),
    new Tranche(151, 210, 0.9451),
    new Tranche(211, 310, 1.0489),
    new Tranche(311, 510, 1.2915),
    new Tranche(511, null, 1.4975)
];


 $tva = 14;
 $timbre = 0.45;
 $redevance= ["small" => 22.65, "medium" => 37.05, "large" => 46.20];

 $oldIndex;
 $newIndex;
 $consommation;
 $montantsFacture = array(); // tbleau fin ghanstokiw mantants facturé
 $montantsHT = array(); // tableau fin ghanstokiw montants HT
 $totalmontantsHT_TVA= [];
 $totalmontantsHT = [];

 if (isset($_POST["submit"])) {
    $oldIndex = $_POST["oldIndex"];
    $newIndex = $_POST["newIndex"];
    $calibre = $_POST["calibre"];
    $consommation = $newIndex - $oldIndex;
    // $consommation <= 150
    if($consommation <= 150) {
        // $consommation <= 100
        if($consommation <= $tarifs[0]->borneMax) {
            $montantsFacture[0] = $consommation;
            $montantsHT[0] = $consommation * $tarifs[0]->tarif;
        }
        // 100 < $consommation <= 150
        else {
            $montantsFacture[0] = 100;
            $montantsFacture[1] = $consommation - $montantsFacture[0];
            $montantsHT[0] = $montantsFacture[0] * $tarifs[0]->tarif;
            $montantsHT[1] = $montantsFacture[1] * $tarifs[1]->tarif;
        }
    }
    // $consommation > 150
    else {
        if($consommation <= $tarifs[2]->borneMax) {
            $montantsFacture[2] = $consommation;
            $montantsHT[2] = $consommation * $tarifs[2]->tarif;
        }
        else if($consommation <= $tarifs[3]->borneMax) {
            $montantsFacture[3] = $consommation;
            $montantsHT[3] = $consommation * $tarifs[3]->tarif;
        }
        else if($consommation <= $tarifs[4]->borneMax) {
            $montantsFacture[4] = $consommation;
            $montantsHT[4] = $consommation * $tarifs[4]->tarif;
        }
        else{
            $montantsFacture[5] = $consommation;
            $montantsHT[5] = $consommation * $tarifs[5]->tarif;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
    <title>calcul</title>
</head>
<body>
    <div class="myForm">
    <form class="row" action="index.php" method="POST">
        <input class ="col-2 m-2" type="text" placeholder="Ancien index" name="oldIndex"><br>
        <input class="col-2" type="text" placeholder="Nouvel index" name="newIndex" > <br>
        <h5>Calibre:</h5> 
        <input type="radio" value="small" name="calibre" >small
        <input type="radio" value="medium" name="calibre">medium
        <input type="radio" value="large" name="calibre">large
        <input class="col-1" type="submit" value="calcul" name="submit">
    </form>
    </div>
    <table class = "m-2 table  table-dark table-striped">
        <tr>
            <td></td>
            <td>Facturé</td>
            <td>P.U</td>
            <td>Montant HT</td>
            <td>Taux TVA</td>
            <td>Montant Taxes</td>
            <td></td>
        </tr>
        <?php
        if (isset($_POST["submit"])) {
            foreach($montantsFacture as $key => $value) {
        ?>
        <tr>
            <td></td>
            <td><?php echo $value ?></td>
            <td><?php echo $tarifs[$key]->tarif ?></td>
            <td><?php echo $montantsHT[$key] ?></td>
            <td><?php echo $tva . "%";?></td>
            <td><?php echo $montantsHT[$key] * $tva /100 ?></td>

            <?php
             array_push($totalmontantsHT_TVA, $montantsHT[$key] * $tva /100);
             array_push($totalmontantsHT, $montantsHT[$key]);
            ?>
            <td></td>
        </tr>

        <?php
            }
        }
        
        ?>

        <tr>
        <td>REDEVANCE FIXE ELECTRICE</td>
            <?php 
                if (isset($_POST["submit"])) { 
                    array_push($totalmontantsHT,$redevance[$calibre] );
            }  
            ?>
            <td></td>
            <td></td>
            <td><?php if (isset($_POST["submit"])) { echo $redevance[$calibre]; } ?></td>
            <td><?php if (isset($_POST["submit"])) { echo $tva . "%";  } ?></td>
            <td><?php if (isset($_POST["submit"])) { array_push($totalmontantsHT_TVA, $redevance[$calibre] * $tva /100); echo $redevance[$calibre] * $tva /100;  } ?></td>
            <td>إثاوة ثابتة الكهرباء</td>
        </tr>
            <tr>
                <td colspan='6'>TAXES POUR LE COMPTE DE L'ETAT</td>
                <td>الرسوم المؤداة لفائدة الدولة</td>
            </tr>
        <tr>
            <td>TOTAL TVA</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?php  if (isset($_POST["submit"])) { echo array_sum($totalmontantsHT_TVA);}?></td>
            <td>مجموع ض ق م</td>
        </tr>

    <tr>
        <td>Timbre</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><?php  if (isset($_POST["submit"])) { echo $timbre; }?></td>
        <td>الطابع</td>
    </tr>

    <tr>
        <td>SOUS-TOTAL</td>
        <td></td>
        <td></td>
        <td><?php if (isset($_POST["submit"])) { echo array_sum($totalmontantsHT);} ?></td>
        <td></td>
        <td><?php  if (isset($_POST["submit"])) { echo array_sum($totalmontantsHT_TVA) + $timbre; }?></td>
        <td>المجموع الجزئي</td>
    </tr>
        

    <tr>
    <td>TOTAL ELECTRICE</td>
    <td></td>
    <td></td>
    <td><?php if (isset($_POST["submit"])) { echo array_sum($totalmontantsHT) + array_sum($totalmontantsHT_TVA) + $timbre ;} ?></td>
    <td></td>
    <td></td>
    <td>مجموع الكهرباء</td>
    </tr>
    
    </table>

    <?php?>
</body>
</html>
 