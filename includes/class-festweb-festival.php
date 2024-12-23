<?php

class Festweb_Festival
{

    public $id = '0';
    public $slug = '';
    public $name = '';
    public $description = '';
    public $start_date = '';
    public $end_date = '';
    public $sequence = '';
    public $cover_id = '0';
    public $current = FALSE;
    public $history = TRUE;
    public $show_schedule = FALSE;

    public $artists = array();

    public function __construct( $db_row = NULL )
    {
        if ( $db_row != NULL ) {
            $this->id = $db_row->id;
            $this->slug = $db_row->slug;
            $this->name = $db_row->name;
            $this->description = $db_row->description;
            $this->start_date = $db_row->startdate;
            $this->end_date = $db_row->enddate;
            $this->sequence = $db_row->sequence;
            $this->cover_id = $db_row->cover_id;
            $this->current = $db_row->current;
            $this->history = $db_row->history;
            $this->show_schedule = $db_row->show_schedule;
        }
    }
}