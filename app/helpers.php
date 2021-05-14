<?php

function getNumber($prefix, $table, $field, $branchid){

	$formatdate = Date('y') . Date('m') . Date('d');
        
    $count = DB::select('SELECT count(' . $field . ') as a FROM ' . $table . ' WHERE ' . $field . ' LIKE "' . $prefix . $formatdate . '%"');
    
    $count = str_pad($count[0]->a +1, 3, '0', STR_PAD_LEFT);
    
    $result = $prefix . $formatdate . $count;
    
    return $result;
}

function removeComma($amount){
    return str_replace(',', '', $amount);
}

function terbilang($x, $style=4) {
    if($x<0) {
        $poin = trim(tkoma($x));
        $hasil = "minus ". trim(kekata($x));
    } else {
        $poin = trim(tkoma($x));
        $hasil = trim(kekata($x));
    }
    switch ($style) {
    case 1:
        if($poin){
            $hasil = strtoupper($hasil) . " KOMA " . strtoupper($poin);
        }else{
            $hasil = strtoupper($hasil);
        }
        
        break;
    case 2:
        if($poin){
            $hasil = strtolower($hasil) . " koma " . strtolower($poin);
        }else{
            $hasil = strtolower($hasil);
        }
        
        break;
    case 3:
        if($poin){
            $hasil = ucwords($hasil) . " Koma " . ucwords($poin);
        }else{
            $hasil = ucwords($hasil);
        }
        
        break;
    default:
        if($poin){
            $hasil = ucfirst($hasil) . " koma " . $poin;
        }else{
            $hasil = ucfirst($hasil);
        }
        
        break;
    }
    return $hasil;
}

function tkoma($x){
    $x = stristr($x,".");

    $angka = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan");

    $temp = " ";
    $pjg = strlen($x);
    $pos = 1;

    while($pos < $pjg){
        $char = substr($x, $pos, 1);
        $pos++;
        $temp .= " " .$angka[$char];
    }
    
    return $temp;
}

function kekata($x) {
    $x = abs($x);
    $angka = array("", "satu", "dua", "tiga", "empat", "lima",
    "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($x <12) {
        $temp = " ". $angka[$x];
    } else if ($x <20) {
        $temp = kekata($x - 10). " belas";
    } else if ($x <100) {
        $temp = kekata($x/10)." puluh". kekata($x % 10);
    } else if ($x <200) {
        $temp = " seratus" . kekata($x - 100);
    } else if ($x <1000) {
        $temp = kekata($x/100) . " ratus" . kekata($x % 100);
    } else if ($x <2000) {
        $temp = " seribu" . kekata($x - 1000);
    } else if ($x <1000000) {
        $temp = kekata($x/1000) . " ribu" . kekata($x % 1000);
    } else if ($x <1000000000) {
        $temp = kekata($x/1000000) . " juta" . kekata($x % 1000000);
    } else if ($x <1000000000000) {
        $temp = kekata($x/1000000000) . " milyar" . kekata(fmod($x,1000000000));
    } else if ($x <1000000000000000) {
        $temp = kekata($x/1000000000000) . " trilyun" . kekata(fmod($x,1000000000000));
    }
    return $temp;
} 
