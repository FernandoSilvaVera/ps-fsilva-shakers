<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Ps_Customer_Loyalty extends Module
{

	const MAX_EURO = "10";

    public function __construct()
    {
        $this->name = 'ps_customer_loyalty';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Fernando Silva - Shakers';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Customer Loyalty Program');
        $this->description = $this->l('Manage customer loyalty points based on orders.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('PS_CUSTOMER_LOYALTY')) {
            $this->warning = $this->l('No name provided');
        }
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('actionValidateOrder')
            && $this->registerHook('displayCustomerAccount')
            && $this->registerHook('moduleRoutes')
            && $this->installDb();
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallDb();
    }

    public function installDb()
    {
        return Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'customer_loyalty` (
                `id_loyalty` INT(11) NOT NULL AUTO_INCREMENT,
                `id_customer` INT(11) NOT NULL,
                `points` INT(11) NOT NULL DEFAULT 0,
                PRIMARY KEY (`id_loyalty`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
        ');
    }

    public function uninstallDb()
    {
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'customer_loyalty`');
    }

    public function hookActionValidateOrder($params)
    {
        $order = $params['order'];
        $customer = new Customer((int)$order->id_customer);
        $totalPaid = $order->total_paid;
        $pointsEarned = floor($totalPaid / self::MAX_EURO);

        $existingPoints = Db::getInstance()->getValue('
            SELECT points FROM `'._DB_PREFIX_.'customer_loyalty`
            WHERE id_customer = '.(int)$customer->id
        );

        if ($existingPoints !== false) {
            Db::getInstance()->execute('
                UPDATE `'._DB_PREFIX_.'customer_loyalty`
                SET points = points + '.(int)$pointsEarned.'
                WHERE id_customer = '.(int)$customer->id
            );
        } else {
            Db::getInstance()->execute('
                INSERT INTO `'._DB_PREFIX_.'customer_loyalty` (id_customer, points)
                VALUES ('.(int)$customer->id.', '.(int)$pointsEarned.')
            ');
        }
    }

    public function hookDisplayCustomerAccount()
	{
        $this->context->smarty->assign(array(
            'loyalty_points_link' => $this->context->link->getModuleLink('ps_customer_loyalty', 'loyalty')
        ));

        return $this->display(__FILE__, 'views/templates/hook/my-account.tpl');
    }

    public function hookModuleRoutes($params)
    {
        return array(
            'module-ps_customer_loyalty-loyalty' => array(
                'controller' => 'loyalty',
                'rule' => 'loyalty',
                'keywords' => array(),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'ps_customer_loyalty',
                )
            )
        );
	}


	public function hookDisplayCheckoutSummary($params)
	{
		die("fer");
		$customer = $this->context->customer;

		if ($customer->isLogged()) {
			$points = Db::getInstance()->getValue('
				SELECT points FROM `'._DB_PREFIX_.'customer_loyalty`
				WHERE id_customer = '.(int)$customer->id
			);

			if ($points === false) {
				$points = 0;
			}

			$this->context->smarty->assign(array(
				'loyalty_points' => $points
			));

			return $this->display(__FILE__, 'views/templates/hook/checkout-loyalty.tpl');
		}
	}

	public function hookActionCartSave($params)
	{
		if (Tools::isSubmit('redeem_points')) {
			$pointsToRedeem = (int)Tools::getValue('loyalty_points_redeem');
			$customer = $this->context->customer;
			
			$points = Db::getInstance()->getValue('
				SELECT points FROM `'._DB_PREFIX_.'customer_loyalty`
				WHERE id_customer = '.(int)$customer->id
			);

			if ($points && $pointsToRedeem > 0 && $pointsToRedeem <= $points) {
				$discountAmount = $pointsToRedeem * 1;

				$cart = $this->context->cart;
				$cart->addCartRule($this->createCartRule($discountAmount));

				Db::getInstance()->execute('
					UPDATE `'._DB_PREFIX_.'customer_loyalty`
					SET points = points - '.(int)$pointsToRedeem.'
					WHERE id_customer = '.(int)$customer->id
				);
			}
		}
	}

	private function createCartRule($discountAmount)
	{
		$cartRule = new CartRule();
		$cartRule->id_customer = $this->context->customer->id;
		$cartRule->date_from = date('Y-m-d H:i:s');
		$cartRule->date_to = date('Y-m-d H:i:s', strtotime('+1 day'));
		$cartRule->description = 'Loyalty points discount';
		$cartRule->quantity = 1;
		$cartRule->reduction_amount = (float)$discountAmount;
		$cartRule->reduction_tax = true;
		$cartRule->active = true;
		$cartRule->add();
		
		return $cartRule;
	}


}
