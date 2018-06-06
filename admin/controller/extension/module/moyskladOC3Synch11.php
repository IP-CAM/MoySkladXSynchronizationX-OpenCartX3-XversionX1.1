<?php

#TODO надо еще переделать модификатор по удалению товара, когда товар удалил то и что бы его uuid в базе тоже удалялся

class ControllerExtensionModuleMoyskladOC3Synch11 extends Controller {
	private $error = array();

	//храним url  МойСклад API
    public $urlAPI = "https://online.moysklad.ru/api/remap/1.1/";

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
		
		//используем ссылку в форме для того что бы создать задачу для крон
		$data['action_import_createCronTask'] = $this->url->link('extension/module/moyskladOC3Synch11/createCronTask', 'user_token=' . $this->session->data['user_token'], true);
		
		//используем ссылку в форме для импорта товара (тестовый режим)
		$data['action_import_test'] = $this->url->link('extension/module/moyskladOC3Synch11/getMethodImportTest', 'user_token=' . $this->session->data['user_token'], true);
 
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
 
	
	//метод который создает задачи
	public function createCronTask(){
	  $this->load->model('setting/setting');
	  if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
	  
	  //создаем массив который обновляет в базе данные
	  $mas = [
	  'module_moyskladOC3Synch11_username' => $this->config->get('module_moyskladOC3Synch11_username'),
	  'module_moyskladOC3Synch11_password' => $this->config->get('module_moyskladOC3Synch11_password'),
	  'module_moyskladOC3Synch11_status' => $this->config->get('module_moyskladOC3Synch11_status'),
	  'module_moyskladOC3Synch11_cron_task' => 1
	  ];
	  
	    //вызываем  метод из коробки для создания задачи для крона под темже ключом, что и основные данные модуля
	    $this->model_setting_setting->editSetting('module_moyskladOC3Synch11', $mas);
	    
 	    //после завершения функции делаем редирект в модуль
	    $this->response->redirect($this->url->link('extension/module/moyskladOC3Synch11', 'user_token=' . $this->session->data['user_token'], true));
	  }
	  
	  return true;
	}
	
	//вызываем метод по крону
	public function getMethodImport(){
	    
	    //проверяем создана ли задача для крона, если да то запускаем метод на создание/обновление товара
	    if(!empty($this->config->get('module_moyskladOC3Synch11_cron_task'))){
		
		//когда крон вызвали то удаляем задачу
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSettingValue('module_moyskladOC3Synch11','module_moyskladOC3Synch11_cron_task','0');
		
		//по клику запускаем API МойСклад для получения всего товара
		$this->getAllProduct(0);
    
	    }

	    return true;
	}
	
	//вызываем метод в форме (тестовый режим)
	public function getMethodImportTest(){
	    if(!empty($_POST['start'])){
		
		//по клику запускаем API МойСклад для получения всего товара
		$this->testMode();
 
		//после завершения функции делаем редирект в модуль
		$this->response->redirect($this->url->link('extension/module/moyskladOC3Synch11', 'user_token=' . $this->session->data['user_token'], true));
		
 	    }
	    
	    return true;
	}
	
	//метод по тестовому режиму (загрузка первых 10 товаров)
	public function testMode(){
	
	    $urlProduct = $this->urlAPI."entity/product?offset=0&limit=10";
	    $products = $this->restAPIMoySklad($urlProduct,0,"GET");
 
 		foreach($products["rows"] as $product){
		    
		    //делаем провекру, что бы товар был с именем
		    if(!empty($product["name"])){
			
			//передаем uuid для проверки существует ли такой uuid в базе или нет
			$this->searchUUID($product["id"],$product);
			
			
 		    }
 		}
	    return true;
	}
	
	//получаем весь товар, что есть (рекурсия)
	public function getAllProduct($position){
	    $urlProduct = $this->urlAPI."entity/product?offset=$position&limit=100";
	    $products = $this->restAPIMoySklad($urlProduct,0,"GET");
	    
	    //если дошли до конца списка то выходим из рекурсии 
	    if(!empty($products["rows"])){
		
		$i = 0;
 
		foreach($products["rows"] as $product){
		    
		    //делаем провекру, что бы товар был с именем
		    if(!empty($product["name"])){
			
			//передаем uuid для проверки существует ли такой uuid в базе или нет
			$this->searchUUID($product["id"],$product);
		    }
		    ++$i;
		}
		//вызов рекурсии  
		$this->getAllProduct($position+$i);
	    
	    }
	    
	    return true; 
	}
	
	//делаем поиск в таблице uuid  на id  товара.
	//Если нету то добавляем товар если есть id  товара то обновляем.
	public function searchUUID($uuid,$mas){
 
	    //получаем доступ к модели модуля
	    $this->load->model('tool/moyskladOC3Synch11');
	    
	    //делаем поиск по uuid
	    $findUUID = $this->model_tool_moyskladOC3Synch11->modelSearchUUID($uuid);
	    
	    $image = "";
 
	    //проверяем существует ли цена продажи
	    if(!empty($mas['salePrices'][0]['value'])){
	  $price = number_format($mas['salePrices'][0]['value']/100, 2, '.', '');
	    
	    }else{
	  $price = 0;
	    }
    
	    $quantity = (!empty($this->getQuantity($mas['name']))) ? $this->getQuantity($mas['name']): 0;
	    
	    //если количество == 0  то ставим статус товара "Нет в наличии" иначе  "В наличии"
	    if($quantity == 0){
		$stock_status_id = 5;
	    }elseif($quantity != 0){
		$stock_status_id = 7;
	    }
  
	    $data = [
	      'model'                 =>  "",
	      'sku'                   =>  "",
	      'upc'                   =>  "",
	      'ean'                   =>  "",
	      'jan'                   =>  "",
	      'isbn'                  =>  "",
	      'mpn'                   =>  "",
	      'location'              =>  "",
	      'quantity'              =>  $quantity,
	      'minimum'               =>  "",
	      'subtract'              =>  "",
	      'stock_status_id'       =>  $stock_status_id,
	      'date_available'        =>  "",
	      'manufacturer_id'       =>  "",
	      'shipping'              =>  "",
	      'price'                 =>  $price,
	      'points'                =>  "",
	      'weight'                =>  (!empty($mas['weight'])) ? $mas['weight']: 0,
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
		      'name'          => $mas['name'],
		      'description'   => (!empty($mas['description'])) ? $mas['description']: " ",
		      'tag'           =>  "",
		      'meta_title'    =>  "",
		      'meta_description'  =>  "",
		      'meta_keyword'  =>  "",
		  ],
	      ],
	      'product_store'     =>[
		  'store_id'          => $this->config->get('config_store_id'),
	      ],
	      
	      'uuid'                  =>  $uuid,
	      'uuid_url'              =>  $mas['meta']['href'],
	      'keyword'               =>  "",
  

	  ];
	    
 
	    //если нашли id товара то update, если нет то insert
	    if(!empty($findUUID)){
		$this->updateProduct($findUUID,$data);
	    }else{
		$this->insertProduct($data);
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
	public function getQuantity($name){
	    $jsonAnswerServer = $this->restAPIMoySklad($this->urlAPI."entity/assortment?filter=name=".urlencode($name),0,"GET");

	    //формируем результат по столбцу "Доступно" в моем складе
	    $quantity = $jsonAnswerServer['rows'][0]['quantity'];
	    return $quantity;
    
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