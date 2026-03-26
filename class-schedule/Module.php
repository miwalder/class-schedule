<?php

namespace humhub\modules\class_schedule;

use Yii;
use yii\helpers\Url;
use humhub\modules\space\models\Space;
use humhub\modules\content\components\ContentContainerModule; 
// NEU: Diese Klasse brauchen wir für die Container-URL
use humhub\modules\content\components\ContentContainerActiveRecord;

// Das Modul erbt von ContentContainerModule
class Module extends ContentContainerModule 
{
    /**
     * @inheritdoc
     * Dies fügt den "Konfigurieren"-Button im globalen HumHub-Backend (Administration) hinzu.
     */
    public function getConfigUrl()
    {
        return Url::to(['/class-schedule/config']);
    }

    /**
     * @inheritdoc
     */
    public function disable()
    {
        Yii::$app->settings->remove('class-schedule');
        parent::disable();
    }
    
    /**
     * @inheritdoc
     * Sagt HumHub, dass dieses Modul in Spaces aktiviert werden darf!
     */
    public function getContentContainerTypes()
    {
        return [
            Space::class,
        ];
    }

    /**
     * NEU: Sagt HumHub, wo die Einstellungen für dieses Modul im Space liegen.
     * Das erzeugt automatisch den türkisen "Konfigurieren" Button in den Space-Modulen!
     */
    public function getContentContainerConfigUrl(ContentContainerActiveRecord $container)
    {
        return $container->createUrl('/class-schedule/timetable/index');
    }
}