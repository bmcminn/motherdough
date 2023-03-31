# Motherdough

> Mother dough is another name for a [REST API] starter. This can be used to make just about any type of [REST API], or to flavor other types of [Web Apps]. It is most well known for making artisan [REST APIs]. -- [Loren McCune, paraphrased](https://www.quora.com/What-kinds-of-bread-are-better-if-you-make-use-of-mother-dough/answer/Loren-McCune-1)


## Overview

Motherdough is an _artisanal_ PHP 8.1+ REST API starter kit built on [Slim 4](https://www.slimframework.com) and [Vue 3](https://vuejs.org/).

This repo is monolithic by design, but is built in a way that you could split it into separate client and REST microservices if you so desired.


## Disclaimer:

This is a personal project starter for myself. If you also like it, great! If you feel there is a missing core feature or something fundamentally broken with it, feel free to post an issue or issue a PR about it.

However, just because an issue or PR is submitted doesn't mean I will implement/accept it as I have my own goals for this framework, and you are more than welcome to fork this as you see fit.


## Features:

- Client _AND_ REST API Routing
- Database (via [RedBeanPHP](https://redbeanphp.com) w/ SQLite by default)
- Authentication
    - cookie based sessions
    - registration
    - login
    - session state
    - password reset
    - email based 2FA by default'
    - Argon2ID password encryption with ["reasonably safe defaults"](https://twitter.com/Sc00bzT/status/1557495201064558592)
- Admin utilities (work in progress)
    - user management
    - session token management
    - roles and permissions
- Middleware
    - auth redirects
- Docker-compose workflow
- CLI based workflow
- File uploads/storage support
- Server-side templates
- Emails
- REST model validation/sanitization
- Content Security Policy (CSP) (work in progress)


-----

## Getting started:

### Requirements:

You will need the following:

- `git`
- if running via CLI
    - `php 8.1+`
    - `node.js/npm`
- if running via docker (work in progress)
    - `docker-compose`

### Docker workflow:

```bash
docker-compose up -b
```

From here you should be able to open `http://localhost:3005` in your browser and view your webapp.

Start making changes and refresh to see things in action.

### CLI workflow:

Run the following commands in your terminal to setup the local environment:

```bash
# Clone the github repo
git clone https://github.com/bmcminn/motherdough ./your-project-name-here

# Copy the sample .env config and fill it out as needed
cp ./.env.sample ./.env

# Install local dependencies; this will install composer
#   and /client dependencies as well
npm i
```


-----

## TODO:

SO many things... lots of things to reference in the original repo: https://github.com/bmcminn/starter-api

In no particular order:

- API: allow for password reset verification
- API: allow for password resets
- API: allow user to update info
- API: compare user passwords against list of invalid passwords
- API: deleting a user should fill the deleted_at field and blank their password field
- API: login should fail on empty password field
- API: use Email class to send emails
- API: use MJML to generate email views (find original implementation and migrate to this repo)
- CLI: allow for docker-compose client instance with `vite --watch`; expose port `:5173` to allow for [HMR](https://vitejs.dev/guide/features.html#hot-module-replacement)
- CRON: daily -- iterate through all "deleted" users and expunge their data from the database
- DB: convert list of `invalidate-passwords.txt` to table
- DB: implement `user_confirmation` table
- DB: implement `user_profile` table
- DB: implement `user_remembered` table?
- DB: implement `user_reset` table -- list of password resets stored by auth token
- DB: implement `user_session` table -- list of sessions with user\_id, expires\_at
- DB: implement `user_throttled` table
- DOCS: add a `docs/` directory with references to internal utilities
- DOCS: add a `docs/cookbook/` resource for more specialized workflows
- UI: allow admin/super to blank users passwords to force reset them
- UI: allow admin/super to lookup sessions
- UI: allow admin/super to lookup users
- UI: allow admin/super to promote/demote user roles
- UI: allow admin/super to revoke one/many/all sessions
- UI: allow admin/super to soft delete users -- super cannot delete themselves
- ~~DB: implement user table~~
- ~~GIT: hookup this repo to a new branch on OG repo~~
