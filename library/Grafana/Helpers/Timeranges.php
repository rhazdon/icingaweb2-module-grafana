<?php
/**
 * Created by PhpStorm.
 * User: carst
 * Date: 19.02.2018
 * Time: 19:05
 */

namespace Icinga\Module\Grafana\Helpers;

use Icinga\Application\Icinga;


class Timeranges
{
    private $urlparams;
    private $link;
    private $view;

    static $timeRanges = array(
        'Minutes' => array(
            '5m' => '5 minutes',
            '15m' => '15 minutes',
            '30m' => '30 minutes',
            '45m' => '45 minutes'
        ),
        'Hours' => array(
            '1h' => '1 hour',
            '3h' => '3 hours',
            '6h' => '6 hours',
            '8h' => '8 hours',
            '12h' => '12 hours',
            '24h' => '24 hours'
        ),
        'Days' => array(
            '2d' => '2 days',
            '7d' => '7 days',
            '14d' => '14 days',
            '30d' => '30 days',
        ),
        'Months' => array(
            '2M' => '2 month',
            '6M' => '6 months',
            '9M' => '9 months'
        ),
        'Years' => array(
            '1y' => '1 year',
            '2y' => '2 years',
            '3y' => '3 years'
        ),
        'Special' => array(
            '1d/d' => 'Yesterday',
            '2d/d' => 'Day b4 yesterday',
            '1w/w' => 'Previous week',
            '1M/M' => 'Previous month',
            '1Y/Y' => 'Previous Year',
        )
    );

    public function __construct(array $array = array(), $link = "")
    {
        $this->urlparams = $array;
        $this->link = $link;

        $this->view = Icinga::app()->getViewRenderer()->view;
    }

    private function getTimerangeLink($rangeName, $timeRange)
    {
        $this->urlparams['timerange'] = $timeRange;

        return $this->view->qlink(
            $rangeName,
            $this->link,
            $this->urlparams,
            array(
                'class' => 'action-link',
                'data-base-target' => '_self',
                'title' => 'Set timerange for graph(s) to ' . $rangeName
            )
        );
    }

    private function buildTimerangeMenu()
    {
        $clockIcon = $this->view->qlink('', 'dashboard/new-dashlet',
            ['url' => 'grafana/dashboard?' . http_build_query($this->urlparams)],
            ['icon' => 'clock', 'title' => 'Add graph to dashboard']);

        $menu = '<table class="grafana-table"><tr>';
        $menu .= '<td>' . $clockIcon . '</td>';
        foreach (self::$timeRanges as $key => $mainValue) {
            $menu .= '<td><ul class="grafana-menu-navigation"><a class="main" href="#">' . $key . '</a>';
            $counter = 1;
            foreach ($mainValue as $subkey => $value) {
                $menu .= '<li class="grafana-menu-n' . $counter . '">' . $this->getTimerangeLink($value,
                        $subkey) . '</li>';
                $counter++;
            }
            $menu .= '</ul></td>';
        }
        $menu .= '</tr></table>';

        return $menu;
    }

    public function getTimerangeMenu()
    {
        return $this->buildTimerangeMenu();
    }

    public static function getTimeranges()
    {
        return call_user_func_array('array_merge', self::$timeRanges);
    }
}