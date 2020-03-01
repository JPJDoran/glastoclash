<table class="text-font padding-bottom-1">
    <thead>
        <tr>
            <th>Artist</th>
            <th>Stage</th>
            <th>Set</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($day as $set): ?>
            <tr>
                <td><?= $set->name ?></td>
                <td><?= $set->stage ?></td>
                <td><?= $set->start ?> - <?= $set->end ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>