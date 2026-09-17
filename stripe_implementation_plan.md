Stripe Recurring Billing – API & Postman Documentation
End-to-end Stripe Test Mode API reference for the POS recurring package flow: Product, Price, Customer, PaymentMethod, SetupIntent, Subscription, first payment, invoices, cancellation, Test Clock renewal testing, and webhooks.
0. Security – IMPORTANT
The secret key from the source notes is intentionally NOT included. Because it was exposed, rotate/revoke that test key in Stripe and use a new one.
STRIPE_SECRET_KEY=sk_test_...
Note: Never commit the secret key to Git or put it in frontend/mobile code.
1. Stripe Object Relationship
POS Customer
   ↓
Stripe Customer (cus_...)
   ↓
Subscription (sub_...)
   ↓
Subscription Item (si_...)
   ↓
Recurring Price (price_...)
   ↓
Product (prod_...)

Payment setup:
Customer → SetupIntent (seti_...) → PaymentMethod (pm_...)

First subscription payment:
Subscription → Invoice (in_...) → PaymentIntent (pi_...)

Future renewals:
Billing period ends → new Invoice → automatic payment attempt → webhook → POS updates status
Note: A Stripe Customer is not directly assigned to a Product. The Customer receives a Price through a Subscription.
2. Postman Environment
STRIPE_SECRET_KEY=sk_test_...
STRIPE_BASE_URL=https://api.stripe.com
Note: For server-side cURL requests use: -u "{{STRIPE_SECRET_KEY}}:"
3. API Sequence
Get Balance → verify Stripe API/Test Mode access.
Create Product → create the POS package in Stripe.
Create recurring Prices → create 1-month, 3-month, and 6-month options.
List/Retrieve Products and Prices → verify IDs and recurring configuration.
Create Customer → create/find one Stripe Customer for the POS customer.
Create Test Clock → only for renewal simulation; create the Customer under the clock for a clean test.
Create SetupIntent → prepare a payment method for future/off-session use.
Confirm SetupIntent → use Stripe test PaymentMethod such as pm_card_visa.
Use the returned PaymentMethod ID → the response gives the actual pm_... value.
Set the default PaymentMethod.
Create Subscription → Customer + selected recurring Price.
If Subscription is incomplete → retrieve its first Invoice and PaymentIntent and confirm the first payment.
Verify Subscription, Invoice, and PaymentIntent.
Advance Test Clock to the next billing date and verify automatic renewal.
Handle Stripe webhooks in the POS backend.
Test immediate cancellation and cancel-at-period-end.
4. Get Balance
GET https://api.stripe.com/v1/balance

curl https://api.stripe.com/v1/balance \
  -u "{{STRIPE_SECRET_KEY}}:"
Note: A response with "livemode": false confirms Test Mode and successful API authentication.
5. Create Product
POST https://api.stripe.com/v1/products

curl https://api.stripe.com/v1/products \
  -u "{{STRIPE_SECRET_KEY}}:" \
  -d "name=Basic Package" \
  -d "description=Basic POS subscription package"
Note: Save response id: prod_.... Use it when creating recurring Prices.
6. Create Recurring Price
POST https://api.stripe.com/v1/prices

curl https://api.stripe.com/v1/prices \
  -u "{{STRIPE_SECRET_KEY}}:" \
  -d "product=prod_xxxxxxxxx" \
  -d "currency=usd" \
  -d "unit_amount=1000" \
  -d "recurring[interval]=month" \
  -d "recurring[interval_count]=1"
Note: unit_amount=1000 means USD 10.00. Save response id: price_.... Use interval_count=3 for a 3-month Price and 6 for a 6-month Price.
7. List Products
GET https://api.stripe.com/v1/products

curl https://api.stripe.com/v1/products \
  -u "{{STRIPE_SECRET_KEY}}:"
Note: Find existing prod_... IDs here.
8. List Prices
GET https://api.stripe.com/v1/prices

curl https://api.stripe.com/v1/prices \
  -u "{{STRIPE_SECRET_KEY}}:"
Note: Find the correct price_... by product, currency, amount, and recurring interval/interval_count.
9. Create Customer
POST https://api.stripe.com/v1/customers

curl https://api.stripe.com/v1/customers \
  -u "{{STRIPE_SECRET_KEY}}:" \
  -d "name=John Doe" \
  -d "email=john@example.com" \
  -d "metadata[pos_customer_id]=123"
Note: Save response id: cus_.... Store this as stripe_customer_id in the POS.
10. Create Test Clock
POST https://api.stripe.com/v1/test_helpers/test_clocks

curl https://api.stripe.com/v1/test_helpers/test_clocks \
  -u "{{STRIPE_SECRET_KEY}}:" \
  -d "frozen_time=1789479000" \
  -d "name=POS Recurring Payment Test"
