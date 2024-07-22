/*pam fnc*/

//выделение при клике чекбокса внутри
function checkChangeByClickOnParent(classParent,classCheck){
  if(!classCheck){
    classCheck='has-checked-input';
  }
  //поиск инпутов с чекбоксом или радио и смена класса у родителя и выбор инпута
  jQuery(classParent).each(function(idx,parentEl){
    //ищем чекбокс или радио
    var isCheckbox=jQuery(parentEl).find('input:checkbox');
    var isRadio=jQuery(parentEl).find('input:radio');
    var checkElem=false;
    //оставим только один
    if(isCheckbox){
      isRadio=false;
      var checkElem=isCheckbox;
    }
    else {
      isCheckbox=false;
      var checkElem=isRadio;
    }
    //обрабатываем клик
    if(checkElem){
      jQuery(parentEl).click(function(){
        //неважно радио или чекбокс работаем как с чекбоксом
        if(jQuery(parentEl).hasClass(classCheck)){
          //снимем выбор
          jQuery(checkElem).prop('checked', false).change();
          //и уберем класс
          jQuery(parentEl).removeClass(classCheck);
        }
        else {
          //поставим выбор
          jQuery(checkElem).prop('checked', true).change();
          //добавим класс
          jQuery(parentEl).addClass(classCheck);
        }
      });
      //обратная совместимость, при загрузки страницы с выбранными элементами
      if(jQuery(checkElem).is(':checked')){
        //console.info('ggh');
        jQuery(parentEl).addClass(classCheck);
      }
    }
  });
}
//отслеживания обязательного заполнения данных в форме, в достатке
function submitAlikeElemToForm(classElem,checkClass,classAdd){
  if(!checkClass){
    checkClass='can-submit';
  }
  if(!classAdd){
    classAdd='form-complite';
  }
  jQuery(classElem).each(function(idx,elem){
    //ищем привязанную форму
    var formCheck=jQuery(elem).data('form');
    if(formCheck){
      //проверим наличие такой формы
      if(jQuery(formCheck).length>0){
        //форма такая есть, работаем просто проверив наличие класса при изменении чего-либо в форме
        jQuery(formCheck).change(function(){
          //console.info('change');
          if(jQuery(formCheck).hasClass(checkClass)){
            //все ок, в форме выполнены условия
            jQuery(elem).addClass(classAdd);
          }
          else {
            //плохо, условия в форме не выполнены
            jQuery(elem).removeClass(classAdd);
          }
        });
      }
    }
  });
}
//форма для отслеживания обязательного заполнения полей в виде чекбокса
function checkFillFields(formClass,elemclassCheck,addClass){
  if(!addClass){
    addClass='can-submit';
  }
  if(elemclassCheck){//без этого нечего искать на проверку
    //обработчик на каждую форму
    jQuery(formClass).each(function(idx,form){
      //ищем элементы каждый раз при изменении данных в форме
      //console.info('change-go');
      //сомтрим у нас массив или элемент
      var typeElemInd=typeof(elemclassCheck);
      var checkThisClass=[];
      if(typeElemInd=='object'){
        //массив несколько вариантов искать
        checkThisClass=elemclassCheck;
      }
      else {
        //только один идентификатор
        checkThisClass=[elemclassCheck];
      }
      var checkFlagKit=[];//флаги на все переборы, чтобы по каждой, был хоть один
      var hasInputKit=false;//флаг, что есть выбранные элементы каждого копплекта
      //вешаем на каждый элемент, который надо проверять
      checkThisClass.forEach(function(inputClass,i,arr){
        checkFlagKit[i]='uncheked';
        //обработчик на каждый элемент и тут
        //console.info(jQuery('input'+inputClass));
        jQuery('input'+inputClass).each(function(idx,input){
          var idIn=input.id;
          jQuery('#'+idIn).change(function(){
            //console.info(this.checked);
            //проверяем что делалось тоесть сняли или поставили
            //if(this.checked || jQuery(this).is(':checked')){
            if(this.checked){
              //console.info('cheked');
              //тут по любому поставим флаг ВЫДЕЛЕНО
              checkFlagKit[i]='cheked';
            }
            else {
              //console.info('uncheked');
              //тут проверим, если не осталось выделенного не одного, то скажем, что плохо
              var checkedElems=jQuery('input'+inputClass+':checked');
              if(checkedElems.length==0){
                //нет выделенных пунктов, значит, надо предупредить, что не все выделено
                checkFlagKit[i]='uncheked';
              }
            }
            var findAncheck=jQuery.inArray('uncheked',checkFlagKit);
            if(findAncheck==-1){
              //все выбрано
              hasInputKit=true;
            }
            else {
              //есть невыделенные пункты
              hasInputKit=false;
            }
            //console.info(findAncheck);
            //console.info(hasInputKit);
            //console.info('go--');
            if(hasInputKit){
              jQuery(form).addClass(addClass);
            }
            else {
              jQuery(form).removeClass(addClass);
            }
            jQuery(form).change();
            //а теперь само отслеживание сабмита и остановка по необходимости
          });
        });
      });
      //транслируем это событие на форму
      /*jQuery(form).change(function(){
        //console.info(checkFlagKit);
        var findAncheck=jQuery.inArray('uncheked',checkFlagKit);
        if(findAncheck==-1){
          //все выбрано
          hasInputKit=true;
        }
        else {
          //есть невыделенные пункты
          hasInputKit=false;
        }
        //console.info(findAncheck);
        console.info(hasInputKit);
        console.info('go--');
        if(hasInputKit){
          jQuery(form).addClass(addClass);
        }
        else {
          jQuery(form).removeClass(addClass);
        }
      });*/

      var findAncheck=jQuery.inArray('uncheked',checkFlagKit);
      if(findAncheck==-1){
        //все выбрано
        hasInputKit=true;
      }
      else {
        //есть невыделенные пункты
        hasInputKit=false;
      }
      if(hasInputKit){
        jQuery(form).addClass(addClass);
      }
      else {
        jQuery(form).removeClass(addClass);
      }
      //jQuery(form).change();
      //остановка самого сабмита
      jQuery(form).submit(function(e){
        if(!jQuery(form).hasClass(addClass)){
          e.preventDefault();
          return false;
        }
      });
    });
  }
}

