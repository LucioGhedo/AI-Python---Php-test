<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php

    $w1 = 0.1344;
    $w2 = 0.8474;
    $b = 0.7638; // bias

    // dataset per istruire la rete
    $dataset=[[9,7.0,0], [2,5.0,1], [3.2,4.94,1], [9.1,7.46,0], [1.6,4.83,1],
    [8.4,7.46,0], [8,7.28,0], [3.1,4.58,1], [6.3,9.14,0], [3.4,5.36,1]];

    function RN($m1, $m2, $w1, $w2, $b){
    $t = ($m1 * $w1) + ($m2 * $w2) + $b;
    return sigmoide($t);
    }

    function sigmoide($t){
    return (1 / (1 + exp(-$t)));
    }

    //definisco la derivata della funzione sigmoide
    function sigmoide_p($t){
    return sigmoide($t)*(1 - sigmoide($t));
    }

    function train($dataset){

    //pesi inizializzati inizialmente in modo casuale
    $random = srand(1);
    $w1 = $random;
    $w2 = $random;
    $b = $random;

    $iterazioni = 10000;  //numero di iterazioni 10000
    $learning_rate = 0.1;  //imposto il learning rate 0.1

    $output = array();

    for($i=0; $i<$iterazioni; ++$i){

        // indice casuale compreso tra zero e il numero di elementi nel dataset
        $point = array_rand($dataset); // genero un indice casuale // prendo un gatto casuale dal dataset

        // uso i valori di peso e lunghezza del gatto dall'elemento del dataset appena recuperato
        $z = (($dataset[$point][0] * $w1) + ($dataset[$point][1] * $w2) + $b);

        $pred = sigmoide($z); // previsione della rete

        $target = $dataset[$point][2]; // il mio valore obiettivo // tipo di gatto da prevedere
        
        $cost = pow(($pred - $target),2); // costo del punto casuale attuale

        // CALCOLO DELLE DERIVATE PARZIALI
        $dcost_dpred = 2 * ($pred - $target); // derivata parziale del costo rispetto alla previsione
        $dpred_dz = sigmoide_p($z); // derivata parziale della previsione rispetto a z

        $dz_dw1 = $dataset[$point][0]; // derivata parziale di z rispetto a w1
        $dz_dw2 = $dataset[$point][1]; // derivata parziale di z rispetto a w2
        $dz_db = 1;         // derivata parziale di z rispetto a b

        $dcost_dz = $dcost_dpred * $dpred_dz; // derivata parziale di z rispetto alla previsione (uso la regola della catena)
        
        //REGOLA DELLA CATENA
        $dcost_dw1 = $dcost_dz * $dz_dw1;  //derivata parziale del costo rispetto a w1
        $dcost_dw2 = $dcost_dz * $dz_dw2;  //derivata parziale del costo rispetto a w2
        $dcost_db = $dcost_dz * $dz_db;  //derivata parziale del costo rispetto a b
        
        //aggiornamento dei pesi e del bias
        $w1 = $w1 - $learning_rate * $dcost_dw1;
        $w2 = $w2 - $learning_rate * $dcost_dw2;
        $b = $b - $learning_rate * $dcost_db;
    }

    $output = [$w1, $w2, $b];
    return $output;
    }

    $val_trained = train($dataset);
    //echo print_r($val_trained);

    $w1 = $val_trained[0];
    $w2 = $val_trained[1];
    $b = $val_trained[2];

    echo "
    w1:$w1 , w2:$w2 , b:$b";

    // otteniamo quasi 0
    //$m1 = 9;
    //$m2 = 7.0;

    // otteniamo quasi 1
    $m1 = 2;
    $m2 = 5.0;

    $previsione = RN($m1, $m2, $w1, $w2, $b);
    echo "
    previsione: ".$previsione;

    $pred = array(); //array vuoto che conterrà le previsioni

    foreach($dataset as $gatto){
    $z = $w1 * $gatto[0] + $w2 * $gatto[1] + $b;
    $prediction = sigmoide($z);
    if($prediction <= 0.5){
    $pred[] = 'giungla'; //aggiungi la stringa "giungla" all'array pred
    }else{
    $pred[] = 'sabbie';//altrimenti aggiungi la stringa "sabbie" all'arrat pred
    }
    }

    echo "
    ".print_r($pred);
    ?>
</body>
</html>