<?php
include("bank_transaction.php");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Padua</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>
  <body>
    <div class="container-fluid">
      <h1>Upload new CSV</h1>
      <form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data">
        <input type="file" name="file">
        <input type="submit" name="btn_submit" value="Upload File">
      </form>
      <?php
      // Loop over file data
      // Populate transaction class
      if ($_FILES['file']['size'] != 0) {
        include("get_transactions.php");
        ?>
      <h1>Bank Transaction from CSV</h1>
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Date</th>
            <th>Transaction Code</th>
            <th>Valid Transaction</th>
            <th>Customer Number</th>
            <th>Reference</th>
            <th>Amount</th>
          </tr>
        </thead>
        <tbody>
          <?php
        foreach ($transactions as $t) {
          ?>
          <tr>
            <td><?php echo $t->date->format('Y-m-d H:i A'); ?></td>
            <td><?php echo $t->transaction_code; ?></td>
            <td><?php echo $t->valid_transaction; ?></td>
            <td><?php echo $t->customer_number; ?></td>
            <td><?php echo $t->reference; ?></td>
            <td class="red"><font color="red"><?php echo $t->amount; ?></font></td>
          </tr>
          <?php
        }
          ?>
        </tbody>
      </table>
      <?php
      } else {
      ?>
      <p>Nothing to see here</p>
      <?php
      }
      ?>
    </div>
  </body>
</html>