//подхват данных формы при шаге назад-вперед
function backForvardStepForm(classForm){
  jQuery(window).bind("pageshow", function() {
    jQuery(classForm).each(function(idx,form){
      //разберем все данные по форме, и заполним как было
      var $form=jQuery(form)
      var itemsForm=$form[0]
      jQuery.each($form[0],function(ind,elem){
        if(elem.type == 'checkbox'){
          //проставим назад, если они выбраны
          if(jQuery(elem).is(':checked')){
            jQuery(elem).attr('checked',true).prop("checked", true).change();
            //$form.change();
          }
          //console.info(jQuery(elem).is(':checked'));
          //if()
        }
        //console.info(elem.type);
      });
      /*var inputs=jQuery(form).find('input');
      jQuery(inputs).each(function(ind,input){
        console.info(input);
      });*/
    });
  });
}
//сделать летающим блок в пределах родителя по нижнему|верхнему краю
function flyElemInParent(classEl,position){
  if(!position){
    position='bottom';
  }
  jQuery(classEl).each(function(idx,elem){
    //найдем родительский блок в рамках которого перемещать
    var parentBx=jQuery(elem).parent();
    //добавим стиль родителя для того, чтобы блок смог "летать"
    if(jQuery(parentBx).css('position')=='static'){
      jQuery(parentBx).css('position','relative');
    }
    //позиционирование плавающего элемента
    if(jQuery(elem).css('position')=='static'){
      jQuery(elem).css('position','relative').addClass('fly-now');
    }
    //для скольжения блока, надо понимать размер родителя в рамках которого можно скользить
    //так же видимую область экрана
    var scrollWindowTop=jQuery(window).scrollTop(),
      positionBxTop=jQuery(parentBx).offset().top,
      positionElemTop=jQuery(elem).position().top,
      heightWindow=jQuery(window).height(),
      widthBx=jQuery(parentBx).innerWidth(),
      heightBx=jQuery(parentBx).innerHeight(),
      elemHeight=jQuery(elem).innerHeight(),
      moveTopEl=0;//смещение элемента СРАЗУ от верха
    //console.info(elemHeight);
    //определим в каких пределах перемещать
    var startFrom=positionBxTop;
    //в зависимости от положения блока, после старта, блок должен быть выше или ниже
    if(position=='bottom'){
      //закрепим кнопку сверху
      jQuery(elem).css({
        'position':'fixed',
        'width': widthBx,
        'bottom':0
      });
      //оставим заглушку
      var idFake='fakeBxEl-'+idx;
      jQuery(elem).after('<div id="'+idFake+'"></div>');
      //стилизуем
      jQuery('#'+idFake).css('height',elemHeight);

      //закрепим блок сразу на расстоянии от верхнего края, а потом будем перемещать
      moveTopEl=0 - (startFrom - elemHeight * 2);//стартовое положение от верха роджителя
      //определим прокручен ли основной элемент за зону видимости
      var needScrollToParent=startFrom - scrollWindowTop;
      //формирует отступ от верха родителя
      if(needScrollToParent>0){
        var seeHeight = heightWindow-needScrollToParent;//видимая часть блока
        //сколько еще до кнопки
        var toElemTop=positionElemTop-seeHeight;
        moveTopEl=0 - toElemTop - elemHeight;
        //console.info(positionElemTop+' - '+seeHeight);
      }
      else {
        //найдем зону блока, которая уже прокручена
        var nowScrollParent=scrollWindowTop-startFrom;
        var toElemTop=positionElemTop-(nowScrollParent + heightWindow);
        moveTopEl=0 - toElemTop - elemHeight;
      }
      //проверим, не пора бы остановиться
      if(moveTopEl>0){
        moveTopEl=0;
        jQuery(elem).css({
          'position':'',
          'width': '',
          'bottom':''
        }).removeClass('fly-now');
        //удалим заглушку
        jQuery('#'+idFake).remove();
      }

      //проверим, не пора бы остановиться
      /*if(moveTopEl>0){
        moveTopEl=0;
        jQuery(elem).css({top:''}).removeClass('fly-now');
      }
      else {
        jQuery(elem).css({top:moveTopEl});
      }*/
      //jQuery(elem).css({top:moveTopEl});
      //добавим оработку на ресайз и скрол
      jQuery(window).on('scroll resize',function(){
        scrollWindowTop=jQuery(window).scrollTop();
        positionBxTop=jQuery(parentBx).offset().top;
        heightWindow=jQuery(window).height();
        widthBx=jQuery(parentBx).innerWidth(),
        heightBx=jQuery(parentBx).innerHeight();
        elemHeight=jQuery(elem).innerHeight();

        //console.info(scrollWindowTop);

        //закрепим кнопку сверху
        jQuery(elem).css({
          'position':'fixed',
          'width': widthBx,
          'bottom':0
        });
        //оставим заглушку
        if(!jQuery('#'+idFake).is('div')){
          jQuery(elem).after('<div id="'+idFake+'"></div>');
        }
        //стилизуем заглушку
        jQuery('#'+idFake).css('height',elemHeight);

        startFrom=positionBxTop;

        moveTopEl=0 - (startFrom - elemHeight * 2);//стартовое положение от верха роджителя
        //определим прокручен ли основной элемент за зону видимости
        var needScrollToParent=startFrom - scrollWindowTop;
        //формирует отступ от верха родителя
        if(needScrollToParent>0){
          var seeHeight = heightWindow-needScrollToParent;//видимая часть блока
          //сколько еще до кнопки
          var toElemTop=positionElemTop-seeHeight;
          moveTopEl=0 - toElemTop - elemHeight;
        }
        else {
          //найдем зону блока, которая уже прокручена
          var nowScrollParent=scrollWindowTop-startFrom;
          var toElemTop=positionElemTop-(nowScrollParent + heightWindow);
          moveTopEl=0 - toElemTop - elemHeight;
        }

        //сколько надо прокрутить для открепления кнопки
        //console.info(moveTopEl);
        //проверим, не пора бы остановиться
        if(moveTopEl>0){
          moveTopEl=0;
          jQuery(elem).css({
            'position':'',
            'width': '',
            'bottom':''
          }).removeClass('fly-now');
          //удалим заглушку
          jQuery('#'+idFake).remove();
        }

        /*if(moveTopEl>0){
          moveTopEl=0;
          jQuery(elem).css({top:''}).removeClass('fly-now');
        }
        else {
          jQuery(elem).css({top:moveTopEl});
        }*/
      });
    }
    else if(position=='top'){
      //закрепим блок сразу на расстоянии от верхнего края, а потом будем перемещать
      moveTopEl=0;//стартовое положение от верха роджителя
      //определим прокручен ли основной элемент за зону видимости
      if(scrollWindowTop>startFrom){
        //можно начать работать
        //определим необходимый отступ от верха
        moveTopEl=scrollWindowTop-startFrom;

        jQuery(elem).css({top:moveTopEl}).addClass('fly-now');
      }
      else {
        moveTopEl=0;
        jQuery(elem).css({top:''}).removeClass('fly-now');
      }
      //при изменении размеров
      jQuery(window).on('scroll resize',function(){
        scrollWindowTop=jQuery(window).scrollTop();
        positionBxTop=jQuery(parentBx).offset().top;
        heightWindow=jQuery(window).height();
        heightBx=jQuery(parentBx).innerHeight();

        elemHeight=jQuery(elem).innerHeight();
        //console.info(scrollWindowTop+' - '+startFrom);

        startFrom=positionBxTop;
        if(scrollWindowTop>startFrom){
          //можно начать работать
          //определим необходимый отступ от верха
          moveTopEl=scrollWindowTop-startFrom;

          jQuery(elem).css({top:moveTopEl}).addClass('fly-now');
        }
        else {
          moveTopEl=0;
          jQuery(elem).css({top:''}).removeClass('fly-now');
        }
      });
    }
  });
}
//плавная прокрутка к якорю
function softScrollTo(className,addClass){
  if(!addClass){
    addClass='look-this-now';
  }
  jQuery(className).each(function(ind,elem){
    //если есть якорь, прокрутим
    var target=jQuery(elem).attr('href');
    //console.info('check-click');
    if(jQuery(target).length>0){
      //console.info('stay-click');
      //есть такой, ставим обработчик
      jQuery(elem).click(function(e){
        //пометим, что прокручили
        //jQuery(className).removeClass(addClass);
        //jQuery(elem).addClass(addClass);
        //console.info('click');
        e.preventDefault();
        var destination = jQuery(target).offset().top;
        jQuery("html:not(:animated),body:not(:animated)").animate({
          scrollTop: destination-10
        },300);
      });
      //так же проверяем область видимости блока, если в пределах нее, то выделяем соответственно блоки
      var scrollWindowTop=jQuery(window).scrollTop(),
        startTarget=jQuery(target).offset().top-11,
        endTarget=startTarget + jQuery(target).height();
        scrollWindowTop=scrollWindowTop.toFixed();//округлим
        if(scrollWindowTop>=startTarget && scrollWindowTop<endTarget){
          jQuery(target).addClass(addClass);
          jQuery(elem).addClass(addClass);
        }
        else {
          jQuery(target).removeClass(addClass);
          jQuery(elem).removeClass(addClass);
        }

      jQuery(window).on('scroll resize',function(){
        startTarget=jQuery(target).offset().top-11;
        endTarget=startTarget + jQuery(target).height();
        scrollWindowTop=jQuery(window).scrollTop();
        scrollWindowTop=scrollWindowTop.toFixed();//округлим
        //console.info(elem);
        //console.info(startTarget+' - '+scrollWindowTop);
        //console.info(endTarget+' - '+scrollWindowTop);
        if(scrollWindowTop>=startTarget && scrollWindowTop<endTarget){
          //console.info('addClass');
          jQuery(target).addClass(addClass);
          jQuery(elem).addClass(addClass);
        }
        else {
          //console.info('removeClass');
          jQuery(target).removeClass(addClass);
          jQuery(elem).removeClass(addClass);
        }
      });
    }
  });
}
//только цыфры
function onlyNumberInp(inputs){
  jQuery(inputs).each(function(idx,elem){
    jQuery(elem).keyup(function() {
        //console.log(jQuery(box).val());
        var nVal = jQuery(elem).val().replace(/[^\d,.]*/g, '')
            .replace(/([,.])[,.]+/g, '$1')
            .replace(/^[^\d]*(\d+([.,]\d{0,5})?).*$/g, '$1');
        jQuery(elem).val(nVal);
    });
  });
}
//добавим кнопку управления цыфрой в поле
function inpSumChangeWthBtm(inputs,step){
  if(!step){
    step=1;
  }
  jQuery(inputs).each(function(idx,input){
    //проверяем отсутвие определенного класса и если так то добавим кнопки и обработчик
    var checkClass='add-chng-btm';
    if(!jQuery(input).hasClass(checkClass)){
      jQuery(input).addClass(checkClass);//добавим класс, по которому отсекаем двойную установку
      jQuery(input).parent().addClass(checkClass+'_prnt');
      //добавим кнопку до и после
      var idAf='chval-af-'+idx;
      var idbf='chval-bf-'+idx;
      var btmAfter='<div id="'+idAf+'" class="dec qtybutton"><i class="fa fa-plus">&nbsp;</i></div>';
      var btmbefore='<div id="'+idbf+'" class="dec qtybutton"><i class="fa fa-minus">&nbsp;</i></div>';
      jQuery(input).after(btmAfter).before(btmbefore);
      //и на каждую кнопку вешаем обработчик
      changeValNum('#'+idAf,input,'plus');
      changeValNum('#'+idbf,input,'minus');
    }
  });
}
//увеличение-уменьшение числа в поле по кнопке
function changeValNum(idEl,input,wey){
  jQuery(idEl).each(function(idx,btn){
    if(!wey){
      wey='plus';
    }
    //ставим на клик по кнопке
    jQuery(btn).click(function(e){
      //console.info('click');
      e.preventDefault();
      var val=jQuery(input).val();
      var max = jQuery(input).data('maxcount');
      var request = true;
      //console.info('val='+val);
      if(wey=='plus'){
        val++;
      }
      else if(wey=='minus'){
        val--;
      }
      else {
        val=val-1;
      }
      if(val > max) {
        val--;
        request = false;
      }
      if(val<0){
        val=0;
      }
      if(val == 0){
        val=1;
      }
      //вернем значение назад
      if(request) {
        jQuery(input).val(val).change();
      }
    });

  });
}
//изменение цены в зависимости от кол-ва
var timer_q;
var timer_b;

