<?php
	class Clash_mod extends CI_Model {
		function __construct() {
	        parent::__construct();
	        $this->load->database();
	        $this->load->helper('form');
	        
	        $this->totalArtists = $this->countArtists();
	    }
	    
	    function getScheduleDay($day) {
	        $schedule = $this->db->where('day', $day)
	            ->where('start >=', "06:00:00")
	            ->order_by('start', 'ASC')
	            ->get('artists');
	            
	        return $schedule->num_rows() > 0 ? $schedule->result() : false;
	    }
	    
	    function getScheduleNight($day) {
	        $schedule = $this->db->where('day', $day)
	            ->where('start <=', "06:00:00")
	            ->order_by('start', 'ASC')
	            ->get('artists');
	            
	        return $schedule->num_rows() > 0 ? $schedule->result() : false;
	    }

	    function getClash($artist1=false,$createClash=false) {
	        if(!$this->totalArtists) {
	            return false;
	        }
	        
	        if(!$artist1) {
    	        $artist1 = $this->getArtist(rand(1, $this->totalArtists));
    	        
    	        if(!$artist1) {
    	            return false;
    	        }
	        }
	        
	        if(!substr($artist1->end, 0, 1) == 0) {
    	        $where = "((`stage` != '".$artist1->stage."' AND `day` = '".$artist1->day."') AND ((`start` >= '".$artist1->start."' AND `start` <= '".$artist1->end."') OR (`start` <= '".$artist1->start."' AND `end` >= '".$artist1->start."')))";
	        } else if(substr($artist1->start, 0, 1) == 0 && substr($artist1->end, 0, 1) == 0) {
	            $where = "((`stage` != '".$artist1->stage."' AND `day` = '".$artist1->day."') AND ((`start` <= '23:59:59' AND `start` >= '08:00:00' AND `end` <= '08:00:00') AND ('".$artist1->start."' <= `end` OR '".$artist1->end."' <= `end`)))";
	        } else {
	            $where = "((`stage` != '".$artist1->stage."' AND `day` = '".$artist1->day."') AND ((`start` <= '"."artist1->start"."' AND `end` <= '08:00:00') OR (`start` <= '08:00:00' AND `end` <= '".$artist1->end."')))";
	        }
	        
	        $clashes = $this->db->where($where, NULL, false)
	            ->get('artists');
	            
	       // var_dump($this->db->last_query());
	       // die();
	        
	        if($clashes->num_rows() <= 0) {
	            return false;
	        }
	        
	        $artists = [];
	        
	        if($createClash) {
	            foreach($clashes->result() as $clash) {
    	           if($artist1->id != $clash->id)
    	           $artists[] = $clash;
    	        }
	            return $artists;
	        }
	        
	        foreach($clashes->result() as $clash) {
	           if($artist1->id != $clash->id)
	           $artists[] = $clash->id;
	        }
	        
	        $artist2 = $this->getArtist($artists[(rand(1, count($artists)) -1)]);
	        
	        if(!$artist2) {
	            return false;
	        }
	        
	        return array("artist1" => $artist1, "artist2" => $artist2);
	    } 
	    
	    private function countArtists() {
	        $artists = $this->db->get('artists');
	        
	        return $artists->num_rows() > 0 ? $artists->num_rows() : false;
	    }
	    
	    function getArtist($id) {
	        if(!$id) {
	            return false;
	        }
	        
	        $artist = $this->db->where('id', $id)->limit(1)->get('artists');
	        
	        return $artist->num_rows() > 0 ? $artist->row() : false;
	    }
	    
	    function insertClash($clash) {
	        $this->db->insert('clashes', $clash);
	    }
	    
	    function updateScore($winner, $score) {
	        $this->db->where('id', $winner)
	            ->update('artists', array('score' => $score));
	    }
	    
	    function insertArtist($insertArr) {
	        $this->db->insert('artists', $insertArr);
	    }
	    
	    function getLeaderboard() {
	        $artists = $this->db->order_by('score', 'DESC')
	            ->order_by('name', 'ASC')
	            ->get('artists');
	            
	        return $artists->num_rows() > 0 ? $artists->result() : false;
	    }
	    
	    function getWinrate($artist1, $artist2) {
	        $this->db->reset_query();
	        
	        $query = $this->db->select('*')
	            ->from('clashes')
	            ->where('(`artist1` = '.$artist1.' AND `artist2` = '.$artist2.') OR (`artist2` = '.$artist1.' AND `artist1` = '.$artist2.')');
	        
	        $total = clone $query;
	        $total = $total->count_all_results();
	        
	        $wins = $query->where('`winner`', $artist1)
	            ->get();
	            
	        return array("total" => $total, "wins" => $wins->num_rows());
	        
	    }
	    
	    function getAllArtists() {
	        $artists = $this->db->get('artists');
	        
	        return $artists->num_rows() > 0 ? $artists->result() : false;
	    }
	}
?>