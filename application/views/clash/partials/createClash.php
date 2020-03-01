<div class="grid-x grid-padding-x grid-padding-y">   
    <?php if($artistDropdown): ?>
    	<div class="cell text-font">
    	    <p class="text-center lead-text">Create your own clash! <br />Choose two clashing artists to generate a link to share with friends to see which act is more popular!</p>
    	</div>
    	<div class="cell auto text-font text-center">
    	    <?= $artistDropdown ?> 
    	</div>
    	<div class="cell medium-shrink text-center text-font">
            <p>VS</p>
        </div>
    	<div class="cell auto text-font text-center" data-target="clashing-artist">
    	    <?= $artist2Dropdown ?> 
    	</div>
    	<div class="cell text-center">
    	    <div class="input-group">
              <input id="copy" class="input-group-field" type="text" data-target="copy-link">
              <div class="input-group-button">
                <input type="submit" class="button secondary" value="Copy" data-clipboard-target="#copy" id="copy-button">
              </div>
            </div>
    	</div>
    <?php else: ?>
        <div class="cell">
            <div class="callout alert text-center text-font">Feature still in progress. Check back soon!</div>
        </div>
    <?php endif; ?>
</div> 