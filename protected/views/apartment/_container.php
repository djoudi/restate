<?php
/**
 * @author Denis A Boldinov
 *
 * @copyright
 * Copyright (c) 2012 Denis A Boldinov
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 * and associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or substantial
 * portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT
 * LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
 * NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 */

?>

<div class="space small"></div>

<div class="grid_12 alpha omega">

    <div class="grid_8 alpha">
        <h2>Фотогалерея</h2>

        <div class="gallery" style="height: 400px;">
            <?php foreach ($apartmentFiles as $apartmentFile) : ?>
            <a href="<?php echo $apartmentFile->getFile(450) ?>">
                <img src="<?php echo $apartmentFile->getFile(450) ?>">
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="grid_4 omega">

        <div class="prepend_left small">

            <h2>Параметры объекта</h2>

            <div class="attributes">

                <ul class="attributes_list">
                    <li class="icon-metro"><span>Метро: </span><?php echo $model->metroName ?></li>
                    <?php foreach ($apartmentAttributes as $apartmentAttribute): ?>
                    <?php if (!empty($apartmentAttribute->value)): ?>
                        <li>
                            <span><?php echo $apartmentAttribute->attribute->name ?></span>: <?php echo $apartmentAttribute->value ?>
                        </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="grid_12 alpha omega">

    <div class="grid_6 alpha">
        <h2>Связаться с нами</h2>

        <?php $this->renderPartial('/site/contact', array('model' => $contactForm)) ?>

    </div>

    <div class="grid_6 omega">

        <h2>Описание</h2>

        <div class="description">

            <?php echo $model->description ?>

            <br/>

            <h2><?php echo (!empty($model->parent_id) ? $model->parentName . ', ' : '') ?><?php echo CHtml::encode($model->address) ?>
                на карте</h2>

            <div id="map" style="position:relative;margin:25px 0;width:100%;height:279px;"></div>

        </div>

    </div>
</div>


<!--

<div class="grid_12 alpha omega">

    <div class="grid_12 alpha omega">

        <div class="grid_9 alpha">
            <div class="prepend_left">

                <h5>Информация о квартире</h5>

                <dl class="dots clearfix">

                    <?php foreach ($apartmentAttributes as $apartmentAttribute): ?>
                    <?php if (!empty($apartmentAttribute->value)): ?>
                        <dt class="grid_5 alpha"><?php echo $apartmentAttribute->attribute->name ?></dt>
                        <dd class="grid_3 omega"><?php echo $apartmentAttribute->value ?></dd>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </dl>

                <h5>Описание</h5>

                <?php echo $model->description ?>
            </div>
        </div>

        <div class="grid_3 omega">

            <p>Введите Ваш номер телефона и укажите удобное для Вас время звонка.</p>
        </div>

        <div class="space"></div>
        <div class="shadow"></div>

    </div>


    <div class="grid_12 alpha omega">

        <div id="special_offers">

            <h2>Специальные предложения</h2>

            <?php $this->widget('SpecialOffersWidget', array('options' => array(
    'vertical' => false,
))); ?>

        </div>

    </div>

    <div class="shadow"></div>
</div>

-->
