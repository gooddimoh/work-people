<?php
namespace app\components\sms;

interface ProviderInterface
{
    /**
     *  @brief send sms
     *  
     *  @return html text (button)
     */
    public function send();

}
