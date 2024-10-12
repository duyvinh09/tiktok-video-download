# Công cụ lấy thông tin video TikTok

Đây là script PHP giúp bạn lấy thông tin metadata của video TikTok bằng cách sử dụng URL đầy đủ của video hoặc URL rút gọn (ví dụ: `https://vt.tiktok.com/... , https://vm.tiktok.com/...`). Script này sẽ lấy thông tin video như ID, vùng, tiêu đề, thông tin tác giả, và nhiều hơn nữa.

## Tính năng

- Lấy metadata video TikTok từ URL đầy đủ hoặc URL rút gọn.
- Lấy thông tin chi tiết như tiêu đề video, thời lượng, thông tin tác giả, dữ liệu âm nhạc và thống kê video.
- Tự động xử lý việc chuyển hướng URL cho các URL rút gọn của TikTok.

## Yêu cầu

- PHP 7.x hoặc cao hơn
- cURL phải được bật trong môi trường PHP của bạn

## Cài đặt

1. Clone repository này hoặc tải script về.
2. Đảm bảo rằng bạn đã cài đặt PHP và bật cURL trong môi trường của mình.

## Sử dụng

### Chạy Script

Để sử dụng script này, truyền ID video TikTok hoặc URL vào dưới dạng tham số GET.

#### Ví dụ với URL video đầy đủ:

```bash
http://yourdomain.com/tiktok.php?id=https://www.tiktok.com/@username/video/1234567890123456789
```

#### Ví dụ với URL rút gọn:
```bash
http://yourdomain.com/tiktok.php?id=https://vt.tiktok.com/ZS23K2jtk/
```

#### Ví dụ với URL rút gọn:
```bash
http://yourdomain.com/tiktok.php?id=https://vm.tiktok.com/ZS23K2jtk/
```
#### Script sẽ trả về một phản hồi JSON với các thông tin sau:

- `id`: ID của video TikTok
- `region`: Vùng nơi video được tạo
- `title`: Tiêu đề/mô tả của video
- `cover`: URL ảnh bìa của video
- `duration`: Thời lượng video
- `play`: Thông tin của video, bao gồm:
  - `DataSize`: Kích thước của video
  - `Width`: Chiều rộng của video
  - `Height`: Chiều cao của video (cũng có thể gọi là chất lượng video)
  - `Uri`: Mình không rõ cái này
  - `UrlList`: Mảng chứa list URL video không logo
  - `UrlKey`: Mình nghĩ sẽ là từ khoá của url
  - `FileHash`: Mã hash của file
  - `FileCs`: Mình không rõ
- `music_info`: Thông tin về âm nhạc trong video, bao gồm:
  - `id`: ID của bài nhạc
  - `title`: Tiêu đề bài nhạc
  - `playUrl`: URL phát nhạc
  - `cover`: Ảnh bìa của bài nhạc
  - `author`: Tên tác giả của bài nhạc
  - `original`: Bài nhạc gốc hay không
  - `duration`: Thời lượng của bài nhạc
- `create_time`: Thời gian tạo video (dạng timestamp)
- `stats`: Thống kê về video, bao gồm:
  - `diggCount`: Số lượt thích
  - `shareCount`: Số lượt chia sẻ
  - `commentCount`: Số lượt bình luận
- `author`: Thông tin về tác giả, bao gồm:
  - `id`: ID tác giả
  - `uniqueId`: Tên người dùng (username)
  - `nickname`: Biệt danh của tác giả
  - `avatarLarger`: URL ảnh đại diện của tác giả
  - `signature`: Chữ ký (nội dung mô tả) của tác giả
  - `verified`: Trạng thái xác thực của tác giả
- `contents`: Hashtag và nội dung văn bản liên quan đến video

