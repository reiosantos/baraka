<?php
/**
 * Created by PhpStorm.
 * User: reiosantos
 * Date: 1/4/18
 * Time: 10:30 PM
 */


class Database
{
	public $artists;
	public $feedback;
	public $songs;

	public function __construct()
	{
		$this->artists = new Artist();
		$this->feedback = new Feedback();
		$this->songs = new Song();

		if ($this->artists==null || $this->feedback == null || $this->songs == null){
			return null;
		}
	}

}