const sumoSelect = {
	placeholder: 'This is a placeholder',
	search: true,
};

$(document).ready(function () {
  $('body').find('.sumoselect').SumoSelect(sumoSelect);
    
  if(window.location.href.indexOf("readonly") > -1) {
    $('body').find('[data-target="skip-clash"]').addClass('hide');
  }  

  $('body').on('click tap', '[data-trigger="artist-vote"]', function () {
    var winnerId = $(this).attr('id');
    var loserId = 'artist1';
    
    if(winnerId == 'artist1') {
        loserId = 'artist2';
    }
    
    var winner = $(this).attr('data-id');
    var winnerScore = $(this).attr('data-score');
    var artists = $('body').find('[data-id]');
    var loser;
    $.each(artists, function (index, val) {
      if (winner != $(this).attr('data-id')) {
        loser = $(this).attr('data-id');
      }
    });
    var loserScore = $('body').find('[data-id="' + loser + '"]').attr('data-score');
    
    let readonly = $('body').find('[data-readonly]').attr('data-readonly');
    
    if(readonly != 'yes') {
        $.when(
            addScore(winner, winnerScore, loser, loserScore)
        ).done(function(data) {
            console.log(data);
            $('body').find('#'+winnerId).find('.card-section').find('p').html('<h4 class="text-font h4">'+data.artist1+'%</h4>');
            $('body').find('#'+loserId).find('.card-section').find('p').html('<h4 class="text-font h4">'+data.artist2+'%</h4>');
            $('body').find('[data-target="total-matches"]').html('<p class="text-font">VS</p>');
            
            let delayInMilliseconds = 2000; // 2 seconds
            
            if(readonly != 'no') {
                setTimeout(function() {
                    refreshClash();
                }, delayInMilliseconds);
            } else {
                $('body').find('[data-target="lead-text"]').html('<p class="text-center lead-text">Results for '+data.total+' votes</p>')
            }
        });
    }
  });
  $('body').on('click tap', '[data-trigger="enter-artist"]', function (event) {
    event.preventDefault();
    $.ajax({
      url: "/clash/addArtist",
      method: "POST",
      dataType: "JSON",
      data: $("#insert-artist").serialize()
    });
  });
  $('body').on('click tap', '[data-target="skip-clash"]', function (event) {
    event.preventDefault();
    
    refreshClash();
  });
  
  $('body').on('click tap', '[data-target="create-clash"]', function (event) {
    event.preventDefault();
    
    $.when(createClash()).done(function (html) {
      $('body').find('[data-target="content-container"]').html(html.view);
      $('body').find('select').SumoSelect(sumoSelect);
      $('body').find('[data-target="copy-link"]').prop('readonly', true);
      $('body').find('[data-target="skip-clash"]').addClass('hide');
      
      var clipboard = new ClipboardJS('#copy-button');
      
      clipboard.on('success', function(e) {
        alert('Link Copied!');
      });
      
      updateClashLink();
    });
  });
  
  $('body').on('change', 'select[name="artist1"]', function() {
        newArtist = $(this).val();
        
        $.when(
            updateClash(newArtist)
        ).done(function(html) {
            $('body').find('[data-target="clashing-artist"]').html(html.dropdown);
            $('body').find('select[name="artist2"]').SumoSelect(sumoSelect);
            
            updateClashLink();
        });
  });
  
  $('body').on('click tap', '[data-target="play-game"]', function (event) {
    event.preventDefault();
    
    let url = 'http://glastoclash.jakedoran.co.uk/';   
    
    $.when(playGame()).done(function (html) {
      $('body').find('[data-target="content-container"]').html(html.view);
      $('body').find('[data-target="skip-clash"]').removeClass('hide');
      
      url += '?artist1='+html.artist1+'&artist2='+html.artist2;
    
      history.pushState({
        id: 'home'
      }, 'Glasto Clash', url);
    });
  });
  $('body').on('click tap', '[data-target="home"]', function (event) {
    event.preventDefault();
    $.when(loadHome()).done(function (html) {
      $('body').find('[data-target="content-container"]').html(html.view);
      $('body').find('[data-target="skip-clash"]').addClass('hide');
      
      $('body').find('[data-target="weather-container"]').addClass('hide');
      $('body').find('[data-target="content-container"]').removeClass('hide');
      
      history.pushState({
        id: 'home'
      }, 'Glasto Clash', 'http://glastoclash.jakedoran.co.uk');
    });
  });
  $('body').on('click tap', '[data-target="weather"]', function (event) {
    event.preventDefault();
    $('body').find('[data-target="content-container"]').addClass('hide');
    $('body').find('[data-target="weather-container"]').removeClass('hide');
  });
  $('body').on('click tap', '[data-target="schedule"]', function (event) {
    event.preventDefault();
    $.when(loadSchedule()).done(function (html) {
      $('body').find('[data-target="content-container"]').html(html.view);
      $('body').find('[data-target="skip-clash"]').addClass('hide');
      history.pushState({
        id: 'home'
      }, 'Glasto Clash', 'http://glastoclash.jakedoran.co.uk');
      $(document).foundation();
    });
  });
  $('body').on('click tap', '[data-target="rankings"]', function (event) {
    event.preventDefault();
    $.when(loadLeaderboard()).done(function (html) {
      $('body').find('[data-target="content-container"]').html(html.view);
      $('body').find('[data-target="skip-clash"]').addClass('hide');
      history.pushState({
        id: 'Leaderboard'
      }, 'Glasto Clash', 'http://glastoclash.jakedoran.co.uk');
    });
  });
  $('body').on('click tap', '[data-target="game"]', function (event) {
    event.preventDefault();
    url = 'http://glastoclash.jakedoran.co.uk';
    $.when(playGame()).done(function (html) {
      $('body').find('[data-target="content-container"]').html(html.view);
      $('body').find('[data-target="skip-clash"]').removeClass('hide');
      url += '?artist1='+html.artist1+'&artist2='+html.artist2;
      history.pushState({
        id: 'Play Game'
      }, 'Glasto Clash', url);
    });
  });
  $('body').on('click tap', '[data-target="leaderboard"]', function (event) {
    event.preventDefault();
    $.when(loadLeaderboard()).done(function (html) {
      $('body').find('[data-target="content-container"]').html(html.view);
    });
  });
  $('body').on('click tap', '[data-trigger="send-feedback"]', function(event) {
		event.preventDefault();
		event.stopPropagation();

		$(this).prop('disabled', true);
		sendFeedback();
	});
});

