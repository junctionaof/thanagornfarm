<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ContentPublish".
 *
 * @property string $contentId
 * @property integer $today
 * @property integer $wap
 * @property integer $rssBreaking
 * @property integer $rssPol
 * @property integer $rssEco
 * @property integer $rssEdu
 * @property integer $rssEnt
 * @property integer $rssLife
 * @property integer $rssMisc
 * @property integer $rssOversea
 * @property integer $rssRegion
 * @property integer $rssSport
 * @property integer $rssTech
 * @property string $tweetContent
 * @property integer $twitterBreaking
 * @property integer $twitterEco
 * @property integer $twitterEdu
 * @property integer $twitterEnt
 * @property integer $twitterLife
 * @property integer $twitterMisc
 * @property integer $twitterOversea
 * @property integer $twitterPol
 * @property integer $twitterRegion
 * @property integer $twitterSport
 * @property integer $twitterTech
 * @property integer $facebook
 * @property integer $published
 * @property string $shortUrl
 * @property integer $showInHome
 */
class ContentPublish extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ContentPublish';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contentId'], 'required'],
            [['contentId', 'today', 'wap', 'rssBreaking', 'rssPol', 'rssEco', 'rssEdu', 'rssEnt', 'rssLife', 'rssMisc', 'rssOversea', 'rssRegion', 'rssSport', 'rssTech', 'twitterBreaking', 'twitterEco', 'twitterEdu', 'twitterEnt', 'twitterLife', 'twitterMisc', 'twitterOversea', 'twitterPol', 'twitterRegion', 'twitterSport', 'twitterTech', 'facebook', 'published', 'showInHome'], 'integer'],
            [['tweetContent'], 'string', 'max' => 140],
            [['shortUrl'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contentId' => 'Content ID',
            'today' => 'Today',
            'wap' => 'Wap',
            'rssBreaking' => 'Rss Breaking',
            'rssPol' => 'Rss Pol',
            'rssEco' => 'Rss Eco',
            'rssEdu' => 'Rss Edu',
            'rssEnt' => 'Rss Ent',
            'rssLife' => 'Rss Life',
            'rssMisc' => 'Rss Misc',
            'rssOversea' => 'Rss Oversea',
            'rssRegion' => 'Rss Region',
            'rssSport' => 'Rss Sport',
            'rssTech' => 'Rss Tech',
            'tweetContent' => 'Tweet Content',
            'twitterBreaking' => 'Twitter Breaking',
            'twitterEco' => 'Twitter Eco',
            'twitterEdu' => 'Twitter Edu',
            'twitterEnt' => 'Twitter Ent',
            'twitterLife' => 'Twitter Life',
            'twitterMisc' => 'Twitter Misc',
            'twitterOversea' => 'Twitter Oversea',
            'twitterPol' => 'Twitter Pol',
            'twitterRegion' => 'Twitter Region',
            'twitterSport' => 'Twitter Sport',
            'twitterTech' => 'Twitter Tech',
            'facebook' => 'Facebook',
            'published' => 'Published',
            'shortUrl' => 'Short Url',
            'showInHome' => 'Show In Home',
        ];
    }
}
