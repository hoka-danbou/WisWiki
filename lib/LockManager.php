<?php
class LockManager
{
    /**
     * ロックを確認する。ロックされていないか、同一セッションが
     * ロックしている場合はtrueを返す。
     *
     * @param int item_id 記事ID
     * @param string session セッション識別子
     */
    public static function is_editable($item_id, $session) {
        $locked = self::is_locked($item_id);
        $same_session = self::is_same_session($item_id, $session);
        $ret = false;
        if($locked && !$same_session) {
            $ret = true;
        }
        return $ret;
    }

    /**
     * ロックを確認する。
     *
     * @param int item_id 記事ID
     */
    public static function is_locked($item_id) {
        $entry = EditLocks::find_by_item_id($item_id);
        $ret = false;
        if(!empty($entry)) {
            if(time() - $entry->datetime > 600) {
                self::unlock($item_id);
            }else{
                $ret = true;
            }
        }
        return $ret;
    }

    /**
     * ロックしているセッションと同一セッションであればtrue
     *
     * @param int item_id 記事ID
     * @param int session セッション識別子
     */
    public static function is_same_session($item_id, $session) {
        $entry = EditLocks::find_by_item_id($item_id);
        $ret = false;
        if(!empty($entry)  && $entry->session == $session) {
            $ret = true;
        }
        return $ret;
    }

    /**
     * 対象の記事をロックする
     *
     * @param int item_id 記事ID
     * @param int session セッション識別子
     */
    public static function lock($item_id, $session) {
        $entry = new EditLocks();
        $entry->item_id = $item_id;
        $entry->session = $session;
        $entry->datetime = time();
        $entry->save();
    }

    /**
     * 記事のロックを削除する
     *
     * @param int item_id 記事ID
     */
    public static function unlock($item_id) {
        $entry = EditLocks::find_by_item_id($item_id);
        if(!empty($entry)) {
            $entry->delete();
        }
    }

    /**
     * ロックの時間を今の時刻で更新する
     *
     * @param int item_id 記事ID
     */
    public static function lock_extend($item_id) {
        $entry = EditLocks::find_by_item_id($item_id);
        $entry->datetime = time();
        $entry->save();
    }

    /**
     * ロックされていなければロックし、すでにロック済みであれば
     * 今の時刻で更新する。
     *
     * @param int item_id 記事ID
     */
    public static function lock_update($item_id, $session) {
        $is_locked = self::is_locked($item_id);
        $is_same_session = self::is_same_session($item_id, $session);
        if($is_locked) {
            if($is_same_session) {
                self::lock_extend($item_id);
            }else{
                //他人がロック
                return false;
            }
        }else{
            self::lock($item_id, $session);
        }
    
    }
}
