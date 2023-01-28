<?php

namespace Osarze\RecentSalesNotification\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use DateTime;

class Data extends AbstractHelper
{

	const XML_PATH_RECENT_SALES = 'osarze_recent_sales_notification/';

	public function getConfigValue($field, $storeId = null)
	{
		return $this->scopeConfig->getValue(
			$field, ScopeInterface::SCOPE_STORE, $storeId
		);
	}

	public function getRecentSalesNotificationConfig($code, $storeId = null)
	{

		return $this->getConfigValue(self::XML_PATH_RECENT_SALES .'recent_sales/'. $code, $storeId);
	}

	public function dateDifference($datetime1, $datetime2 = ''){
		$datetime1 = new DateTime($datetime1);
		$datetime2 = new DateTime($datetime2);
		$diff = date_diff($datetime1,$datetime2);

		$diffFormat = $diff->days . ' day' . ($diff->days > 1 ? 's' : '');
		if($diff->days = 0){
			if($diff->h > 0){
				$diffFormat = $diff->h . ' hour' . ($diff->h > 1 ? 's' : '');
			}else{
				$diffFormat = $diff->i . ' hour' . ($diff->i > 1 ? 's' : '');
			}
		}else if($diff->days >= 31 && $diff->days < 365){
			$diffFormat = $diff->m . ' month' . ($diff->m > 1 ? 's' : '');
		}elseif($diff->days >= 365){
			$diffFormat = $diff->y . ' year' . ($diff->y > 1 ? 's' : '');
		}
		
		return $diffFormat . ' ago';
	}

}