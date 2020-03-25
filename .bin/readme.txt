Stripe set up
Add this folder to your enviroment path.

Logging in:
1.  Login to stripe on your browser,
2.  Login to stripe cli with:
->  stripe login --api-key secret_key_here

Webhooks:
1.  Log in
2.  Copy webhook signing into env
3.  forward stripe evnts to local webhook
->  stripe listen --forward-to localhost:8000/stripe/webhooks

Filter events:
stripe listen --events payment_intent.created,payment_intent.succeeded \
  --forward-to localhost:8000/stripe/webhooks