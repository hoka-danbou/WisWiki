<?php
class App
{
    private static function remove_tag_and_path_str($str) {
        $str = str_replace('/','', $str);
        $str = str_replace('.','', $str);
        $str = strip_tags($str);
        return $str;
    }
    public static function get_top_url() {

    }

    public static function redirect($path) {
        $proto = ($_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
        header('Location: '.$proto.'://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI']).$path);
    }

    public static function get_requeset_page_name() {
        $name = isset($_GET['n']) ? $_GET['n'] : MAIN_PAGE;
        return self::remove_tag_and_path_str($name);
    }

    public static function get_request_search_query() {
        $query = $_GET['q'];
        return self::remove_tag_and_path_str($query);
    }

    public static function get_sidebar_content() {
        $item = Item::find_by_name(SIDEBAR_PAGE);
        $text = "";
        if(!empty($item)) {
            $text = $item->text;
        }
        return $text;
    }

}
