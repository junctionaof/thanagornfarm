<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ContentRef".
 *
 * @property string $contentId
 * @property integer $refType
 * @property string $refId
 * @property integer $relationType
 *
 * @property Content $content
 */
class ContentRef extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ContentRef';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contentId', 'refType', 'refId'], 'required'],
            [['contentId', 'refType', 'refId', 'relationType'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contentId' => 'Content ID',
            'refType' => 'Ref Type',
            'refId' => 'Ref ID',
            'relationType' => 'Relation Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContent()
    {
        return $this->hasOne(Content::className(), ['id' => 'contentId']);
    }
}
