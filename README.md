# Search Engine Extender

A simple Wordpress plugin to extend default search engine features.

## Features

* Deactivate some articles to be found from search

## Requirements

* npm
* apache - MAMP or equivalent works just fine
* zip - command line executable

## Getting started

1. Install dependencies `npm i`
2. Modify constant `localDomain` from [webpack.dev.js](./webpack.dev.js) by pointing to your localhost server port (default: 8090) 
3. Run `npm run watch` </br>
> This step generates the `dist` folder containing transpiled plugin sources (css, js) and dynamic variables replacements
4. Create symbolic link `ln -s /full-path-to-current-folder/dist sainplh-recipe-plugin` </br>
> This step allows to have your plugin available in your wordpress installed extensions and benefit of continuous sources refresh while developing


## Build release

> To keep a track of releases, do not forget to tag current git sha1 with the desired version 

Release is automated using [release.sh](./release.sh) by simply running `./release.sh`.
For more details see `./release.sh --help` command.

The following functionalities are performed:

- release tag is incremented automatically based on last tag in current working branch
- style.css is updated accordingly to new tag
- release is built and packaged into [releases folder](./releases/) with new tag naming to store a backup
- modified style.css is commit and push to remote
- sha1 is tagged and is pushed to remote


### Build manually

> To keep a track of releases, do not forget to tag current git sha1 with the desired version `git tag -a v1.0.0 -m "my version 1.0.0"` and push to distant repository `git push origin v1.0.0`

1. Update [search-engine-extender.php](./src/search-engine-extender.php) version semver
2. Run `npm run build`
3. Zip the ./dist folder by naming zip file `search-engine-extender.zip`
4. Upload zip file manually from Wordpress admin add extension interface