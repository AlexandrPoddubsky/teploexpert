<?php
/**
 * @file
 * Returns the HTML for a node.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728164
 */
?>
<article class="<?php print $classes; ?> clearfix node-<?php print $node->nid; ?>"<?php print $attributes; ?>>

  <?php if ($unpublished || $preview): ?>
      <?php if ($unpublished): ?>
        <mark class="watermark"><?php print t('Unpublished'); ?></mark>
      <?php elseif ($preview): ?>
        <mark class="watermark"><?php print t('Preview'); ?></mark>
      <?php endif; ?>
  <?php endif; ?>

  <div class="main-info">
    <?php print render($content['uc_product_image']); ?>
    <div class="info-pane">
      <?php
        print '<h1>' . $title . '</h1>';
        if (isset($content['field_sku'])) $content['field_sku']['#title'] = t('SKU');
        ?>
      <div class="info-fields">
      <?php
        print render($content['field_sku']);
        print render($content['display_price']);
        ?>
        <div class="payment-box">
          <em>Способы оплаты:</em>
          <div><img src="/sites/all/themes/teploexpert/images/img-payment.jpg"></div>
        </div>
      <?php
        print render($content['add_to_cart']);
      ?>
      </div>
      <div class="box2">
                <ul class="tools">
                    <li class="delivery">
                        <span class="strong link">доставка</span>
                        <p>Бесплатная доставка по Москве</p>
                        <div class="tools-drop">
                            <span class="corner"></span>
                            <p>Доставка товаров осуществляется со следующего дня после принятия и обработки заказа. С Вами заранее обговаривается наиболее удобный для Вас день и временной интервал. Доставка осуществляется до подъезда Вашего дома.</p>
                            <p>Наши водители не являются грузчиками, они будут рады вам помочь, если вы их попросите, но повторяем, что это в их обязанности не входит :)<br/>??нтервалы: 10.00-.20-00 – дневной интервал; 16.00-22.00 – вечерний интервал.  (Просим вас понять нас в случаях, когда наши шоферы задерживаются, пробки в Москве. </p>
                        </div>
                    </li>
                    <li class="montaz">
                        <span class="strong link">с монтажом</span>
                        <p>С монтажом дешевле</p>
                        <div class="tools-drop">
                            <span class="corner"></span>
                            <p>Многолетний опыт компании позволяет проводить различные электромонтажные работы в частных и загородных домах. Как правило, речь идет о подключении дома к электромагистрали («ввод в дом»), а также о внутренних электротехнических работах в кирпичных, деревянных, панельных домах (монтаж электропроводки в новом доме, замена «старой проводки», увеличение мощности энергопотребления). </p>
                        </div>
                    </li>
                    <li class="order">
                        <span class="strong link">Заказ по телефону</span>
                        <p><span class="strong tel">+7 (495) 649-61-65</span></p>
                        <p>Ежедневно с 9:00 до 21:00</p>
                    </li>
                    <li class="print">
                        <span class="link strong"><a   href="#"  title="Версия для печати" rel="nofollow" onclick="window.print()">Распечатать</a></span>


                    </li>
                </ul>
                <div class="social-widget">
                    <script type="text/javascript" src="//yandex.st/share/share.js"
charset="utf-8"></script>
<div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="button" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki"></div>
                </div>
            </div> <!---end of box2 -->
    </div>
  </div>

  <?php
    // We hide the comments and links now so that we can render them later.
    hide($content['comments']);
    hide($content['links']);
    hide($content['body']);
    ?>
    <div class="chapter">
    <div class="chapter-title">Характеристики</div>
    <div class="fields">
    <?php
    print render($content);
  ?>
  </div></div>
  <div class="chapter">
    <div class="chapter-title">Описание</div>
    <div class="fields">
    <?php
    //unset $content['body']['#title'];
    print render($content['body'][0]['#markup']);
  ?>
  </div></div>
</article>
