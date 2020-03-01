<?php
class Clash extends CI_Controller {

	function __construct() {
		 parent::__construct();
		 $this->load->library('email');
		 $this->load->model('clash_mod');
	}

	function index() {
		$this->setup["js"] = "clash";
		$this->setup["sumoselect"] = true;
		
		$artist1 = $this->input->get('artist1');
		$artist2 = $this->input->get('artist2');
		$readOnly = $this->input->get('readonly');
		
		if($artist1 && $artist2) {
		    $this->recoverClash($artist1, $artist2, $readOnly);
		} else {
    		$data['view'] = $this->load->view('clash/partials/home', '', true);
    		
    		$this->load->view('header');
    		$this->load->view('clash/clash', $data);
    		$this->load->view('footer', $this->setup);
		}
	}
	
	public function recoverClash($artist1, $artist2, $readonly=false) {
	    $this->setup["js"] = "clash";
	    
	    $data['artists'] = false;
	    
	    $artist1 = $this->clash_mod->getArtist($artist1);
	    $artist2 = $this->clash_mod->getArtist($artist2);
	    
	    if($artist1 && $artist2) {
	        if($readonly == 'yes') {
	            $winrate = $this->getArtistWinrate($artist1->id, $artist2->id);
	            $artist1->winrate = $winrate['artist1'];
	            $artist2->winrate = $winrate['artist2'];
	            
	            $data['total'] = $winrate['total'];
	        }
	        
            $clash = [
    	        "artist1"   => $artist1,
    	        "artist2"   => $artist2,
    	    ];
    	    
    	    $clash = $this->formatClash($clash);
    	    
    	    $data['artists'] = $clash;
	    }
	    
	    $data['readonly'] = $readonly;
	    
    	$data['view'] = $this->load->view('clash/partials/game', $data, true);
    		
		$this->load->view('header');
		$this->load->view('clash/clash', $data);
		$this->load->view('footer', $this->setup);
	}
	
	public function loadGame() {
	    $data['artists'] = false;
	    $data['readonly'] = false;
	    
	    $clash = $this->clash_mod->getClash();
	    
	    if(!$clash) {
	        echo $this->load->view('clash/partials/game', $data, true);
	        return;
	    }
	    
	    $clash = $this->formatClash($clash);
	    
	    if(!$clash) {
	        echo $this->load->view('clash/partials/game', $data, true);
	        return;
	    }
	    
	    $data['artists'] = $clash;
	    
	    $response = [
	        "view"      => $this->load->view('clash/partials/game', $data, true),
	        "artist1"   => $clash['firstArtist']->id,
	        "artist2"   => $clash['secondArtist']->id
	    ];
	    
	    echo json_encode($response);
	}
	
	public function loadHome() {
	   echo json_encode(array("view" => $this->load->view('clash/partials/home', '', true)));
	}
	
    public function loadWeather() {
	   echo json_encode(array("view" => $this->load->view('clash/partials/weather.php', '', true)));
	}
	
	public function loadSchedule() {
	   $thursdaySchedule = $this->getSchedule('Thursday');
	   $fridaySchedule = $this->getSchedule('Friday');
	   $saturdaySchedule = $this->getSchedule('Saturday');
	   $sundaySchedule = $this->getSchedule('Sunday');
	   
	   $schedule = [
	       'Thursday' => $thursdaySchedule, 
	       'Friday' => $fridaySchedule, 
	       'Saturday' => $saturdaySchedule, 
	       'Sunday' => $sundaySchedule, 
	   ];
	   
	   //var_dump($schedule['Saturday']);
	   //die();
	   
	   $data['schedule'] = $schedule;
	   
	   echo json_encode(array("view" => $this->load->view('clash/partials/schedule', $data, true)));
	}
	
	private function getSchedule($day) {
	   $daySet = $this->clash_mod->getScheduleDay($day);
	   $nightSet = $this->clash_mod->getScheduleNight($day);
	   
	   $lastSet = null;
	   $lastSetStart = null;
	   $lastSetEnd = null;
	   $lastSetStage = null;
	   $position = 0;
	   
	   $schedule = [];
	   
	   foreach($daySet as $set) {
	       if($lastSet == null) {
	           $schedule[$position] = $set;
	           $lastSet = $set->score;
        	   $lastSetStart = $set->start;
        	   $lastSetEnd = $set->end;
	       }
	       
	       if($set->score > $lastSet && $set->start < $lastSetEnd) {
	           // current set higher rating 
	           // current set same slot
	           // overwrite current set
	           $schedule[$position] = $set;
	           $lastSet = $set->score;
        	   $lastSetStart = $set->start;
        	   $lastSetEnd = $set->end;
        	   $lastSetStage = $set->stage;
	       }
	       
	       if(substr($lastSetEnd, 0, 1) == 0) {
	           break;
	       } else if ($set->start > $lastSetEnd || ($set->start >= $lastSetEnd && $set->stage == $lastSetStage)) {
	           // current set higher rating 
	           // current set different slot
	           // push current set
	           $position++;
	           $schedule[$position] = $set;
	           $lastSet = $set->score;
        	   $lastSetStart = $set->start;
        	   $lastSetEnd = $set->end;
        	   $lastSetStage = $set->stage;
	       }
	   }
	   
	   foreach($nightSet as $set) {
	       if($set->score > $lastSet && $set->start < $lastSetEnd) {
	           // current set higher rating 
	           // current set same slot
	           // overwrite current set
	           $schedule[$position] = $set;
	           $lastSet = $set->score;
        	   $lastSetStart = $set->start;
        	   $lastSetEnd = $set->end;
        	   $lastSetStage = $set->stage;
	       }
	       
	       if ($set->start > $lastSetEnd || ($set->start >= $lastSetEnd && $set->stage == $lastSetStage) || substr($lastSetStart, 0, 1) != 0) {
	           // current set higher rating 
	           // current set different slot
	           // push current set
	           $position++;
	           $schedule[$position] = $set;
	           $lastSet = $set->score;
        	   $lastSetStart = $set->start;
        	   $lastSetEnd = $set->end;
        	   $lastSetStage = $set->stage;
	       }
	   }
	   
	   return $schedule;
	}
	
