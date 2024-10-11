<?php
function parse_tiktok_post($id_or_url) {
    if (strpos($id_or_url, 'vt.tiktok.com') !== false) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $id_or_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        ]);
        
        curl_exec($ch);
        $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);
    } else {
        $url = "https://www.tiktok.com/@user/video/{$id_or_url}";
    }
    // tương tự với 'vm.tiktok.com'

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) {
        return ['error' => 'Unable to fetch the page'];
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($response);
    $xpath = new DOMXPath($dom);
    $script = $xpath->query("//script[@id='__UNIVERSAL_DATA_FOR_REHYDRATION__']");

    if ($script->length === 0) {
        return ['error' => 'Data not found'];
    }

    $json_data = $script->item(0)->textContent;
    $post_data = json_decode($json_data, true)["__DEFAULT_SCOPE__"]["webapp.video-detail"]["itemInfo"]["itemStruct"];

    $parsed_post_data = [
        'data' => [
            'id' => $post_data['id'],
            'region' => $post_data['locationCreated'],
            'title' => $post_data['desc'],
            'cover' => $post_data['video']['cover'],
            'duration' => $post_data['video']['duration'],
            'play' => $post_data['video']['playAddr'],
            'wmplay' => $post_data['video']['downloadAddr'],
            'ratio' => $post_data['video']['ratio'],
            'bitrate' => $post_data['video']['bitrate'],
            'create_time' => $post_data['createTime'],
            'author' => [
                'id' => $post_data['author']['id'],
                'uniqueId' => $post_data['author']['uniqueId'],
                'nickname' => $post_data['author']['nickname'],
                'avatarLarger' => $post_data['author']['avatarLarger'],
                'signature' => $post_data['author']['signature'],
                'verified' => $post_data['author']['verified'],
            ],
            'music' => [
                'id' => $post_data['music']['id'],
                'title' => $post_data['music']['title'],
                'playUrl' => $post_data['music']['playUrl'],
                'coverLarge' => $post_data['music']['coverLarge'],
                'coverThumb' => $post_data['music']['coverThumb'],
                'authorName' => $post_data['music']['authorName'],
                'original' => $post_data['music']['original'],
                'duration' => $post_data['music']['preciseDuration']['preciseDuration'],
            ],
            'stats' => $post_data['stats'],
            'diversificationLabels' => $post_data['diversificationLabels'],
            'suggestedWords' => $post_data['suggestedWords'],
            'contents' => array_map(function ($content) {
                return [
                    'textExtra' => array_map(function ($textExtra) {
                        return [
                            'hashtagName' => $textExtra['hashtagName']
                        ];
                    }, $content['textExtra'])
                ];
            }, $post_data['contents'])
        ]
    ];

    return $parsed_post_data;
}

header('Content-Type: application/json');
$id_or_url = $_GET['id'] ?? '';
if ($id_or_url) {
    echo json_encode(parse_tiktok_post($id_or_url));
} else {
    echo json_encode(['error' => 'No ID or URL provided']);
}
?>
