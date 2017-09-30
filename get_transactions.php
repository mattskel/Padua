<?php

//include("bank_transaction.php");

$fh = fopen($_FILES['file']['tmp_name'], 'r+');

function cmp($a, $b) {
  if ($a->date == $b->date) {
    return 0;
  }
  return ($a->date < $b->date) ? -1 : 1;
}

function VerifyKey($key) {
  
  if (strlen($key) != 10) {
    return false;
  }
  
  $checkDigit = GenerateCheckCharacter(substr($key, 0, 9));
  if ($key[9] == $checkDigit) {
    return true;
  } else {
    return false;
  }
//  return $key[9] == $checkDigit;
}

function GenerateCheckCharacter($input) {
  $validChars = "23456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $factor = 2;
  $sum = 0;
  $n = 34;
  //$n = count($validChars);
  echo "HERE";
  for ($i = strlen($input) - 1; $i >= 0; $i--) {
    $codePoint = strpos($validChars, $input[$i]);
    $addend = $factor * $codePoint;
    if ($factor == 2) {
      $factor = 1;
    } else {
      $factor = 2;
    }
    echo " ";
    echo floor($addend/$n);
    echo " ";
    $addend = floor($addend/$n) + ($addend % $n);
    echo $addend;
    echo " ";
    $sum += $addend;
  }
  $remainder = $sum % $n;
  $checkCodePoint = ($n - $remainder) % $n;
  return $validChars[$checkCodePoint];
//  return 0;
}

$transactions = array();
$lines = array();
$i = 0;
while( ($row = fgetcsv($fh, 8192)) !== FALSE ) {
  $t = new BankTransaction();
  $lines[] = $row;
  $format = 'Y-m-d H:i A';
  $t->date = DateTime::createFromFormat($format, $row[0]);
  $t->transaction_code = $row[1];
  $t->customer_number = $row[2];
  $t->reference = $row[3];
  $t->amount = $row[4];
  
  if (VerifyKey($t->transaction_code)) {
    $t->valid_transaction = "Yes";
  } else {
    $t->valid_transaction = "No";
  }
  
  if ($i > 0) {
    $transactions[] = $t;
  }
  $i = $i + 1;
}

usort($transactions, 'cmp');

?>