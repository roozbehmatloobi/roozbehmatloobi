<?php

/**
 * API version 1
 *
 * @category  API
 * @package   API_Controllers
 */
class API_V1Controller extends Modules_API_Controller_Action {
    
    public function indexAction() {  
        // sample page
    }
    
    public function totalCopiesAction() {
        $cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'totalCopies';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $data = $cache->load($cache_key)) {
	    $result =  $data;
	   
	}else{
	

	    $itemsModel = Base::getModel('Common_Items');
	    
	    //the Items that are currently Active, on Hold or RTF
	    $select    = $itemsModel->select()
				    ->setIntegrityCheck(false)
				    ->from(array('ci' => 'common_items'), array( 'copies' => 'sum(ci.copies)'))
				    ->joinInner(array('co' => 'common_orders'), 'co.id = ci.common_order_id', null)
				    ->where("co.status NOT IN ('CANCELLED', 'SENT' , 'PENDING')")
				    ->where("ci.status is NULL or ci.status NOT IN  (?)", array('SENT', 'CANCELLED'));
				    
	    $item = $itemsModel->fetchRow($select);
	    
	    if (!$item) {
		    
			    $result['errcode']  = 1;
			    $result['errmsg' ] = "errmsg";
	    }else{
		$result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> (int)$item->copies, 'link' => '/common/items?custom=api_total_copies');
		$cache->save($result, $cache_key);
	    }
	}
        
        $this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$this->getResponse()->setBody(Zend_Json::encode($result));
    }
    
     public function nzCopiesAction() {
     
       $itemsModel = Base::getModel('Common_Items');
	    
	    //the Items that are currently Active, on Hold or RTF
	    $select    = $itemsModel->select()
				    ->setIntegrityCheck(false)
				    ->from(array('ci' => 'common_items'), array( 'copies' => 'sum(ci.copies)'))
				    ->joinInner(array('co' => 'common_orders'), 'co.id = ci.common_order_id', null)
				    ->where("co.status NOT IN ('CANCELLED', 'SENT' , 'PENDING')")
				    ->where("ci.status is NULL or ci.status NOT IN  (?)", array('SENT', 'CANCELLED'))
					->where('ci.sub_id=21 or ci.sub_id=22');
				    
	    $item = $itemsModel->fetchRow($select);
	    
	    if (!$item) {
		    
			    $result['errcode']  = 1;
			    $result['errmsg' ] = "errmsg";
	    }else{
			$result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> (int)$item->copies, 'link' => '/common/items?custom=nzitems');
	    }
	    
	    $this->_helper->viewRenderer->setNoRender(true);
		$this->getResponse()->setHeader('Content-Type','text/html');
		$this->getResponse()->setBody(Zend_Json::encode($result));
	    
	}
    
    public function priorityAction() {
        
        $this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'priority';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
	    
	$itemsModel = Base::getModel('Common_Items');
	
	$select    = $itemsModel->select()
				->setIntegrityCheck(false)
				->from(array('ci' => 'common_items'), array( 'copies' => 'sum(ci.copies)'))
				->joinInner(array('co' => 'common_orders'), 'co.id = ci.common_order_id', null)
				->where("co.status NOT IN ('CANCELLED', 'SENT' , 'PENDING')")
				->where('co.priority = ?', 1)
				->where("ci.status is NULL or ci.status NOT IN  (?)", array('SENT', 'CANCELLED'));
	$item = $itemsModel->fetchRow($select);
	
	if (!$item) {
			$result['errcode']  = 1;
			$result['errmsg' ] = "errmsg";
	} else {		
	    $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> (int)$item->copies, 'link'=>'/common/items?custom=api_priority');
	    $cache->save($result, $cache_key);
	}

	$this->getResponse()->setBody(Zend_Json::encode($result));
       
    }
    
    
    
    public function watchAction() {
        
	$this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'watch';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
	
        $itemsModel = Base::getModel('Common_Items');
	
	$select    = $itemsModel->select()
	                        ->setIntegrityCheck(false)
                                ->from(array('ci' => 'common_items'), array( 'copies' => 'sum(ci.copies)'))
				->joinInner(array('co' => 'common_orders'), 'co.id = ci.common_order_id', null)
				->where('co.watch = ?', 1)
                                ->where("co.status NOT IN ('CANCELLED', 'SENT' , 'PENDING')")
                                ->where("ci.status is NULL or ci.status NOT IN  (?)", array('SENT', 'CANCELLED'));
        $item = $itemsModel->fetchRow($select);
        
        if (!$item) {
			$result['errcode']  = 1;
			$result['errmsg' ] = "errmsg";
        } else {
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> (int)$item->copies, 'link'=>'/common/items?custom=api_watch');
	    $cache->save($result, $cache_key);
        }      
                
	$this->getResponse()->setBody(Zend_Json::encode($result));
    }
    
   
    
    public function holdAction() {
        $this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'hold';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
        $itemsModel = Base::getModel('Common_Items');
	
	$select    = $itemsModel->select()
	                        ->setIntegrityCheck(false)
                                ->from(array('ci' => 'common_items'), array( 'copies' => 'sum(ci.copies)'))
				->joinInner(array('co' => 'common_orders'), 'co.id = ci.common_order_id', null)
                                //->where('dominantStatus(ci.common_order_id, ci.status) = ?', 'HOLD');
				->where("(ci.status = 'HOLD') OR (co.status = 'HOLD' AND (ci.status is NULL OR ci.status IN('ACTIVE', 'HOLD', 'RTF')))")
                                ->where("co.status NOT IN ('SENT', 'CANCELLED', 'PENDING')");
                               
        $item = $itemsModel->fetchRow($select);
        
        if (!$item) {
			$result['errcode']  = 1;
			$result['errmsg' ] = "errmsg";
        } else {
            $result  = array('errcode' => 0, 'errmsg' => 'OKh', 'data'=> (int)$item->copies, 'link'=>'/common/items?custom=api_hold');
	    $cache->save($result, $cache_key);
        }
       
	$this->getResponse()->setBody(Zend_Json::encode($result));
    }
    
   
    
    public function salesCopies1Action() {
        $this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'salesCopies';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
        $request    = $this->getRequest();
        
        $week_start_date    = Base_Utils_Date::weekStartDate(); //'2014-05-25';
        $num_weeks          = $request->getParam('num_weeks');
        if((int)$num_weeks == 0){
            $num_weeks = 20;
        }
        
	$days               = 7 * $num_weeks;
	$date_start_epoch   = date('Y-m-d', strtotime($week_start_date . " -$days day"));
        
        // common items
        $ciModel  = Base::getModel('Common_Items');
        $exclude_statuses = array('CANCELLED');
        $select = $ciModel->select()
                        ->setIntegrityCheck(false)
                        ->from(array('ci' => 'common_items'), new Zend_Db_Expr('WEEKOFYEAR(FROM_DAYS(TO_DAYS(co.date_first_active) -MOD(TO_DAYS(co.date_first_active) -1, 7))) as week ,  FROM_DAYS(TO_DAYS(co.date_first_active) -MOD(TO_DAYS(co.date_first_active) -1, 7)) AS week_beginning, COUNT(ci.id) as count, SUM(ci.copies) as units'))
                        ->join(array('co' => 'common_orders'), 'ci.common_order_id = co.id', null)
                        ->where(new Zend_Db_Expr("co.date_first_active between '$date_start_epoch' AND NOW()"))
                        ->where('co.status NOT IN (?)', $exclude_statuses)
                        ->where('ci.status NOT IN (?) OR ci.status IS NULL', $exclude_statuses)
                        ->group(new Zend_Db_Expr('FROM_DAYS(TO_DAYS(co.date_first_active) -MOD(TO_DAYS(co.date_first_active) -1, 7))'))
                        ->order(new Zend_Db_Expr('FROM_DAYS(TO_DAYS(co.date_first_active) -MOD(TO_DAYS(co.date_first_active) -1, 7))'));
        $select = $ciModel->select()
                        ->setIntegrityCheck(false)
                        ->from(array('ci' => 'common_items'), new Zend_Db_Expr('co.date_first_active as week, COUNT(ci.id) as count, SUM(ci.copies) as units'))
                        ->join(array('co' => 'common_orders'), 'ci.common_order_id = co.id', null)
                        //->where(new Zend_Db_Expr("co.date_first_active between '$date_start_epoch' AND NOW()"))
                        ->where('co.status NOT IN (?)', $exclude_statuses)
                        ->where('ci.status NOT IN (?) OR ci.status IS NULL', $exclude_statuses)
                        ->group('co.date_first_active')
                        ->order('co.date_first_active');
                        
        // filter sub ids
        if (isset($sub_id) && $sub_id > 0) {
                $select->where('co.sub_id = ?', $sub_id);
        }		
        // filter product lines based in the order item
        if (isset($product_line_id) && $product_line_id > 0) {
            $select->join(array('cp' => 'client_products'), 'ci.client_product_id = cp.id', null)
                        ->where('cp.product_line_id = ?', $product_line_id);
        }		
        if (isset($internal) && $internal != '') {
            $select->join(array('cu' => 'common_users'), 'co.common_user_id = cu.id', null)
                       ->where('cu.internal = ?', $internal);
        }
        
        $common_items_data =$ciModel->fetchAll($select);
        if (!$common_items_data) {
			$result['errcode']  = 1;
			$result['errmsg' ] = "errmsg";
        }else{
            $item_count_units_grp = array();
           
            foreach($common_items_data->toArray() as $item){
                $item_count_units_grp[$item['week']] = array(
                                                                                         $item['week'],
                                                                                         (int)$item['count'],
                                                                                         (int)$item['units']);
                //$item_count_units_grp[$item['week'].'|'.$item['week_beginning']] = array('week_no'           => $item['week'],
                //                                'week_start_date'   => $item['week_beginning'],
                //                                'total_item'        => $item['count'],
                //                                'copies'            => $item['units']);
            }
            $item_count_by_week[] = array('Week', 'Total Items', 'Copies');
            
            foreach(range(0, ($num_weeks-1)) as $range) {
                $days             = 7 * $range;
                $date_start_epoch = strtotime($week_start_date . " -$days day");
                $week_no          = (integer) date('W', $date_start_epoch);
                $date_start_ymd = date('Y-m-d', $date_start_epoch);
                if(isset($item_count_units_grp[$week_no.'|'.$date_start_ymd])){
                    $item_count_by_week[] = $item_count_units_grp[$week_no.'|'.$date_start_ymd]  ;               
                }else{
                    $item_count_by_week[] = array(  $week_no .','.$date_start_ymd, 0, 0);
                    //$item_count_by_week[] = array(  'week_no'           => $week_no,
                    //                                'week_start_date'   => $date_start_ymd,
                    //                                'total_item'        => '',
                    //                                'copies'            => '');
                }
            }        
            //Zend_Debug::dump($item_count_by_week);
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=>$item_count_by_week);
	    $cache->save($result, $cache_key);
        }
        
	$this->getResponse()->setBody(Zend_Json::encode($result));        
    }
    
    public function overdueOrderAction() {
        
        $this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'overdueOrder';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
	$ordersModel = Base::getModel('Common_Orders');
	
	$select    = $ordersModel->select()
	                        ->setIntegrityCheck(false)
                                ->from(array('co' => 'common_orders'), array( 'count' => 'count(co.id)'))
								->where("co.date_due < CURDATE()")
                                ->where("co.status NOT IN ('SENT', 'CANCELLED', 'PENDING')");
                        
        $order = $ordersModel->fetchRow($select);
        
        if (!$order) {
			$result['errcode']  = 1;
			$result['errmsg' ] = "errmsg";
        } else {    
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> (int)$order->count, 'link'=>'/common/orders?custom=api_overdue_orders');
	    $cache->save($result, $cache_key);
        }
       
	$this->getResponse()->setBody(Zend_Json::encode($result));
        
    }
    
    public function overdueItemsAction() {
        $this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'overdueItems';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
        $itemsModel = Base::getModel('Common_Items');
	
	$select    = $itemsModel->select()
	                        ->setIntegrityCheck(false)
                                ->from(array('ci' => 'common_items'), array( 'copies' => 'sum(ci.copies)'))
								->joinInner(array('co' => 'common_orders'), 'co.id = ci.common_order_id', null)
                                ->where("co.date_due < CURDATE()")
								->where("ci.status is NULL or ci.status NOT IN  (?)", array('SENT', 'CANCELLED'))
                                ->where("co.status NOT IN ('SENT', 'CANCELLED', 'PENDING')");
                               
        $item = $itemsModel->fetchRow($select);
        
        if (!$item) {
			$result['errcode']  = 1;
			$result['errmsg' ] = "errmsg";
        } else {            
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> (int)$item->copies, 'link'=>'/common/items?custom=api_overdue_items');
	    $cache->save($result, $cache_key);
        }
 
	$this->getResponse()->setBody(Zend_Json::encode($result));
        
    }
    
    public function activeItemAction() {
        $this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'activeItem';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
        $itemsModel = Base::getModel('Common_Items');
	
	$select    = $itemsModel->select()
	                        ->setIntegrityCheck(false)
                                ->from(array('ci' => 'common_items'), array( 'copies' => 'sum(ci.copies)'))
				->joinInner(array('co' => 'common_orders'), 'co.id = ci.common_order_id', null)
				->where("co.date_due < NOW()")
                                ->where("ci.status = 'ACTIVE' AND co.status = 'ACTIVE'")
				->orwhere("co.status = 'ACTIVE' AND (ci.status in (?) or ci.status is NULL)",   array('ACTIVE','HOLD','CANCELLED','RTF', ''));
                                
                       
        $item = $itemsModel->fetchRow($select);
        
        if (!$item) {
			$result['errcode']  = 1;
			$result['errmsg' ] = "errmsg";
        } else {    
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> $item->copies);
	    $cache->save($result, $cache_key);
        }
	
	$this->getResponse()->setBody(Zend_Json::encode($result));
        
    }
    
    public function activeItemsPlatinumUsersAction() {
        $this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'activeItemsPlatinumUsers';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
	$itemsModel = Base::getModel('Common_Items');
		
	//the Items that are currently Active, on Hold or RTF for Users who are flagged as a Platinum customer
	$select    = $itemsModel->select()
		                ->setIntegrityCheck(false)
				->from(array('ci' => 'common_items'), array( 'copies' => 'sum(ci.copies)'))
				->joinInner(array('co' => 'common_orders'), 'co.id = ci.common_order_id', null)
				->joinInner(array('cu' => 'common_users'), 'cu.id = co.common_user_id', null)
				->where("cu.platinum_account = ?" ,'1')
				->where("co.status NOT IN ('SENT', 'CANCELLED', 'PENDING')")
				->where("ci.status is NULL or ci.status NOT IN  (?)", array('SENT', 'CANCELLED'))
				->order("co.date_due");
                               
        $item = $itemsModel->fetchRow($select);
        
        if (!$item) {
			$result['errcode']  = 1;
			$result['errmsg' ] = "errmsg";
        } else {    
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> $item->copies, 'link'=>'/common/items?custom=api_active_items_platinum_users');
	    $cache->save($result, $cache_key);
        }
       
	$this->getResponse()->setBody(Zend_Json::encode($result));
        
    }
    
    public function criticalDatesAction() {
        
	$this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'criticalDates';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
	
        $request    = $this->getRequest();
        
        $itemsModel = Base::getModel('Common_Items');
	
	$select    = $itemsModel->select()
	                        ->setIntegrityCheck(false)
                                ->from(array('ci' => 'common_items'), array('copies' => 'sum(ci.copies)'))
				->joinInner(array('co' => 'common_orders'), 'co.id = ci.common_order_id', null)
                                ->joinInner(array('s' => 'subs'), 'co.sub_id = s.id', null)
                                ->where('co.date_due <= s.seasonicon_cutoff_date')                                
                                ->where("co.status NOT IN ('SENT', 'CANCELLED', 'PENDING')")
				->where("ci.status is NULL or ci.status NOT IN  (?)", array('SENT', 'CANCELLED'));
                        
        $item = $itemsModel->fetchRow($select);
        
         if (!$item) {
			$result['errcode']  = 1;
			$result['errmsg' ] = "errmsg";
        } else {    
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> (int)$item->copies, 'link'=>'/common/items?custom=api_critical_dates');
	    $cache->save($result, $cache_key);
        }
	
	$this->getResponse()->setBody(Zend_Json::encode($result));        
    }
    
    
    
     public function bulkItemsAction() {
      
	$this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'bulkItems';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
	
	$itemsModel = Base::getModel('Common_Items');
       
	$select    = $itemsModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('ci' => 'common_items'), array('copies' => 'sum(ci.copies)'))
				->joinInner(array('co' => 'common_orders'), 'co.id = ci.common_order_id', null)
				->where("ci.copies >= 20")
                                ->where("co.status NOT IN ('SENT', 'CANCELLED', 'PENDING')")
				->where("ci.status is NULL or ci.status NOT IN  (?)", array('SENT', 'CANCELLED')); 
        
        $item = $itemsModel->fetchRow($select);
        
        if (!$item) {
			$result['errcode']  = 1;
			$result['errmsg' ] = "errmsg";
        } else {    
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> (int)$item->copies, 'link'=>'/common/items?custom=api_bulk_items');
	    $cache->save($result, $cache_key);
        }
	
	$this->getResponse()->setBody(Zend_Json::encode($result));
        
    }
    
    public function almostOverdueAction() {
	
	$this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'almostOverdue';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
	
	$itemsModel = Base::getModel('Common_Items');
       
	//the Items that are currently Active, on Hold or RTF for Users who are flagged as a Platinum customer
	//where it will return the sum of quantity of all active/hold/RTF orders less than 3 days to due date
        //but not overdue
        $select    = $itemsModel->select()
	                        ->setIntegrityCheck(false)
                                ->from(array('ci' => 'common_items'), array( 'count' => 'count(ci.id)'))
				->joinInner(array('co' => 'common_orders'), 'co.id = ci.common_order_id', null)
                                ->where("date_due between  curdate() AND CURDATE() + INTERVAL 2 DAY ")
                                ->where('ci.status NOT IN (?) OR ci.status IS NULL', 'CANCELLED')                                
                                ->where("co.status NOT IN ('SENT', 'CANCELLED', 'PENDING')");
                
        $item = $itemsModel->fetchRow($select);
        
        if (!$item) {
			$result['errcode']  = 1;
			$result['errmsg' ] = "errmsg";
        } else {    
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> (int)$item->count, 'link'=>'/common/items?custom=api_almostoverdue');
	    $cache->save($result, $cache_key);
        }
	
	$this->getResponse()->setBody(Zend_Json::encode($result));
        
    }    
    
    public function layFlatsAction() {
        
	$this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'layFlats';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
	
        $request    = $this->getRequest();
        $itemsModel = Base::getModel('Common_Items');
	
	$select    = $itemsModel->select()
	                        ->setIntegrityCheck(false)
                                ->from(array('ci' => 'common_items'), array( 'count' => 'sum(ci.copies)'))
				->joinInner(array('co' => 'common_orders'), 'co.id = ci.common_order_id', null)
                                ->join(array('cio' => 'common_items_options'), 'ci.id = cio.common_item_id', null)
                                ->joinLeft(array('cao' => 'catalogue_attributes_options'), 'cao.id = cio.catalogue_attribute_option_id', null)
                                ->where("cao.name LIKE 'Lay-flat%'")
                                ->where("ci.status is NULL or ci.status NOT IN  (?)", array('SENT', 'CANCELLED'))                                
                                ->where("co.status NOT IN ('SENT', 'CANCELLED', 'PENDING')");
       
        $item = $itemsModel->fetchRow($select);
        
        if (!$item) {
			$result['errcode']  = 1;
			$result['errmsg' ] = "errmsg";
        } else {    
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> (int)$item->count , 'link'=>'/common/items?custom=api_layflats');
	    $cache->save($result, $cache_key);
        }
	
	$this->getResponse()->setBody(Zend_Json::encode($result));
       
    }
    
    public function productLinesAction() {
	$this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'productLines';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
        $request    = $this->getRequest();
        $itemsModel = Base::getModel('Common_Items');
	
	$select    = $itemsModel->select()
	                        ->setIntegrityCheck(false)
                                ->from(array('ci' => 'common_items'), array( 'count' => 'sum(ci.copies)'))
				->joinInner(array('co' => 'common_orders'), 'co.id = ci.common_order_id', null)
                                ->joinLeft(array('cp' => 'client_products'), 'ci.client_product_id = cp.id', null)
				->joinLeft(array('cpl' => 'catalogue_products_lines'), 'cp.product_line_id = cpl.id', array( 'product_line' => 'cpl.name'))
                                ->where("ci.status is NULL or ci.status NOT IN  (?)", array('SENT', 'CANCELLED'))                                
                                ->where("co.status NOT IN ('SENT', 'CANCELLED', 'PENDING')")
                                ->group('cp.product_line_id')
                                ->order('count DESC');
        $items = $itemsModel->fetchAll($select);
        
        if (!$items) {
			$result['errcode']  = 1;
			$result['errmsg' ] = "errmsg";
        } else {
            $result['errcode']  = 0;
	    $result['errmsg' ] = 'OK';
            $result['data']='';
            $product_line_data = array();
            foreach($items as $item):
                $product_line_data[] = array($item->product_line, (int)$item->count);
            endforeach;
            $result['data'] = $product_line_data;
            $columns = array('string' => 'Product Line', 'number' => 'Count');
            $result['columns'] = $columns;
	    $cache->save($result, $cache_key);
        }
	
	$this->getResponse()->setBody(Zend_Json::encode($result));
        
    
    }
    
    public function totalProductImagesAction() {
        
	$this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'totalProductImages';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
	
        $request    = $this->getRequest();
        $itemsModel = Base::getModel('Common_Items');
	
	$select    = $itemsModel->select()
	                        ->setIntegrityCheck(false)
                                ->from(array('ci' => 'common_items'), array( 'count' => 'sum(ci.copies * cp.image_count)'))
				->joinInner(array('co' => 'common_orders'), 'co.id = ci.common_order_id', null)
                                ->join(array('cp' => 'client_products'), 'ci.client_product_id = cp.id', null)
                                ->where('product_line_id NOT IN (1,2,3,4,5,6,7,8,9,10) ')
				->where("ci.status = 'SENT' OR ci.status IS NULL")
                                ->where("co.status = 'SENT'");
                                
        $item = $itemsModel->fetchRow($select);
        
        if (!$item) {
			$result['errcode']  = 1;
			$result['errmsg' ] = "errmsg";
        } else {    
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> $item->count, 'link'=>'/client/products-manage?custom=api_total_product_images');
	    $cache->save($result, $cache_key);
        }
	
	$this->getResponse()->setBody(Zend_Json::encode($result));
       
    }
    

    
    public function itemDescAction() {
        
        $request    = $this->getRequest();
        $id         = $request->getParam('id');
        $desc_type  = $request->getParam('desc_type');
        $bpm_type   = $request->getParam('bpm_type');
        $show_notes = $request->getParam('show_notes');
        $show_status = $request->getParam('show_status');
        $show_bpm_issue = $request->getParam('show_bpm_issue');
        $itemsModel = Base::getModel('Common_Items');
	$item       = $itemsModel->fetchById($id);
        
        $this->view->item       = $item;
        $this->view->desc_type  = $desc_type;
        $this->view->bpm_type   = $bpm_type;
        $this->view->show_notes = $show_notes;
        $this->view->show_status = $show_status;
        $this->view->show_bpm_issue = $show_bpm_issue;  
    }
    
    public function itemDescGroupAction() {
        
        $request    = $this->getRequest();
        $ids         = $request->getParam('ids');
        $arrIds = array_map('intval', explode(',', $ids)); 
       // Zend_Debug::dump($arrIds);
        $desc_type  = $request->getParam('desc_type');
        $bpm_type   = $request->getParam('bpm_type');
        $show_notes = $request->getParam('show_notes');
        $show_status = $request->getParam('show_status');
        $show_bpm_issue = $request->getParam('show_bpm_issue');
        $itemsModel = Base::getModel('Common_Items');
	$select   = $itemsModel->select()->where(" id IN (?)", $arrIds);
        //Zend_Debug::dump((string)$select);
        $items       = $itemsModel->fetchAll($select);
        // Zend_Debug::dump($items);
        $this->view->items       = $items;
        $this->view->desc_type  = $desc_type;
        $this->view->bpm_type   = $bpm_type;
        $this->view->show_notes = $show_notes;
        $this->view->show_status = $show_status;
        $this->view->show_bpm_issue = $show_bpm_issue;  
    }
    public function itemsInDeptAction() {
        
	$request    = $this->getRequest();
        $parts_id         = $request->getParam('parts_id');
	
	$this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .$parts_id .'_itemsInDept';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
	
        
        
        $db             = Zend_Registry::get('db');
        $db->query("CREATE TEMPORARY TABLE tmp_bpm_action_items select bpp.* FROM bpm_production_paths bpp JOIN common_items ci ON ci.id = bpp.items_id JOIN  common_orders co ON co.id = ci.common_order_id  WHERE co.status NOT IN ('CANCELLED', 'SENT' , 'PENDING') AND  (ci.status is NULL or ci.status NOT IN  ('SENT','CANCELLED'))  AND parts_id = ".$parts_id." group by bpp.items_id");
        $db->query("CREATE TEMPORARY TABLE tmp_bpm_action_child select bpp.id, bpp.items_id , (select quantity FROM bpm_item_parts bip where bip.parts_id = bpp.parts_id and bip.items_id = bpp.items_id ) as quantity FROM bpm_production_paths bpp JOIN tmp_bpm_action_items tb ON tb.id = bpp.parents_id");
        $db->query("CREATE TEMPORARY TABLE tmp_min_qty_child SELECT id, items_id, MIN(quantity) as quantity FROM tmp_bpm_action_child WHERE quantity IS NOT NULL GROUP BY items_id");
        $db->query("CREATE TEMPORARY TABLE tmp_null_child SELECT items_id FROM tmp_bpm_action_child WHERE quantity IS NULL");
        //$db->query("SELECT items_id, quantity FROM tmp_bpm_action_child WHERE items_id NOT IN (SELECT items_id FROM tmp_null_child) GROUP BY items_id");
        $item = $db->fetchRow("SELECT SUM(t2.quantity) as count from tmp_bpm_action_child t1 JOIN  tmp_min_qty_child t2 ON  t1.id = t2.id where t1.items_id NOT IN (SELECT items_id FROM tmp_null_child)");
       
        if (!$item) {
			$result['errcode']  = 1;
			$result['errmsg' ] = "errmsg";
        } else {
            $link = '/common/items/index/custom/api_item_in_parts/parts_id/'.$parts_id;
            if((int)$item['count'] == 0)
                $link = '';
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> (int)$item['count'], 'link'=> $link);
            $cache->save($result, $cache_key);
        }
	
	$this->getResponse()->setBody(Zend_Json::encode($result));
    }

    public function almostOverdueOrdersAction() {
	
	$this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'almostOverdue';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
	
	$ordersModel = Base::getModel('Common_Orders');

        $select    = $ordersModel->select()
	                        ->setIntegrityCheck(false)
                                ->from(array('co' => 'common_orders'), array( 'count' => 'count(co.id)'))
                                ->where("(round( abs(NOW()- co.date_due) / 86400) < 3)")
                                ->where("(round( abs(NOW()- co.date_due) / 86400) >= 0)")                                                        
                                ->where("co.status NOT IN ('SENT', 'CANCELLED', 'PENDING')");
                
        $order = $ordersModel->fetchRow($select);
        
        if (!$order) {
			$result['errcode']  = 1;
			$result['errmsg' ] = "errmsg";
        } else {    
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> (int)$order->count, 'link'=>'/common/items?custom=api_almostoverdueorders');
	    $cache->save($result, $cache_key);
        }
	
	$this->getResponse()->setBody(Zend_Json::encode($result));
        
    }
    
    public function salesCopiesAction() {
        
        $request    = $this->getRequest();
        
        //$week_start_date    = Base_Utils_Date::weekStartDate(); //'2014-05-25';
        $num_days           = $request->getParam('num_days');
	
	$this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .$num_days .'salesCopies';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
	
        if((int)$num_days == 0){
            $num_days = 20;
        }
        
	//$days               = 1 * $num_weeks;
	//$date_start_epoch   = date('Y-m-d', strtotime($week_start_date . " -$days day"));
        $startdate = date('Y-m-d', strtotime('-'.$num_days.' days')). ' 00:00:00';
        $enddate = date('Y-m-d'). ' 23:59:59';
       
        $startTime = strtotime($startdate);
        $endTime = strtotime($enddate);

        
          
        // common items
        $ciModel  = Base::getModel('Common_Items');
        $exclude_statuses = array('CANCELLED');
        
        
        $select = $ciModel->select()
                        ->setIntegrityCheck(false)
                        ->from(array('ci' => 'common_items'), new Zend_Db_Expr("DATE_FORMAT(co.date_first_active, '%Y-%m-%d') as day, DATE_FORMAT(co.date_first_active, '%d %b %Y') as display_date, COUNT(ci.id) as count, SUM(ci.copies) as units"))
                        ->join(array('co' => 'common_orders'), 'ci.common_order_id = co.id', null)
                        ->where(new Zend_Db_Expr("co.date_first_active between '$startdate' AND NOW()"))
                        ->where('co.status NOT IN (?)', $exclude_statuses)
                        ->where('ci.status NOT IN (?) OR ci.status IS NULL', $exclude_statuses)
                        ->group("DATE_FORMAT(co.date_first_active, '%Y-%m-%d')")
                        ->order('co.date_first_active');
        //Zend_Debug::dump((string)$select);        
                       
        // filter sub ids
        if (isset($sub_id) && $sub_id > 0) {
                $select->where('co.sub_id = ?', $sub_id);
        }		
        // filter product lines based in the order item
        if (isset($product_line_id) && $product_line_id > 0) {
            $select->join(array('cp' => 'client_products'), 'ci.client_product_id = cp.id', null)
                        ->where('cp.product_line_id = ?', $product_line_id);
        }		
        if (isset($internal) && $internal != '') {
            $select->join(array('cu' => 'common_users'), 'co.common_user_id = cu.id', null)
                       ->where('cu.internal = ?', $internal);
        }
        
        $common_items_data =$ciModel->fetchAll($select);
        if (!$common_items_data) {
			$result['errcode']  = 1;
			$result['errmsg' ] = "errmsg";
        }else{
            $item_count_units_grp = array();
           
            foreach($common_items_data->toArray() as $item){
                $item_count_units_grp[$item['day']] = array(
                                                            $item['display_date'],
                                                            //(int)$item['count'],
                                                            (int)$item['units']);
                
            }
            $item_count_by_week[] = array('Day', 'Copies');
            
            for ($i = $startTime; $i <= $endTime; $i = $i + 86400) {
                $thisDate = date('Y-m-d', $i);
                if(isset($item_count_units_grp[$thisDate])){
                    $item_count_by_week[] = $item_count_units_grp[$thisDate]  ;               
                }else{
                    $item_count_by_week[] = array($thisDate,  0);
                }
                
            }     
            //Zend_Debug::dump($item_count_by_week);
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=>$item_count_by_week);
	    $cache->save($result, $cache_key);
        }
	
	$this->getResponse()->setBody(Zend_Json::encode($result));        
    }
    
    public function averageItemCopiesAction() {     
        $this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'averageItemCopies';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
	
	$startdatetime = date('Y-m-d H:i:s', strtotime('-7 days')) ;
       
        $ciModel = Base::getModel('Common_Items');
        $select    = $ciModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('ci' => 'common_items'), array( 'count' => 'sum(ci.copies)'))
                                ->joinInner(array('co' => 'common_orders'), 'co.id = ci.common_order_id', null)
                                ->where(new Zend_Db_Expr("co.date_first_active between '$startdatetime' AND NOW()"))                                           
                                ->where("ci.status is NULL or ci.status NOT IN  (?)", array('CANCELLED'))
                                ->where("co.status NOT IN ('CANCELLED', 'PENDING')");
        $item = $ciModel->fetchRow($select);
       
        if (!$item) {
                $result['errcode']  = 1;
                $result['errmsg' ] = "errmsg";
        } else {               
            $average = round((int)$item->count / 7);
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> $average, 'link'=>'');
	    $cache->save($result, $cache_key);
        }
	
	$this->getResponse()->setBody(Zend_Json::encode($result));       
    }
    
    public function proApplicationsPendingAction() {      
	
	$this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'proApplicationsPending';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
	
	// init variables
	$usersModel = Base::getModel('Common_Users');

	$select     = $usersModel->select()
	                ->setIntegrityCheck(false)
	   	        ->from(array('cu' => 'common_users'), array( 'count' => 'count(cu.id)'))
			->where('cu.pro_application = ?', 'Applied');
	$users = $usersModel->fetchRow($select);	
        if (!$users) {
                $result['errcode']  = 1;
                $result['errmsg' ] = "errmsg";
        } else {                
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> $users->count, 'link'=>'/reports/common-users/pro-application');
	    $cache->save($result, $cache_key);
        }
	
	$this->getResponse()->setBody(Zend_Json::encode($result));       
    }
    
    //issues-advised paid-and-pending paid-and-cancelled overpaid-orders  pending-orders
    public function issuesAdvisedAction() {      
	
	$this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'issuesAdvised';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
	
	// init variables
	$itemsModel = Base::getModel('Common_Items');
		
	$select     = $itemsModel->select()
		                    ->setIntegrityCheck(false)
				    ->from(array('ci' => 'common_items'), array( 'count' => 'count(ci.id)'))
				    ->join(array('co' => 'common_orders'), 'co.id = ci.common_order_id', null)
				    ->where('ci.return_adviced = ?', 1);
	$item = $itemsModel->fetchRow($select);
	if (!$item) {
                $result['errcode']  = 1;
                $result['errmsg' ] = "errmsg";
        } else {                
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> $item->count, 'link'=>'/common/items?keywords_items=return_adviced:1');
	    $cache->save($result, $cache_key);
        }
	
	$this->getResponse()->setBody(Zend_Json::encode($result));
    }
    
    public function paidAndPendingAction() {      
	
	$this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'paidAndPending';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
	
	// init variables
	$coModel = Base::getModel('Common_Orders');
		
	$select     = $coModel->select()
				    ->setIntegrityCheck(false)
				    ->from(array('co' => 'common_orders'), array( 'count' => 'count(co.id)'))
				    ->where("status = 'PENDING'")
				    ->where('payment_due <= 0');
	$order = $coModel->fetchRow($select);
	if (!$order) {
                $result['errcode']  = 1;
                $result['errmsg' ] = "errmsg";
        } else {                
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> $order->count, 'link'=>'/admin/reports/orders-report/type/paid_and_pending');
	    $cache->save($result, $cache_key);
        }
	
	$this->getResponse()->setBody(Zend_Json::encode($result));
    }
    
    public function paidAndCancelledAction() {      
	$this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'paidAndCancelled';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
	
	// init variables
	$coModel = Base::getModel('Common_Orders');
		
	$select     = $coModel->select()
				    ->setIntegrityCheck(false)
				    ->from(array('co' => 'common_orders'), array( 'count' => 'count(co.id)'))
				    ->where("status = 'CANCELLED'")
				    ->where('total > 0')
				    ->where('payment_due <= 0');
	$order = $coModel->fetchRow($select);
	if (!$order) {
                $result['errcode']  = 1;
                $result['errmsg' ] = "errmsg";
        } else {                
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> $order->count, 'link'=>'/admin/reports/orders-report/type/paid_and_cancelled');
	    $cache->save($result, $cache_key);
        }
	
	$this->getResponse()->setBody(Zend_Json::encode($result));
    }
    
    public function overpaidOrdersAction() {      
	$this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'overpaidOrders';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
	// init variables
	$coModel = Base::getModel('Common_Orders');
		
	$select     = $coModel->select()
				    ->setIntegrityCheck(false)
				    ->from(array('co' => 'common_orders'), array( 'count' => 'count(co.id)'))
				    ->where('payment_due < 0');
	$order = $coModel->fetchRow($select);
	if (!$order) {
                $result['errcode']  = 1;
                $result['errmsg' ] = "errmsg";
        } else {                
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> $order->count, 'link'=>'/admin/reports/orders-report/type/overpaid/page/3');
	    $cache->save($result, $cache_key);
        }
	
	$this->getResponse()->setBody(Zend_Json::encode($result));
    }
    
    public function pendingOrdersAction() {      
	$this->_helper->viewRenderer->setNoRender(true);
	$this->getResponse()->setHeader('Content-Type','text/html');
	$cache     = Zend_Registry::get('cache');
	$date_start = date('Y-m-d') . ' 00:00:00';
	$date_end  = date('Y-m-d') . ' 23:59:59';
	
	$key       = __METHOD__ . '_' . $date_start . '_' . $date_end . '_' .'pendingOrders';
	$cache_key = Base_Utils_String::slug($key);
	
	if ($cache_date_end < date('Y-m-d', time()) && $result = $cache->load($cache_key)) {	    
	    $this->getResponse()->setBody(Zend_Json::encode($result));
	    return;
	}
	// init variables
	$coModel = Base::getModel('Common_Orders');
		
	$select     = $coModel->select()
				    ->setIntegrityCheck(false)
				    ->from(array('co' => 'common_orders'), array( 'count' => 'count(co.id)'))
				    ->where("co.status = 'PENDING'");
	$order = $coModel->fetchRow($select);
	if (!$order) {
                $result['errcode']  = 1;
                $result['errmsg' ] = "errmsg";
        } else {                
            $result  = array('errcode' => 0, 'errmsg' => 'OK', 'data'=> $order->count, 'link'=>'/common/orders?status=PENDING');
	    $cache->save($result, $cache_key);
        }
	
	$this->getResponse()->setBody(Zend_Json::encode($result));
    }

}