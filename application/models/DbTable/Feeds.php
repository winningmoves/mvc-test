<?php

class Application_Model_DbTable_Feeds extends Zend_Db_Table_Abstract
{

    protected $_name = 'feeds';

	public function getFeed($id){
		$_id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
		if (!$row){
			throw new Exception("Could not find row $id");
		}
		return $row->toArray(); 
	}
    
    public function getAllDomainFeeds($domain) {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array('f'=>'feeds'), 'f.*');

        if(!is_null($domain)){
            $select->where('domain = ?', $domain);
        }
        
        $select->order("pubDate");
        
        return $this->fetchAll($select)->toArray();
    }
    
    public function getAllFavFeeds($domain) {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array('f'=>'feeds'), 'f.*')
            ->where('fav = ?', 1);
                
        if(!is_null($domain)){
            $select->where('domain = ?', $domain);
        }
                
        $select->order("pubDate");
        
        return $this->fetchAll($select)->toArray();
    }
    
    public function getDomains() {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array('f'=>'feeds'), 'f.domain')
            ->group("domain");
        
        return $this->fetchAll($select)->toArray();
    }
    
    public function checkIfItemExists($pubDate, $domain) {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array('f'=>'feeds'), 'f.id')
            ->where('pubDate = ?', $pubDate)
            ->where('domain = ?', $domain);
        
        return $this->fetchAll($select)->toArray();
    }
    
    public function getAllFeeds() {
        $row = $this->fetchAll();
		if (!$row){
			throw new Exception("Could not find any rows");
		}
		return $row->toArray(); 
    }
	
	public function addFeed($title, $link, $pubDate, $description, $domain, $fav){
		$data = array(
			'title' => $title,
			'link' => $link,
			'pubDate' => $pubDate,
			'description' => $description,
			'domain' => $domain,
            'fav' => $fav,
		);
		
        return $this->insert($data);
	}
	
	public function updateFeed($id, $title, $link, $pubDate, $description, $domain, $fav){
		
        if(!is_null($title)){
            $data['title'] = $title;
        }
        if(!is_null($link)){
            $data['link'] = $link;
        }
        if(!is_null($pubDate)){
            $data['pubDate'] = $pubDate;
        }
        if(!is_null($description)){
            $data['description'] = $description;
        }
        if(!is_null($domain)){
            $data['domain'] = $domain;
        }
        if(!is_null($fav)){
            $data['fav'] = $fav;
        }
        
		return $this->update($data, 'id = '. (int)$id);
	}
	
	public function deleteFeed($id){
		return $this->delete('id =' . (int)$id);
	}
}

