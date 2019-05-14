<?php

class ModelToolMoyskladOC3Synch11 extends Model {

    //При инстализации модуля создаем таблицы
    public function createTables(){
        $sql = array();
 
        $sql[] = "
                        
            CREATE TABLE IF NOT EXISTS `".DB_PREFIX."uuid` (
	     `id` int(255) NOT NULL auto_increment,
             `product_id` int(255) NOT NULL,
             `uuid_id` varchar(255) NOT NULL,
             `url` varchar(255) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

        ";

       
        foreach( $sql as $q ){
             $this->db->query( $q );
        }
        return true;
    }
 
    //функция для поиска в таблице uuid
    public function modelSearchUUID($uuid){
        $query = $this->db->query("SELECT product_id FROM `".DB_PREFIX."uuid` WHERE uuid_id = '$uuid' ");
        return $query->row;
    }

    //функция для поиска в таблице uuid ссылку на товар
    public function modelSearchUUIDUrl($product_id){
        $query = $this->db->query("SELECT url FROM `".DB_PREFIX."uuid` WHERE product_id = '$product_id' ");
        return $query->row;
    }

    
    //после удачного добавления товара в базу, заносим id  товара и uuid товара с моего 
    //склада в таблицу uuid для проверок существования товара
    public function modelInsertUUID($data){
      $this->db->query('INSERT INTO `'.DB_PREFIX.'uuid` SET product_id = ' . (int)$data["product_id"] . ', `uuid_id` = "' . $data["uuid"] . '", `url` = "' . $data["url"] . '"');  
      return true;
    }
 

 	#TODO возможно тут лучше заюзать готовую функцию editProduct чем юзать свой метож
    
    //обновляем информацию о товаре
    public function updateProduct($product_id,$data){
        $this->db->query("UPDATE " . DB_PREFIX . "product SET  quantity = '" . (int)$data['quantity'] . "',  price = '" . (float)$data['price'] . "',stock_status_id = '" . (int)$data['stock_status_id'] . "', sku = '" . $data['sku'] . "', upc = '" . $data['upc'] . "' , location = '" . $data['location'] . "', weight = '" . (float)$data['weight'] . "',  date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");

            if (isset($data['image'])) {
    $this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
            }

            $this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}

            foreach ($data['product_description'] as $language_id => $value) {
    $this->db->query("UPDATE " . DB_PREFIX . "product_description SET name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'  WHERE product_id = '" . (int)$id . "'");
            }
            
             return true;
     }
     
    //обновляем количество товара
    public function updateProductQuantity($id,$quantity,$stock_status_id){

        $this->db->query("UPDATE " . DB_PREFIX . "product SET  quantity = '" . (int)$quantity. "', stock_status_id = '" . (int)$stock_status_id. "'  WHERE product_id = '" . (int)$id . "'");

        return true;
    }
    
    //обновляем количество товара
    public function updateProductCountry($id,$country){

        $this->db->query("UPDATE " . DB_PREFIX . "product SET  location = '" . $country. "'  WHERE product_id = '" . (int)$id . "'");

        return true;
    }
    
    //удаляем с таблицы инфу о товаре, которого удалили с моегосклада
    public function modelDeleteUUID($uuid){
      $this->db->query("DELETE FROM `".DB_PREFIX."uuid` WHERE uuid_id = '$uuid' ");  
      return true;
    }
    
    //получаем данные с таблицы uuid (все)
    public function getAllUUID(){
        $query = $this->db->query("SELECT product_id,uuid_id FROM `".DB_PREFIX."uuid` ");
        return $query->rows;
    }
    
    //по ссылке с МС делаем поиск в таблице uuid и если находим то получаем id  товара
    public function findUrlGetProductID($url){
      $query = $this->db->query("SELECT product_id FROM `".DB_PREFIX."uuid` WHERE url = '$url' ");
       return $query->row;
    }
    