function changePriceByValueSum(classBx){
  jQuery(classBx).each(function(idx,elem){
    var onePrice=jQuery(elem).data('oneprice');
    var inpLookSum=jQuery(elem).data('inp');
    var itemId =jQuery(elem).data('item');
    var curStr=jQuery(elem).data('cur');
    if(onePrice && onePrice!='0'){
      if(jQuery(inpLookSum).length>0){
        //есть элемент, мотрим по нему
        jQuery(inpLookSum).change(function(){
          var val=this.value;
          clearTimeout(timer_q);
          clearTimeout(timer_b);
          timer_q  = setTimeout(() => { OpenAjaxResponse('Q', jQuery(elem).data('item'), val) }, 1000);
          //для исключения ошибок в строку, для замены
          onePrice=String(onePrice);
          var price=onePrice.replace(',','.');
          price=parseFloat(price) * val;
          var htmlPrice=number_format(price, 2, '.', ' ');
          //вернем это в данные
          //console.info(htmlPrice);
          jQuery(elem).html(htmlPrice+curStr);
        });
      }
    }
  });
}

function ResetCount(data) {
 $('tfoot .hide-on-change').text(data.FINAL_PRICE);
}

//обработка формы ajax
function ajaxFormProcess(classForms,miniCartBoxId){
  jQuery('body').delegate(classForms,'submit',function(e){
    //отправим данные через главную, на ней должен быть скрипт, который если отловит ajax в параметре, должен не выполнять более ничего
    //планирую это организовать на уровне класса, класс прикрепить в LoadBefore
    $form=jQuery(this)
    e.preventDefault();//чтобы не пошел сабмит формы
    //получим данные этой формы для отправки
    var actionPath=$form.attr('action');
    //массив данных формы включая файлы
    var ajaxData = new FormData($form.get(0));
    ajaxData.append('is_ajax', '1');
    jQuery.ajax({
      url: actionPath,
      type: 'POST',
      data: ajaxData,
      //dataType: 'json',
      cache: false,
      contentType: false,
      processData: false,
      success:function(respond, textStatus, jqXHR){
        //console.info(respond);
        respondJSON=JSON.parse(respond);
        if(!respondJSON.error){
          //обновим данные текущей формы, что уже добавление было
          $form.parent().addClass('addet-to-cart');
          //обновим корзину, если надо
          if(respondJSON.fncgo=='updateCart'){
            if(miniCartBoxId && jQuery(miniCartBoxId).length>0){
              jQuery(miniCartBoxId).trigger('refreshcart');
            }
          }
          else if (respondJSON.fncgo=='reloadPage') {
            //перезагрузим страницу
            window.location.reload();
          }
          else if(respondJSON.fncgo=='showForm'){
            openHoverWindow(respondJSON.html);
          }
          else if(respondJSON.fncgo=='goAction'){
            console.info(actionPath);
            //window.location.reload();
            window.location.href = actionPath;
          }
        }
      },
      error:function(jqXHR, textStatus, errorThrown){
        console.info(textStatus);
        var msg=textStatus;
      }
    });

    //console.info(ajaxData);
    return false;
  });
}
function refreshMiniCart(miniCartBoxId,actionPath){
  //обновление миникорзины
  if(miniCartBoxId && jQuery(miniCartBoxId).length>0){
    //console.info('upt_set2');
    //отлавливаем событие
    jQuery(miniCartBoxId).on('refreshcart',function(){
      //console.info('upt_do');
      //отправим обычный ajax
      var fdata = {
      'fnc-form':'order_cart',
      'fnc-elem':'updateCart',
      is_ajax : '1'
    };
      jQuery.ajax({
        url: actionPath,
        type: 'POST',
        data: fdata,
        success:function(data){
          //console.info(data);
          jQuery(miniCartBoxId).html(data);
        }
      });
    });
  }
}
//обязательное обновление основной корзины, при изменении данных в ней
function refshCartOnChange(inpCheck,mainBxWrap,addClass){
  if(!addClass){
    addClass='has_changes_sum';
  }
  //отслеживаем изменение на всех нужных input
  jQuery(inpCheck).each(function(idx,elem){
    jQuery(elem).change(function(){
      var form=jQuery(elem).data('form');
      //ставим метку текущего родителя, что данные были изменены
      if(jQuery(mainBxWrap).length>0){
        jQuery(mainBxWrap).addClass(addClass);
      }
    });
  });
}
//удаление елемента корзины по кнопке с очисткой поля кол-ва
function authUserCartAdd(classBtm,delClassParent){
  if(!delClassParent){
    delClassParent='remove-this-str'
  }
  jQuery(classBtm).each(function(idx,btm){
    //найдем все нужные блоки
    var inputCtrl=jQuery(btm).data('input');
    var parentEl=jQuery(btm).data('parent');
    var itemId = jQuery(btm).data('item');
    if(!inputCtrl || !parentEl){
      if(!inputCtrl){
        console.info('ERROR: на элементе['+jQuery(btm).attr('class')+'] не установлено input для управления, добавьте кнопке атрибут data-input');
      }
      else {
        console.info('ERROR: на элементе['+jQuery(btm).attr('class')+'] не установлено input для управления, добавьте кнопке атрибут data-parent');
      }
    }
    else {
      //все элементы есть, ставим обработчик
      jQuery(btm).click(function(e){
        e.preventDefault();
        //обнулим кол-во элементов
        //jQuery(inputCtrl).val(0).change();
        //добавим класс родителю
        OpenAjaxResponse('D', jQuery(btm).data('item'), 1)
        timer_b  = setTimeout(() => { OpenAjaxResponse('B', 1,1) }, 2000);
        var parent=jQuery(btm).closest(parentEl);
        if(parent){
          jQuery(parent).addClass(delClassParent);
        }
      });
    }
  });
}

