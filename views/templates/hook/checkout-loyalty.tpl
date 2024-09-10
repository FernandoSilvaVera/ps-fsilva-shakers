<div class="loyalty-section">
    <h3>{l s='Redeem your loyalty points' d='Modules.Ps_Customer_Loyalty.Loyalty'}</h3>
    <p>{l s='You have' d='Modules.Ps_Customer_Loyalty.Loyalty'} {$loyalty_points} {l s='points available.' d='Modules.Ps_Customer_Loyalty.Loyalty'}</p>

    {if $loyalty_points > 0}
    <form action="" method="post" id="redeem-loyalty-form">
        <label for="loyalty_points_redeem">{l s='Enter the number of points to redeem:' d='Modules.Ps_Customer_Loyalty.Loyalty'}</label>
        <input type="number" name="loyalty_points_redeem" id="loyalty_points_redeem" max="{$loyalty_points}" value="0" />
        <button type="submit" name="redeem_points" class="btn btn-primary">{l s='Apply points' d='Modules.Ps_Customer_Loyalty.Loyalty'}</button>
    </form>
    {/if}
</div>
