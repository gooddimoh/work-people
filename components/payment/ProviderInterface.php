<?php
namespace app\components\payment;

interface ProviderInterface
{
    /**
     *  @brief geterate form for transition on payment system
     *  
     *  @return html text (button)
     */
    public function getHtml();
    
    /**
     *  @brief check payment status if it possible
     *  
     *  @return status (success|error|waiting)
     */
    public function getStatus();
}
