
        
        
/*
* sPreLoader - jQuery plugin
* Create a Loading Screen to preloader images and content for you website
*
* Name:         sPreLoader.js
* Author:       Siful Islam - http://www.getsiful.com
* Date:         06.08.16       
* Version:      1.0
* Example:  
*   
*/

(function($) {
    var items               = new Array(),
        errors              = new Array(),
        onComplete          = function() {},
        current             = 0;
    
    var jpreOptions = {
        preMainSection:     '#main-preloader',
        prePerText:         '.preloader-percentage-text',
        preBar:             '.preloader-bar',
        showPercentage:     true,
        debugMode:          false,
        splashFunction:     function() {}
    }
    
    var getImages = function(element) {
        $(element).find('*:not(script)').each(function() {
            var url = "";

            if ($(this).css('background-image').indexOf('none') == -1) {
                url = $(this).css('background-image');
                if(url.indexOf('url') != -1) {
                    var temp = url.match(/url\((.*?)\)/);
                    url = temp[1].replace(/\"/g, '');
                }
            } else if ($(this).get(0).nodeName.toLowerCase() == 'img' && typeof($(this).attr('src')) != 'undefined') {
                url = $(this).attr('src');
            }
            
            if (url.length > 0) {
                items.push(url);
            }
        });
    }
    
    var preloading = function() {
        for (var i = 0; i < items.length; i++) {
            loadImg(items[i]);
        }
    }
    
    var loadImg = function(url) {
        var imgLoad = new Image();
        $(imgLoad)
        .load(function() {
            completeLoading();
        })
        .error(function() {
            errors.push($(this).attr('src'));
            completeLoading();
        })
        .attr('src', url);
    }
    
    var completeLoading = function() {
        current++;

        var per = Math.round((current / items.length) * 100);
        $(jBar).stop().animate({
            width: per + '%'
        }, 500, 'linear');
        
        if(jpreOptions.showPercentage) {
            $(jPer).text(per);
        }
        
        if(current >= items.length) {
        
            current = items.length;
            
            if (jpreOptions.debugMode) {
                var error = debug();
                
            } 
            loadComplete();
        }
    }
    
    var loadComplete = function() {
        $(jBar).stop().animate({
            width: '100%'
        }, 500, 'linear', function() {
            $(jOverlay).fadeOut(800, function() {
                onComplete();
            });
        }); 
    }
    
    var debug = function() {
        if(errors.length > 0) {
            var str = 'ERROR - IMAGE FILES MISSING!!!\n\r'
            str += errors.length + ' image files cound not be found. \n\r'; 
            str += 'Please check your image paths and filenames:\n\r';
            for (var i = 0; i < errors.length; i++) {
                str += '- ' + errors[i] + '\n\r';
            }
            return true;
        } else {
            return false;
        }
    }
    
    var createContainer = function(tar) {

        jOverlay    = $( jpreOptions.preMainSection );
        jBar        = jOverlay.find( jpreOptions.preBar );
        jPer        = jOverlay.find( jpreOptions.prePerText );
     
    }
    
    $.fn.jpreLoader = function(options, callback) {
        if(options) {
            $.extend(jpreOptions, options );
        }
        if(typeof callback == 'function') {
            onComplete = callback;
        }
        
        createContainer(this);
        getImages(this);
        preloading();
        return this;
    };

})(jQuery);

