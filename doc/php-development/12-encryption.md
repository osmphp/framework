# Encryption #

This module used for handling security aspects, including hashing. 

Hashing is used to encrypt and decrypt data.
You probably know that user passwords should not be stored in database without encryption.
So data used for authentication should be hashed in your database, because this ensure that passwords are not recoverable from the database.

Hash values also are used to retrieve data from database faster than just searching by original value.

Later on private and public key encryption will be supported.

{{ child_pages }}