function OpenAjaxResponse(action, itemId, count) {

  var data = {}
  data.action = action;
  data.ItemId = itemId;
  data.count = count || '';

  $.ajax({
    type: "POST",
    url: "/ajax/basketAjax.php",
    data: data,
    dataType: "json",
    success: function (data) {
      if(data.STATUS == 'BASKET') {
        ResetCount(data);
      }
    }
  });
}


//показать окно всплывающее в стиле темы
function openHoverWindow(htmlInc){
  var overlayId='quick_view_popup-overlay';
  var winWrapId='quick_view_popup-wrap';
  var wrapWinId='quick_view_popup-outer';
  var boxWinId='quick_view_popup-content';
  var closeWinBtm='quick_view_popup-close';
  //проверяем наличие фонового перекрытия, если есть

  //проверяем наличие уже такого окна
  if(jQuery('#'+overlayId).length>0){
    jQuery('#'+overlayId).remove();
  }
  //console.info('show');
  //добавим в боди перекрытие
  var overlay='<div id="'+overlayId+'"></div>';
  jQuery('body').addClass('hover-window').append(overlay);
  //добавим в боди саму форму
  if(jQuery('#'+winWrapId).length>0){
    //удалим старое
    jQuery('#'+winWrapId).remove();
  }
  var divForm='<div id="'+winWrapId+'"></div>';
  jQuery('body').append(divForm);
  var containerWin=jQuery('#'+winWrapId);
  //новое окно рамка
  var wrapWin='<div id="'+wrapWinId+'"></div>';
  jQuery(containerWin).append(wrapWin);
  //блок окна и кнопка закрытия
  var boxWin='<div id="'+boxWinId+'"></div><a style="display: inline;" id="'+closeWinBtm+'" href="#"><i class="icon pe-7s-close"></i></a>';
  //добавим в блок
  jQuery('#'+wrapWinId).append(boxWin);
  //покажем блок
  jQuery(containerWin).css('display','block');
  //вставим данные htmlInc
  jQuery('#'+boxWinId).html(htmlInc);
  //выровняем его по высоте
  var winHeight=jQuery(window).height();
  var bxHeight=jQuery(containerWin).height();
  if(bxHeight<winHeight){
    //надо что то выровнять
    var padding=(winHeight-bxHeight) / 2;
    jQuery(containerWin).css({top:padding});
  }
  jQuery(window).resize(function(){
    var winHeight=jQuery(window).height();
    var bxHeight=jQuery(containerWin).height();
    if(bxHeight<winHeight){
      //надо что то выровнять
      var padding=(winHeight-bxHeight) / 2;
      jQuery(containerWin).position().top=padding;
    }
  });
  //обработку на закрытияе формы
  jQuery('#'+closeWinBtm).click(function(e){
    e.preventDefault();
    //удалим форму и фон
    jQuery('#'+winWrapId).remove();
    jQuery('#'+overlayId).remove();
    //обработчик после
  });
  jQuery('#'+overlayId).click(function(e){
    e.preventDefault();
    //удалим форму и фон
    jQuery('#'+winWrapId).remove();
    jQuery('#'+overlayId).remove();
    //обработчик после
  });
}
//плавающая шапка сайта
function flyHeader(){
  var headerEl=jQuery('header.header-fly');
  var navEl=jQuery('nav.header-fly');
  if(headerEl && navEl){
    //завернем это все в один блок
    jQuery(headerEl).add(navEl).wrapAll('<div id="fly-header-pam" class="wrap-header-all"></div>');
  }
  else if(headerEl){
    jQuery(headerEl).wrapAll('<div id="fly-header-pam" class="wrap-header-all"></div>');
  }
  //новая шапка уже обернутая
  var wrapHeader = jQuery('#fly-header-pam');
  //меняем классы при прокрутке
  var heihghtHeader=jQuery(wrapHeader).height();
  //отключаем на определенных разрешений
  if(jQuery(window).width() <= 960) {
    jQuery(wrapHeader).removeClass('header-fly');
    jQuery('body').removeClass('header-fly-show');
    //удалим замещающий блок
    jQuery('#fake-div').remove();
  }
  else {
    jQuery(window).scroll(function(){
      if(jQuery(window).scrollTop() > heihghtHeader) {
        jQuery(wrapHeader).addClass('header-fly');
        //добавим замещающий блок
        if(jQuery('#fake-div').length<1){
          jQuery(wrapHeader).before('<div id="fake-div" style="height:'+heihghtHeader+'px"></div>');
        }
        jQuery('body').addClass('header-fly-show');
      }
      else {
        jQuery(wrapHeader).removeClass('header-fly');
        jQuery('body').removeClass('header-fly-show');
        //удалим замещающий блок
        jQuery('#fake-div').remove();
      }
    });
  }

  //то же при изменении размера окна
  jQuery(window).resize(function(){
    //отключаем на определенных разрешений
    if(jQuery(window).width() <= 960) {
      jQuery(wrapHeader).removeClass('header-fly');
      jQuery('body').removeClass('header-fly-show');
      //удалим замещающий блок
      jQuery('#fake-div').remove();
    }
    else {
      //меняем классы при прокрутке
      heihghtHeader=jQuery(wrapHeader).height();
      jQuery(window).scroll(function(){
        if(jQuery(window).scrollTop() > heihghtHeader) {
          jQuery(wrapHeader).addClass('header-fly');
          //добавим замещающий блок
          if(jQuery('#fake-div').length<1){
            jQuery(wrapHeader).before('<div id="fake-div" style="height:'+heihghtHeader+'px"></div>');
          }
          jQuery('body').addClass('header-fly-show');
        }
        else {
          jQuery(wrapHeader).removeClass('header-fly');
          jQuery('body').removeClass('header-fly-show');
          //удалим замещающий блок
          jQuery('#fake-div').remove();
        }
      });
    }
  });
}
//получение геоданных пользователя
function getGeoUser(){
  //смотрим, чт в кеше
  var cookieName='geouserpam';
  //https://github.com/carhartl/jquery-cookie/tree/v1.4.1

  var userInfoGeo;
  //jQuery.cookie(cookieName,'')
  //console.info(jQuery.cookie(cookieName));
  if(jQuery.cookie(cookieName)){
    jQuery.cookie.json = true;
    userInfoGeo=jQuery.cookie(cookieName);
    //console.info(userInfoGeo[1].name);
    //console.info(userInfoGeo);
    //формируем данные для записи в поле блока
    addInfGeoToHeaderLine(userInfoGeo,'#user-siti-select-ymap');
    changeDepatmInfoGeo(userInfoGeo,'#geo-phone');
  }
  else {
    ymaps.ready(getYmapsCoords);
  }
}

