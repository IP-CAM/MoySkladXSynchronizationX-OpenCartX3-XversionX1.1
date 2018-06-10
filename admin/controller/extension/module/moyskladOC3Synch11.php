<?php
/*
$_SESSION["moysklad_uuid"] - хранит информацию об uuid коде товара
$_SESSION["moysklad"] - хранит всю инфу нужную для создания/обновления товара
$_SESSION["moysklad_mod"] - хранит инфу модификаций

*/

session_start();
class ControllerExtensionModuleMoyskladOC3Synch11 extends Controller {
	private $error = array();

	//храним url  МойСклад API
    public $urlAPI = "https://online.moysklad.ru/api/remap/1.1/";
    
    //сохраняем временные данные
    public $cahce_quantity = [];
    public $cahce_country = [];
 

	public function index() {
		$this->load->language('extension/module/moyskladOC3Synch11');
 
		$this->document->addStyle('view/stylesheet/moyskladOC2_3Synch.css');
		$this->document->addScript('view/javascript/jquery/tabs.js');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			 $this->model_setting_setting->editSetting('module_moyskladOC3Synch11', $this->request->post);
 
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module/moyskladOC3Synch11', 'user_token=' . $this->session->data['user_token'], true));
		}

		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['text_tab_setting'] = $this->language->get('text_tab_setting');
		$data['entry_save'] = $this->language->get('entry_save');
		$data['text_tab_import'] = $this->language->get('text_tab_import');
		$data['entry_import'] = $this->language->get('entry_import');
		$data['text_tab_author'] = $this->language->get('text_tab_author');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['setting_module'] = $this->language->get('setting_module');
		$data['import_text'] = $this->language->get('import_text');
		$data['import_button'] = $this->language->get('import_button');
		$data['create_task_button'] = $this->language->get('create_task_button');
		$data['import_test_text'] = $this->language->get('import_test_text');
		$data['import_task_text'] = $this->language->get('import_task_text');
		$data['task'] = $this->language->get('task');
 

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['module_moyskladOC3Synch11_username'])) {
		    $data['error_module_moyskladOC3Synch11_username'] = $this->error['module_moyskladOC3Synch11_username'];
		}
		else {
		    $data['error_module_moyskladOC3Synch11_username'] = '';
		}

		if (isset($this->error['module_moyskladOC3Synch11_password'])) {
		    $data['error_module_moyskladOC3Synch11_password'] = $this->error['module_moyskladOC3Synch11_password'];
		}
		else {
		    $data['error_module_moyskladOC3Synch11_password'] = '';
		}
		
		if (isset($this->error['module_moyskladOC3Synch11_cron_task'])) {
		    $data['error_module_moyskladOC3Synch11_cron_task'] = $this->error['module_moyskladOC3Synch11_cron_task'];
		}
		else {
		    $data['error_module_moyskladOC3Synch11_cron_task'] = '';
		}
 
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/moyskladOC3Synch11', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/moyskladOC3Synch11', 'user_token=' . $this->session->data['user_token'], true);
		
		//используем ссылку в форме для импорта товара
		$data['action_import'] = $this->url->link('extension/module/moyskladOC3Synch11/getMethodImport', 'user_token=' . $this->session->data['user_token'], true);
 
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_moyskladOC3Synch11_status'])) {
			$data['module_moyskladOC3Synch11_status'] = $this->request->post['module_moyskladOC3Synch11_status'];
		} else {
			$data['module_moyskladOC3Synch11_status'] = $this->config->get('module_moyskladOC3Synch11_status');
		}
		
		if (isset($this->request->post['module_moyskladOC3Synch11_username'])) {
		    $data['module_moyskladOC3Synch11_username'] = $this->request->post['module_moyskladOC3Synch11_username'];
		}
		else {
		    $data['module_moyskladOC3Synch11_username'] = $this->config->get('module_moyskladOC3Synch11_username');
		}

		if (isset($this->request->post['module_moyskladOC3Synch11_password'])) {
		    $data['module_moyskladOC3Synch11_password'] = $this->request->post['module_moyskladOC3Synch11_password'];
		}
		else {
		    $data['module_moyskladOC3Synch11_password'] = $this->config->get('module_moyskladOC3Synch11_password');
		}
		
		if (isset($this->request->post['module_moyskladOC3Synch11_cron_task'])) {
		    $data['module_moyskladOC3Synch11_cron_task'] = $this->request->post['module_moyskladOC3Synch11_cron_task'];
		}
		else {
		    $data['module_moyskladOC3Synch11_cron_task'] = $this->config->get('module_moyskladOC3Synch11_cron_task');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		 //получаем доступ к модели модуля и создаем таблицы в базе
		$this->load->model('tool/moyskladOC3Synch11');
		$this->model_tool_moyskladOC3Synch11->createTables();

		$this->response->setOutput($this->load->view('extension/module/moyskladOC3Synch11', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/moyskladOC3Synch11')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	//тут хранитятся доступы до МоегоСклада
	protected function dataClient(){
	    //получаем данные в переменные
	    $mas = [
		"login" => (!empty($this->config->get('module_moyskladOC3Synch11_username'))) ? $this->config->get('module_moyskladOC3Synch11_username') : false,
		"pass" => (!empty($this->config->get('module_moyskladOC3Synch11_password'))) ? $this->config->get('module_moyskladOC3Synch11_password') : false
	    ];

	    return $mas;
	}
 
	
	//вызываем метод по крону
	public function getMethodImport(){
	    
	  //if(!empty($_POST['start'])){
 
		
		//по клику запускаем API МойСклад для получения всего товара
		$this->getAllProduct(0);
 
	    //}

	    return true;
	}
 
	//получаем весь товар, что есть (рекурсия)
	public function getAllProduct($position){
	    //$urlProduct = $this->urlAPI."entity/product?offset=$position&limit=100";
	    $urlProduct = $this->urlAPI."entity/product?offset=$position&limit=10";
	    $products = $this->restAPIMoySklad($urlProduct,0,"GET");
	    
	    
	    
	    //если дошли до конца списка то выходим из рекурсии 
	    if(!empty($products["rows"])){
		
		$i = 0;
 
		foreach($products["rows"] as $product){
		    
		    //делаем провекру, что бы товар был с именем
		    if(!empty($product["name"])){
			
			//храним массив для создание/обновления товара
			$_SESSION["moysklad"][$position+$i] = [
			  "uuid" => $product["id"],
			  "product" => $product,
			];
			
			//храним массив для поиска удаленного uuid на МС, что бы удалить в базе опенкарт
			$_SESSION["moysklad_uuid"][$position+$i] = $product["id"];
		    }
		    ++$i;
		}
 		//вызов рекурсии  
		//$this->getAllProduct($position+$i);
	    
	    }
	    
	    //перед загрузкой товара делаем проверку не удалили товар ли с МС который лежит в базе опенкарт
	    $this->deleteProductFromBaseMC();
 
	    //вызываем метод для создания массива (обновление/создание товара)
	    $this->searchUUID();
	    
	    //загружаем остатки
	    $this->getQuantity();
	    
	    //загружаем страну для товара
	    $this->getCountry();
	    
	    //подтягиваем модификации товара с МС
	    $this->getModification();
	    
	    //$this->response->redirect($this->url->link('extension/module/moyskladOC3Synch11', 'user_token=' . $this->session->data['user_token'], true));
	    
	    return true; 
	}
	
	//делаем поиск в таблице uuid  на id  товара.
	//Если нету то добавляем товар если есть id  товара то обновляем.
	public function searchUUID(){
	 
	    //получаем доступ к модели модуля
	    $this->load->model('tool/moyskladOC3Synch11');
	    
	    $data = [];
	    
	    foreach($_SESSION["moysklad"] as $mas){
		
		//делаем поиск по uuid
		$findUUID = $this->model_tool_moyskladOC3Synch11->modelSearchUUID($mas['uuid']);
 
		//делаем проверку, есть ли страна
		if(!empty($mas["product"]["country"]["meta"]["href"])){
		
		 //сохраняем временные данные о стране
		  $this->cahce_country[] = [
		      'uuid'  => $mas["uuid"],
		      'href'  => $mas["product"]["country"]["meta"]["href"],
		  ];
		  
		}
    
		$image = "";
    
		//проверяем существует ли цена продажи
		if(!empty($mas["product"]['salePrices'][0]['value'])){
	      $price = number_format($mas["product"]['salePrices'][0]['value']/100, 2, '.', '');
		
		}else{
	      $price = 0;
		}
 
 
		$data[] = [
		  'findUUID'              =>  (!empty($findUUID['product_id'])) ? 
                                            $findUUID['product_id'] : 0,
		  'model'                 =>  "",
		  'sku'                   =>  (!empty($mas["product"]["article"])) ? $mas["product"]["article"]: "",
		  'upc'                   =>  (!empty($mas["product"]["code"])) ? $mas["product"]["code"]: "",
		  'ean'                   =>  "",
		  'jan'                   =>  "",
		  'isbn'                  =>  "",
		  'mpn'                   =>  "",
		  'location'              =>  "",
		  'quantity'              =>  0,
		  'minimum'               =>  "",
		  'subtract'              =>  "",
		  'stock_status_id'       =>  "",
		  'date_available'        =>  "",
		  'manufacturer_id'       =>  "",
		  'shipping'              =>  "",
		  'price'                 =>  $price,
		  'points'                =>  "",
		  'weight'                =>  (!empty($mas["product"]['weight'])) ? $mas["product"]['weight']: 0,
		  'weight_class_id'       =>  "",
		  'length'                =>  "",
		  'width'                 =>  "",
		  'height'                =>  "",
		  'length_class_id'       =>  "",
		  'status'                =>  1,
		  'tax_class_id'          =>  "",
		  'sort_order'            =>  "",
		  'image'                 =>  $image,
		  'product_description'   =>  [
		      $this->config->get('config_language_id') =>[
			  'name'          => $mas["product"]['name'],
			  'description'   => (!empty($mas["product"]['description'])) ? $mas["product"]['description']: " ",
			  'tag'           =>  "",
			  'meta_title'    =>  "",
			  'meta_description'  =>  "",
			  'meta_keyword'  =>  "",
		      ],
		  ],
		  'product_store'     =>[
		      'store_id'          => $this->config->get('config_store_id'),
		  ],
		  
		  'uuid'                  =>  $mas['uuid'],
		  'uuid_url'              =>  $mas["product"]['meta']['href'],
		  'keyword'               =>  "",
      

	      ];
	      
	      //сохраняем временные данные о количестве
	      $this->cahce_quantity[] = [
		  'uuid'  => $mas["uuid"],
		  'name'  => $mas["product"]['name'],
	      ];
 
	  }
	   
	  
	    foreach($data as $cache){
	      //если нашли id товара то update, если нет то insert
	      if(!empty($cache['findUUID'])){
		   $this->updateProduct($cache['findUUID'],$cache);
	      }else{
		  $this->insertProduct($cache);
	      }
	    
	    }
	    
	    return true;
	}
	
	//метод по обновлению инфы товара, параметр id товара
	public function updateProduct($id,$data){
	    
	    //получаем доступ к модели модуля
	    $this->load->model('tool/moyskladOC3Synch11');
	    $this->model_tool_moyskladOC3Synch11->updateProduct($id,$data);

	    return true;
	}
	
	//метод по добавлению нового товара
	public function insertProduct($data){
	    
	    //подгружаем стандартный метод опенкарт по добавлению нового товара
	    $this->load->model('catalog/product');
	    $product_id = $this->model_catalog_product->addProduct($data);
 
	    //получаем доступ к модели модуля
	    $this->load->model('tool/moyskladOC3Synch11');
	    
	    //делаем проверку если товар добавлен то заносим его id  в таблицу uuid
	    if(!empty($product_id)){
		$data = [
		  'product_id' =>  $product_id,
		  'uuid'       =>  $data['uuid'],
		  'url'        =>  $data['uuid_url'],  
		];
		
		
	      //передаем массив в модель модуля  
	      $this->model_tool_moyskladOC3Synch11->modelInsertUUID($data);
	   
	    }
	    
	    return true;
	}
 
	
	//получаем количество доступного товара в "Остатках"
	public function getQuantity(){
	
	    //получаем доступ к модели модуля
	    $this->load->model('tool/moyskladOC3Synch11');
 

		//проверяем есть ли кэш остатки
		if(!empty($this->cahce_quantity)){
		    foreach ($this->cahce_quantity as $data){
			$findUUID = $this->model_tool_moyskladOC3Synch11->modelSearchUUID($data["uuid"]);

			//проверяем существует ли такой товар в базе
			if(!empty($findUUID['product_id'])){
			    $jsonAnswerServer = $this->restAPIMoySklad($this->urlAPI."entity/assortment?filter=name=".urlencode($data['name']),0,"GET");
			    
			    //формируем результат по столбцу "Доступно" в моем складе
			    $quantity = (!empty($jsonAnswerServer['rows'][0]['quantity'])) ? $jsonAnswerServer['rows'][0]['quantity'] : 0;
			    
			    //если количество == 0  то ставим статус товара "Нет в наличии" иначе  "В наличии"
			    if($quantity == 0){
				$stock_status_id = 5;
			    }elseif($quantity != 0){
				$stock_status_id = 7;
			    }
			    
			    $this->model_tool_moyskladOC3Synch11->updateProductQuantity($findUUID['product_id'],$quantity,$stock_status_id);
	
			}
		    }

		}
 
	    //удаляем переменную
	    unset($this->cahce_quantity);
	    
	    return true;
    
	}
	
	//по ссылке получаем название странны товара
	public function getCountry(){
 
	 if(!empty($this->cahce_country)){
	 
	  //получаем доступ к модели модуля
	    $this->load->model('tool/moyskladOC3Synch11');
	 
	    foreach($this->cahce_country as $mas){
	      $jsonAnswerServer = $this->restAPIMoySklad($mas['href'],0,"GET");
	      $findUUID = $this->model_tool_moyskladOC3Synch11->modelSearchUUID($mas["uuid"]);
	      
	      $this->model_tool_moyskladOC3Synch11->updateProductCountry($findUUID['product_id'],$jsonAnswerServer["name"]);
	      
	    }
	  }	  
	 
	 unset($this->cahce_country);
	 return true;
	}
 
	
	//получаем все ид с базы uuid, для проверки на удаленость товара. 
	//Если товар удален с моего склада то и удаляем инфу о нем  в таблице uuid
	public function deleteProductFromBaseMC(){
	    //получаем доступ к модели модуля
	    $this->load->model('tool/moyskladOC3Synch11');
	    
	    //заносим в переменную все uuid с базы
	    $allUUID = $this->model_tool_moyskladOC3Synch11->getAllUUID();
 
	    foreach($allUUID as $uuid){
	    
	      //делаем перебор, поиск по массиву uuid
	      $key = array_search($uuid['uuid_id'], $_SESSION["moysklad_uuid"]);
	      
	      //если false то удаляем с базы строку
	      if($key === false){
 		  //делаем апдейт товара, ставим количество товара 0 и статус "нет в наличии" 
		  $this->model_tool_moyskladOC3Synch11->updateProductQuantity($uuid['product_id'],0,5);
		  
		  //удаляем инфу с базы по uuid, товар который удалили с моего склада
		  $this->model_tool_moyskladOC3Synch11->modelDeleteUUID($uuid['uuid_id']);
	      }
	     
	      
	    }
 
	    return true;
	}
	
	//получаем все модификации с МоегоСклада
	public function getModification($position = 0){
	
	    //получаем доступ к модели модуля
	    $this->load->model('tool/moyskladOC3Synch11');
	    
	    //$urlProduct = $this->urlAPI."entity/variant?offset=$position&limit=100";
	    $urlModification = $this->urlAPI."entity/variant?offset=$position&limit=10";
	    $modification = $this->restAPIMoySklad($urlModification,0,"GET");
	    
	    //если дошли до конца списка то выходим из рекурсии 
	    if(!empty($modification["rows"])){
	    
	      $i = 0;
	      
		foreach($modification["rows"] as $mod){
		
		  //делаем проверку существует ли ссылка на товар
		  if(!empty($mod["product"]["meta"]["href"])){
		  
		    $product_id = $this->model_tool_moyskladOC3Synch11->findUrlGetProductID($mod["product"]["meta"]["href"]);
		      
		      //если id товара получили по ссылке то создаем массив с ним
		      if(!empty($product_id)){
		      
			$_SESSION["moysklad_mod"][$position+$i] =[
			
			  "product_id" => $product_id["product_id"],
			  "id"	 => $mod["id"],
			  "name"  => $mod["name"],
			  "code"  => $mod["code"],
			  "externalCode"  => $mod["externalCode"],
			  "characteristics"  => $mod["characteristics"],
			  "price"  => $mod["salePrices"][0]["value"],
			  
			];
		      }
		    
		    
		  }
		    ++$i;
		  
		}
		
		//добавляем все модификации что есть к товару
		if(!empty($_SESSION["moysklad_mod"])){
		    $this->model_tool_moyskladOC3Synch11->addModMC($_SESSION["moysklad_mod"]);
		  }
		 
		
		
		
		//вызов рекурсии  
		//$this->getModification($position+$i);
	    }
 
	 }
	
	
	//restAPI моего склада
	public function restAPIMoySklad($url,$data,$method){
	    
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
	    //Делаем проверку, если данные есть для отправки то отправляем.
	    if(!empty($data)){
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    }

	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);  
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_USERPWD, $this->dataClient()['login'].":".$this->dataClient()['pass']);

	    if(!empty($data)){
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data))                                                                       
	    );  
	    }else{
	      curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json'));    
	    }

	    $response = curl_exec($ch);
	    curl_close($ch);
	    
	    //true  ставим, что бы получить массив, а не объект
	    return json_decode($response, true);
	}
}