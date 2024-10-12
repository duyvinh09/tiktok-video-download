<?php
function parse_tiktok_post($id_or_url) {
    $start_time = microtime(true);

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
    } else if (strpos($id_or_url, 'vm.tiktok.com') !== false) {
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

    $best_bitrate_info = null;
    $max_resolution = 0;

    foreach ($post_data['video']['bitrateInfo'] as $bitrate_info) {
        $resolution = $bitrate_info['PlayAddr']['Width'] * $bitrate_info['PlayAddr']['Height'];
        if ($resolution > $max_resolution) {
            $max_resolution = $resolution;
            $best_bitrate_info = $bitrate_info;
        }
    }

    $best_playAddr = $best_bitrate_info ? $best_bitrate_info['PlayAddr'] : null;
    $parsed_post_data = [
        'status' => "success",
        'processed_time' => round(microtime(true) - $start_time, 4),
        'data' => [
            'id' => $post_data['id'],
            'region' => $post_data['locationCreated'],
            'title' => $post_data['desc'],
            'cover' => $post_data['video']['cover'],
            'duration' => $post_data['video']['duration'],
            'play' => [
                'DataSize' => $best_playAddr['DataSize'] ?? '',
                'Width' => $best_playAddr['Width'] ?? 0,
                'Height' => $best_playAddr['Height'] ?? 0,
                'Uri' => $best_playAddr['Uri'] ?? '',
                'UrlList' => $best_playAddr['UrlList'] ?? [],
                'UrlKey' => $best_playAddr['UrlKey'] ?? '',
                'FileHash' => $best_playAddr['FileHash'] ?? '',
                'FileCs' => $best_playAddr['FileCs'] ?? '',
            ],
            'music_info' => [
                'id' => $post_data['music']['id'],
                'title' => $post_data['music']['title'],
                'playUrl' => $post_data['music']['playUrl'],
                'cover' => $post_data['music']['coverLarge'],
                'author' => $post_data['music']['authorName'],
                'original' => $post_data['music']['original'],
                'duration' => $post_data['music']['preciseDuration']['preciseDuration'],
            ],
            'create_time' => $post_data['createTime'],
            'stats' => $post_data['stats'],
            'author' => [
                'id' => $post_data['author']['id'],
                'uniqueId' => $post_data['author']['uniqueId'],
                'nickname' => $post_data['author']['nickname'],
                'avatarLarger' => $post_data['author']['avatarLarger'],
                'signature' => $post_data['author']['signature'],
                'verified' => $post_data['author']['verified'],
            ],
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

header('Content-Type: application/json; charset=UTF-8');

$id_or_url = $_GET['url'] ?? '';
if ($id_or_url) {
    echo json_encode(parse_tiktok_post($id_or_url), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} else {
    echo json_encode(['error' => 'Vui lòng cung cấp URL video cần tải bằng cách thêm tham số "?url=" vào URL của trang.'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}
?>
