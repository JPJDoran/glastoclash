<div class="grid-x grid-padding-x" data-readonly="<?= $readonly ?>">   
    <?php if($artists): ?>
    	<div class="cell text-font" data-target="lead-text">
    	    <?php if(!$readonly || $readonly != 'yes'): ?>
    	        <p class="text-center lead-text">Who would you rather see?</p>
    	    <?php else: ?>
    	        <p class="text-center lead-text">Results for <?= $total ?> votes</p>
    	    <?php endif; ?>
    	</div>
    	<div class="cell text-center">
    	    <div class="grid-x grid-padding-x align-middle">
    	        <div class="cell medium-auto text-font">
                    <div id="artist1" class="card" style="cursor: pointer" data-trigger="artist-vote" data-winrate="<?= $artists['firstArtist']->winRate ?>" data-id="<?= $artists['firstArtist']->id ?>" data-score="<?= $artists['firstArtist']->score ?>">
                        <div class="card-section">
                            <?php if(!$readonly || $readonly != 'yes'): ?>
                                <h4 data-target="title" class="text-font"><?= $artists['firstArtist']->name ?></h4>
                                <p><small><span data-target="stage"><?= $artists['firstArtist']->stage ?></span>, <span data-target="day"><?= $artists['firstArtist']->day ?></span>, <span data-target="start"><?= $artists['firstArtist']->start ?></span> - <span data-target="end"><?= $artists['firstArtist']->end ?></span></small></p>
                            <?php else: ?>
                                <h4 data-target="title" class="text-font"><?= $artists['firstArtist']->name ?></h4>
                                <h4 class="text-font h4"><?= $artists['firstArtist']->winrate ?>%</h4>
                            <?php endif; ?>
                        </div>
                    </div>
    	        </div>
    	        <div class="cell medium-shrink text-font" data-target="total-matches" data-totalmatches="<?= $artists['total'] ?>">
    	            <?php if(!$readonly || $readonly != 'yes'): ?>
            	        <p class="text-center">OR</p>
            	    <?php else: ?>
            	        <p class="text-center">VS</p>
            	    <?php endif; ?>
    	        </div>
    	        <div class="cell medium-auto text-font">
                    <div id="artist2" class="card" style="cursor: pointer" data-trigger="artist-vote" data-winrate="<?= $artists['secondArtist']->winRate ?>" data-id="<?= $artists['secondArtist']->id ?>" data-score="<?= $artists['secondArtist']->score ?>">
                        <div class="card-section">
                           <?php if(!$readonly || $readonly != 'yes'): ?>
                               <h4 data-target="title" class="text-font"><?= $artists['secondArtist']->name ?></h4>
                               <p><small><span data-target="stage"><?= $artists['secondArtist']->stage ?></span>, <span data-target="day"><?= $artists['secondArtist']->day ?></span>, <span data-target="start"><?= $artists['secondArtist']->start ?></span> - <span data-target="end"><?= $artists['secondArtist']->end ?></span></small></p>
                            <?php else: ?>
                                <h4 data-target="title" class="text-font"><?= $artists['secondArtist']->name ?></h4>
                                <h4 class="text-font h4"><?= $artists['secondArtist']->winrate ?>%</h4>
                            <?php endif; ?>
                        </div>
                    </div>
    	        </div>
    	    </div>
    	</div>
    <?php else: ?>
        <div class="cell">
            <div class="callout alert text-center text-font">No clashes to show currently. Check back soon!</div>
        </div>
    <?php endif; ?>
</div> 