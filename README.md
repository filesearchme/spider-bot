# spider-bot

# installation
- Upload files to server
- Edit config.json
  - bot_version - no changes needed
  - jobs_per_fire - number of jobs to fetch each run
  - api_key - your filesearch.me api key
  - unique_key - a unique key to protect the bot from unwanted runs
  - supervisor - if you use supervisor instead of cron set to true
- set cronjob '* * * * * /direct/path/to/index.php your_unique_key > /dev/null 2>&1'
