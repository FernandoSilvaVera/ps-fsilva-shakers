{extends file='page.tpl'}

{block name='page_content'}
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h1 class="display-4 mb-4 text-primary">{l s='My Loyalty Points' d='Modules.Ps_Customer_Loyalty.Loyalty'}</h1>
                        <div class="loyalty-points-container mt-5">
                            <p class="lead">
                                {l s='You have' d='Modules.Ps_Customer_Loyalty.Loyalty'} 
                                <span class="font-weight-bold text-success" style="font-size: 2rem;">{$loyalty_points}</span> 
                                {l s='loyalty points.' d='Modules.Ps_Customer_Loyalty.Loyalty'}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}
