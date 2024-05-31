<?php
class Ccc_Salesman_Model_Commission_Observer
{
    public function addCommissionToOrder(Varien_Event_Observer $observer)
    {
        $order = $observer->getOrder();
        $userId = Mage::getSingleton('admin/session')->getUser()->getId();
        $items = $order->getAllVisibleItems();

        $metricCollection = Mage::getModel('salesman/metric')->getCollection();
        $metricCollection->addFieldToFilter('user_id', ['eq' => $userId]);

        $productItem = $metricCollection->getItemByColumnValue('metric', Ccc_Salesman_Model_Metric::PRODUCT_METRIC);
        $productPercentage = $productItem ? $productItem->getPercentage() : 0;

        $shippingItem = $metricCollection->getItemByColumnValue('metric', Ccc_Salesman_Model_Metric::SHIPPING_METRIC);
        $shippingPercentage = $shippingItem ? $shippingItem->getPercentage() : 0;

        $taxItem = $metricCollection->getItemByColumnValue('metric', Ccc_Salesman_Model_Metric::TAX_METRIC);
        $taxPercentage = $taxItem ? $taxItem->getPercentage() : 0;

        //product
        foreach ($items as $item) {
            $quantity = $item->getQtyOrdered();
            $metric = Ccc_Salesman_Model_Metric::PRODUCT_METRIC;
            $productId = $item->getProductId();
            $product = Mage::getModel('catalog/product')->load($item->getProductId());
            $optionValue = $product->getAttributeText('is_low_seller_product');
            $oldPrice = $item->getOriginalPrice();
            $description = 'Commission for product ID ' . $productId;
            $newPrice = $item->getBasePrice();
            $upsold = $quantity * ($newPrice - $oldPrice);
            $commissionAmount = ($upsold) * ($productPercentage / 100);
            if($optionValue){
                $productPercentage+=5;
                $commissionAmount = ($upsold) * ($productPercentage / 100);
            }

            $this->addCommission($order->getId(), $userId, $productId, $metric, $oldPrice, $description, $newPrice, $upsold, $productPercentage, $commissionAmount);
        }

        //shipping
        $orderId = $order->getId();
        $metric = Ccc_Salesman_Model_Metric::SHIPPING_METRIC;
        $newPrice = $order->getShippingAmount();
        $upsold = $newPrice;
        $commissionAmount = ($upsold) * ($shippingPercentage / 100);

        $this->addCommission($order->getId(), $userId, $productId = 0, $metric, $oldPrice = 0, $description = "shipping for order $orderId", $newPrice, $upsold, $shippingPercentage, $commissionAmount);

        //tax
        $orderId = $order->getId();
        $metric = Ccc_Salesman_Model_Metric::TAX_METRIC;
        $newPrice = $order->getTaxAmount();
        $upsold = $newPrice;
        $commissionAmount = ($upsold) * ($taxPercentage / 100);

        $this->addCommission($orderId, $userId, $productId = 0, $metric, $oldPrice = 0, $description = "tax for order $orderId", $newPrice, $upsold, $taxPercentage, $commissionAmount);
    }

    public function addCommission($orderId, $userId, $productId, $metric, $oldPrice, $description, $newPrice, $upsold, $percentage, $commission)
    {
        $commissionModel = Mage::getModel('salesman/commission');
        $commissionModel->setData(
            array(
                'order_id' => $orderId,
                'user_id' => $userId,
                'product_id' => $productId,
                'metric' => $metric,
                'old_price' => $oldPrice,
                'description' => $description,
                'new_price' => $newPrice,
                'upsold' => $upsold,
                'percentage' => $percentage,
                'commission' => $commission,
                'created_at' => now(),
                'updated_at' => now(),
                'is_paid' => 0,
                'paid_date' => null,
                'paid_by' => null
            )
        )->save();
    }

    public function cancelOrderCommission(Varien_Event_Observer $observer)
    {
        $order = $observer->getOrder();
        $orderId = $order->getId();

        $commissionRecords = Mage::getModel('salesman/commission')
            ->getCollection()
            ->addFieldToFilter('order_id', $orderId);

        foreach ($commissionRecords as $record) {

            $productId = $record->getProductId();
            $metric = $record->getMetric();
            $oldPrice = $record->getOldPrice();
            $newPrice = $record->getNewPrice();
            $percentage = $record->getPercentage();
            $upsold = $record->getUpsold();
            $commission = $record->getCommission();

            $this->addCommission(
                $orderId,
                $record->getUserId(),
                $productId,
                $metric,
                $oldPrice,
                'Cancelled: ' . $record->getDescription(),
                $newPrice,
                -$upsold,
                $percentage,
                -$commission);
        }
    }
}