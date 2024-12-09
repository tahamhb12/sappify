<?php

namespace App\Actions;

use App\Models\BillingEvents;
use Illuminate\Database\Eloquent\Model;

class CreateBillingEvent
{
    private $billing_event;

    private $shop_id;

    private $shopify_app;

    public function __construct($data, $shop_id, Model $shopify_app)
    {
        $this->billing_event = $data;
        $this->shopify_app = $shopify_app;
        $this->shop_id = $shop_id;
    }

    public function createBillingEvent()
    {
        $billing_event = BillingEvents::firstOrCreate([
            'event_id' => substr($this->billing_event['type'], 0, 6) === 'CREDIT' ? $this->billing_event['appCredit']['id'] : $this->billing_event['charge']['id'],
            'type' => $this->billing_event['type'],
            'amount' => substr($this->billing_event['type'], 0, 6) === 'CREDIT' ? $this->billing_event['appCredit']['amount']['amount'] : $this->billing_event['charge']['amount']['amount'],
            'currency' => substr($this->billing_event['type'], 0, 6) === 'CREDIT' ? $this->billing_event['appCredit']['amount']['currencyCode'] : $this->billing_event['charge']['amount']['currencyCode'],
            'billingOn' => substr($this->billing_event['type'], 0, 12) === 'SUBSCRIPTION' ? $this->billing_event['charge']['billingOn'] : null,
            'name' => substr($this->billing_event['type'], 0, 6) === 'CREDIT' ? $this->billing_event['appCredit']['name'] : $this->billing_event['charge']['name'],
            'isTest' => substr($this->billing_event['type'], 0, 6) === 'CREDIT' ? $this->billing_event['appCredit']['test'] : $this->billing_event['charge']['test'],
            'app_id' => $this->shopify_app->id,
            'partner_id' => $this->shopify_app->partner_id,
            'shop_id' => $this->shop_id,
            'occurred_at' => $this->billing_event['occurredAt'],
        ]);

        return $billing_event;
    }
}