//получение координат юзера по ip или местоположению
function getYmapsCoords(){
  var cookieName='geouserpam';
  var geolocation = ymaps.geolocation;
  geolocation.get({
      provider: 'yandex',
      //mapStateAutoApply: true,
      // Включим автоматическое геокодирование результата.
      autoReverseGeocode: true
  }).then(function (result) {
    // Выведем результат геокодирования.
      var metaDataProperty = result.geoObjects.get(0).properties.get('metaDataProperty');
      var address=metaDataProperty['GeocoderMetaData']['Address'];
      var country_code = address['country_code'];//RU
      //адрес детальнее
      var arrAddr= address['Components'];
      //добавим это в куки
      //console.info(Object.values(arrAddr));
      jQuery.cookie.json = true;
      jQuery.cookie(cookieName,arrAddr);
      //формируем данные для записи в поле блока
      addInfGeoToHeaderLine(arrAddr,'#user-siti-select-ymap');

  });

  geolocation.get({
      provider: 'browser',
      //mapStateAutoApply: true
      // Включим автоматическое геокодирование результата.
      autoReverseGeocode: true
  }).then(function (result) {
    var metaDataProperty = result.geoObjects.get(0).properties.get('metaDataProperty');
    var address=metaDataProperty['GeocoderMetaData']['Address'];
    //добавим это в куки
    jQuery.cookie.json = true;
    jQuery.cookie(cookieName,arrAddr);
    //формируем данные для записи в поле блока
    addInfGeoToHeaderLine(arrAddr,'#user-siti-select-ymap');
  });
}
//запись геоджанных в шапке город
function addInfGeoToHeaderLine(arrAddr,bxId){
  //console.info(arrAddr);
  var infAddr=[];
  arrAddr.forEach(function (item,i,arr){
    var key=item['kind'];
    var val=item['name'];
    infAddr[key]=val;
  });
  jQuery(bxId).html(infAddr['locality']);
}
//смена конатков по региону
function changeDepatmInfoGeo(arrAddr,bxId){
  //готовим для ajax запроса
  var ajaxData = new FormData();
  ajaxData.append('fnc-elem', 'geolocate');
  ajaxData.append('param', 'phone');
  ajaxData.append('val', arrAddr);
  var actionPath = '/ajax';
  jQuery.ajax({
    url: actionPath,
    type: 'POST',
    data: ajaxData,
    //dataType: 'json',
    cache: false,
    contentType: false,
    processData: false,
    success:function(respond, textStatus, jqXHR){
      //console.info(respond);
      respondJSON=JSON.parse(respond);
      if(!respondJSON.error){
        var html = respondJSON.html;
        if(html){
          jQuery(bxId).html(html);
        }
      }
    },
    error:function(jqXHR, textStatus, errorThrown){
      console.info(textStatus);
    }
  });
  //jQuery(bxId).html('<!--'+infAddr+'-->');

}

