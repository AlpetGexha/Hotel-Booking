<?php

return [
    /**
     * Defines the timezone for the calendar.
     * Set to null to use the browser's local timezone.
     */
    'timezone' => null,

    /**
     * Defines the initial view when the calendar loads.
     * Available views: dayGridMonth, dayGridWeek, dayGridDay, timeGridWeek, timeGridDay, listWeek
     */
    'defaultView' => 'dayGridMonth',

    /**
     * Whether to display week numbers.
     * See: https://fullcalendar.io/docs/week-numbers
     */
    'weekNumbers' => true,

    /**
     * The height of the calendar.
     * See: https://fullcalendar.io/docs/height
     */
    'height' => '800px',

    /**
     * First day of the week.
     * 0 = Sunday, 1 = Monday, etc
     */
    'firstDay' => 1, // Monday

    /**
     * Date format for the calendar
     */
    'dateFormat' => [
        'month' => 'MMMM YYYY',
        'week' => 'MMM D, YYYY',
        'day' => 'dddd, MMM D, YYYY',
    ],

    /**
     * Localization options for the calendar
     */
    'locale' => 'en',

    /**
     * Customize the navigation buttons
     */
    'headerToolbar' => [
        'left' => 'prev,next today',
        'center' => 'title',
        'right' => 'dayGridMonth,timeGridWeek,timeGridDay',
    ],

    /**
     * Loading event callbacks
     */
    'eventDidMount' => null,

    /**
     * Calendar configuration options
     */
    'plugins' => [
        'dayGrid',
        'timeGrid',
        'interaction',
        'resourceTimeline',
        'multiMonthYear',
    ],

    /**
     * Resort events after dragging to accurately reflect day/time order
     */
    'rerenderEventsOnResize' => true,
];
