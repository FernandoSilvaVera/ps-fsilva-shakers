<?php

class Ps_Customer_LoyaltyLoyaltyModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $points = Db::getInstance()->getValue('
            SELECT points FROM `'._DB_PREFIX_.'customer_loyalty`
            WHERE id_customer = '.(int)$this->context->customer->id
        );

        if ($points === false) {
            $points = 0;
        }

        $this->context->smarty->assign(array(
            'loyalty_points' => (int)$points
        ));

        $this->setTemplate('module:ps_customer_loyalty/views/templates/front/loyalty.tpl');
    }
}
