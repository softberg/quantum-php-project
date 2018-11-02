(function($){"use strict";var _UI=window._UI||{};window._UI=_UI;_UI.animations=function(){$.each($('.header-content-inner'),function(index,val){var element=$(val),transition='';if(element.hasClass('top-t-bottom'))
transition='top-t-bottom';if(element.hasClass('bottom-t-top'))
transition='bottom-t-top';if(element.hasClass('left-t-right'))
transition='left-t-right';if(element.hasClass('right-t-left'))
transition='right-t-left';if(element.hasClass('zoom-in'))
transition='zoom-in';if(element.hasClass('zoom-out'))
transition='zoom-out';if(element.hasClass('alpha-anim'))
transition='alpha-anim';if(transition!=''){$(val).removeClass(transition);var container=element,containerDelay=container.attr('data-delay'),containerSpeed=container.attr('data-speed'),items=$('.header-title > *, .post-info',container);$.each(items,function(index,val){var element=$(val),delayAttr=(containerDelay!=undefined)?containerDelay:400;if(!element.hasClass('animate_when_almost_visible')){delayAttr=Number(delayAttr)+(400*index);if(containerSpeed!=undefined)
element.attr('data-speed',containerSpeed);element.addClass(transition+' animate_when_almost_visible').attr('data-delay',delayAttr)}});container.css('opacity',1)}});if(!window.waypoint_animation){window.waypoint_animation=function(){$.each($('.animate_when_almost_visible:not(.start_animation):not(.t-inside), .tmb-media .animate_when_almost_visible:not(.start_animation)'),function(index,val){var run=!0;if($(val).closest('.owl-carousel').length>0)
run=!1;if(run){new Waypoint({element:val,handler:function(){var element=$(this.element),index=element.index(),delayAttr=element.attr('data-delay');if(delayAttr==undefined)
delayAttr=0;setTimeout(function(){element.addClass('start_animation')},delayAttr);this.destroy()},offset:_UI.isFullPage?'100%':'90%'})}})}}
var runWaypoints=function(){setTimeout(function(){window.waypoint_animation()},100)};runWaypoints()};_UI.init=function(){_UI.animations()}
_UI.init()})(jQuery)