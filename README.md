EAV Example with Pricefiles from FTP Servers
=============

Magento has an infamous database structure, known as the EAV (Entity Attribute Value) Model which essentially locks a developer into it, as the foreign keys on their tables can number as many as 15 on trivial calls. Tasked with devloping a solution for importing data via FTP, I created this sample project to test the concept. The following is a fully functional FTP-based data integration codebase. 

0. The data is fetched from an FTP location, which is left open at a personal server.
0. The XML file is retrieved and unzipped.
0. The adapters write the data to the Magento EAV database.
0. Everything is logged.


Markups
-------

The following markups are supported.  The dependencies listed are required if
you wish to run the library. You can also run `script/bootstrap` to fetch them all.

* [.markdown, .mdown, .mkdn, .md](http://daringfireball.net/projects/markdown/) -- `gem install redcarpet` (https://github.com/vmg/redcarpet)
* [.textile](http://www.textism.com/tools/textile/) -- `gem install RedCloth`
* [.rdoc](http://rdoc.sourceforge.net/) -- `gem install rdoc -v 3.6.1`
* [.org](http://orgmode.org/) -- `gem install org-ruby`
* [.creole](http://wikicreole.org/) -- `gem install creole`
* [.mediawiki, .wiki](http://www.mediawiki.org/wiki/Help:Formatting) -- `gem install wikicloth`
* [.rst](http://docutils.sourceforge.net/rst.html) -- `easy_install docutils`
* [.asciidoc, .adoc, .asc](http://asciidoc.org/) -- `gem install asciidoctor` (http://asciidoctor.org)
* [.pod](http://search.cpan.org/dist/perl/pod/perlpod.pod) -- `Pod::Simple::HTML`
  comes with Perl >= 5.10. Lower versions should install [Pod::Simple](http://search.cpan.org/~dwheeler/Pod-Simple-3.28/lib/Pod/Simple.pod) from CPAN.

Installation
-----------

This respository folder must be placed on the same level as the Mangento installation. The genericProductsAdapter has a path which will be specific to an installation on the third line. Then simply run the interpreter.

Usage
-----

```
php genericProductsIntegration.php
```

