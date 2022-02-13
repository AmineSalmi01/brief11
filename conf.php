<?php
$tva = 0.14;
$calibre = ["small"=>22.65, "medium" =>37.05, "large" =>46.20];
$timbre = 0.45;

class Tranche {
    public $min;
    public $max;
    public $prix_uni;

    function __construct($min, $max, $prix_uni){
        $this->min = $min;
        $this->max = $max;
        $this->prix_uni = $prix_uni;
    }
}

$tranches = [
    new Tranche(0, 100, 0.794),
    new Tranche(101, 150, 0.883),
    new Tranche(151, 210, 0.9451),
    new Tranche(211, 310, 1.0489),
    new Tranche(311, 510, 1.2915),
    new Tranche(511, NULL, 1.4975)
];

function calculate($t_num, $facture, $tarif){
    global $tva;
    return "<tr>
        <td> tranche $t_num </td>
        <td>$facture</td>
        <td>$tarif</td>
        <td>".$facture*$tarif."</td>    
        <td>14%</td>
        <td>".$facture*$tarif*$tva."</td>
        <td class='right'>الشطر $t_num -</td>
        </tr>";
}
?>