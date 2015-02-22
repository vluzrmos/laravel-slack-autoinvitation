## Slack Auto Invitations
Just configure the environment in .env file:

## Instalation

via composer:  <code>composer create-project vluzrmos/slack-autoinvitation project-folder ~1.0</code>
or just clone this git repository: <code>git clone https://github.com/vluzrmos/laravel-slack-autoinvitation.git</code>

```ini
# The Name of That app
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
