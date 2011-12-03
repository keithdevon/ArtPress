#!/usr/bin/python

import sys
import subprocess
import os
import commands
import shutil
import re

# Global variables
exp_dir = 'export' # working directory
theme_name = ''
theme_uri = ''
upload_directory = ''
version_file_location = 'meta'
version_file_name='latest-ArtPress-version-number.txt'
version_number_file_uri = ''

# function to programmatically intitalise some global variables
def init_vars():
    global theme_name
    global theme_uri
    global upload_directory
    global version_number_file_uri
    
    new_cwd = os.path.dirname(__file__)
    os.chdir(new_cwd)
    
    for line in open( 'style.css' ):
        # get theme name
        if "Theme Name:" in line:
            theme_name = line.split()[-1]
        # get theme uri    
        if "Theme URI:" in line:
            theme_uri = line.split()[-1]
            
    for line in open( 'theme-options.php' ):
        m = re.search( "\$upload_directory.*CANONICAL", line )
        if( m ):
            upload_directory = theme_uri + line.split()[-3][:-2][1:]
        
    version_number_file_uri =  theme_uri + '/'  + version_file_name

# gets the version number for the lastest update from the wpfa.com
def get_version_number_file( ):
    current_version_string = commands.getoutput( 'curl -s ' + version_number_file_uri )
    if( len(current_version_string) == 0 ):
        print "cannot reach " + version_number_file_uri
        exit()
        
    current_version = current_version_string.split( '.' )
    return current_version

# uses curl/ftp to upload a given file to url
def upload_file(file, destination_uri):
    command = 'curl -u k31thd3v0n:kKeio0n\!kduir -T '
    command += file
    command += ' ftp://ftp.' + destination_uri
    return commands.getoutput( command )

# updates the local version number file
# note: this local file is not guaranteed in any way to be up to date
def update_version_number_file( new_version ):
    version_file_path = version_file_location + '/' + version_file_name;
    
    # update local file
    with open( version_file_path, 'w' ) as f:
        f.write( new_version )
    f.closed
    
    return upload_file( version_file_path, version_number_file_uri ) 

# this takes a verion number x.y.z and according
# to the level supplied [major,minor,patch] 
# it will generate the appropriate new version number
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

# wordpress expects the version number to be recorded in style.css
# this function updates the relevant string in that file
def update_style_css_number( directory, new_number ):
    command =  "sed -i 's/^Version:.*/Version: " 
    command += new_number 
    command +=  "/' "
    command += directory 
    command += "/style.css"
    commands.getoutput( command )

# general purpose function to copy a folder (and its contents)
# from one directory to another
# uses the UNIX 'cp' program
def copy_folder( parent_dir, dir ):
    new_dir = str( parent_dir + '/' + dir )
    os.mkdir( new_dir )
    command =  str( 'cp -R ' + dir + ' ' + parent_dir )
    commands.getoutput( command )

# this function creates a directory called /export 
# this is where the zip is built
def create_export_dir( version_number ):
    try:
        shutil.rmtree( exp_dir )
    except:
        print ''

    os.mkdir( exp_dir )
    artpress_directory = exp_dir + '/' + theme_name #+ version_number
    os.mkdir( artpress_directory )
    
    commands.getoutput( str( 'cp -R *.css '       + artpress_directory ) )
    commands.getoutput( str( 'cp license.txt '    + artpress_directory ) )
    commands.getoutput( str( 'cp screenshot.png ' + artpress_directory ) )
    commands.getoutput( str( 'cp -R *.php '       + artpress_directory ) )

    for dir in [ 'sidebars', 'form', 'ht-functions', 'ht-widgets', 
                 'css', 'fancybox', 'scripts', 'js', 'images' ]:
        copy_folder( artpress_directory, dir )
        
    return artpress_directory

# creates a new zip with the specifed version as a suffix
# eg ArtPress1.2.3.zip
# returns the new zip name
def create_zip(theme_name, version):
    zip_name = theme_name + '.zip'
    zip_path = exp_dir + '/' + zip_name
    cwd = os.getcwd()
    os.chdir( exp_dir )
    command = str( "zip -r " + zip_name + ' ' + theme_name )
    commands.getoutput( command )
    new_zip_name = theme_name + version + '.zip'
    os.rename(zip_name, new_zip_name)
    os.chdir(cwd)
    return new_zip_name

# simple util function to append 'version' to the levels 'major' and 'minor'
# but not the level 'patch'
# just used to improve readibility
def get_level_string( level ):
    if ( level == 'major' ):
        return 'major version'

    elif ( level == 'minor' ):
        return 'minor version'

    else: 
        return 'patch'

# tags the current commit in git 
# this tag won't have any knowledge of uncommitted changes!
# probably best to make sure there are no outstanding changes
def tag_commit( level, new_version_number ):
    command = 'git tag -a v' + new_version_number
    command += " -m '" + get_level_string( level ) + "'"
    print commands.getoutput( command );

# uploads the specified zip to the uploads directory at wpfa.com
def upload_zip( zip_name ):
    print 'uploading ' + zip_name + ' ...'
    upload_file( exp_dir + '/' + zip_name, upload_directory + '/' + zip_name )
    
# this top level function will
# - run tests
# - create the zip file
# - tag the current commit
# - upload it to wpfa.com
# - update the public latest version number
#
# level must be either major, minor or patch
def publish( level ):
    # get current version number
    current_version = get_version_number_file()
    current_version_string = current_version[0] + '.'
    current_version_string += current_version[1] + '.'
    current_version_string += current_version[2]
    

    print "Creating a new " + get_level_string( level ) + " for " + theme_name + " " + str( current_version_string )

    # run artpress tests

    # run publish tests
    ## outstanding changes
    command = 'git status -s'
    result = commands.getoutput( command )
    if( len( result ) > 0 ):
        m  = "\nCurrent Git status:"
        m += "--------------------------------------------------------------------------"
        m += result
        m += "--------------------------------------------------------------------------"
        m += "\nThere are uncommitted changes in your directory."
        m += "It is highly recommended you publish from a clean working directory. Proceed anyway?"
        print m
        invalid_input = True
        
        while( invalid_input ):
            response = raw_input("[type 'yes' or 'no'] ");
            if( response == 'no'):
                exit()
            elif( response == 'yes' ):
                invalid_input = False
                
    ## can upload to wpfa
    
    # take screenshot
    
    # create new number
    new_version = create_new_version_number( level, current_version )
    
    # create export dir
    new_dir = create_export_dir( new_version )
    
    # update version number in style.css
    update_style_css_number( new_dir, new_version )

    # create zip with version number
    zip_file = create_zip( theme_name, new_version )

    # tag commit
    tag_commit( level, new_version )
    
    # push tag?

    # upload zip to wfa
    upload_zip( zip_file )

    # update lavn.txt
    update_version_number_file( new_version )

# this function will just create a new installable zip in export/
# it does not upload it
# version can be any string
def zip( version ):
     # create export dir
    new_dir = create_export_dir( version  )
    
    # update version number in style.css
    update_style_css_number( new_dir, version )

    # create zip with version number
    zip_file = create_zip( theme_name, version )
    
# -----------------------------------------------------------------
# handle command line arguments 

init_vars()

if( sys.argv[1] == 'publish' ):
    publish( sys.argv[2] )
    
elif( sys.argv[1] == 'zip' ):
    if( len(sys.argv) < 3 ):
        version = ''
    else:
        version = sys.argv[2] 

    zip( version )


