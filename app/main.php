#!/usr/bin/php
<?php
    require_once '../conf/conf.php';
    require_once './define.php';
    $rss_url = TARGET_URL;
    $rss = file_get_contents($rss_url);

    if (!is_null($rss))
    {
        $contents_list = [];
        // RSS内容からURLとテキストを抽出する
        $pattern_url = '/rdf:li rdf:resource=\"(.*)\"/';
        if (preg_match_all($pattern_url,$rss,$rss_matches))
        {
            foreach($rss_matches[PREG_MATCH_PART] as $contents_url)
            {
                // URLのアクセス先にあるTITLEを取得してURLとくっつける（雑
                $contents_page = file_get_contents($contents_url);
                $pattern_title = '/\<title\>(.*)\<\/title\>/';
                if (preg_match($pattern_title,$contents_page,$title_matches))
                {
                    $contents_list[] = ['TITLE'=>$title_matches[PREG_MATCH_PART],'URL'=>$contents_url];
                }
            }
        }
        // JSON返す
        $json = json_encode($contents_list,JSON_UNESCAPED_UNICODE,JSON_FORCE_OBJECT);
        return $json;
    }
    else
    {
        exit;
    }
?>