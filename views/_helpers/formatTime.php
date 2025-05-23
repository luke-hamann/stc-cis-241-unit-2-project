<?php
/**
 * Title: Format Time
 * Purpose: Format a number of seconds as a human-readable string
 */
function formatTime(int $duration, bool $abbreviationMode = TRUE) : string
{
    // Calculate the time components
    $hours = intdiv($duration, 60 * 60);
    $seconds = $duration - ($hours * 60 * 60);
    $minutes = intdiv($seconds, 60);
    $seconds -= $minutes * 60;

    if ($abbreviationMode)
    {
        // Time unit abbreviation mode
        $hours_f = "$hours hr" . ($hours == 1 ? '' : 's');
        $minutes_f = "$minutes min" . ($minutes == 1 ? '' : 's');
        $seconds_f = "$seconds sec" . ($seconds == 1 ? '' : 's');

        if ($duration < 60)
        {
            $output = $seconds_f;
        }
        else if ($duration < 60 * 60)
        {
            $output = $minutes_f . ($seconds > 0 ? " $seconds_f" : '');
        }
        else
        {
            $output = $hours_f . ($minutes > 0 ? " $minutes_f" : '');
        }
    }
    else
    {
        // Colon separator mode
        $seconds_padded = str_pad($seconds, 2, '0', STR_PAD_LEFT);
        $minutes_padded = str_pad($minutes, 2, '0', STR_PAD_LEFT);

        if ($duration < 60 * 60)
        {
            $output = "$minutes:$seconds_padded";
        }
        else
        {
            $output = "$hours:$minutes_padded:$seconds_padded";
        }
    }

    return $output;
}
?>
