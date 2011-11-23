#!/bin/bash
wd="export";   #working directory
td="artpress"; #top level zip directory
dt=`date "+%Y%m%d%H%M%S"`; # date / time
zd=$td\_$dt;
apdir=$wd/$zd;

function copyfolder {
    echo "! copying $1/";
    mkdir $apdir/$1/;
    cp -R $1 $apdir;
}

function zip {
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
    cd $wd
    
    zip -r $td.zip $zd
    
    rm -r $zd
    cd ..
    
}

function publish {
    # run tests

    # get current version number
    # update number
    # update version number in style.css

    # create zip - with version number

    # upload to wordpress-for-artists.com 

}