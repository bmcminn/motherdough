# Emails!

Everybody hates making them, so I opted for [MJML](https://mjml.io) here.


## Quick tutorial/reference

### Getting Started Using MJML In Under 10 Minutes
https://www.youtube.com/watch?v=Q1M4tKmBM7k

### Get better at building emails with MJML
https://www.youtube.com/watch?v=JY_B65U01vc


## Dev process

1. Create your file `views/mjml/[filename].mjml`; make it meaningful to its function.
1. Build your email template, feel free to pull inspiration/layouts from other files
1. Use `npm run mjml` to build and test your email templates in the browser
    - `npm run mjml` will default to dev mode, which watches all changes in `views/mjml` and writes the test files to `views/emails/[filename].html`
1. You can use your preferred template syntax as needed, MJML will typically not munge anything it isn't supposed to, so feel free to templatize any content you see fit, _though your mileage may vary when dealing with block level control structures._
1. Run `npm run mjml:prod` once you're ready to publish your templates, which will output your files to `views/emails/[filename].twig`
1. Have fun!


## Files overview


### `new-registration.mjml`

This email is sent to users after successfully registering an account


### `new-user-invite.mjml`

This email is sent to users when invited by existing users


### `one-time-password.mjml`

This is the template users receive when logging in and we send them their one-time password.


## Data model

Most emails will have access to the API config located in `/src/config.php`