//открыть окно с полученными по ajax данными
function openHoverWindContentAjax(btmElems){
  var htmlOut='';
  jQuery(btmElems).click(function(e){
    e.preventDefault();
    //отправим ajax запрос
    var fnc = jQuery(this).data('fnc');
    var afterfnc = jQuery(this).data('afterfnc');
    if(fnc){
      //отправим обычный ajax
      var fdata = {
        'fnc-form':'ajax',
        'fnc-elem':fnc,
        is_ajax : '1'
      };
      jQuery.ajax({
        url: '/',
        type: 'POST',
        data: fdata,
        success:function(data){
          //console.info(data);
            //откроем окно
          openHoverWindow(data,afterfnc);
        }
      });
    }
    else {
      htmlOut="No ask elems.";
      //откроем окно
      openHoverWindow(htmlOut);
    }
  });
}
//показ окна при клике или скрытие
function swShowByClick(btm,classAdd){
  jQuery(btm).each(function(idx,bt){
    var target=jQuery(bt).data('target');
    if(target){
      //переключаем по клику
      jQuery(bt).click(function(){
        if(jQuery(target).hasClass(classAdd)){
          jQuery(target).removeClass(classAdd);
        }
        else {
          jQuery(target).addClass(classAdd);
        }
      });
    }
  });
}
//заполняем input данными, в зависимости от выбранного пункта, альтернатива radio
function radioLikeInput(elemsClass){
  jQuery(elemsClass).each(function (idx,elem){
    var input=jQuery(elem).data('input');
    if(jQuery(input).is('input')){
      //console.log('input');
      jQuery(elem).click(function(){
        var val=jQuery(elem).data('val');
        jQuery(input).val(val);
      });
    }
  });

}

