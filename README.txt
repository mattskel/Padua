The following program is a simple web application that allows a user to upload a .csv and displays the contents of the file in a table format. The program assumes the .csv file is correctly formatted and the first row is column names.

The Model-View-Controller design pattern was implemented. 

The bank_transaction.php file is the model and defines the BankTransaction class. This class encapsulates a bank transaction and stores all the relevant information associated with a bank transaction. 

The index.php file is the view. The view defines the html and css styles that are presented on the page in the browser. A form is used to handle the file upload. 

The get_transactions.php is the controller. The controller is responsible for reading the uploaded file and converting this to an array of BankTransaction class objects. The file also defines the functions for checking the transaction code using the Luhn Mod N algorithm.