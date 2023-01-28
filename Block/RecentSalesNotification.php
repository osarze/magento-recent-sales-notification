<?php
/**
 * Created by PhpStorm.
 * User: Okhamafe Emmanuel
 * Date: 7/8/2019
 * Time: 5:02 AM
 */

namespace Osarze\RecentSalesNotification\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Osarze\RecentSalesNotification\Helper\Data;
use Magento\Catalog\Helper\Image;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;

class RecentSalesNotification extends Template
{
    public $helperData;
    protected $imageHelper;
    protected $_orderCollectionFactory;

    public function __construct(
        Template\Context $context, 
        OrderCollectionFactory $orderCollectionFactory,
        Image $imageHelper,
        Data $helperData, 
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->imageHelper = $imageHelper;
        $this->helperData = $helperData;
    }

    /**
     * Determine if you want to eneable this widget
     */
    public function showRecentSalesWidget(){
        return false;/** Remove this so the plugin will be cintroled by the system config */
        return $this->helperData->getRecentSalesNotificationConfig('enable');
    }

    public function dateDifference($datetime1){
        return $this->helperData->dateDifference($datetime1);
    }

    public function getRecentOrderCollection(){
        $collection = $this->_orderCollectionFactory->create()
        ->addAttributeToSelect('*')
        // ->addFieldToFilter($field, $condition)
        ->setOrder('entity_id','desc')
        ->setPageSize(10); //increase this as the order increase to prevent I used thid to prevent the plugin from picking up Order whose product has been deleted

        return $collection;
    }

    public function getRecentProductSalesCollection(){
        $products = array();

        $i = 0;

        foreach($this->getRecentOrderCollection() as $order){
            foreach($order->getAllVisibleItems() as $item){
                $data = [
                    'name' => $order->getCustomerFirstname(),
                    'state' => $order->getShippingAddress()->getCity() . ', ' . $order->getShippingAddress()->getRegion(),
                    'time' => $this->dateDifference($order->getCreatedAt()),
                    'product' => $item->getName(),
                    'price' =>  number_format($item->getPrice(), 2),
                    'image' => $this->imageHelper->init($item->getProduct(), 'product_thumbnail_image')->getUrl()
                ];
                array_push($products, $data);
            }
        }

        return $products;
    }
}