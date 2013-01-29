#Packfire Torch

>web assets made easy

Packfire Torch is a tiny CLI tool to help web developers manage web assets and resources. You no longer have to include those JavaScript libraries, CSS files, webfont files and images into your project version control anymore.

Project resources such as web assets may take up a lot of space in your repository. To work around this, include a `torch.json` file that contains the metadata of your resources for Packfire Torch to install.

A sample `torch.json` file looks like this:

    {
		"assets":[
			{
				"file": "../assets/scripts/jquery.js",
				"source": "http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js",
				"version": "1.8.3"
			},
			{
				"file": "../assets/scripts/bootstrap.js",
				"source": "http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js",
				"version": "2.2.2"
			},
			{
				"file": "../assets/styles/bootstrap.css",
				"source": "http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css",
				"version": "2.2.2"
			},
			{
				"file": "../assets/scripts/jquery-ui.js",
				"source": "http://code.jquery.com/ui/1.10.0/jquery-ui.js",
				"version": "1.10.0"
			},
			{
				"file": "../assets/styles/jquery-ui.css",
				"source": "http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css",
				"version": "1.10.0"
			}
		]
	}

##Requirements

- PHP 5.3 or higher

##Compiling

To compile Packfire Torch into a PHAR binary, you will need to install the dependencies through Composer after `git clone`-ing. After which, you can run the `bin/concrete` script against PHP in the repository folder.

    $ git clone https://github.com/packfire/torch.git torch
    $ cd torch
    $ composer install
    $ php bin/concrete

You will receive a truely magically PHAR file on the repository root folder.


##License

Packfire Torch is released open source under the BSD 3-Clause License. See the `license` file in repository for details.