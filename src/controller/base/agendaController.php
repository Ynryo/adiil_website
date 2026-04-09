<?php
declare(strict_types=1);

require_once 'src/model/bdd/membre.php';
require_once 'src/model/bdd/evenement.php';

class agenda
{
    private const START_HOUR = 8;
    private const END_HOUR = 21;
    private const SLOT_MINUTES = 30;
    private const ROW_HEIGHT = 26;

    private array $resourceMap = [
        '11A' => 282,
        '11B' => 567,
        '12C' => 861,
        '12D' => 869,
        '21A' => 2667,
        '21B' => 2668,
        '22C' => 3113,
        '22D' => 3115,
        '31A' => 5269,
        '31B' => 5419,
        '32C' => 6239,
        '32D' => 6241,
    ];

    public function show()
    {
        $tp_user = getMembre($_SESSION['userid'])[0]['tp_membre'] ?? null;
        if ($tp_user === null || !isset($this->resourceMap[$tp_user])) {
            http_response_code(400);
            die('TP inconnu.');
        }

        $weekAnchor = $this->getWeekAnchorFromRequest();
        $weekStart = $this->weekStartMonday($weekAnchor);
        $weekEnd = (clone $weekStart)->modify('+6 days')->setTime(23, 59, 59);

        $url = $this->buildPlanningUrl((int) $this->resourceMap[$tp_user]);
        $ics = @file_get_contents($url);
        if ($ics === false) {
            http_response_code(500);
            die('Impossible de récupérer le calendrier.');
        }

        $events = $this->getWeekEvents($ics, $weekStart, $weekEnd);
        $events = array_merge($events, $this->getManualEventsForWeek($weekStart, $weekEnd));
        usort($events, static fn(array $a, array $b): int => $a['start'] <=> $b['start']);

        $days = $this->buildDays($weekStart, $events);
        $now = new DateTime('now', new DateTimeZone('Europe/Paris'));

        $startHour = self::START_HOUR;
        $endHour = self::END_HOUR;
        $slotMinutes = self::SLOT_MINUTES;
        $slotsPerHour = intdiv(60, $slotMinutes);
        $totalSlots = ($endHour - $startHour) * $slotsPerHour;
        $totalGridHeight = $totalSlots * self::ROW_HEIGHT;

        $weekLabel = $this->shortWeekRange($weekStart, $weekEnd);
        $weekValue = $this->formatIsoWeekInputValue($weekStart);
        $prevWeekValue = $this->formatIsoWeekInputValue((clone $weekStart)->modify('-7 days'));
        $nextWeekValue = $this->formatIsoWeekInputValue((clone $weekStart)->modify('+7 days'));

        include 'src/view/base/agendaView.php';
    }

    private function buildPlanningUrl(int $resourceId): string
    {
        return 'https://planning.univ-lemans.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources='
            . $resourceId
            . '&projectId=8&calType=ical&nbWeeks=44';
    }

    private function getWeekAnchorFromRequest(): DateTime
    {
        $tz = new DateTimeZone('Europe/Paris');
        $week = $_GET['week'] ?? null;

        if (is_string($week) && preg_match('/^(\d{4})-W(\d{2})$/', $week, $matches)) {
            $year = (int) $matches[1];
            $isoWeek = (int) $matches[2];

            $date = new DateTime('now', $tz);
            $date->setISODate($year, $isoWeek, 1);
            $date->setTime(0, 0, 0);
            return $date;
        }

        if (!empty($_GET['date']) && is_string($_GET['date'])) {
            return new DateTime($_GET['date'], $tz);
        }

        return new DateTime('now', $tz);
    }

    private function getWeekEvents(string $ics, DateTime $weekStart, DateTime $weekEnd)
    {
        $events = [];

        foreach ($this->parseEventsFromIcs($ics) as $event) {
            if (empty($event['DTSTART'])) {
                continue;
            }

            $start = $this->parseIcsDate($event['DTSTART']);
            $end = !empty($event['DTEND'])
                ? $this->parseIcsDate($event['DTEND'])
                : (clone $start)->modify('+1 hour');

            if (!$this->eventIntersectsWeek($start, $end, $weekStart, $weekEnd)) {
                continue;
            }

            $events[] = $this->formatAgendaEvent(
                $start,
                $end,
                trim((string) ($event['SUMMARY'] ?? 'Sans titre')),
                trim((string) ($event['LOCATION'] ?? ''))
            );
        }

        return $events;
    }

    private function getManualEventsForWeek(DateTime $weekStart, DateTime $weekEnd)
    {
        $events = [];

        $evList = eventWhereMembreIsSubscribed($_SESSION['userid']);
        foreach ($evList as $ev) {
            $id_ev = $ev["id_evenement"];
            $event = getEvenement($id_ev);

            $this->addEvent(
                $events,
                $event["date_debut_evenement"],
                $event["date_fin_evenement"],
                $event["nom_evenement"],
                $event["lieu_evenement"]
            );
        }

        return array_values(array_filter(
            $events,
            fn(array $event): bool => $this->eventIntersectsWeek($event['start'], $event['end'], $weekStart, $weekEnd)
        ));
    }

    private function addEvent(array &$events, string $start, string $end, string $summary, string $location = '', ?string $bg = null, ?string $fg = null): void
    {
        $startDate = new DateTime($start, new DateTimeZone('Europe/Paris'));
        $endDate = new DateTime($end, new DateTimeZone('Europe/Paris'));

        $events[] = $this->formatAgendaEvent(
            $startDate,
            $endDate,
            $summary,
            $location,
            $bg,
            $fg
        );
    }

