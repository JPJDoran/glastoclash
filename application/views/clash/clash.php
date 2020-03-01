<body class="festival-background">
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v3.3&appId=196134347724758&autoLogAppEvents=1"></script>
	<main class="cell flex-child-grow flex-child-grow grid-x grid-padding-x align-middle" id="main">
		<div class="grid-container">
			<div class="grid-x">
				<div class="cell medium-10 medium-offset-1">
					<div class="grid-y align-middle grid-padding-y">
						<div class="cell">
							<div class="slightly-rounded white-background padding-2 shadow bordered">
								<div class="grid-x grid-padding-x grid-margin-x">
									<div class="cell" id="nav" style="padding-top: 25px">
									    <div class="grid-x align-right">
									        <div class="cell shrink">
                                        	    <a class="button secondary menu-button-item" data-target="home"><small>Home</small></a>
                                        	</div>
                                        	<div class="cell shrink">
                                        	    <a class="button secondary menu-button-item" data-target="rankings"><small>Rankings</small></a>
                                        	</div>
                                        	<div class="cell shrink">
                                        	    <a class="button secondary menu-button-item" data-target="game"><small>Play</small></a>
                                        	</div>
                                        	<div class="cell shrink">
                                        	    <a class="button secondary menu-button-item" data-target="schedule"><small>Schedule</small></a>
                                        	</div>
                                        	<div class="cell shrink">
                                        	    <a class="button secondary menu-button-item" data-target="weather"><small><i class="fas fa-cloud-sun"></i></small></a>
                                        	</div>
									    </div>
									</div>
									<div class="cell">
									    <h1 class="text-center header-font h1" style="margin-top: 15px">Glasto Clash</h1>
									</div>
                                    <div class="cell hide" data-target="weather-container">
                                        <div style="max-height: 300px; overflow: auto;">
                                            <a class="weatherwidget-io" href="https://forecast7.com/en/51d19n2d55/shepton-mallet/" data-label_1="GLASTONBURY FESTIVAL" data-label_2="WEATHER" data-theme="metallic" >GLASTONBURY FESTIVAL WEATHER</a>
                                            <script>
                                            !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='https://weatherwidget.io/js/widget.min.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','weatherwidget-io-js');
                                            </script>
                                        </div>
                                    </div>
									<div class="cell" data-target="content-container">
									    <?= $view ?>
									</div>
									<div class="cell"  style="padding-top: 10px;">
									    <div class="grid-x grid-padding-x">
									        <div class="cell shrink">
									            <div class="fb-share-button" data-href="http://glastoclash.jakedoran.co.uk" data-layout="button_count" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fglastoclash.jakedoran.co.uk%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Share</a></div>
									        </div>
									        <div class="cell shrink">
									            <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-size="large" data-text="Lovin&#39; GlastoClash! Check it out here:" data-url="http://www.glastoclash.com" data-via="GlastoClash" data-hashtags="Glastonbury2019" data-related="GlastoClash" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
									        </div>
									        <div class="cell auto text-right">
                                        	    <a class="button small secondary hide" data-target="skip-clash"><small>Skip</small></a>
                                        	</div>
									    </div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	<div class="contact-panel" id="contact-panel" data-toggler=".is-active">
		<a class="contact-panel-button" data-toggle="contact-panel">Leave feedback</a>
		<form id="feedback-form">
			<div class="row">
				<label>Full name *
					<input type="text" placeholder="Full name" name="name" required>
				</label>
			</div>
			<div class="row">
				<label>Email
					<input type="email" placeholder="Email address" name="email">
				</label>
			</div>
			<div class="row">
				<label>Message *
					<textarea style="resize:none;" placeholder="List your feedback" rows="3" name="feedback"></textarea>
				</label>
			</div>
			<div class="contact-panel-actions">
				<button class="cancel-button" data-toggle="contact-panel">Nevermind</button>
				<input data-trigger="send-feedback" type="submit" class="button submit-button" value="Send" required>
			</div>
		</form>
	</div>
    <div class="footer" style="background-color: #0a0a0a; padding-top: 10px">
    	<div class="grid-x grid-padding-x align-middle">
    		<!--<div class="cell shrink">-->
    		<!--	<img style="height: 35px; width: 15px" src="/httpdocs/images/future-unicorn-logo.png" />-->
    		<!--</div>-->
    		<div class="cell shrink">
    		    <p style="color: white; font-style: italic;"><a href="http://www.futureunicorn.io" target="_blank">A Future Unicorn Site</a></p>
    		</div>
    	</div>
    </div>
</body>

<?php /*<div class="cell">
 <form id="insert-artist">
    <input type="text" name="artist" placeholder="artist" />
    <input type="text" name="stage" placeholder="stage" />
    <input type="text" name="day" placeholder="day" />
    <input type="text" name="start" placeholder="start" />
    <input type="text" name="end" placeholder="end" />
    <button data-trigger="enter-artist">Click</button>
</form> */ ?>