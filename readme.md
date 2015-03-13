## Slack Auto Invitations
WebSite to automate invitations to your teams on [Slack](slack.com) made with Laravel 5.
That application use the package [Slack API Facade](https://github.com/vluzrmos/laravel-slack-api).

[![Latest Stable Version](https://poser.pugx.org/vluzrmos/slack-autoinvitation/v/stable.svg)](https://packagist.org/packages/vluzrmos/slack-autoinvitation) [![Total Downloads](https://poser.pugx.org/vluzrmos/slack-autoinvitation/downloads.svg)](https://packagist.org/packages/vluzrmos/slack-autoinvitation) [![Latest Unstable Version](https://poser.pugx.org/vluzrmos/slack-autoinvitation/v/unstable.svg)](https://packagist.org/packages/vluzrmos/slack-autoinvitation) [![License](https://poser.pugx.org/vluzrmos/slack-autoinvitation/license.svg)](https://packagist.org/packages/vluzrmos/slack-autoinvitation)

## Instalation
via composer:  <code>composer create-project vluzrmos/slack-autoinvitation project-folder ~1.2</code>

## Configuration 

Just configure the file <code>.env</code> on project root path. If it doesn't exists, just copy the <code>.env.example</code> file and rename to <code>.env</code>.

```ini
# The name of that app
APP_NAME=My Slack Invitator

# The Title of Your Slack Team
SLACK_TEAMNAME=My Slack Team 

# DOMAIN of your Slack (eg.: https://{DOMAIN}.slack.com)
SLACK_TEAM=my-slack-domain

#Get a token on https://api.slack.com/web#authentication
SLACK_TOKEN=Your Slack Token

#Channels to users be joined, eg.: general, UE45487X45, or all; general is default.
SLACK_CHANNELS=all
```
### License

This software is licensed under the [DBAD license](http://www.dbad-license.org/).