Note: Save response id: clock_.... Test Clocks simulate future billing dates without waiting months.
11. Create Customer Under Test Clock
POST https://api.stripe.com/v1/customers

curl https://api.stripe.com/v1/customers \
  -u "{{STRIPE_SECRET_KEY}}:" \
  -d "name=John Doe" \
  -d "email=john@example.com" \
  -d "test_clock=clock_xxxxxxxxx" \
  -d "metadata[pos_customer_id]=123"
Note: For a clean renewal test, use this Customer for the subscription.
12. Create SetupIntent
POST https://api.stripe.com/v1/setup_intents

curl https://api.stripe.com/v1/setup_intents \
  -u "{{STRIPE_SECRET_KEY}}:" \
  -d "customer=cus_xxxxxxxxx" \
  -d "payment_method_types[]=card"
Note: Save id=seti_.... A new SetupIntent normally starts as requires_payment_method. Its client_secret is for client-side Stripe UI; it is not a PaymentIntent ID.
13. Confirm SetupIntent
POST https://api.stripe.com/v1/setup_intents/{SETUP_INTENT_ID}/confirm

curl https://api.stripe.com/v1/setup_intents/seti_xxxxxxxxx/confirm \
  -u "{{STRIPE_SECRET_KEY}}:" \
  -d "payment_method=pm_card_visa"
Note: After successful confirmation, response.payment_method contains the actual pm_... ID. pm_card_visa is a Stripe Test Mode PaymentMethod. Because the SetupIntent was created for the Customer, a separate attach call is normally unnecessary.
14. Attach PaymentMethod – Only When Needed
POST https://api.stripe.com/v1/payment_methods/{PAYMENT_METHOD_ID}/attach

curl https://api.stripe.com/v1/payment_methods/pm_xxxxxxxxx/attach \
  -u "{{STRIPE_SECRET_KEY}}:" \
  -d "customer=cus_xxxxxxxxx"
Note: Use only for an independently created PaymentMethod that is not already attached.
15. Set Default PaymentMethod
POST https://api.stripe.com/v1/customers/{CUSTOMER_ID}

curl https://api.stripe.com/v1/customers/cus_xxxxxxxxx \
  -u "{{STRIPE_SECRET_KEY}}:" \
  -d "invoice_settings[default_payment_method]=pm_xxxxxxxxx"
Note: The pm_... must belong to the Customer.
16. Create Subscription
POST https://api.stripe.com/v1/subscriptions

curl https://api.stripe.com/v1/subscriptions \
  -u "{{STRIPE_SECRET_KEY}}:" \
  -d "customer=cus_xxxxxxxxx" \
  -d "items[0][price]=price_3month_xxxxxxxxx" \
  -d "payment_behavior=default_incomplete" \
  -d "payment_settings[save_default_payment_method]=on_subscription" \
  -d "billing_mode[type]=flexible" \
  -d "expand[0]=latest_invoice.confirmation_secret"
Note: Save sub_... and latest_invoice=in_.... The selected Price determines the renewal interval. With default_incomplete, the subscription can remain incomplete until the first payment succeeds.
17. Find First PaymentIntent for an Incomplete Subscription
GET https://api.stripe.com/v1/invoices/{INVOICE_ID}?expand[]=payment_intent

curl "https://api.stripe.com/v1/invoices/in_xxxxxxxxx?expand[]=payment_intent" \
  -u "{{STRIPE_SECRET_KEY}}:"
Note: Use latest_invoice from the Subscription response. Find payment_intent.id in the invoice; that is the pi_... ID.
18. Confirm First PaymentIntent
POST https://api.stripe.com/v1/payment_intents/{PAYMENT_INTENT_ID}/confirm

curl https://api.stripe.com/v1/payment_intents/pi_xxxxxxxxx/confirm \
  -u "{{STRIPE_SECRET_KEY}}:" \
  -d "payment_method=pm_card_visa"
Note: Use only pi_... as the PaymentIntent ID. Never use pi_..._secret_... in the URL. Successful payment should make the PaymentIntent succeeded and the Subscription active.
19. Retrieve Subscription
GET https://api.stripe.com/v1/subscriptions/{SUBSCRIPTION_ID}

curl https://api.stripe.com/v1/subscriptions/sub_xxxxxxxxx \
  -u "{{STRIPE_SECRET_KEY}}:"
Note: Check status, customer, collection_method, default_payment_method, latest_invoice, cancel_at_period_end, and test_clock.
20. List Customer Subscriptions
GET https://api.stripe.com/v1/subscriptions?customer={CUSTOMER_ID}

curl "https://api.stripe.com/v1/subscriptions?customer=cus_xxxxxxxxx" \
  -u "{{STRIPE_SECRET_KEY}}:"