    private function formatAgendaEvent(DateTime $start, DateTime $end, string $summary, string $location = '', ?string $bg = null, ?string $fg = null): array
    {
        $bg ??= $this->getColor($summary);
        $fg ??= $this->textColorForBg($bg);

        return [
            'start' => $start,
            'end' => $end,
            'summary' => $summary,
            'location' => $location,
            'bg' => $bg,
            'fg' => $fg,
        ];
    }

    private function buildDays(DateTime $weekStart, array $events): array
    {
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $day = (clone $weekStart)->modify('+' . $i . ' days');
            $days[$day->format('Y-m-d')] = [
                'date' => $day,
                'events' => [],
            ];
        }

        foreach ($events as $event) {
            $dayKey = $event['start']->format('Y-m-d');
            if (isset($days[$dayKey])) {
                $days[$dayKey]['events'][] = $event;
                continue;
            }

            $mondayKey = $weekStart->format('Y-m-d');
            if ($event['start'] < $weekStart && isset($days[$mondayKey])) {
                $days[$mondayKey]['events'][] = $event;
            }
        }

        foreach ($days as &$day) {
            usort($day['events'], static fn(array $a, array $b): int => $a['start'] <=> $b['start']);
        }
        unset($day);

        return $days;
    }

    private function eventIntersectsWeek(DateTime $start, DateTime $end, DateTime $weekStart, DateTime $weekEnd): bool
    {
        return $end >= $weekStart && $start <= $weekEnd;
    }

    private function unfoldIcsLines(string $ics): array
    {
        $lines = preg_split('/\r\n|\n|\r/', $ics) ?: [];
        $result = [];

        foreach ($lines as $line) {
            if (!empty($result) && (str_starts_with($line, ' ') || str_starts_with($line, "\t"))) {
                $result[count($result) - 1] .= substr($line, 1);
                continue;
            }

            $result[] = $line;
        }

        return $result;
    }

    private function parseEventsFromIcs(string $ics): array
    {
        $events = [];
        $current = null;
        $inEvent = false;

        foreach ($this->unfoldIcsLines($ics) as $line) {
            if ($line === 'BEGIN:VEVENT') {
                $inEvent = true;
                $current = [];
                continue;
            }

            if ($line === 'END:VEVENT') {
                $inEvent = false;
                if ($current) {
                    $events[] = $current;
                }
                $current = null;
                continue;
            }

            if ($inEvent && str_contains($line, ':')) {
                [$key, $value] = explode(':', $line, 2);
                $key = explode(';', $key)[0];
                $current[$key] = $value;
            }
        }

        usort($events, static fn(array $a, array $b): int => strcmp($a['DTSTART'] ?? '', $b['DTSTART'] ?? ''));
        return $events;
    }

    private function parseIcsDate(string $value): DateTime
    {
        $value = trim($value);
        $tzParis = new DateTimeZone('Europe/Paris');

        if (str_ends_with($value, 'Z')) {
            $dt = DateTime::createFromFormat('Ymd\THis\Z', $value, new DateTimeZone('UTC'));
            if ($dt instanceof DateTime) {
                $dt->setTimezone($tzParis);
                return $dt;
            }
        }

        $dt = DateTime::createFromFormat('Ymd\THis', $value, $tzParis);
        if ($dt instanceof DateTime) {
            return $dt;
        }

        return new DateTime($value, $tzParis);
    }

    private function weekStartMonday(DateTime $date): DateTime
    {
        $clone = clone $date;
        $dow = (int) $clone->format('N');
        if ($dow > 1) {
            $clone->modify('-' . ($dow - 1) . ' days');
        }
        $clone->setTime(0, 0, 0);
        return $clone;
    }

    private function formatIsoWeekInputValue(DateTime $date): string
    {
        return $date->format('o') . '-W' . $date->format('W');
    }

    private function getColor(string $cours): string
    {
        $palette = ['#c49cff', '#e1de00', '#ff6a92', '#64bff0', '#5ff0c2', '#d9fbff', '#d9ff2c', '#ffb516', '#b8f3ff'];
        $hash = abs(crc32($cours));
        return $palette[$hash % count($palette)];
    }

    private function textColorForBg(string $hex): string
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
        return $yiq >= 150 ? '#000000' : '#111111';
    }

    public function escape(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    public function dayLabelFrench(DateTime $date): string
    {
        static $days = [
        1 => 'Lundi',
        2 => 'Mardi',
        3 => 'Mercredi',
        4 => 'Jeudi',
        5 => 'Vendredi',
        6 => 'Samedi',
        7 => 'Dimanche',
        ];

        return $days[(int) $date->format('N')] . ' ' . $date->format('d/m/Y');
    }

    public function shortWeekRange(DateTime $monday, DateTime $sunday): string
    {
        static $months = [
        1 => 'janv.',
        2 => 'févr.',
        3 => 'mars',
        4 => 'avr.',
        5 => 'mai',
        6 => 'juin',
        7 => 'juil.',
        8 => 'août',
        9 => 'sept.',
        10 => 'oct.',
        11 => 'nov.',
        12 => 'déc.',
        ];

        return $monday->format('d') . ' - ' . $sunday->format('d') . ' ' . $months[(int) $sunday->format('n')] . ' ' . $sunday->format('y');
    }
}
