#!/usr/bin/python

import sys
import subprocess
import os
import commands
import shutil
import re
from optparse import OptionParser 
from optparse import OptionGroup
import inspect

parser = OptionParser()

global options

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

def handleoutput( output ):
    verbose = parser.values.verbose 
    if( verbose ):
         print output

def deactivate_on_test( function_name ):
    if (parser.values.test) :
        list = ['update_version_number_file'
                , 'upload_file'
                , 'tag_commit'
                , 'push_tags'
                ]
    
        if ( function_name in list ):
            return True
        else: 
            return False
    else:
        return False

def commandline( command ):
    handleoutput( command )
    frm = inspect.stack()[1]
    from0 = frm[0]
    fcode = from0.f_code
    co_name = fcode.co_name
    
    if( True != deactivate_on_test( co_name ) ):
        output = commands.getoutput( command )
        handleoutput( output )
        return output
    else:
        handleoutput('TESTING MODE: did not actually perform previous command')
    
# gets the version number for the lastest update from the wpfa.com
def get_version_number_file( ):
    current_version_string = commands.getoutput( 'curl -s ' + version_number_file_uri )
    handleoutput( 'The current version is: ' + current_version_string )
    if( len(current_version_string) == 0 ):
        print "cannot reach " + version_number_file_uri
        exit()
        
    current_version = current_version_string.split( '.' )
    return current_version

# uses curl/ftp to upload a given file to url
def upload_file(file, destination_uri):
    handleoutput( 'uploading ' + file + ' to ' + destination_uri )
    command = 'curl -u k31thd3v0n:kKeio0n\!kduir -T '
    command += file
    command += ' ftp://ftp.' + destination_uri
    return commandline( command )

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
def create_new_version_number( level, current_version ):
    if( level == 'patch' ):
        new_version = current_version[0] + '.' 
        new_version += current_version[1] + '.' 
        new_version += str( ( int(current_version[2]) + 1 ) )
    elif( level == 'minor' ):
        new_version = current_version[0] + '.' 
        new_version += str( ( int(current_version[1]) + 1 ) )  + '.0' 
    elif( level == 'major' ):
        new_version = str( ( int(current_version[0]) + 1 ) )  + '.0.0' 
        
    handleoutput( 'New version is: ' + new_version )
    return new_version

# wordpress expects the version number to be recorded in style.css
# this function updates the relevant string in that file
def update_style_css_number( directory, new_number ):
    handleoutput( 'updating the version number in ' + directory + '/style.css to ' + new_number )
    command =  "sed -i -e 's/^Version:.*/Version: " 
    command += new_number 
    command +=  "/' "
    command += directory 
    command += "/style.css"
    commandline( command )

# general purpose function to copy a folder (and its contents)
# from one directory to another
# uses the UNIX 'cp' program
def copy_folder( parent_dir, dir ):
    new_dir = str( parent_dir + '/' + dir )
    os.mkdir( new_dir )
    command =  str( 'cp -R ' + dir + ' ' + parent_dir )
    commandline( command )

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
    
    commandline( str( 'cp -R *.css '       + artpress_directory ) )
    commandline( str( 'cp license.txt '    + artpress_directory ) )
    commandline( str( 'cp screenshot.png ' + artpress_directory ) )
    commandline( str( 'cp -R *.php '       + artpress_directory ) )

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
    commandline( str( "zip -r " + zip_name + ' ' + theme_name ) )
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
    commandline( command )

# tags are not pushed to remote repositories by default
# so this function explicity pushes any locally created tags to origin
def push_tags():
    command = 'git push --tags'
    commandline( command )

# uploads the specified zip to the uploads directory at wpfa.com
def upload_zip( zip_name ):
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
    

    handleoutput( "Creating a new " + get_level_string( level ) 
                  + " for " + theme_name + " " + str( current_version_string ) )

    # run artpress tests

    # run publish tests
    ## outstanding changes
    command = 'git status -s'
    result = commandline( command )
    if( len( result ) > 0 ):
        m  = "\nCurrent Git status:"
        m += "\n--------------------------------------------------------------------------"
        m += "\n" + result
        m += "\n--------------------------------------------------------------------------"
        m += "\nThere are uncommitted changes in your directory."
        m += "\nIt is highly recommended you publish from a clean working directory. Proceed anyway?"
        print m
        invalid_input = True
        ##print options
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
    
    # push tag
    push_tags()

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

def optionshandler(option, opt_str, value, parser):
    if( opt_str == '-p' ):
        publish( value )
        
    elif( opt_str == '-z' ):
        zip( value )

parser.add_option("-z", 
                  "--zip",
                  action="callback",
                  callback=optionshandler,
                  dest="zipsuffix", 
                  type="string",
                  help="""Creates a stand-alone installable zip,
                          where ZIPSUFFIX is some string.
                
                          The newly created zip can be found in the 
                          <theme path>/exports/ folder.
                          
                          
                          Example: ./ap.py -z 1.2.3
                          
                          would create
                           
                          /exports/ArtPress1.2.3.zip .""")

publishgroup = OptionGroup( parser, 'publishing options', 'use these to distribute a new version of ArtPress to ArtPress users')
publishgroup.add_option("-p", 
                  "--publish",
                  action="callback",
                  callback=optionshandler,
                  dest="level", 
                  type="string",
                  help="""Publishes a new version of ArtPress. 
                          LEVEL must either be either
                          
                          'major', 'minor', or 'patch'.
                          
                          Example: ./ap.py -p minor
                          
                          would publish a new minor version
                          to wordpress-for-artists.com
                          ArtPress users would be notified
                          it is available and would be able
                          to upgrade to it.
                          """)


publishgroup.add_option("-t", 
                  "--test",
                  action="store_true",
                  dest="test", 
                  help="""When used in conjunction with --publish,
                  this flag ensures that no permanent actions are taken.
                  For example,
                  
                  - git tag won't take place
                  
                  - public version number won't be updated
                  
                  - zip is not uploaded to wpfa.com
                  
                  Useful for debugging but ...
                  
                  ENSURE THAT THE -t FLAG PRECEDES THE -p FLAG!!!

                  Otherwise it won't be recognized
                  and a real publish event will take place!
                  """)

parser.add_option_group( publishgroup )

parser.add_option("-v", 
                  "--verbose",
                  action="store_true",
                  dest="verbose", 
                  #default=True,
                  help="""This flag ensures that commands 
                  generate a lot of output.
                  
                  Useful for debugging.
                  """)

##parser.add_option("-q", 
##                  "--quiet",
##                  action="store_true",
##                  dest="quiet", 
##                  help="""This flag ensures that any commands don't 
##                  generate any more output than absolutely necessary.
##                  Overrides the --verbose flag.
##                  """)
##

(options, args) = parser.parse_args()


