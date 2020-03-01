<div class="grid-x grid-padding-x">
    <div class="cell">
        <p class="text-center text-font lead-text">All Time Leaderboards</p>
    </div>
    <div class="cell" style="max-height: 300px; overflow: auto;">
        <?php if($artists): ?>
            <table class="text-font padding-bottom-1">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Artist</th>
                        <!--<th>Stage</th>-->
                        <!--<th>Day</th>-->
                        <!--<th>Time</th>-->
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($artists as $artist): ?>
                        <tr>
                            <td><?= $artist->position ?></td>
                            <td><?= $artist->name ?></td>
                            <!--<td><?= $artist->stage ?></td>-->
                            <!--<td><?= $artist->day ?></td>-->
                            <!--<td><?= $artist->start ?> - <?= $artist->end ?></td>-->
                            <td><?= $artist->score ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="callout secondary">No data to show currently. Please check back later.</div>
        <?php endif; ?>
    </div>
</div>