Note: The cus_... comes from Create/Retrieve Customer.
21. Retrieve Customer
GET https://api.stripe.com/v1/customers/{CUSTOMER_ID}

curl https://api.stripe.com/v1/customers/cus_xxxxxxxxx \
  -u "{{STRIPE_SECRET_KEY}}:"
Note: Use the cus_... returned by Create Customer.
22. Retrieve Product
GET https://api.stripe.com/v1/products/{PRODUCT_ID}

curl https://api.stripe.com/v1/products/prod_xxxxxxxxx \
  -u "{{STRIPE_SECRET_KEY}}:"
Note: Use the prod_... from Create/List Products.
23. Retrieve Price
GET https://api.stripe.com/v1/prices/{PRICE_ID}

curl https://api.stripe.com/v1/prices/price_xxxxxxxxx \
  -u "{{STRIPE_SECRET_KEY}}:"
Note: Use the price_... from Create/List Prices.
24. List Customer Invoices
GET https://api.stripe.com/v1/invoices?customer={CUSTOMER_ID}

curl "https://api.stripe.com/v1/invoices?customer=cus_xxxxxxxxx" \
  -u "{{STRIPE_SECRET_KEY}}:"
Note: Use this to verify initial and recurring invoices. Check id, status, amount_due, amount_paid, amount_remaining, subscription, and payment_intent.
25. Retrieve PaymentIntent
GET https://api.stripe.com/v1/payment_intents/{PAYMENT_INTENT_ID}

curl https://api.stripe.com/v1/payment_intents/pi_xxxxxxxxx \
  -u "{{STRIPE_SECRET_KEY}}:"
Note: The pi_... comes from the Invoice/payment flow. Prefer the PaymentIntent response id field rather than deriving it from a client secret.
26. Advance Test Clock – Test Automatic Renewal
POST https://api.stripe.com/v1/test_helpers/test_clocks/{CLOCK_ID}/advance

curl -X POST https://api.stripe.com/v1/test_helpers/test_clocks/clock_xxxxxxxxx/advance \
  -u "{{STRIPE_SECRET_KEY}}:" \
  -d "frozen_time=1797379200"

Example:
Simulation start: Sep 15, 2026
3-month subscription: Sep 15 → Dec 15
Advance to: Dec 15, 2026 or slightly after
Note: Expected: new invoice → automatic payment attempt → invoice.paid → subscription remains active → new billing period starts.
27. Retrieve Test Clock
GET https://api.stripe.com/v1/test_helpers/test_clocks/{CLOCK_ID}

curl https://api.stripe.com/v1/test_helpers/test_clocks/clock_xxxxxxxxx \
  -u "{{STRIPE_SECRET_KEY}}:"
Note: Check frozen_time and status. After advancing, wait until status=ready before checking billing objects.
27. Verify Automatic Subscription Renewal
This is the final test that confirms Stripe is automatically renewing the subscription and charging the saved PaymentMethod. You do NOT create a new subscription manually and you do NOT manually charge the customer.
Step 1 – Retrieve the subscription
GET https://api.stripe.com/v1/subscriptions/{SUBSCRIPTION_ID}

curl https://api.stripe.com/v1/subscriptions/sub_xxxxxxxxx \
  -u "{{STRIPE_SECRET_KEY}}:
Expected after renewal: status remains active (assuming payment succeeds), and current_period_start/current_period_end move to the new billing period.
Step 2 – List the customer's invoices
GET https://api.stripe.com/v1/invoices?customer={CUSTOMER_ID}

curl "https://api.stripe.com/v1/invoices?customer=cus_xxxxxxxxx" \
  -u "{{STRIPE_SECRET_KEY}}:
Look for the new invoice created for the renewal. Its billing reason should indicate a subscription cycle, and the invoice should become paid when the automatic charge succeeds.
Step 3 – Retrieve the renewal invoice
GET https://api.stripe.com/v1/invoices/{INVOICE_ID}

curl https://api.stripe.com/v1/invoices/in_xxxxxxxxx \
  -u "{{STRIPE_SECRET_KEY}}:
Expected: the invoice status is paid after Stripe successfully collects the recurring payment.
Step 4 – Verify the renewal PaymentIntent
GET https://api.stripe.com/v1/payment_intents/{PAYMENT_INTENT_ID}

curl https://api.stripe.com/v1/payment_intents/pi_xxxxxxxxx \
  -u "{{STRIPE_SECRET_KEY}}:
