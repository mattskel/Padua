<?php

$fh = fopen($_FILES['file']['tmp_name'], 'r+');

// Sort function
// The function sorts by date
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
}

function GenerateCheckCharacter($input) {
  $validChars = "23456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $factor = 2;
  $sum = 0;
  $n = strlen($validChars);
  for ($i = strlen($input) - 1; $i >= 0; $i--) {
    $codePoint = strpos($validChars, $input[$i]);
    $addend = $factor * $codePoint;
    if ($factor == 2) {
      $factor = 1;
    } else {
      $factor = 2;
    }
    $addend = intval($addend/$n) + ($addend % $n);
    $sum += $addend;
  }
  $remainder = $sum % $n;
  $checkCodePoint = ($n - $remainder) % $n;
  return $validChars[$checkCodePoint];
}

// The following code block reads the csv file and creates a list of BankTransactions
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
  $t->amount = $row[4]/100;
  
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

// Transaction are sorted by date
usort($transactions, 'cmp');

?>