	public function loadLeaderboard() {
	    $artists = $this->clash_mod->getLeaderboard();
        
        $lastArtistScore = null;
        $lastArtistPosition = null;
        $count = 1;
        
        foreach($artists as $artist) {
            if($lastArtistPosition == null) {
                $artist->position = 1;
                $lastArtistPosition = $artist->position;
                $lastArtistScore = $artist->score;
            } else {
                if($artist->score == $lastArtistScore) {
                    $artist->position = '-';
                    $count++;
                } else {
                    $artist->position = $lastArtistPosition + $count;
                    $count = 1;
                    $lastArtistPosition = $artist->position;
                }
                $lastArtistScore = $artist->score;
            }
        }
	    
	    $data['artists'] = $artists;
	    
	    echo json_encode(array("view" => $this->load->view('clash/partials/leaderboards', $data, true)));
	}
	
	public function loadClash() {
	    $clash = $this->clash_mod->getClash();
	    
	    if(!$clash) {
	        return false;
	    }
	    
	    $clash = $this->formatClash($clash);
	    
	    if(!$clash) {
	        return false;
	    }
	   
	    if(IS_AJAX) {
	        echo json_encode($clash);
	    } else {
    	    return $clash;
	    }
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
	
	function addScore() {
	    $winner = $this->input->post('winner');
	    $loser = $this->input->post('loser');
	    
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
	
	function addArtist() {
	    $artist = $this->input->post('artist');
	    $stage = $this->input->post('stage');
	    $day = $this->input->post('day');
	    $start = $this->input->post('start');
	    $end = $this->input->post('end');
	    
	    $insertArr = [
	        "name"    => $artist,
	        "stage"     => $stage,
	        "day"       => $day,
	        "start"     => $start,
	        "end"       => $end
	    ];
	    
	    $this->clash_mod->insertArtist($insertArr);
	}
    
    public function sendFeedback() {
		if(!IS_AJAX) {
			return FALSE;
		}

		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$feedback = $this->input->post('feedback');

        if($feedback != "" && $name != "") {
        	$data['name'] = $name;
        	$data['email'] = $email;
        	$data['feedback'] = $feedback;

            $to = "glastoclash@gmail.com";

            $message = $this->load->view('email', $data, true);

            $message = strip_tags($message);

            $this->email->from('feedback@morphologicalanalysisonline.com', 'Morphological Analysis Online');
            $this->email->to($to);
            $this->email->subject('You have new feedback');
            $this->email->message($message);
            $this->email->send();
        }
    }
    
    function createClash() {
        $firstResult = null;
        
        $artists = $this->clash_mod->getAllArtists();
        
        if(!$artists) {
            $data['artistDropdown'] = false;
            echo json_encode(array("view" => $this->load->view('clash/partials/createClash', $data, true)));
            return;
        }
        
        foreach($artists as $artist) {
            if(!$firstResult) {
                $firstResult = $artist->id;
            }
            
			$array[$artist->id] = $artist->name;
		}
			
		$data['artistDropdown'] = form_dropdown('artist1',$array);
		$data['artist2Dropdown'] = $this->getClashingArtists($firstResult);
		
		echo json_encode(array("view" => $this->load->view('clash/partials/createClash', $data, true)));
		return;
    }
    
    function getClashingArtists($onLoad=null) {
        
        if(!$onLoad) {
            $artist = $this->input->post('artist1');
        } else {
            $artist = $onLoad;
        }
        
        $artist = $this->clash_mod->getArtist($artist);
        
        $artists = $this->clash_mod->getClash($artist, true);
        
        if(!$artists) {
            echo json_encode(array("dropdown" => false));
            return;
        }
        
        foreach($artists as $artist) {
			$array[$artist->id] = $artist->name;
		}
			
		$dropdown = form_dropdown('artist2',$array);
		
		if($onLoad) {
		    return $dropdown;
		}
		
		echo json_encode(array("dropdown" => $dropdown));
		return;
    }
    
    function crawl_page() {
        // $url = "http://www.glastoclash.com";
        $url = "https://glastoaddict.co.uk/";
        $html = new DOMDocument();
        @$html->loadHTML(file_get_contents($url));
        
        foreach($html->getElementsByTagName('meta') as $meta) {
            if($meta->getAttribute('property')=='og:image'){ 
               $image = $meta->getAttribute('content');
            }
            if($meta->getAttribute('property')=='og:title'){ 
               $title = $meta->getAttribute('content');
            }
            if($meta->getAttribute('property')=='og:description'){ 
               $description = $meta->getAttribute('content');
            }
        }
        
        echo '<div class="card"><div class="card-divider"><h4 class="h4">'.$title.'</h4></div><img style="width: 100px;" src="'.$image.'" /><div class="card-section"><p>'.$description.'</p></div>';

        die();
    }
}
?>