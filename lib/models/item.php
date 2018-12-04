<?php
class Item extends ActiveRecord\Model
{
	public function is_locked() {
		return false;
	}
	public function lock() {
		return true;
	}
	public function unlock() {
		return true;
	}
	public function is_lock_timeout() {
		return false;
	}
}
