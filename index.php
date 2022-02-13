<?php
    include "conf.php";
    $ancien_index = $_POST["ancien"];
    $nouvel_index = $_POST["nouvel"];
    $consommation = $nouvel_index - $ancien_index;
    $cal = $_POST["calibr"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    
    <form action="index.php" method="POST">
        <input name="ancien" type="text" placeholder="Ancien Index">
        <input name="nouvel" type="text" placeholder="Nouvel Index">
        <input name="calibr" id="r1" type="radio" value="small"><label for="r1">Small</label>
        <input name="calibr" id="r2" type="radio" value="medium"><label for="r2">Medium</label>
        <input name="calibr" id="r3" type="radio" value="large"><label for="r3">Large</label>
        <input type="submit">
    </form>
    <table class="table table-striped">
     <thead>
            <th></th>
            <th>Facturé</th>
            <th>P.U</th>
            <th>Montant HT</th>
            <th>Taux TVA</th>
            <th>Montant Taxes</th>
            <th></th>
        </thead>
        <tbody>
            <tr>
                <th colspan="4">Consommation Electricite</th>
                <th colspan="3" class="right">إستهلاك الكهرباء</th>
            </tr>
            <?php
                $montantHT = 0;
                $montantTaxes = 0;
                $montantTotal = 0;
                if($consommation <= 150){
                    if($consommation <= 100){
                        echo calculate(1, $consommation, $tranches[0]->prix_uni);
                        $montantHT+=$consommation*$tranches[0]->prix_uni;
                    }
                    else{
                        echo calculate(1, $tranches[0]->max, $tranches[0]->prix_uni);
                        echo calculate(2, $consommation-$tranches[0]->max, $tranches[1]->prix_uni);
                        $montantHT += $tranches[0]->max * $tranches[0]->prix_uni;
                        $montantHT += ($consommation - $tranches[0]->max)*$tranches[1]->prix_uni;
                    }
                }
                else{
                    if($consommation <= 210){
                        echo calculate(3, $tranches[2]->max, $tranches[2]->prix_uni);
                        $montantHT += $tranches[2]->max * $tranches[2]->prix_uni;
                    }
                    else if($consommation <= 310){
                        echo calculate(4, $tranches[3]->max, $tranches[3]->prix_uni);
                        $montantHT += $tranches[3]->max * $tranches[3]->prix_uni;
                    }
                    else if($consommation <= 510){
                        echo calculate(5, $tranches[4]->max, $tranches[4]->prix_uni);
                        $montantHT += $tranches[4]->max * $tranches[4]->prix_uni;
                    }
                    else{
                        echo calculate(6, $tranches[5]->max, $tranches[5]->prix_uni);
                        $montantHT += $tranches[5]->max * $tranches[5]->prix_uni;
                    }
                }

                echo "<tr>
                         <th colspan='3'>Redevance fixe electricite</th>
                         <td>". $calibre[$cal] ."</td>
                         <td>14%</td>
                         <td>".$calibre[$cal]*$tva."</td>
                         <th class='right'>إثاوة ثابثة الكهرباء</th>
                      </tr>";
                      $montantHT += $calibre[$cal];
            ?>
            <tr>
                <th colspan="6">TAXES POUR LE COMPTE DE L'ETAT</th>
                <th class="right">الرسوم المؤداة لفائدة الدولة</th>
            </tr>
            <tr>
                <td colspan="5">Total TVA</td>
                <td><?php echo ($montantHT)*$tva ?></td>
                <td class="right">مجموع ض.ق.م</td>
            </tr>
            <tr>
                <td colspan="5">TIMBRE</td>
                <td><?php echo $timbre?></td>
                <td class="right">الطابع</td>
            </tr>
            <tr>
                <th colspan='3'>SOUS-TOTAL</th>
                <th colspan="2"><?php echo $montantHT;?></th>
                <th><?php echo ($montantHT * $tva) + $timbre;?></th>
                <th class="right">المجموع الجزئي</th>
            </tr>
            <tr>
                <th colspan='4'>TOTAL ELECTRICITE</th>
                <th colspan="2"><?php
                    $montantTotal = $montantHT + ($montantHT*$tva) + $timbre;
                    echo $montantTotal;
                ?></th>
                <th class='right'>مجموع الكهرباء</th>
            </tr>
        </tbody>
     </table>
</body>
</html>