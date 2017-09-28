<?php

include("bank_transaction.php");

$fh = fopen($_FILES['file']['tmp_name'], 'r+');

//$validChars = array("2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
//$validChars = "23456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

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

$lines = array();
$transactions = array();
$i = 0;
while( ($row = fgetcsv($fh, 8192)) !== FALSE ) {
  
  $t = new BankTransaction();
  $lines[] = $row;
  $t->date = $row[0];
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
?>
<table>
  <tr>
    <th>Date</th>
    <th>Transaction Code</th>
    <th>Valid Transaction</th>
    <th>Customer Number</th>
    <th>Reference</th>
    <th>Amount</th>
  </tr>
<?php
foreach ($transactions as $t) {
  ?>
  <tr>
    <td><?php echo $t->date; ?></td>
    <td><?php echo $t->transaction_code; ?></td>
    <td><?php echo $t->valid_transaction; ?></td>
    <td><?php echo $t->customer_number; ?></td>
    <td><?php echo $t->reference; ?></td>
    <td><?php echo $t->amount; ?></td>
  </tr>
  <?php
}
?>
</table>


