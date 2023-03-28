# Base API starter kit


## TODO:

SO many things... lots of things to reference in the original repo: https://github.com/bmcminn/starter-api

- GIT: hookup this repo to a new branch on OG repo
- API: allow for password reset verification
- API: allow for password resets
- API: allow user to update info
- API: compare user passwords against list of invalid passwords
- API: deleting a user should fill the deleted_at field and blank their password field
- API: login should fail on empty password field
- API: use Email class to send emails
- API: use MJML to generate email views (find original implementation and migrate)
- CRON: daily -- iterate through all "deleted" users and expunge their data from the database
- DB: convert list of invalidate-passwords.txt to table
- DB: implement user_confirmation table
- DB: implement user_profile table
- DB: implement user_remembered table?
- DB: implement user_reset table -- list of password resets stored by authtoken
- DB: implement user_session table -- list of sessions with user\_id, expires\_at
- DB: implement user_throttled table
- UI: allow admin/super to blank users passwords to force reset them
- UI: allow admin/super to lookup sessions
- UI: allow admin/super to lookup users
- UI: allow admin/super to promote/demote user roles
- UI: allow admin/super to revoke one/many/all sessions
- UI: allow admin/super to soft delete users -- super cannot delete themselves
- ~~DB: implement user table~~
