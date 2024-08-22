

                        <div class="products_show">
                            <a href="<?= $APPLICATION->GetCurPageParam(
                                "show=list",
                                array('show')
                            ) ?>">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </a>
                            <a href="<?= $APPLICATION->GetCurPageParam(
                                "show=tile",
                                array('show')
                            ) ?>">
                                <i class="fa fa-table" aria-hidden="true"></i>
                            </a>
                        </div>
                        <?
                        foreach ($arResult['ITEMS'] as $item): ?>
                                <?
                                if(!empty($item['PREVIEW_PICTURE']['ID'])) {

                                    $img = CFile::ResizeImageGet(
                                        $item['PREVIEW_PICTURE']['ID'],
                                        array('width' => 100, 'height' => 100),
                                        BX_RESIZE_IMAGE_EXACT,
                                        true,
                                    );
                                    $picture['SRC'] = $img['src'];

                                } elseif ($item['DETAIL_PICTURE']['ID']) {

                                   $img = CFile::ResizeImageGet(
                                        $item['DETAIL_PICTURE']['ID'],
                                        array('width' => 100, 'height' => 70),
                                        BX_RESIZE_IMAGE_EXACT,
                                        true,
                                    );
                                    $picture['SRC'] = $img['src'];

                                } else {
                                    $picture['SRC'] = SITE_TEMPLATE_PATH.'/img/altermax_logo.jpg';
                                    $item['PREVIEW_PICTURE']['FILE_NAME'] = 'no_photo.png';
                                }
                                ?>
                            <li>
                                <div class="product-item litle_list_li"
                                     id="<?= $areaIds[$item['ID']] ?>">
                                    <div class="item-inner litle-inner">
                                        <div class="icon-sale-label sale-left"></div>
                                        <div class="product-thumbnail litle-product">
                                            <div class="pr-img-area litle-img-area">
                                                <a title="<?= $item['NAME'] ?>"
                                                   href="<?= $item['DETAIL_PAGE_URL'] ?>">
                                                    <figure class="litle">
                                                        <img class="first-img litle"
                                                             src="<?= $picture['SRC'] ?>"
                                                             alt="<?= $item['NAME'] ?>">
                                                        <img class="hover-img litle"
                                                             src="<?= $picture['SRC'] ?>"
                                                             alt="<?= $item['NAME'] ?>">
                                                    </figure>
                                                </a>
                                            </div>
                                            <div class="item-info">
                                                <div class="info-inner">
                                                    <div class="item-title">
                                                        <a title="<?= $item['NAME'] ?>"
                                                           href="<?= $item['DETAIL_PAGE_URL'] ?>"><?= $item['NAME'] ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pr-info-area">
                                            </div>
                                        </div>
                                        <div class="litle-description">
                                            <p> <?= TruncateText($item['PREVIEW_TEXT'], 100) ?> </p>
                                        </div>
                                        <div class="item-content litle-item-content">
                                        <div class="item-price">
                                                <div class="price-box"><span
                                                            class="regular-price"> <span
                                                                class="price"> <?=$item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']]['PRINT_RATIO_BASE_PRICE']?> </span> </span>
                                                </div>
                                            </div>
                                            <div class="pro-action litle-pro-action">
                                                <a type="button"
                                                   class="add-to-cart hashref"
                                                   href="<?= $item['DETAIL_PAGE_URL'] ?>"><span> Подробнее</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                        <?
                        endforeach; ?>