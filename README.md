EAV Example with Pricefiles from FTP Servers
=============

Magento has an infamous database structure, known as the EAV (Entity Attribute Value) Model which essentially locks a developer into it, as the foreign keys on their tables can number as many as 15 on trivial calls. If one is not a dedicated Magento developer this is wholly cumbersome. Tasked with devloping a solution for importing product data via an FTP server, I created this sample project to see how this worked properly. The following is a fully functional FTP-based data integration codebase. 

0. The data is fetched from an FTP location, which is left open at a personal server.
0. The XML file is retrieved and unzipped.
0. The adapters write the data to the Magento EAV database.
0. Everything is logged.


Structure
-------

The structure of the codebase is as follows:

* [Config] All the FTp configuration files should be placed under config.
* [Core] All core services including FTP and Logging capabilities.
* [Interfaces] The interfaces for the engines and adapters.
* [Engine] The concrete classes for FTP, and SFTP will follow suit here.
* [Adapters] Concrete product classes for CRUD operations on products.

Installation
-----------

This respository folder must be placed on the same level as the Mangento installation. The genericProductsAdapter has a path which will be specific to an installation on the third line. Then simply run the interpreter.

Usage
-----

```
php genericProductsIntegration.php
```

Compatibility
-----

This code was written on Magento 1.8.0 and works in the 1.8 series and up to tested 1.9.2. Magento changes frequently so with higher versions please use discretion as to what is reported as bugs.

