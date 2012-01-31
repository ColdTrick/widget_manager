<?php

	class MultiDashboard extends ElggObject {
		
		const SUBTYPE = "multi_dashboard";
		
		private $allowed_dashboard_types = array(
			"widgets",
			"iframe"
		);
		
		protected function initializeAttributes() {
			if(!parent::initializeAttributes()){
				return false;
			}
			
			$this->attributes["subtype"] = self::SUBTYPE;
		}
		
		function save(){
			if(!$this->guid){
				$this->attributes["owner_guid"] = elgg_get_logged_in_user_guid();
				$this->attributes["container_guid"] = elgg_get_logged_in_user_guid();
				$this->attributes["access_id"] = ACCESS_PRIVATE;
			}
			
			return parent::save();
		}
		
		function getURL(){
			$result = false;
				
			if($this->guid){
				$site = elgg_get_site_entity($this->site_guid);
		
				$result = $site->url . "dashboard/" . $this->getGUID();
			}
				
			return $result;
		}
		
		function setDashboardType($type = "widgets"){
			$result = false;
			
			if(in_array($type, $this->allowed_dashboard_types)){
				$result = $this->set("dashboard_type", $type);
			}
			
			return $result;
		}
		
		function getDashboardType(){
			return $this->dashboard_type;
		}
		
		function setNumColumns($num = 3){
			$result = false;
			$num = sanitise_int($num);
			
			if(!empty($num) && $num <= 6){
				$result = $this->set("num_columns", $num);
			}
			
			return $result;
		}
		
		function getNumColumns(){
			return $this->num_columns;
		}
		
		function setIframeUrl($url){
			$result = false;
			
			if(!empty($url)){
				$result = $this->set("iframe_url", $url);
			}
			
			return $result;
		}
		
		function getIframeUrl(){
			return $this->iframe_url;
		}
		
		function setIframeHeight($height){
			$result = false;
			$height = sanitise_int($height);
			
			if(!empty($height)){
				$result = $this->set("iframe_height", $height);
			}
			
			return $result;
		}
		
		function getIframeHeight(){
			return $this->iframe_height;
		}
		
		function getContext(){
			$result = false;
			
			if($this->guid){
				$result = "dashboard_" . $this->guid;
			}
			
			return $result;
		}
	}