//подгрузка данных при переключении блока как при select
function getInfOnSelectLikeChangeSelect(elemClass){
  jQuery(elemClass).each(function(idx,elem){
    //получим данные блока вывода
    var outBx=jQuery(elem).data('bx');
    //получим данные функции блока
    var fnc=jQuery(elem).data('fnc');
    if(outBx && fnc){
      jQuery(elem).click(function(){
        //получим данные значения по-умолчанию
        var val=jQuery(elem).data('info');
        //проверим, может есть инпут в кором другое значение
        var parentEl=jQuery(elem).closest('li');
        if(parentEl){
          //console.info('parent');
          //console.info(parentEl);
          var inpElm=jQuery(parentEl).find('select');
          if(inpElm){
            //console.info(inpElm);
            val=jQuery(inpElm).val();
          }
        }
        //console.info(val);
        //готовим для ajax запроса
        var ajaxData = new FormData();
        ajaxData.append('fnc-elem', 'bx-checkuot');
        ajaxData.append('param', fnc);
        ajaxData.append('val', val);
        var actionPath = '/ajax';
        jQuery.ajax({
          url: actionPath,
          type: 'POST',
          data: ajaxData,
          //dataType: 'json',
          cache: false,
          contentType: false,
          processData: false,
          success:function(respond, textStatus, jqXHR){
            //console.info(respond);
            respondJSON=JSON.parse(respond);
            if(!respondJSON.error){
              var html = respondJSON.html;
              if(html){
                jQuery(outBx).html(html);
              }
            }
          },
          error:function(jqXHR, textStatus, errorThrown){
            console.info(textStatus);
          }
        });
      });
      //а так же при загрузке, если надо
      var asActive = jQuery(elem).data('active');
      if(asActive && asActive !=''){
        var val=jQuery(elem).data('info');
        //готовим для ajax запроса
        var ajaxData = new FormData();
        ajaxData.append('fnc-elem', 'bx-checkuot');
        ajaxData.append('param', fnc);
        ajaxData.append('val', val);
        var actionPath = '/ajax';
        jQuery.ajax({
          url: actionPath,
          type: 'POST',
          data: ajaxData,
          //dataType: 'json',
          cache: false,
          contentType: false,
          processData: false,
          success:function(respond, textStatus, jqXHR){
            //console.info(respond);
            respondJSON=JSON.parse(respond);
            if(!respondJSON.error){
              var html = respondJSON.html;
              if(html){
                jQuery(outBx).html(html);
              }
            }
          },
          error:function(jqXHR, textStatus, errorThrown){
            console.info(textStatus);
          }
        });
      }
    }
  });
}

//подгрузка данных, при изменении input
function getInfOnChangeSelect(selectClass){
  jQuery(selectClass).each(function(idx,select){
    //получим данные блока вывода
    var outBx=jQuery(select).data('bx');
    //получим данные функции блока
    var fnc=jQuery(select).data('fnc');
    if(outBx && fnc){
      var lastValue=jQuery(select).val();
      jQuery(select).change(function(e){
        //проверим, активен ли родитель
        if(jQuery(select).closest('li').hasClass('active')){
          jQuery(select).removeClass('noacive');
          var val = jQuery(select).val();

          //готовим для ajax запроса
          var ajaxData = new FormData();
          ajaxData.append('fnc-elem', 'bx-checkuot');
          ajaxData.append('param', fnc);
          ajaxData.append('val', val);
          var actionPath = '/ajax';
          jQuery.ajax({
            url: actionPath,
            type: 'POST',
            data: ajaxData,
            //dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success:function(respond, textStatus, jqXHR){
              //console.info(respond);
              respondJSON=JSON.parse(respond);
              if(!respondJSON.error){
                var html = respondJSON.html;
                if(html){
                  jQuery(outBx).html(html);
                }
              }
            },
            error:function(jqXHR, textStatus, errorThrown){
              console.info(textStatus);
            }
          });
        }
        else {
          $(select).val(lastValue).addClass('noacive');
        }
      });
      //а так же при загрузке, если надо
      var asActive = jQuery(select).data('active');
      if(asActive && asActive !=''){

        var val = jQuery(select).val();
        //готовим для ajax запроса
        var ajaxData = new FormData();
        ajaxData.append('fnc-elem', 'bx-checkuot');
        ajaxData.append('param', fnc);
        ajaxData.append('val', val);
        var actionPath = '/ajax';
        jQuery.ajax({
          url: actionPath,
          type: 'POST',
          data: ajaxData,
          //dataType: 'json',
          cache: false,
          contentType: false,
          processData: false,
          success:function(respond, textStatus, jqXHR){
            //console.info(respond);
            respondJSON=JSON.parse(respond);
            if(!respondJSON.error){
              var html = respondJSON.html;
              if(html){
                jQuery(outBx).html(html);
              }
            }
          },
          error:function(jqXHR, textStatus, errorThrown){
            console.info(textStatus);
          }
        });
      }
      else {
        jQuery(select).addClass('noacive');
      }
    }
  });
}
//смена данных в одном селекте и при этом автоматически сменить и заблокировать другой селект
function selectCommonOnChange(selectClass,removeAllVars){
  jQuery(selectClass).each(function(idx,selectEl){
    //находим есть ли нужный объект
    var targetCommonSelect=jQuery(selectEl).data('cmnselect');
    if(jQuery(targetCommonSelect).is('select')){
      //то что нужно отслеживаем
      var oldValSelect=jQuery(selectEl).val();
      jQuery(selectEl).change(function(e){
        //если не менялось значение, не делаем ничего
        var valNew=this.value;
        //меняем иди выбираем, если есть
        if(removeAllVars){
          //удалим все, что там есть
          jQuery(targetCommonSelect).children().remove();
          //добавим толкьо нужный
          jQuery(targetCommonSelect).attr('readonly','readonly').append(jQuery("<option></option>", {value: valNew, text: valNew}));
        }
        else {
          jQuery(targetCommonSelect).val(valNew).attr('readonly','readonly');
        }
        //console.info(this.value+' change');
      });
    }
  });
}

//переход по ссулке для bottom
function buttonHrefLike(classElems){
  $(classElems).each(function(ind,button){
    var target=$(button).data('href');
    if(target !=''){
      $(button).click(function(){
        document.location.href = target;
      });
    }
  });
}
//анимация кружка В КОРЗИНУ
function addToCartFlyRound(classBtm){
  $(classBtm).each(function(idx,btm){
    var target=$(btm).data('objto');
    if(target.length>0){
      $(btm).click(function(e){
        var butWrap = $(btm).offsetParent(); /* Запоминаем враппер кнопки */
        //console.info(butWrap);
        $(butWrap).css('position','relative');
        $(butWrap).append('<div class="animtocart"></div>'); /* Добавляем во враппер кружок, который будет анимирован и улетать от кнопки в корзину */
        //console.info($(btm).offset());
        //var pX=e.pageX-25;
        var pX='82%';
        //var pY=e.pageY-25;
        var pY='54%';
        $('.animtocart').css({ /* Присваиваем стили кружку и позицию курсора мыши */
        	'position' : 'absolute',
        	'background' : '#FF9000',
        	'width' :  '25px',
        	'height' : '25px',
        	'border-radius' : '100px',
        	'z-index' : '9999999999',
        	'left' : pX,
        	'top' : pY,
        });
        var cart = $(target).offset(); /* Получаем местоположение корзины на странице, чтобы туда полетел кружок */
        //анимация
        var offsTop=0 - ($(butWrap).offset().top + 100);
        $('.animtocart').animate({ top: offsTop + 'px', left: '99%', width: 0, height: 0 }, 800, function(){
          // Делаем анимацию полёта кружка от кнопки в корзину и по окончанию, удаляем его
    		    $(this).remove();
        });
      });
    }
  });
}

