#!/usr/bin/python

import sys
import subprocess
import os
import commands
import shutil

# CONSTANTS

# working directory
exp_dir = 'export'

for line in open('style.css'):
    # get theme name
    if "Theme Name:" in line:
        theme_name = line.split()[-1]
    # get theme uri    
    if "Theme URI:" in line:
        theme_uri = line.split()[-1]

version_file_location = 'meta/version-number'
version_file_name=os.listdir(version_file_location)[0]
version_number_file_uri =  theme_uri + '/'  + version_file_name

def get_version_number_file( ):
    current_version_string = commands.getoutput( 'curl -s ' + version_number_file_uri )
    print "(current version:  " + current_version_string + ")"
    current_version = current_version_string.split( '.' )
    return current_version

def update_version_number_file( new_version ):
    version_file_path = version_file_location + '/' + version_file_name;
    # update local file
    with open( version_file_path, 'w' ) as f:
        f.write( new_version )
    f.closed
    
    command = 'curl -u k31thd3v0n:kKeio0n\!kduir -T '
    command += version_file_path
    command += ' ftp://ftp.' + version_number_file_uri
    print command
    print 'updating version number file at ' + version_number_file_uri + ' to ' + new_version
    print commands.getoutput( command );    

def create_new_version_number( level, current_version):
    if( level == 'patch' ):
        new_version = current_version[0] + '.' 
        new_version += current_version[1] + '.' 
        new_version += str( ( int(current_version[2]) + 1 ) )
    elif( level == 'minor' ):
        new_version = current_version[0] + '.' 
        new_version += str( ( int(current_version[1]) + 1 ) )  + '.0' 
    elif( level == 'major' ):
        new_version = str( ( int(current_version[0]) + 1 ) )  + '.0.0' 

    return new_version


def update_style_css_number( directory, new_number ):
    command =  "sed -i 's/^Version:.*/Version: " 
    command += new_number 
    command +=  "/' "
    command += directory 
    command += "/style.css"
    commands.getoutput( command )

def copy_folder( parent_dir, dir ):
    new_dir = str( parent_dir + '/' + dir )
    os.mkdir( new_dir )
    command =  str( 'cp -R ' + dir + ' ' + parent_dir )
    commands.getoutput( command )

def create_export_dir( version_number ):
    try:
        shutil.rmtree( exp_dir )
    except:
        print ''

    os.mkdir( exp_dir )
    artpress_directory = exp_dir + '/' + theme_name + version_number
    os.mkdir( artpress_directory )
    
    commands.getoutput( str( 'cp -R *.css '       + artpress_directory ) )
    commands.getoutput( str( 'cp license.txt '    + artpress_directory ) )
    commands.getoutput( str( 'cp screenshot.png ' + artpress_directory ) )
    commands.getoutput( str( 'cp -R *.php '       + artpress_directory ) )

    for dir in [ 'sidebars', 'form', 'ht-functions', 'ht-widgets', 
                 'css', 'fancybox', 'scripts', 'js', 'images' ]:
        copy_folder( artpress_directory, dir )
        
    return artpress_directory

def create_zip(theme_name, version):
    # ArtPress1.2.2
    full_name = theme_name + version
    # export/ArtPress1.2.2
    theme_export_folder = exp_dir + '/' + full_name
    # ArtPress1.2.3.zip
    zip_name = full_name + '.zip'
    # export/ArtPress1.2.3.zip
    zip_path = exp_dir + '/' + zip_name
    command = str( "zip -r " + zip_path + ' ' + theme_export_folder )
    commands.getoutput( command )
    return zip_name

def get_level_string( level ):
    if ( level == 'major' ):
        return 'major version'

    elif ( level == 'minor' ):
        return 'minor version'

    else: 
        return 'patch'

def tag_commit( level, new_version_number ):
    command = 'git tag -a v' + new_version_number
    command += " -m '" + get_level_string( level ) + " " 
    command += new_version_number + "'"
    print command

def upload_zip( zip_name ):
    command = 'curl -u k31thd3v0n:kKeio0n\!kduir -T '
    command += exp_dir + '/' + zip_name
    command += ' ftp://ftp.' + theme_uri + '/' + zip_name
    print 'uploading ' + zip_name + ' ...'
    print commands.getoutput( command );

def publish( level ):
    print "Creating a new " + get_level_string( level ) + " for " + theme_name

    # run artpress tests

    # run publish tests
    ## outstanding changes
    ## can upload to wpfa
    
    # take screenshot
    
    # get current version number
    current_version = get_version_number_file()

    # create new number
    new_version = create_new_version_number( level, current_version )
    
    # create export dir
    new_dir = create_export_dir( new_version )
    
    # update version number in style.css
    update_style_css_number( new_dir, new_version )

    # create zip with version number
    zip_file = create_zip( theme_name, new_version )

    # upload zip to wfa
    upload_zip( zip_file )
    
    # tag commit
    tag_commit( level, new_version )

    # update lavn.txt
    update_version_number_file( new_version )

if( sys.argv[1] == 'publish' ):
    publish( sys.argv[2] )