function playGame() {
  return $.ajax({
    url: "/clash/loadGame",
    method: "POST",
    dataType: "JSON"
  });
}

function loadHome() {
  return $.ajax({
    url: "/clash/loadHome",
    method: "POST",
    dataType: "JSON"
  });
}

function loadSchedule() {
  return $.ajax({
    url: "/clash/loadSchedule",
    method: "POST",
    dataType: "JSON"
  });
}

function loadWeather() {
  return $.ajax({
    url: "/clash/loadWeather",
    method: "POST",
    dataType: "JSON"
  });
}

function loadLeaderboard() {
  return $.ajax({
    url: "/clash/loadLeaderboard",
    method: "POST",
    dataType: "JSON"
  });
}

function createClash() {
  return $.ajax({
    url: "/clash/createClash",
    method: "POST",
    dataType: "JSON"
  });
}

function updateClash(artist) {
    return $.ajax({
      url: "/clash/getClashingArtists",
      method: "POST",
      dataType: "JSON",
      data: {artist1: artist}
    });
}

function addScore(winner, winnerScore, loser, loserScore) {
  return $.ajax({
    url: "/clash/addScore",
    method: "POST",
    dataType: "JSON",
    data: {
      winner: winner,
      winnerScore: winnerScore,
      loser: loser,
      loserScore: loserScore
    }
  });
}