jQuery(document).ready(function(){
  //ymaps.ready(getYmapsCoords);
  //геопозиционирование юзера

  //поставить отслеживания клика по botton
  buttonHrefLike('.hashref');
  //getYmapsCoords();//координаты смотрим
  //обернем шапку для создания скольжения
  flyHeader();
  //добавим обработчик клика на выбор элементов в любом элементе с классам
  checkChangeByClickOnParent('.check-on-click');
  //при клике на элементе, заполняем инпут его данными
  radioLikeInput('.input-fill-by');
  //помечаем блоки связанные с формой
  submitAlikeElemToForm('.submit-alike','can-submit','form-complite');
  //проверим все ли элементы необходимые в форме выбраны
  checkFillFields('.check-req-fields','.req-fld-1','can-submit');
  //checkFillFields('.check-req-fields',['.req-fld-1','.req-fld-2'],'can-submit');
  //отлавливания данных форм при шаге наза-вперед и перенос их на страницу
  backForvardStepForm('#pre-order-next');
  //сделать элементы плавающими в блоке
  flyElemInParent('.fly-button-bottom','bottom');
  //сделать элемент прикрепленным к верхнему краю блока
  flyElemInParent('.fly-button-top','top');
  //прокрутка к эелементу
  softScrollTo('.soft-scroll-t_target');
  //аякс перехват сабмита форм
  ajaxFormProcess('.to-order-ajax-form','#minicart-ajax-rfsh');
  //аякс перехват завершения заказа
  ajaxFormProcess('.ajax-check-send-form','#minicart-ajax-rfsh');
  //разрешаем тольоко цыфры
  onlyNumberInp('.numb-only');
  //вешаем кнопки увеличения в поле
  inpSumChangeWthBtm('.ajax-numgds');
  //изменение цены строки в зависимотсти от кол-ва
  changePriceByValueSum('.ajax-change-price');
  //отследим изменение миникорзины
  refreshMiniCart('#minicart-ajax-rfsh','/order');
  //обновление корзины, при изменении данных в проверке заказа
  //refshCartOnChange('#checkuot-bx-wrap input.refresh-form-on-focus','#checkuot-bx-wrap','has-changes-val');
  //удаление элементов из корзины по кнопке
  authUserCartAdd('.js-clear-this');
  //маска телеофна
  //переключим показ блока по клику
  swShowByClick('.sw-bx-show-click','active');
  //таблица сортировки
  //$("#user_list").tablesorter({sortList: [[0,1]]});
  jQuery('.jq-sortertable').each(function(idx,el){
    jQuery(el).tablesorter();
  });
  //таблица сортировки для товаров
  jQuery('.jq-sortertable_order').each(function(idx,el){
    jQuery(el).tablesorter({
      sortList:[[4,0]],
      headers:{5:{sorter:false},7:{sorter:false}}
    });
  });
  //таблица сортировки для товаров малая
  jQuery('.jq-sortertable_order_short').each(function(idx,el){
    jQuery(el).tablesorter({
      sortList:[[3,0]],
      headers:{4:{sorter:false},5:{sorter:false}}
    });
  });
  //открытие окна с отправкой данных по ajax для его наполнения
  openHoverWindContentAjax('.open-win-get-ajax');

  //отследим измнение select для смены данных в блоке
  getInfOnChangeSelect('.ajax-upl-inf');
  //отследим измениния в элемента-табах, по типу select
  getInfOnSelectLikeChangeSelect('.ajax-upl-inf-one');

  //связываем два селекта, чтобы при смене родительского, в дочернем сменялось или заменялось значение
  selectCommonOnChange('.chng-comm-select',true);

  //календарь всплывающий


  //анимация добавить в корзину
  addToCartFlyRound('.add-tocart-animr');
});

/***
number - исходное число
decimals - количество знаков после разделителя
dec_point - символ разделителя
thousands_sep - разделитель тысячных
***/
function number_format(number, decimals, dec_point, thousands_sep) {
  number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + (Math.round(n * k) / k)
        .toFixed(prec);
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
    .split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '')
    .length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1)
      .join('0');
  }
  return s.join(dec);
}

//  Пример 1: number_format(1234.56);
//  Результат: '1,235'

//  Пример 2: number_format(1234.56, 2, ',', ' ');
//  Результат: '1 234,56'

//  Пример 3: number_format(1234.5678, 2, '.', '');
//  Результат: '1234.57'

//  Пример 4: number_format(67, 2, ',', '.');
//  Результат: '67,00'

//  Пример 5: number_format(1000);
//  Результат: '1,000'

//  Пример 6: number_format(67.311, 2);
//  Результат: '67.31'

//  Пример 7: number_format(1000.55, 1);
//  Результат: '1,000.6'

//  Пример 8: number_format(67000, 5, ',', '.');
//  Результат: '67.000,00000'

//  Пример 9: number_format(0.9, 0);
//  Результат: '1'

//  Пример 10: number_format('1.20', 2);
//  Результат: '1.20'

//  Пример 11: number_format('1.20', 4);
//  Результат: '1.2000'

//  Пример 12: number_format('1.2000', 3);
//  Результат: '1.200'

//  Пример 13: number_format('1 000,50', 2, '.', ' ');
//  Результат: '100 050.00'

//  Пример 14: number_format(1e-8, 8, '.', '');
//  Результат: '0.00000001'
