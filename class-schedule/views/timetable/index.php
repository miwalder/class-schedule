<?php
use yii\helpers\Html;

// NEU: Der offizielle Yii2-Weg, um das Dropdown mit einem Klick-Event auszustatten!
// Das funktioniert auch, wenn HumHub das Dropdown grafisch aufhübscht.
$this->registerJs("
    $('#school-year-dropdown').on('change', function() {
        var targetUrl = $(this).val();
        if (targetUrl) {
            // HumHubs hauseigenen Lade-Befehl nutzen (PJAX), ansonsten normaler Redirect
            if (typeof $.pjax !== 'undefined') {
                $.pjax.reload({container: '#layout-content', url: targetUrl});
            } else {
                window.location.href = targetUrl;
            }
        }
    });
");

$weekdays = [1 => 'Montag', 2 => 'Dienstag', 3 => 'Mittwoch', 4 => 'Donnerstag', 5 => 'Freitag'];
?>

<div class="panel panel-default">
<div class="panel-heading">
        <?php if (!empty($schoolYears)): ?>
            <div class="pull-right" style="display: flex; align-items: center; margin-top: -5px;">
                
                <select id="school-year-dropdown" class="form-control input-sm" style="width: auto; margin-right: 10px;">
                    <?php foreach ($schoolYears as $sy): ?>
                        <?php $url = $space->createUrl('/class-schedule/timetable/index', ['school_year_id' => $sy->id]); ?>
                        <option value="<?= $url ?>" <?= ($sy->id == $selectedSchoolYearId) ? 'selected' : '' ?>>
                            Stundenplan: <?= Html::encode($sy->name) ?> 
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <a href="<?= $space->createUrl('/class-schedule/timetable/config') ?>" class="btn btn-default btn-sm" title="Berechtigungen anpassen">
                    <i class="fa fa-cog"></i>
                </a>
                
            </div>
        <?php endif; ?>

        <strong>Stundenplan</strong> für <?= Html::encode($space->name) ?>
    </div>
    
    <div class="panel-body">
        
        <?php if (empty($schoolYears)): ?>
            <div class="alert alert-danger">
                <strong>Halt!</strong> Es wurden noch keine globalen Schuljahre angelegt. Bitte erstelle zuerst ein Schuljahr in den globalen Einstellungen.
            </div>
        <?php elseif (empty($lessonTimes)): ?>
            <div class="alert alert-warning">
                <strong>Achtung:</strong> Es wurden noch keine globalen Unterrichtszeiten angelegt!
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr class="active">
                            <th>Zeit</th>
                            <?php foreach ($weekdays as $dayName): ?>
                                <th class="text-center"><?= $dayName ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lessonTimes as $time): ?>
                            <tr>
                                <td class="align-middle" style="vertical-align: middle;">
                                    <strong><?= date('H:i', strtotime($time->start_time)) ?> - <?= date('H:i', strtotime($time->end_time)) ?></strong>
                                </td>
                                
                                <?php foreach ($weekdays as $dayNum => $dayName): ?>
                                    <td class="align-middle" style="vertical-align: middle;">
                                        
                                        <?php if (isset($schedule[$time->id][$dayNum])): ?>
                                            <?php $entry = $schedule[$time->id][$dayNum]; ?>
                                            <div style="background-color: <?= Html::encode($entry->color) ?>; color: white; padding: 8px; border-radius: 4px; margin-bottom: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                                                <strong><?= Html::encode($entry->subject) ?></strong>
                                            </div>
                                            <a href="<?= $space->createUrl('/class-schedule/timetable/edit', ['lesson_time_id' => $time->id, 'weekday' => $dayNum, 'school_year_id' => $selectedSchoolYearId]) ?>" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i> Ändern</a>
                                        
                                        <?php else: ?>
                                            <a href="<?= $space->createUrl('/class-schedule/timetable/edit', ['lesson_time_id' => $time->id, 'weekday' => $dayNum, 'school_year_id' => $selectedSchoolYearId]) ?>" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Fach eintragen</a>
                                        <?php endif; ?>

                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>
</div>