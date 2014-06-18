YanlJ
======
YanlJ is an interactive wiki platform built with PHP on a postgresql database. Its goal is a superior experience for documenting, browsing, and interacting with things you know.

#Install
After cloning, and once in the YanlJ dir, make an authorization file with a desired username and password:
```
echo usr_name > auth.txt
echo some_pwd >> auth.txt
```

Now run the database setup script: `bash setup/setup_db.sh`

You can find more detailed instructions, including how to setup an nginx server with PHP to host YanlJ, at http://easythereentropy.wordpress.com/2014/05/29/getting-setup-with-nginx-php-and-postgresql/

#Contributing
YanlJ needs lots of work! Comments, suggestions, criticisms, pull requests very welcome :)
