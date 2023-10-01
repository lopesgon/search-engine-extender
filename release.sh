#!/bin/bash

case "$1" in
    --help)
        echo "Usage: $0 [OPTIONS]"
        echo "Release script options."
        echo "All options are optional by default."
        echo
        echo "Options:"
        echo "--help              Display this help message and exit"
        echo "-d | --dry-run      Run release in dry-run mode and exit"
        echo "--release=<type>    Replace <type> by one of the following values:" 
        echo "                      - major"
        echo "                      - minor"
        echo "                      - patch (default)"
        echo
        exit 0
        ;;
esac

# CONSTANTS
versionFile="src/search-engine-extender.php"
# Set the name of the directory and destination folder
dir_to_tar="dist"
dir_to_tar_renamed="search-engine-extender"
tarball_dir="releases"
# Options default
dryRun=0
release="patch"
# END CONSTANTS

##########################
### OPTIONS VALIDATION ###
# Process command line options
for arg in "$@"
do
    case $arg in
        -d|--dry-run)
        dryRun=1
        shift # Remove --dry-run or -d from processing
        ;;
        --release=*)
        release="${arg#*=}"
        shift # Remove --release from processing
        ;;
        *)
        shift # Remove generic argument from processing
        ;;
    esac
done

# Options values validation
if [[ "$release" != "major" && "$release" != "minor" && "$release" != "patch" ]]; then
    echo "Error: Invalid value for --release. Must be 'major', 'minor', or 'patch'."
    exit 1
fi

### END OPTIONS VALIDATION ###
##############################

# Prompt confirmation to perform release except if dryRun == true
if [ "$dryRun" -eq "0" ]; then
  echo "Do you really want to perform the release? [y/n]"
  read answer

  if [ "$answer" = "y" ]; then
      # Put your release logic here
      echo "Performing release..."
      # ... release steps ...

  else
      echo "Release cancelled."
      exit 1
  fi
fi

###################################
### RELEASE VERSION PREPARATION ###
# Get the latest tag
latestTag=$(git describe --tags `git rev-list --tags --max-count=1`)

# Get the parts of the tag
major=$(echo $latestTag | cut -d. -f1)
minor=$(echo $latestTag | cut -d. -f2)
patch=$(echo $latestTag | cut -d. -f3)

# Remove the v from the major version number
major=${major:1}

# construct latest tag without v prefix
latestTag="$major.$minor.$patch"

# Determine if we should increment major, minor, or patch
if [ "$release" = "major" ]; then
    let "major+=1"
    minor=0
    patch=0
elif [ "$release" = "minor" ]; then
    let "minor+=1"
    patch=0
else
    let "patch+=1"
fi

# Combine the parts back into a new tag
newTag="$major.$minor.$patch"

# Check if the version exists in the file
if grep -q $latestTag $versionFile; then
    if [ "$dryRun" -eq "1" ]; then
        echo "Next version: $newTag"
        # cp $versionFile "$versionFile.bak"
    fi
    # Replace the version in the file
    sed -i.bak "s#$latestTag#$newTag#g" $versionFile
else
    echo "Error: version $latestTag not found in $versionFile"
    exit 1
fi

### END RELEASE VERSION PREPARATION ###
#######################################

###########
### NPM ###
npm run build

# Check if npm command was successful
if [ $? -ne 0 ]; then
    echo "npm command failed."
    exit 1
fi
### END NPM ###
###############

##############################
### COMPRESS RELEASE BUILT ###
# Check if $dir_to_tar directory exists after build command
if [ ! -d "$dir_to_tar" ]; then
    echo "Directory $dir_to_tar does not exist."
    exit 1
fi

# Create the tarball directory if it does not exist
mkdir -p "$tarball_dir"

if [ "$dryRun" -eq "1" ]; then
  newTag="$newTag-dryRun"
fi

# Copy $dir_to_tar into new folder $dir_to_tar_renamed
cp -r $dir_to_tar $dir_to_tar_renamed

# Create a zip file with the directory, named with the latest tag
#tar -czf "$tarball_dir/$newTag.tar.gz" "$dir_to_tar_renamed" # old command
zip -r "$tarball_dir/$newTag.zip" "$dir_to_tar_renamed"
rm -rf $dir_to_tar_renamed
### END COMPRESS RELEASE BUILT ###
##################################


###########
### GIT ###
if [ "$dryRun" -eq "1" ]; then
  mv "$versionFile.bak" $versionFile
  echo "dryRun - git commands not executed"
  exit 0
fi

git commit $versionFile -m "Released theme version $newTag"
git push

# Add prefix and tag current sha1
newTag="v$newTag"
git tag $newTag
git push origin $newTag

### END GIT ###
###############