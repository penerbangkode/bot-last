<?php
/**
 * Copyright 2020 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at=>
 *
 *   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

namespace LINE\Tests\LINEBot;

use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\QuickReplyBuilder\ButtonBuilder\QuickReplyButtonBuilder;
use LINE\LINEBot\QuickReplyBuilder\QuickReplyMessageBuilder;
use LINE\LINEBot\SenderBuilder\SenderMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraRollTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use PHPUnit\Framework\TestCase;

class SenderTest extends TestCase
{
    public function testTextMessageWithSender()
    {
        $sender = new SenderMessageBuilder("test1", "https://example.com/test2");

        $messageTemplate = new TextMessageBuilder('test text1', 'test text2', $sender);

        $this->assertEquals(
            [
                [
                    'type' => 'text',
                    'text' => 'test text1',
                    'sender' => [
                        'name' => 'test1',
                        'iconUrl' => 'https://example.com/test2',
                    ],
                ],
                [
                    'type' => 'text',
                    'text' => 'test text2',
                    'sender' => [
                        'name' => 'test1',
                        'iconUrl' => 'https://example.com/test2',
                    ],
                ],
            ],
            $messageTemplate->buildMessage()
        );
    }

    public function testStickerMessageWithSender()
    {
        $sender = new SenderMessageBuilder(null, "https://example.com/test2");

        $messageTemplate = new StickerMessageBuilder('1', '1', null, $sender);

        $this->assertEquals(
            [
                [
                    "type" => "sticker",
                    "packageId" => "1",
                    "stickerId" => "1",
                    'sender' => [
                        'iconUrl' => 'https://example.com/test2',
                    ],
                ],
            ],
            $messageTemplate->buildMessage()
        );
    }

    public function testImageMessageWithSender()
    {
        $sender = new SenderMessageBuilder("test1");

        $messageTemplate = new ImageMessageBuilder(
            'https://example.com/original.jpg',
            'https://example.com/preview.jpg',
            null,
            $sender
        );

        $this->assertEquals(
            [
                [
                    "type" => "image",
                    "originalContentUrl" => "https://example.com/original.jpg",
                    "previewImageUrl" => "https://example.com/preview.jpg",
                    'sender' => [
                        'name' => "test1"
                    ],
                ],
            ],
            $messageTemplate->buildMessage()
        );
    }

    public function testVideoMessageWithSender()
    {
        $sender = new SenderMessageBuilder("test1", "https://example.com/test2");

        $messageTemplate = new VideoMessageBuilder(
            'https://example.com/original.mp4',
            'https://example.com/preview.jpg',
            null,
            $sender
        );

        $this->assertEquals(
            [
                [
                    "type" => "video",
                    "originalContentUrl" => "https://example.com/original.mp4",
                    "previewImageUrl" => "https://example.com/preview.jpg",
                    'sender' => [
                        'name' => 'test1',
                        'iconUrl' => 'https://example.com/test2',
                    ],
                ],
            ],
            $messageTemplate->buildMessage()
        );
    }

    public function testAudioMessageWithSender()
    {
        $sender = new SenderMessageBuilder("test1", "https://example.com/test2");

        $messageTemplate = new AudioMessageBuilder('https://example.com/original.m4a', '60000', null, $sender);

        $this->assertEquals(
            [
                [
                    "type" => "audio",
                    "originalContentUrl" => "https://example.com/original.m4a",
                    "duration" => 60000,
                    'sender' => [
                        'name' => 'test1',
                        'iconUrl' => 'https://example.com/test2',
                    ],
                ],
            ],
            $messageTemplate->buildMessage()
        );
    }

    public function testLocationMessageWithSender()
    {
        $sender = new SenderMessageBuilder("test1", "https://example.com/test2");

        $messageTemplate = new LocationMessageBuilder(
            'my location',
            '〒150-0002 東京都渋谷区渋谷２丁目２１−１',
            35.65910807942215,
            139.70372892916203,
            null,
            $sender
        );

        $this->assertEquals(
            [
                [
                    "type" => "location",
                    "title" => "my location",
                    "address" => "〒150-0002 東京都渋谷区渋谷２丁目２１−１",
                    "latitude" => 35.65910807942215,
                    "longitude" => 139.70372892916203,
                    'sender' => [
                        'name' => 'test1',
                        'iconUrl' => 'https://example.com/test2',
                    ],
                ],
            ],
            $messageTemplate->buildMessage()
        );
    }

    public function testImagemapMessageWithSender()
    {
        $sender = new SenderMessageBuilder("test1", "https://example.com/test2");

        $messageTemplate = new ImagemapMessageBuilder(
            'https://example.com/bot/images/rm001',
            'This is an imagemap',
            new BaseSizeBuilder(1040, 1040),
            [
                new ImagemapUriActionBuilder('https://example.com/', new AreaBuilder(0, 0, 520, 1040)),
                new ImagemapMessageActionBuilder('Hello', new AreaBuilder(520, 0, 520, 1040)),
            ],
            null,
            null,
            $sender
        );

        $this->assertEquals(
            [
                [
                    "type" => "imagemap",
                    "baseUrl" => "https://example.com/bot/images/rm001",
                    "altText" => "This is an imagemap",
                    "baseSize" => [
                        "height" => 1040,
                        "width" => 1040,
                    ],
                    "actions" => [
                        [
                            "type" => "uri",
                            "linkUri" => "https://example.com/",
                            "area" => [
                                "x" => 0,
                                "y" => 0,
                                "width" => 520,
                                "height" => 1040,
                            ],
                        ],
                        [
                            "type" => "message",
                            "text" => "Hello",
                            "area" => [
                                "x" => 520,
                                "y" => 0,
                                "width" => 520,
                                "height" => 1040,
                            ],
                        ],
                    ],
                    'sender' => [
                        'name' => 'test1',
                        'iconUrl' => 'https://example.com/test2',
                    ],
                ],
            ],
            $messageTemplate->buildMessage()
        );
    }

    public function testTemplateMessageWithSender()
    {
        $quickReply = new QuickReplyMessageBuilder([
            new QuickReplyButtonBuilder(new CameraTemplateActionBuilder('Camera')),
            new QuickReplyButtonBuilder(new CameraRollTemplateActionBuilder('Camera roll')),
        ]);
        $sender = new SenderMessageBuilder("test1", "https://example.com/test2");

        $messageTemplate = new TemplateMessageBuilder(
            'This is a buttons template',
            new ButtonTemplateBuilder(
                'Menu',
                'Please select',
                'https://example.com/bot/images/image.jpg',
                [
                    new PostbackTemplateActionBuilder('Buy', 'action=buy&itemid=123'),
                    new PostbackTemplateActionBuilder('Add to cart', 'action=add&itemid=123'),
                    new UriTemplateActionBuilder('View detail', 'http://example.com/page/123'),
                ],
                'rectangle',
                'cover',
                '#FFFFFF',
                new UriTemplateActionBuilder('View detail', 'http://example.com/page/123')
            ),
            $quickReply,
            $sender
        );

        $this->assertEquals(
            [
                [
                    "type" => "template",
                    "altText" => "This is a buttons template",
                    "template" => [
                        "type" => "buttons",
                        "thumbnailImageUrl" => "https://example.com/bot/images/image.jpg",
                        "imageAspectRatio" => "rectangle",
                        "imageSize" => "cover",
                        "imageBackgroundColor" => "#FFFFFF",
                        "title" => "Menu",
                        "text" => "Please select",
                        "defaultAction" => [
                            "type" => "uri",
                            "label" => "View detail",
                            "uri" => "http://example.com/page/123",
                        ],
                        "actions" => [
                            [
                                "type" => "postback",
                                "label" => "Buy",
                                "data" => "action=buy&itemid=123",
                            ],
                            [
                                "type" => "postback",
                                "label" => "Add to cart",
                                "data" => "action=add&itemid=123",
                            ],
                            [
                                "type" => "uri",
                                "label" => "View detail",
                                "uri" => "http://example.com/page/123",
                            ],
                        ],
                    ],
                    'quickReply' => [
                        'items' => [
                            [
                                'type' => 'action',
                                'action' => [
                                    "type" => "camera",
                                    "label" => "Camera",
                                ],
                            ],
                            [
                                'type' => 'action',
                                'action' => [
                                    "type" => "cameraRoll",
                                    "label" => "Camera roll",
                                ],
                            ],
                        ],
                    ],
                    'sender' => [
                        'name' => 'test1',
                        'iconUrl' => 'https://example.com/test2',
                    ],
                ],
            ],
            $messageTemplate->buildMessage()
        );
    }
}
