<?php
namespace app\components\payment;

use yii\base\BaseObject;
use app\components\payment\PaymentSystemInterface;

class PaymentSystem extends BaseObject
{
    protected $provider;
    
    public function __construct(ProviderInterface $provider, $config = [])
    {
        $this->provider = $provider;
        parent::__construct($config);
    }
    
    /**
     *  @brief geterate form for transition on payment system
     *  
     *  @return html text (button)
     */
    public function renderWidget()
    {
        return $this->provider->getHtml();
    }
    
    public function getStatus()
    {
        return $this->provider->getStatus();
    }
    
}
