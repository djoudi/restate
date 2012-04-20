<?php

/**
 * This is the model class for table "apartment".
 *
 * The followings are the available columns in table 'apartment':
 * @property string $id
 * @property string $name
 * @property string $city_id
 * @property string $area_id
 * @property string $type_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $lng
 * @property string $lat
 * @property int $rating
 * @property int $parent_id
 * @property int $is_special
 * @property int $metro_id
 *
 * The followings are the available model rel   ations:
 * @property ApartmentType $type
 * @property Area $area
 * @property City $city
 * @property ApartmentAttribute[] $apartmentAttributes
 * @property ApartmentFile[] $apartmentFiles
 */
class Apartment extends CActiveRecord
{

    public $files;
    public $fileType = array('jpg', 'jpeg', 'gif', 'png');
    public $fileSize = array(100, 150, 450);

    public $routeable_pattern;
    public $routeable_keywords;
    public $routeable_description;
    public $routeable_title;

    public function toArray()
    {
        $result = array(
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city->name,
            'area' => $this->area->name,
            'type' => $this->type->name,
            'metro' => $this->metro->name,
            'coord' => array(floatval($this->lat), floatval($this->lng)),
            'lat' => floatval($this->lat),
            'lng' => floatval($this->lng),
            'is_special' => $this->is_special,
            'images' => array(),
            'attributes' => array(),
            'link' => Yii::app()->createUrl('apartment/view', array('id' => $this->id)),
        );

        foreach ($this->apartmentFiles as $file) {
            $result['images'][150][] = $file->getFile(150);
            $result['images'][450][] = $file->getFile(450);
        }

        foreach ($this->apartmentAttributes as $apartmentAttribute) {
            $attribute = array(
                'name' => $apartmentAttribute->attribute->name,
                'value' => $apartmentAttribute->value,
            );
            $result['attributes'][] = $attribute;
        }

        return $result;
    }

    public function getTypedApartmentList()
    {
        $cacheDependency = new CDbCacheDependency('SELECT MAX(updated_at) FROM apartment');
        $types = ApartmentType::model()->container()->with('apartments')->cache(3600, $cacheDependency)->findAll();
        $result = array();
        foreach ($types as $type) {
            $result[$type->name] = CHtml::listData($type->apartments, 'id', 'name');
        }
        return $result;
    }

    /**
     * Returns the static model of the specified AR class.
     * @return Apartment the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'apartment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('routeable_pattern', 'required'),
            array('routeable_keywords, routeable_description, routeable_title', 'safe'),
            array('name, city_id, area_id, type_id', 'required'),
            array('name', 'length', 'max' => 255),
            array('city_id, area_id, type_id, metro_id', 'length', 'max' => 10),
            array('created_at, updated_at, lng, lat, rating, address, parent_id, is_special', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, city_id, area_id, type_id, created_at, updated_at', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'type' => array(self::BELONGS_TO, 'ApartmentType', 'type_id'),
            'area' => array(self::BELONGS_TO, 'Area', 'area_id'),
            'city' => array(self::BELONGS_TO, 'City', 'city_id'),
            'metro' => array(self::BELONGS_TO, 'MetroStation', 'metro_id'),
            'apartmentAttributes' => array(self::HAS_MANY, 'ApartmentAttribute', 'apartment_id'),
            'apartmentFiles' => array(self::HAS_MANY, 'ApartmentFile', 'apartment_id'),
            'parent' => array(self::BELONGS_TO, 'Apartment', 'parent_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Объект',
            'city_id' => 'Город',
            'area_id' => 'Район',
            'metro_id' => 'Станция метро',
            'type_id' => 'Тип объекта',
            'address' => 'Адрес',
            'lng' => 'Долгота',
            'lat' => 'Широта',
            'is_special' => 'Специальное предложение?',
            'parent_id' => 'Родительский объект',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',

            'routeable_title' => 'SEO Описание',
            'routeable_keywords' => 'SEO Ключевые слова',
            'routeable_description' => 'SEO Описание',
        );
    }

    /**
     * @return array behaviors.
     */
    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'updateAttribute' => 'updated_at',
                'setUpdateOnCreate' => true
            ),
            'ResourcesBehavior' => array(
                'class' => 'ext.resourcesBehavior.ResourcesBehavior',
                'resourcePath' => Yii::app()->params['uploadDir'],
            ),
            'RoutableBehavior' => array(
                'class' => 'application.components.RouteableBehavior',
                'controller' => 'apartment',
            ),
        );
    }

    protected function beforeValidate()
    {
        $valid = false;
        if (parent::beforeValidate()) {

            // Routes
            if (empty($this->routeable_pattern)) {
                $this->routeable_pattern = '/apartment/' . TextBox::transliteUrl($this->type->name . '_' . $this->name . '_' . $this->address);
            }

            if (empty($this->routeable_title)) {
                $this->routeable_title = $this->type->name . ' - ' . $this->name . ' - ' . $this->address;
            }

            if (empty($this->metro_id)) {
                $this->metro_id = NULL;
            }

            $valid = true;
            foreach ($this->apartmentAttributes as $apartmentAttribute) {
                $valid = $apartmentAttribute->validate(array('value')) && $valid;
            }
        }
        return $valid;
    }


    protected function afterSave()
    {
        foreach ($this->apartmentAttributes as $id => $apartmentAttribute) {
            $apartmentAttribute->attributes = array(
                'apartment_id' => $this->id,
                'attribute_id' => $id
            );
            $apartmentAttribute->save();
        }


        // Uploads
        $files = CUploadedFile::getInstances($this, 'files');
        $hash = $this->generatePathHash();
        if (!empty($files)) {
            foreach ($files as $file) {
                $attachment = new ApartmentFile();
                $meta = Common::processImage($attachment, $file, false, $hash);

                foreach ($this->fileSize as $size) {
                    Common::processImage($attachment, $file, $size, $hash);
                }

                $attributes = array(
                    'apartment_id' => $this->id,
                    'file' => $meta['fileName'],
                );
                $attachment->attributes = $attributes;
                $attachment->save();
            }
        }

        parent::afterSave();
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('city_id', $this->city_id, true);
        $criteria->compare('area_id', $this->area_id, true);
        $criteria->compare('type_id', $this->type_id, true);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}