Expected: the PaymentIntent status is succeeded. This confirms Stripe actually processed the automatic renewal payment.
What the successful automatic renewal proves:
• The subscription reached its billing date.
• Stripe automatically generated the next recurring invoice.
• Stripe automatically attempted to charge the customer's saved PaymentMethod.
• The renewal invoice was paid successfully.
• The subscription continued into the next billing period.
• No new customer or subscription was created for the renewal.
Example: For a 3-month subscription starting Sep 15, 2026, the first period is Sep 15 → Dec 15. After the test clock reaches Dec 15 and Stripe completes the renewal, the next period becomes Dec 15 → Mar 15, 2027.
Important: In Test Clock simulations, Stripe may need some processing time after reaching the billing date. Wait until the Test Clock status is ready, then check the invoice/subscription/payment status.
29. Cancel Subscription Immediately
DELETE https://api.stripe.com/v1/subscriptions/{SUBSCRIPTION_ID}

curl -X DELETE \
  https://api.stripe.com/v1/subscriptions/sub_xxxxxxxxx \
  -u "{{STRIPE_SECRET_KEY}}:"
Note: Use the sub_... from Create/Retrieve Subscription.
30. Cancel at End of Billing Period
POST https://api.stripe.com/v1/subscriptions/{SUBSCRIPTION_ID}

curl https://api.stripe.com/v1/subscriptions/sub_xxxxxxxxx \
  -u "{{STRIPE_SECRET_KEY}}:" \
  -d "cancel_at_period_end=true"
Note: The subscription remains active through the current billing period and ends at period end.
31. Webhooks – Required for Production
Recommended events:
invoice.paid
invoice.payment_failed
invoice.payment_action_required
customer.subscription.created
customer.subscription.updated
customer.subscription.deleted
payment_intent.succeeded
payment_intent.payment_failed
Note: Use webhooks to synchronize Stripe state with the POS. Verify Stripe-Signature using the endpoint signing secret whsec_.... Make webhook handling idempotent because events can be retried and are not guaranteed to arrive in creation order.
32. POS Database IDs to Store
customers
- stripe_customer_id

package_prices
- stripe_product_id
- stripe_price_id
- billing_interval
- billing_interval_count
- amount
- currency

subscriptions
- stripe_subscription_id
- stripe_customer_id
- stripe_price_id
- status
- current_period_start
- current_period_end
- cancel_at_period_end
- latest_invoice_id

payments / invoices
- stripe_payment_intent_id
- stripe_invoice_id
- amount
- currency
- status
- paid_at
Note: Useful Stripe metadata: pos_customer_id, pos_package_id, pos_subscription_id.
33. Where Each Stripe ID Comes From
ID
Meaning
Where to get it
prod_...
Product
Create Product response / List Products
price_...
Recurring Price
Create Price response / List Prices
cus_...
Customer
Create Customer response
seti_...
SetupIntent
Create SetupIntent response
pm_...
PaymentMethod
Successful SetupIntent confirmation; test value can be pm_card_visa
sub_...
Subscription
Create Subscription response
in_...
Invoice
Subscription latest_invoice / List Invoices
pi_...
PaymentIntent
Invoice payment_intent / PaymentIntent response
clock_...
Test Clock
Create Test Clock response
whsec_...
Webhook signing secret
Stripe Dashboard/Workbench webhook endpoint

34. Common Postman Mistakes
Never use the Stripe secret key in frontend/mobile code.
Do not send raw real card numbers to /v1/payment_methods. Use Stripe test PaymentMethods/test UI.
Do not use pi_..._secret_... as a PaymentIntent ID. Use the id field: pi_....
Do not create a new Product for every interval; use multiple recurring Prices under one Product.
Do not manually charge every month with a cron job for normal subscriptions; Stripe Billing handles recurring billing.
SetupIntent creation alone does not produce a PaymentMethod ID. Confirm it first.
Do not duplicate PaymentMethod attachment after a successful Customer SetupIntent.
Do not treat an incomplete Subscription as a successful purchase.
Use webhooks for future payment/subscription state changes.
35. Final POS Flow
Customer selects package
        ↓
Find/Create Stripe Customer
        ↓
Select Stripe Price
        ↓
Collect/save payment method
        ↓
Create/confirm SetupIntent when needed
        ↓
Create Subscription
        ↓
First Invoice / PaymentIntent
        ↓
Confirm first payment
        ↓
Subscription active
        ↓
Billing period expires
        ↓
Stripe creates next invoice
        ↓
Stripe automatically attempts payment
        ↓
Webhook: invoice.paid / payment_failed
        ↓
POS updates subscription/package status
36. Recommended Responsibility Split
Stripe handles: billing dates, recurring invoices, automatic payment attempts, subscription lifecycle, payment status, retries/revenue recovery configuration, and webhooks.
POS handles: package catalog, customer records, package selection, access/feature rules, local subscription records, UI, and webhook-driven synchronization.
37. Official Stripe References
https://docs.stripe.com/recurring-payments
https://docs.stripe.com/api/subscriptions/create
https://docs.stripe.com/api/setup_intents/confirm
https://docs.stripe.com/api/payment_methods/attach
https://docs.stripe.com/api/test_clocks
https://docs.stripe.com/webhooks


