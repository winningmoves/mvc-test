<?php
class IndexController extends Zend_Controller_Action
{

    public function preDispatch()
    {
        
    }
    
    public function init(){}
    
    public function indexAction() {
        
    }

    public function addRssAction(){
        $form = new Application_Form_Feed();
        $feed = "https://news.google.co.uk/news/feeds?hl=en&q=internet+news&bav=on.2,or.r_gc.r_pw.r_cp.r_qf.,cf.osb&biw=1269&bih=884&um=1&ie=UTF-8&output=rss";
        $form->getElement('feed')->setValue($feed);
        $form->setAction("/index/get-rss");
        
        $this->view->form = $form;
    }
    
    public function viewRssAction() {
        $db_feeds = new Application_Model_DbTable_Feeds();
        $request = $this->getRequest();
        
        if($request->getParam('favs')){
            $this->view->h1 = "Favourite Feeds";
            $this->view->xml = $db_feeds->getAllFavFeeds();
        }else if($request->getParam('domain')){
            $this->view->h1 = "Feeds By domain";
            $domain = $db_feeds->getDomains();
            if($domain){
                $this->view->domains = $this->createDomainForm($domain);;
                
                if($request->getParam('domain_name')){
                    $domain_name = $request->getParam('domain_name');
                }else{
                    $domain_name = $domain[0];
                }
                $this->view->xml = $db_feeds->getAllDomainFeeds($domain_name);
            }else{
                $this->_helper->redirector('add', 'index');
            }
        }else if($request->getParam('combo')){
            $this->view->h1 = "Favourite Feeds By Domain";
            $domain = $db_feeds->getDomains();
            if($domain){
                $this->view->domains = $this->createDomainForm($domain);
                
                if($request->getParam('domain_name')){
                    $domain_name = $request->getParam('domain_name');
                }else{
                    $domain_name = $domain[0];
                }
                $this->view->xml = $db_feeds->getAllFavFeeds($domain_name);
            }else{
                $this->_helper->redirector('add', 'index');
            }
        }else{
            $this->view->h1 = "All Feeds";
            
            $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($db_feeds->getAllFeeds()));
            
            $page = $request->page;
        
            if($page === "all"){
                $all = $paginator->getTotalItemCount();
                $paginator->setItemCountPerPage($all);
                $this->view->controls = FALSE;
            }else{
                $paginator->setItemCountPerPage(15);
                $paginator->setCurrentPageNumber($page);
                $this->view->controls = TRUE;
            }

            $this->view->xml = $paginator;
        }
    }
    
    private function createDomainForm($domain) {
        $form = new Zend_Form();
        $select_box = new Zend_Form_Element_Select('domain_name');

        $select_array = array();
        foreach ($domain as $dom) {
            $select_array[$dom['domain']] = $dom['domain'];
        }

        $select_box->addMultiOptions($select_array);
        $select_box->setAttrib('class', 'form-control');
        $form->addElement($select_box);

        $submit = new Zend_Form_Element_Submit('fetch');
        $submit->setAttrib('class', 'btn btn-success');
        $form->addElement($submit);
        
        return $form;
    }
    
    public function getRssAction() {
        $request = $this->getRequest();
        $form = new Application_Form_Feed();
        
        if($request->getPost()){
            if ($form->isValid($request->getPost())) {
                $url = $request->getParam('feed');
                $xml = simplexml_load_file($url);
                if($xml){
                    $parse = parse_url($url);
                    $domain = $parse['host'];
                    
                    $db_feeds = new Application_Model_DbTable_Feeds();
                    
                    foreach($xml->channel->item AS $item){
                        $exists = $db_feeds->checkIfItemExists($item->pubDate, $domain);
                        if(!$exists){
                            $db_feeds->addFeed($item->title, $item->link, $item->pubDate, $item->description, $domain, 0);
                        }
                    }
                    
                    $this->view->xml = $db_feeds->getAllDomainFeeds($domain);
                }else{
                    $this->_helper->redirector('index', 'index');
                }
            }
        }
    }
    
    private function clean_text($text) {
		$html = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
		$text = strip_tags($html);
		$text = htmlentities($text, ENT_QUOTES, 'UTF-8');
		return $text;
	}
    
    public function ajaxAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $request = $this->getRequest();
        $feed_id = $request->getParam('feed_id');
        $fav = (int)$request->getParam('fav');
        
        $db_feed = new Application_Model_DbTable_Feeds();
        
        $updated = $db_feed->updateFeed($feed_id, NULL, NULL, NULL, NULL, NULL, $fav);
        
        if($updated){
            if($fav === 1){
                $return = array("success"=>TRUE, "message"=>"<span style='color:green;'>fav added</span>");
            }else{
                $return = array("success"=>TRUE, "message"=>"<span style='color:green;'>fav removed</span>");
            }
        }else{
            $return = array("success"=>FALSE, "message"=>"<span style='color:red;'>fav not added</span>");
        }
        
        $return = json_encode($return);
        
        print_r($return);
    }

}















