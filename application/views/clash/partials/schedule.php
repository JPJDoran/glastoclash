<div class="grid-x grid-padding-x">
    <div class="cell">
        <p class="text-center text-font lead-text">Glastonbury Planner</p>
    </div>
    <div class="cell">
        <?php if($schedule): ?>
            <ul class="tabs" data-tabs id="schedule-tabs">
				<li class="tabs-title is-active"><a href="#thursdaySchedule" aria-selected="true">Thursday</a></li>
				<li class="tabs-title"><a href="#fridaySchedule">Friday</a></li>
				<li class="tabs-title"><a href="#saturdaySchedule">Saturday</a></li>
				<li class="tabs-title"><a href="#sundaySchedule">Sunday</a></li>
			</ul>
			<div class="tabs-content" data-tabs-content="schedule-tabs">
  				<div class="tabs-panel is-active" id="thursdaySchedule" style="max-height: 300px; overflow: auto;">
  				    <?php $data['day'] = $schedule['Thursday'] ?>
  					<?php $this->load->view('clash/partials/scheduleTable', $data) ?>
  				</div>
  				<div class="tabs-panel" id="fridaySchedule" style="max-height: 300px; overflow: auto;">
					<?php $data['day'] = $schedule['Friday'] ?>
  					<?php $this->load->view('clash/partials/scheduleTable', $data) ?>
				</div>
				<div class="tabs-panel" id="saturdaySchedule" style="max-height: 300px; overflow: auto;">
					<?php $data['day'] = $schedule['Saturday'] ?>
  					<?php $this->load->view('clash/partials/scheduleTable', $data) ?>
				</div>
				<div class="tabs-panel" id="sundaySchedule" style="max-height: 300px; overflow: auto;">
					<?php $data['day'] = $schedule['Sunday'] ?>
  					<?php $this->load->view('clash/partials/scheduleTable', $data) ?>
				</div>
			</div>
        <?php else: ?>
            <div class="callout secondary">No data to show currently. Please check back later.</div>
        <?php endif; ?>
    </div>
</div>