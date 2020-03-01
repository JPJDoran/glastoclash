<?php
    class Api extends CI_Controller {
    
    	function __construct() {
    		 parent::__construct();
    		 $this->load->model('clash_mod');
    	}
    	
    	public function get_clash() {
    	    $response = [
    	        "error" => false,
    	        "clash" => null,
    	    ];
    	    
    	    $clash = $this->clash_mod->getClash();
	    
    	    if(!$clash) {
    	        $response['error'] = true;
    	        echo json_encode($response);
    	        return;
    	    }
    	    
    	    $clash = $this->formatClash($clash);
    	    
    	    $response['clash'] = $clash;
    	    
    	    echo json_encode($response);
    	    return;
    	}
    	
    	private function formatClash($clash) {
    	    $clash['artist1']->start = date('H:i', strtotime($clash['artist1']->start));
    	    $clash['artist1']->end = date('H:i', strtotime($clash['artist1']->end));
    	    
    	    $clash['artist2']->start = date('H:i', strtotime($clash['artist2']->start));
    	    $clash['artist2']->end = date('H:i', strtotime($clash['artist2']->end));
    	    
    	    $winRate = $this->getArtistWinrate($clash['artist1']->id, $clash['artist2']->id);
    	    
    	    $clash['artist1']->winRate = $winRate['artist1'];
    	    $clash['artist2']->winRate = $winRate['artist2'];
    	    
        	$artists['firstArtist'] = $clash['artist1'];
        	$artists['secondArtist'] = $clash['artist2'];
        	$artists['total'] = $winRate['total'];
    	    
    	    return $artists;
    	}
    	
    	private function getArtistWinrate($artist1, $artist2) {
    	    $response = [
    	        "artist1"   => 0,
    	        "artist2"   => 0,
    	        "total"     => 0
    	    ];
    	    
    	    // acts on winner being first artist
    	    $winRate = $this->clash_mod->getWinrate($artist1, $artist2);
    	    
    	    if($winRate['total'] > 0) {
    	        $response['artist1'] = (($winRate['wins']/$winRate['total'])*100);
    	        $response['artist2'] = ((($winRate['total']-$winRate['wins'])/$winRate['total'])*100);
    	        $response['total'] = $winRate['total'];
    	        return $response;
    	    }
    	    
    	    return $response;
    	}
    	
    	public function post_add_score($winner, $loser) {
    	    $response = [
    	        "error"     => false,
    	        "winrates"  => null
    	    ];
    	    
    	    $clash = [
    	        "artist1"   => $winner,
    	        "artist2"   => $loser,
    	        "winner"    => $winner,
    	        "dateTime"  => date('Y-m-d H:i:s', strtotime('+1 hour')),
    	    ];
    	    
    	    $this->updateRankings($winner, $loser);
    	    $this->clash_mod->insertClash($clash);
    	    
    	    $winrates = $this->getArtistWinrate($winner, $loser);
    	    
    	    echo json_encode($winrates);
    	    return;
    	}
    	
    	private function updateRankings($winner, $loser) {
    	    $winner = $this->clash_mod->getArtist($winner);
    	    $loser = $this->clash_mod->getArtist($loser);
    	    
    	    $winnerRating = $winner->score;
    	    $loserRating = $loser->score;
    	    
    	    $weighting = $winnerRating != 0 ? $winnerRating / 10 : 0;
    	    
    	    if($winnerRating <= $loserRating) {
    	        // winner is lower ranked
    	        $difference = $loserRating - $winnerRating;
    	        $favoured = false;
    	    } else {
    	        // winner is higher ranked
    	        $difference = $winnerRating - $loserRating;
    	        $favoured = true;
    	    }
    	    
    	    // If difference is greater than 10% of the current ranking
    	    if($difference > $weighting) {
    	        // If the winner has higher ranking
    	        if($favoured) {
    	           $winnerRating = $winnerRating + 5;
    	           $loserRating = $loserRating - 5;
    	        } else {
    	           $winnerRating = $winnerRating + 20;
    	           $loserRating = $loserRating - 20;
    	        }
    	    } else {
    	        $winnerRating = $winnerRating + 10;
    	        $loserRating = $loserRating - 10;
    	    }
    	    
    	    $this->clash_mod->updateScore($winner->id, $winnerRating);
    	    $this->clash_mod->updateScore($loser->id, $loserRating);
    	}
    }
?>