<div class="loyalty-section p-4 border rounded shadow-sm bg-light">
    <h3 class="mb-4 text-primary">{l s='Redeem your loyalty points' d='Modules.Ps_Customer_Loyalty.Loyalty'}</h3>
    <p class="mb-3">{l s='You have' d='Modules.Ps_Customer_Loyalty.Loyalty'} <strong>{$loyalty_points}</strong> {l s='points available.' d='Modules.Ps_Customer_Loyalty.Loyalty'}</p>

    {if $loyalty_points > 0}
    <form action="{$link->getPageLink('order')}" method="post" id="redeem-loyalty-form" class="form-inline">
    <div class="form-group mr-3">
    <label for="loyalty_points_redeem" class="sr-only">{l s='Enter the number of points to redeem:' d='Modules.Ps_Customer_Loyalty.Loyalty'}</label>
    <input type="number" class="form-control" name="loyalty_points_redeem" id="loyalty_points_redeem" max="{$loyalty_points}" value="0" min="0" placeholder="{l s='Enter points' d='Modules.Ps_Customer_Loyalty.Loyalty'}" />
    </div>
    <button type="submit" name="redeem_points" value="1" class="btn btn-success">{l s='Apply points' d='Modules.Ps_Customer_Loyalty.Loyalty'}</button>
    </form>
    {/if}
</div>
