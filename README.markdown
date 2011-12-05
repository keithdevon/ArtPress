ArtPress
========

ArtPress is a highly customizable theme for WordPress.

Create Installable Zip File
---------------------------

To create an installable zip file, run the following in the top level ArtPress directory

    ./ap.py zip <version suffix>

Where <code>&lt;version suffix&gt;</code> is some string denoting a version E.g. :

    ./ap.py zip 1.2.3 

will produce a zip file called ArtPress1.2.3.zip in the <code>export/</code> directory.

Publishing
----------

Publishing a new version of ArtPress can be achieved by using the following command in the top level ArtPress directory

    ./ap.py publish <level>

Where <code>&lt;level&gt;</code> is either <code>major</code>, <code>minor</code> or <code>patch</code>. E.g. :

    ./ap.py publish minor

would update a current version of <code>1.2.3</code> to <code>1.3.0</code>
