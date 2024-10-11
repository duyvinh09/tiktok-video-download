# Công cụ lấy thông tin video TikTok

Đây là script PHP giúp bạn lấy thông tin metadata của video TikTok bằng cách sử dụng URL đầy đủ của video hoặc URL rút gọn (ví dụ: `https://vt.tiktok.com/...`). Script này sẽ lấy thông tin video như ID, vùng, tiêu đề, thông tin tác giả, và nhiều hơn nữa.

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
http://yourdomain.com/parse_tiktok.php?id=https://www.tiktok.com/@username/video/1234567890123456789
```

#### Ví dụ với URL rút gọn:
```bash
http://yourdomain.com/parse_tiktok.php?id=https://vt.tiktok.com/ZS23K2jtk/
```
#### Script sẽ trả về một phản hồi JSON với các thông tin sau:

- `id`: ID của video TikTok
- `region`: Vùng nơi video được tạo
- `title`: Tiêu đề/mô tả của video
- `cover`: URL ảnh bìa của video
- `duration`: Thời lượng video
- `play`: URL trực tiếp của video
- `wmplay`: URL video có watermark
- `ratio`: Tỷ lệ khung hình của video
- `bitrate`: Bitrate của video
- `create_time`: Thời gian tạo video (dạng timestamp)
- `author`: Thông tin về tác giả, bao gồm:
  - `id`: ID tác giả
  - `uniqueId`: Tên người dùng (username)
  - `nickname`: Biệt danh của tác giả
  - `avatarLarger`: URL ảnh đại diện của tác giả
  - `signature`: Chữ ký (nội dung mô tả) của tác giả
  - `verified`: Trạng thái xác thực của tác giả
- `music`: Thông tin về âm nhạc trong video, bao gồm:
  - `id`: ID của bài nhạc
  - `title`: Tiêu đề bài nhạc
  - `playUrl`: URL phát nhạc
  - `coverLarge`: Ảnh bìa của bài nhạc
  - `authorName`: Tên tác giả của bài nhạc
  - `original`: Bài nhạc gốc hay không
  - `duration`: Thời lượng của bài nhạc
- `stats`: Thống kê về video, bao gồm:
  - `diggCount`: Số lượt thích
  - `shareCount`: Số lượt chia sẻ
  - `commentCount`: Số lượt bình luận
- `contents`: Hashtag và nội dung văn bản liên quan đến video

#### Ví dụ phản hồi
```json
{
    "data": {
        "id": "7422520466969005330",
        "region": "US",
        "title": "Check out my latest video!",
        "cover": "https://p16-sign-va.tiktokcdn.com/tos-maliva-p-0068/...",
        "duration": 15,
        "play": "https://v16-webapp.tiktok.com/video/...",
        "wmplay": "https://v16-webapp.tiktok.com/video/...wm",
        "ratio": "720x1280",
        "bitrate": 128000,
        "create_time": 1609459200,
        "author": {
            "id": "123456789",
            "uniqueId": "user123",
            "nickname": "User 123",
            "avatarLarger": "https://p16-sign-va.tiktokcdn.com/tos-maliva-p-0068/...",
            "signature": "Follow me for more updates!",
            "verified": true
        },
        "music": {
            "id": "654321",
            "title": "Cool Song",
            "playUrl": "https://sf-tk-sg.snssdk.com/...",
            "coverLarge": "https://p16-sign-va.tiktokcdn.com/tos-maliva-p-0068/...",
            "authorName": "Artist",
            "original": true,
            "duration": 15
        },
        "stats": {
            "diggCount": 12000,
            "shareCount": 150,
            "commentCount": 300
        },
        "contents": [
            {
                "textExtra": [
                    {
                        "hashtagName": "example"
                    }
                ]
            }
        ]
    }
}
```

### Header
Script này sử dụng các header để giả lập yêu cầu từ trình duyệt thực nhằm tránh các hạn chế:

User-Agent: Giả lập một trình duyệt trên máy tính để vượt qua các hạn chế.
Accept: Yêu cầu các loại nội dung khác nhau như HTML, XML, hình ảnh, v.v.
```php
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36',
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
]);
```
