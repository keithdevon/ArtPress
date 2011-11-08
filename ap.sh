#!/bin/bash

apdir="export/artpress";

function copyfolder {
    echo "! copying $1/";
    mkdir $apdir/$1/;
    cp -R $1 $apdir;
}

echo "*****************************"
echo "* creating ArtPress zip ... *"
echo "*****************************"

echo "! making artpress folder"
mkdir -pv $apdir;


echo "! copying top level .css files"
cp -R *.css $apdir;

echo "! copying license.txt"
cp license.txt $apdir;

echo "! copying screenshot.png"
cp screenshot.png $apdir;


# php files
echo "! copying top level .php files"
cp -R *.php $apdir;

copyfolder 'sidebars';
copyfolder 'form';
copyfolder 'ht-functions';
copyfolder 'ht-widgets';
copyfolder 'css';
copyfolder 'fancybox';
copyfolder 'scripts';
copyfolder 'js';
copyfolder 'images';

echo "finished copying files"

echo "! zipping up folder ..."
cd export
zip -r artpress.zip artpress
cd ..