#### Ví dụ phản hồi
```json
{
    "status": "success",
    "processed_time": 0.8217,
    "data": {
        "id": "7422250015885675783",
        "region": "VN",
        "title": "Tối qua tuyệt quá Đà Lạt ơiii #GheQua #PC #Tofu #HuynhCongHieu #TaynguyenSound #Tns4life #honda #hondaunitour2024 #dlu ",
        "cover": "https://p16-sign-sg.tiktokcdn.com/obj/tos-alisg-p-0037/osIAGefnIIeoQiQT2AOALEyCIIjkAAOuADdA8r?lk3s=81f88b70&x-expires=1728900000&x-signature=dxPBI6xbpy0r9xRTczRbrzubpoU%3D&shp=81f88b70&shcp=-",
        "duration": 57,
        "play": {
            "DataSize": "11319848",
            "Width": 1080,
            "Height": 1920,
            "Uri": "v14044g50000cs0i157og65tp1g94gpg",
            "UrlList": [
                "https://v16-webapp-prime.us.tiktok.com/video/tos/alisg/tos-alisg-pve-0037c001/o4ZDPAKQRIYwyBVCsniSgi4QEELGBlhAEr9zy/?a=1988&bti=ODszNWYuMDE6&ch=0&cr=3&dr=0&lr=all&cd=0%7C0%7C0%7C&cv=1&br=3102&bt=1551&cs=2&ds=4&eid=8192&ft=4KJMyMzm8Zmo0RPV1b4jV.dbdpWrKsd.&mime_type=video_mp4&qs=15&rc=OzkzN2U7NDpnZDplZDlmZ0Bpamo8NHc5cjhsdjMzODczNEBeLWE0MWAuX18xNTRfYDExYSNtMWRtMmQ0Li1gLS1kMTFzcw%3D%3D&btag=e00088000&expire=1728750899&l=20241012103402C5807B35B7505C030E9A&ply_type=2&policy=2&signature=16e0d5951d3fd1051f5c78035b8217d6&tk=tt_chain_token",
                "https://v19-webapp-prime.us.tiktok.com/video/tos/alisg/tos-alisg-pve-0037c001/o4ZDPAKQRIYwyBVCsniSgi4QEELGBlhAEr9zy/?a=1988&bti=ODszNWYuMDE6&ch=0&cr=3&dr=0&lr=all&cd=0%7C0%7C0%7C&cv=1&br=3102&bt=1551&cs=2&ds=4&eid=8192&ft=4KJMyMzm8Zmo0RPV1b4jV.dbdpWrKsd.&mime_type=video_mp4&qs=15&rc=OzkzN2U7NDpnZDplZDlmZ0Bpamo8NHc5cjhsdjMzODczNEBeLWE0MWAuX18xNTRfYDExYSNtMWRtMmQ0Li1gLS1kMTFzcw%3D%3D&btag=e00088000&expire=1728750899&l=20241012103402C5807B35B7505C030E9A&ply_type=2&policy=2&signature=16e0d5951d3fd1051f5c78035b8217d6&tk=tt_chain_token",
                "https://www.tiktok.com/aweme/v1/play/?faid=1988&file_id=4d4cc6ba7b874abdb711c84b74d006a8&is_play_url=1&item_id=7422250015885675783&line=0&ply_type=2&signaturev3=dmlkZW9faWQ7ZmlsZV9pZDtpdGVtX2lkLmQzNGY5NTIwMGM4MWJhMWQ3NmJiMTIzM2ZiNDg4NWY3&tk=tt_chain_token&video_id=v14044g50000cs0i157og65tp1g94gpg"
            ],
            "UrlKey": "v14044g50000cs0i157og65tp1g94gpg_bytevc1_1080p_1588639",
            "FileHash": "7a62fc70d0803e1768577cf0e99befb9",
            "FileCs": "c:0-48658-2289"
        },
        "music_info": {
            "id": "6970001961354299394",
            "title": "Ghé Qua",
            "playUrl": "https://sf16-ies-music-sg.tiktokcdn.com/obj/tos-alisg-ve-2774/oYXIah9YILFYr0OAMzlGgrfNAaA1ZvNoQDeeCz",
            "cover": "https://p16-sg.tiktokcdn.com/aweme/720x720/tos-alisg-v-2774/bf5f93196aa647f9b8279e982fbe7b33.jpeg",
            "author": "PC & Dick & tofutns",
            "original": false,
            "duration": 60
        },
        "create_time": "1728127252",
        "stats": {
            "diggCount": 40700,
            "shareCount": 438,
            "commentCount": 226,
            "playCount": 641500,
            "collectCount": "1528"
        },
        "author": {
            "id": "7325337202241127426",
            "uniqueId": "pc.tns",
            "nickname": "PC",
            "avatarLarger": "https://p9-sign-sg.tiktokcdn.com/aweme/1080x1080/tos-alisg-avt-0068/19f61dd6beafca81fe4bae584fe70dec.jpeg?lk3s=a5d48078&nonce=75662&refresh_token=c574c72f9ea24d0c2a622977fbeb1587&x-expires=1728900000&x-signature=fIRPrKh2Z%2BL2SuYtTb4cCMUwjN4%3D&shp=a5d48078&shcp=81f88b70",
            "signature": "PC & Specter - 4GetU (Remake)",
            "verified": false
        },
        "diversificationLabels": [
            "Music",
            "Entertainment"
        ],
        "suggestedWords": [],
        "contents": [
            {
                "textExtra": []
            },
            {
                "textExtra": [
                    {
                        "hashtagName": "ghequa"
                    },
                    {
                        "hashtagName": "pc"
                    },
                    {
                        "hashtagName": "tofu"
                    },
                    {
                        "hashtagName": "huynhconghieu"
                    },
                    {
                        "hashtagName": "taynguyensound"
                    },
                    {
                        "hashtagName": "tns4life"
                    },
                    {
                        "hashtagName": "honda"
                    },
                    {
                        "hashtagName": "hondaunitour2024"
                    },
                    {
                        "hashtagName": "dlu"
                    }
                ]
            }
        ]
    }
}
```

### Header
Script này sử dụng các header để giả lập yêu cầu từ trình duyệt thực nhằm tránh các hạn chế:

- `User-Agent`: Giả lập một trình duyệt trên máy tính để vượt qua các hạn chế.
- `Accept`: Yêu cầu các loại nội dung khác nhau như HTML, XML, hình ảnh, v.v.
```php
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36',
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
]);
```
