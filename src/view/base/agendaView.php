<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title>Agenda</title>

    <link rel="stylesheet" href="assets/css/base/general_style.css">
    <link rel="stylesheet" href="assets/css/base/planner_style.css">
    <link rel="stylesheet" href="assets/css/base/agenda_style.css">
</head>

<body class="body_margin">
    <?php require_once 'src/view/header.php'; ?>

    <h1>Agenda <?= $this->escape((string) $tp_user) ?></h1>

    <div class="titlebar">
        <a class="agenda-link" href="?page=base-agenda&week=<?= $this->escape($prevWeekValue) ?>">← semaine précédente</a>
        <?= $this->escape($this->shortWeekRange($weekStart, $weekEnd)) ?>
        <a class="agenda-link" href="?page=base-agenda&week=<?= $this->escape($nextWeekValue) ?>">semaine suivante →</a>
    </div>

    <div class="planner-wrap">
        <table class="planner" aria-label="Emploi du temps">
            <thead>
                <tr>
                    <th class="corner"></th>
                    <?php foreach ($days as $dayData): ?>
                        <?php
                            $date = $dayData['date'];
                            $isToday = $date->format('Y-m-d') === $now->format('Y-m-d');
                        ?>
                        <th class="day-head <?= $isToday ? 'today' : '' ?>">
                            <?= $this->escape($this->dayLabelFrench($date)) ?>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="hours-col">
                        <?php for ($hour = $startHour; $hour <= $endHour; $hour++): ?>
                            <div class="hours-cell"><?= sprintf('%02d:00', $hour) ?></div>
                            <?php if ($hour < $endHour): ?>
                                <div class="hours-cell"><?= sprintf('%02d:30', $hour) ?></div>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </td>

                    <?php foreach ($days as $dayKey => $dayData): ?>
                        <td>
                            <div class="day-cell" style="height: <?= (int) $totalGridHeight ?>px;">
                                <?php for ($slot = 0; $slot <= $totalSlots; $slot++): ?>
                                    <?php $top = $slot * 26; ?>
                                    <div class="hour-line" style="top: <?= (int) $top ?>px;"></div>
                                <?php endfor; ?>

                                <?php foreach ($dayData['events'] as $event): ?>
                                    <?php
                                        $startMin = ((int) $event['start']->format('H') * 60) + (int) $event['start']->format('i');
                                        $endMin = ((int) $event['end']->format('H') * 60) + (int) $event['end']->format('i');

                                        $visibleStart = max($startMin, $startHour * 60);
                                        $visibleEnd = min($endMin, $endHour * 60);

                                        if ($visibleEnd <= $visibleStart) {
                                            continue;
                                        }

                                        $top = (($visibleStart - ($startHour * 60)) / 30) * 26;
                                        $height = max((($visibleEnd - $visibleStart) / 30) * 26, 30);
                                    ?>
                                    <div
                                        class="event"
                                        style="top: <?= (int) $top ?>px; height: <?= (int) $height ?>px; background: <?= $this->escape($event['bg']) ?>; color: <?= $this->escape($event['fg']) ?>;"
                                    >
                                        <div class="summary"><?= $this->escape($event['summary']) ?></div>

                                        <?php if ($event['location'] !== ''): ?>
                                            <div class="location"><?= $this->escape($event['location']) ?></div>
                                        <?php endif; ?>

                                        <div class="time">
                                            <?= $this->escape($event['start']->format('H:i')) ?> - <?= $this->escape($event['end']->format('H:i')) ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>
    </div>

    <?php require_once 'src/view/footer.php'; ?>
</body>
</html>
