# ps_customer_loyalty

## Description

The **ps_customer_loyalty** module allows customers to accumulate loyalty points based on the amount spent on their orders. These points can be redeemed as discounts on future purchases. The module includes a loyalty page in the customer account section to display accumulated points and a field in the checkout page to redeem those points as a discount.

## Module Features

- **Point accumulation**: Customers earn points for every 10 euros spent (this value can be changed by modifying the constant `MAX_EURO`).
- **Point redemption**: Customers can redeem accumulated points for discounts on future purchases.
- **Customer account page**: Displays the customer's accumulated points.
- **Checkout page field**: Allows the customer to apply accumulated points as a discount.

## Installation

1. **Upload the module**: Copy the `ps_customer_loyalty` folder to the `/modules/` directory of your PrestaShop installation.
2. **Install the module**:
   - In the PrestaShop Back Office, go to **Modules > Module Manager**.
   - Search for the module **Customer Loyalty Program** and click **Install**.
3. **Configuration**:
   - Access the module's configuration page and adjust the ratio of points per euros spent if needed.

## How the Module Works

1. **Point Accumulation**:
   - Each time a customer places an order, the `actionValidateOrder` hook calculates the points based on the total paid. By default, 1 point is earned for every 10 euros. This is controlled by the constant `MAX_EURO` in the `ps_customer_loyalty.php` file, which can be modified as needed:

     ```php
     const MAX_EURO = 10; // Maximum value in euros to calculate points
     ```

   - The points are stored in a custom database table named `ps_customer_loyalty`.

2. **Point Redemption**:
   - In the checkout page, the customer can input the number of points they want to redeem. These points are converted into a discount and applied to the cart total.
   - The discount value is controlled by the constant `POINT_TO_EURO`, which specifies how many euros are given per point redeemed:

     ```php
     const POINT_TO_EURO = 1; // 1 point = 1 euro
     ```

   - The redeemed points are automatically deducted from the customer's account.

3. **Loyalty Points in "My Account"**:
   - A new tab is added to the "My Account" section, which shows the accumulated loyalty points. This is handled using the `displayCustomerAccount` hook.

## Hooks Used

1. **actionValidateOrder**: This hook is triggered when an order is validated and calculates and stores the loyalty points for the customer.
2. **displayCustomerAccount**: Adds a new tab in the customer account to display the accumulated loyalty points.
3. **displayCheckoutSummary**: Adds a field in the checkout page where customers can redeem their points as a discount for future purchases.

## Database

The module uses a custom table called `ps_customer_loyalty` to store the loyalty points for each customer. The table structure is as follows:

```sql
CREATE TABLE IF NOT EXISTS `ps_customer_loyalty` (
    `id_loyalty` INT(11) NOT NULL AUTO_INCREMENT,
    `id_customer` INT(11) NOT NULL,
    `points` INT(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id_loyalty`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

