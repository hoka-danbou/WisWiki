<?php
class LinkMaker
{
    public $input_text;
    public $linked_text;

    private function strip_xss_tags($text) {
        //TODO:aタグのhrefにjavascript:に対応できない
        $text = preg_replace('/<script.*?>.*?<\/script>/mis','',$text);
        $text = preg_replace('/<iframe.*?>.*?<\/iframe>/mis','',$text);
        $text = preg_replace('/<style.*?>.*?<\/style>/mis','',$text);
        return $text;
    }
    //TODO:scriptタグiframeタグが検出できないため使用保留
    //うまくいけば、h2タグのid付け等を一本化できるため検討する
    private function strip_xss_tags_dom($text) {
        //headがないhtmlをDOMDocumentに読み込ませると文字化けする
        //読み込み、書き込み時に再変換を行う
        $text = mb_convert_encoding($text, 'HTML-ENTITIES', 'utf-8');
        @$doc = DOMDocument::loadHTML($text);

        $script_tags = $doc->getElementsByTagName('li')->item(0);
        //$script_tags = $doc->childNodes->item(1);
        foreach($script_tags as $t) {
            $doc->removeChild($t);
        }

        $iframe_tags = $doc->getElementsByTagName('iframe');
        foreach($iframe_tags as $t) {
            $doc->removeChild($t);
        }
        $text = $doc->getElementsByTagName('body')->item(0)->nodeValue;
        return $text;
    }

    private function make_a_tag($name,  $class_name) {
        $class = ' class="'.$class_name.'"';
        $a = '<a href="./'.$name.'"'.$class.'>'
		    . $name
		    . '</a>';
        return $a;
    }

    private function make_h2_id($text) {
        $text = preg_replace_callback('(<h2>(.*?)</h2>)',
            function($m) {
                return '<h2 id="'.$m[1].'">'.$m[1].'</h2>';
            },
            $text
        );
        return $text;
    }

    public function make() {
        $txt = $this->input_text;
        preg_match_all('/\[\[(.+?)\]\]/', $txt, $keywords); 
        foreach($keywords[0] as $keyword) {
            $name = substr($keyword,2,-2);

            $class_name = '';
            if(Item::count(['conditions' => ['name = ?', $name ]]) == 0) {
                $class_name = 'uncreated';
            }
            $txt = str_replace($keyword, $this->make_a_tag($name, $class_name), $txt);
        }
        $txt = $this->make_h2_id($txt);
        $txt = $this->strip_xss_tags($txt);
        return $txt;
    }

    public static function load($str) {
        $obj = new LinkMaker();
        $obj->input_text = $str;
        return $obj;
    }

    public static function conversion($str) {
        $obj = new LinkMaker();
        $obj->input_text = $str;
        return $obj->make();
    }
}