    //добавляем  модификации с МС в опенкарт
    public function addModMC($datas){
      
      //удаляем инфу о товаре с 3 таблиц
      $this->delModMC($datas);
      
      //заполняем таблицы с атрибутами
      foreach($datas as $data){
	 
	//oc_product_modification_groups
	  $this->db->query('INSERT INTO `'.DB_PREFIX.'product_modification_groups` SET product_id = ' . (int)$data["product_id"] . ',
	  `language_id` = 1, `name` = "' . $data["name"] . '", `code` = "' . $data["code"] . '", `price` = "' . $data["price"] . '", `mod_id` = "' . $data["name"] . '",
	  `externalCode` = "' . $data["externalCode"] . '"');  
	  
	  $modification_group_id = $this->db->getLastId();
	 
	
 
	  foreach($data["characteristics"] as $dat){
	  
	    $query = $this->db->query("SELECT attribute_id FROM `".DB_PREFIX."attribute_description` WHERE name = '" . $dat["name"] . "' ");
	    
	    //делаем проверку, если такое имя атрибута есть то не добавляем его в базу
	    if(!empty($query->row['attribute_id'])){
	      $attribute_id = $query->row['attribute_id'];
	    }else{
	      //oc_attribute
	     $this->db->query('INSERT INTO `'.DB_PREFIX.'attribute` SET `attribute_group_id` = 8');  
	     
	     $attribute_id = $this->db->getLastId();
	      
	     //oc_attribute_description
	     $this->db->query('INSERT INTO `'.DB_PREFIX.'attribute_description` SET `attribute_id` = "' . $attribute_id . '", `language_id` = 1, `name` = "' . $dat["name"] . '"');  
	      
	      
 	    }
 
	    
	    //oc_product_attribute
	    $this->db->query('INSERT INTO `'.DB_PREFIX.'product_attribute` SET `product_id` = ' . (int)$data["product_id"] . ', `attribute_id` = ' . (int)$attribute_id . ',
	    `language_id` = 1,`text` = "' . $dat["value"] . '", `modification_group_id` = "' . $modification_group_id . '"'); 
	  
	  }
	}
	
	
	//заполняем таблицы с фильтрами
	foreach($datas as $data){
	
	  foreach($data["characteristics"] as $dat){
	  
	    //oc_filter_group_description
	    $query = $this->db->query("SELECT filter_group_id FROM `".DB_PREFIX."filter_group_description` WHERE name = '" . $dat["name"] . "' ");
	    
	    if(!empty($query->row['filter_group_id'])){
	    
	      $filter_group_id = $query->row['filter_group_id'];
	      
	    }else{
	    
	      //oc_filter_group
	      $this->db->query('INSERT INTO `'.DB_PREFIX.'filter_group` SET `sort_order` = 0'); 
	      
	      $filter_group_id = $this->db->getLastId();
	      
	      //oc_ filter_group_description
	      $this->db->query('INSERT INTO `'.DB_PREFIX.' filter_group_description` SET `filter_group_id` = ' .$filter_group_id. ', `language_id` = 1, `name` = "' . $dat["name"] . '"');  
	      
	      
	    }
	    
	    //oc_filter_description
	    $query_group_description = $this->db->query("SELECT filter_id FROM `".DB_PREFIX."filter_description` WHERE name = '" . $dat["value"] . "'");
	    
	    if(!empty($query_group_description->row['filter_id'])){
		
		//oc_filter
		$query_filter = $this->db->query("SELECT filter_id FROM `".DB_PREFIX."filter` WHERE filter_id = '" . $query_group_description->row['filter_id'] . "'  AND filter_group_id = '" . $filter_group_id . "'");
		
		if(!empty($query_filter->row['filter_id'])){
 
		  //oc_product_filter
		  $this->db->query('INSERT INTO `'.DB_PREFIX.'product_filter` SET `product_id` = ' .(int)$data["product_id"]. ',`filter_id` = "' .$query_filter->row['filter_id']. '"'); 
		
		}else{
		
		  //oc_filter
		  $this->db->query('INSERT INTO `'.DB_PREFIX.'filter` SET `filter_id` = ' .(int)$query_group_description->row['filter_id']. ',`filter_group_id` = "' .$filter_group_id. '"'); 
		  
		  //oc_product_filter
		  $this->db->query('INSERT INTO `'.DB_PREFIX.'product_filter` SET `product_id` = ' .(int)$data["product_id"]. ',`filter_id` = "' .(int)$query_group_description->row['filter_id']. '"'); 
		  
		}
		
	    }else{
	      
	      //oc_filter
	      $this->db->query('INSERT INTO `'.DB_PREFIX.'filter` SET `filter_group_id` = "' .$filter_group_id. '"');
	      $filter_id = $this->db->getLastId();
	      
	      //oc_filter_description
	      $this->db->query('INSERT INTO `'.DB_PREFIX.'filter_description` SET `filter_id` = "' .$filter_id. '",
	      `language_id` = 1,`filter_group_id` = "' .$filter_group_id. '",`name` = "' .$dat["value"]. '"');
	      
	      
	      //oc_product_filter
	      $this->db->query('INSERT INTO `'.DB_PREFIX.'product_filter` SET `product_id` = ' .(int)$data["product_id"]. ',`filter_id` = "' .(int)$filter_id. '"'); 
 
	    
	    }
 
	    
	  }
	
	
	}
 
      return true;
    }
    
    
    //удаляем строки по product_id 3 с таблиц oc_product_modification_groups, oc_product_attribute, oc_product_filter
    public function delModMC($datas){
    
      //удаляем по строчно по product_id, что бы заново внести свежие данные
      foreach($datas as $data){
	 $this->db->query("DELETE FROM `".DB_PREFIX."product_modification_groups` WHERE product_id = '" .(int)$data["product_id"]. "' ");
	 $this->db->query("DELETE FROM `".DB_PREFIX."product_attribute` WHERE product_id = '" .(int)$data["product_id"]. "' ");
	 $this->db->query("DELETE FROM `".DB_PREFIX."product_filter` WHERE product_id = '" .(int)$data["product_id"]. "' ");
      }
      
      return true;
      
    }
 
}