function refreshClash() {
  let url = 'http://glastoclash.jakedoran.co.uk/';

    $.when(getNewClash()).done(function (artists) {
        // $('body').find('#artist1').find('[data-target="title"]').html(artists.firstArtist.name);
        // $('body').find('#artist1').find('[data-target="stage"]').html(artists.firstArtist.stage);
        // $('body').find('#artist1').find('[data-target="day"]').html(artists.firstArtist.day);
        // $('body').find('#artist1').find('[data-target="start"]').html(artists.firstArtist.start);
        // $('body').find('#artist1').find('[data-target="end"]').html(artists.firstArtist.end);
        $('body').find('#artist1').attr('data-id', artists.firstArtist.id);
        // $('body').find('#artist1').attr('data-score', artists.firstArtist.score);
        // $('body').find('#artist1').attr('data-winrate', artists.firstArtist.winRate || 0);
        
        // $('body').find('#artist2').find('[data-target="title"]').html(artists.secondArtist.name);
        // $('body').find('#artist2').find('[data-target="stage"]').html(artists.secondArtist.stage);
        // $('body').find('#artist2').find('[data-target="day"]').html(artists.secondArtist.day);
        // $('body').find('#artist2').find('[data-target="start"]').html(artists.secondArtist.start);
        // $('body').find('#artist2').find('[data-target="end"]').html(artists.secondArtist.end);
        $('body').find('#artist2').attr('data-id', artists.secondArtist.id);
        // $('body').find('#artist2').attr('data-score', artists.secondArtist.score);
        // $('body').find('#artist2').attr('data-winrate', artists.secondArtist.winRate || 0);
        
        // $('body').find('[data-target="total-matches"]').attr('data-totalmatches', artists.total || 0);
        
        $('body').find('#artist1').find('.card-section').html('<h4 data-target="title" class="text-font">'+artists.firstArtist.name+'</h4><p><small><span data-target="stage">'+artists.firstArtist.stage+'</span>, <span data-target="day">'+artists.firstArtist.day+'</span>, <span data-target="start">'+artists.firstArtist.start+'</span> - <span data-target="end">'+artists.firstArtist.end+'</span></small></p>');
        $('body').find('#artist2').find('.card-section').html('<h4 data-target="title" class="text-font">'+artists.secondArtist.name+'</h4><p><small><span data-target="stage">'+artists.secondArtist.stage+'</span>, <span data-target="day">'+artists.secondArtist.day+'</span>, <span data-target="start">'+artists.secondArtist.start+'</span> - <span data-target="end">'+artists.secondArtist.end+'</span></small></p>');
        $('body').find('[data-target="total-matches"]').html('<p class="text-font">OR</p>');
        
        url += '?artist1='+artists.firstArtist.id+'&artist2='+artists.secondArtist.id;
        
          history.pushState({
            id: 'home'
          }, 'Glasto Clash', url);
    });
}

function getNewClash() {
  return $.ajax({
    url: "/clash/loadClash",
    method: "GET",
    dataType: "JSON"
  });
}

function sendFeedback() {
	return $.ajax({
		url: "/magenerator/sendFeedback",
		method: "POST",
		dataType: "JSON",
		data: $("#feedback-form").serialize(),
	});
}

function updateClashLink() {
    let artist1 = $('body').find('select[name="artist1"]').val();
    let artist2 = $('body').find('select[name="artist2"]').val();
    let url = "http://www.glastoclash.com";
    
    url += '?artist1='+artist1+'&artist2='+artist2+'&readonly=no';
    
    $('body').find('[data-target="copy-link"]').val(url);
}

// closes the panel on click outside
$(document).mouseup(function (e) {
  var container = $('#contact-panel');
  if (!container.is(e.target) // if the target of the click isn't the container...
  && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
      container.removeClass('is-active');
    }
});