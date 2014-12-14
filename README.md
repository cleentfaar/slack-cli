# Slack CLI [![License](https://poser.pugx.org/cleentfaar/slack-cli/license.svg)](https://packagist.org/packages/cleentfaar/slack)

Command-line application for interacting with the Slack API. Provides commands for all of the API methods currently available.

**NOTE:** If your project is built on top of the Symfony Framework, consider using the bundle I created for it [here](https://github.com/cleentfaar/CLSlackBundle).
Once installed, the commands are smart enough to use the token you have configured in the bundle, so you don't have to repeat it for every command.

[![Build Status](https://secure.travis-ci.org/cleentfaar/slack.svg)](http://travis-ci.org/cleentfaar/slack)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cleentfaar/slack-cli/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cleentfaar/slack-cli/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/cleentfaar/slack-cli/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/cleentfaar/slack-cli/?branch=master)<br/>
[![Latest Stable Version](https://poser.pugx.org/cleentfaar/slack-cli/v/stable.svg)](https://packagist.org/packages/cleentfaar/slack)
[![Total Downloads](https://poser.pugx.org/cleentfaar/slack-cli/downloads.svg)](https://packagist.org/packages/cleentfaar/slack)
[![Latest Unstable Version](https://poser.pugx.org/cleentfaar/slack-cli/v/unstable.svg)](https://packagist.org/packages/cleentfaar/slack)


### Documentation

- [Getting Started](Resources/doc/getting-started.md) - Before you use this package, you need to generate a token or setup oAuth.
- [Installation](Resources/doc/installation.md) - Information on installing this library through composer or as a git submodule.
- [Usage](Resources/doc/installation.md) - A few simple examples on how to use some of the console commands provided by this package

#### Detailed documentation

This package only provides a console wrapper to access the Slack API, so if you want more detailed documentation on how to
use the payloads and responses in your own application, check out the library that this package implements: [Slack API library](https://github.com/cleentfaar/slack).


### Contributing

Got a good idea for this project? Found a nasty bug that needs fixing? That's great!
Before submitting your PR though, make sure it complies with the [contributing guide](Resources/doc/contributing.md) to
speed up the merging of your code.


### Attributions

- The [Slack](https://slack.com/) staff, for making an awesome product and very clean API documentation.
