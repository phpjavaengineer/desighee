/**
 * @Package Module: Aassgroup_Designer
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */
var DD_Share_Model = DD_ModelBase.extend({

    constMargin: 10,

    init: function (obj) {
        this.obj = obj;
        this._super(obj);
    },

    initShareFb: function (mainModel) {
        this.mainModel = mainModel;
        var self = this;
        this.obj.get().on('click', function () {
            self.sendData('facebook');
        });
    },
    
    initShareTw: function(mainModel) {
        this.mainModel = mainModel;
        var self = this;
        this.obj.get().on('click', function () {
            self.sendData('twitter');
        });
    },
    
    initSharePn: function(mainModel) {
        this.mainModel = mainModel;
        var self = this;
        this.obj.get().on('click', function () {
            self.sendData('pinterest');
        });
    },
    
    initMainClick: function() {
        var obj = this.obj;
        var self = this;
        obj.get().on('click', function () {
            
            for(var a in obj.controlButtons) {
                
                var objButton = obj.controlButtons[a];
                if(!$(this).attr('data-active')) {
                    $(objButton.get())
                            .css({opacity: 1.0, visibility: "hidden"})
                            .animate({opacity: 0}, 200);
                }else{
                    $(objButton.get())
                            .css({opacity: 0, visibility: "visible"})
                            .animate({opacity: 1.0}, 200);
                }
            }
            var currentButton = obj.get();
            var buttonTop = obj.get().offset().top;
            
            for(var i in obj.shareButtons) {
                var shareButton = obj.shareButtons[i];
                var prev = $(currentButton).prev();
                if(obj.get().next()) {
                    var baseTop = obj.get().next().offset().top;
                    var top = prev.offset().top - baseTop;

                    if(!$(this).attr('data-active')) {
                        $(shareButton.get())
                                .css({top: (buttonTop-baseTop - self.constMargin), display: "block"})
                                .animate({top: top - self.constMargin},  200);
                    }else{
                        $(shareButton.get())
                                .css({top: (buttonTop-baseTop - self.constMargin), display: "none"});
                    }

                    currentButton = prev;
                }
                
            }
            
            if($(this).attr('data-active')) {
                $(this).removeAttr('data-active');
                $(this).removeClass('fa-close');
                $(this).addClass('fa-share-alt');
            }else{
                $(this).attr('data-active', '1');
                $(this).addClass('fa-close');
                $(this).removeClass('fa-share-alt');
            }
        });
    },

    sendData: function (type) { //fb or twitter or pinterest
        var self = this;
        if(this.mainModel.mainConfig != null) {
            var config = this.mainModel.mainConfig[this.mainModel.obj.options.media_id];
        }
        switch (type) {
            case 'facebook':
                var _class = 'fa-facebook';
                break;
                
            case 'twitter': 
                var _class = 'fa-twitter';
                break;
                
            case 'pinterest': 
                var _class = 'fa-pinterest';
                break;

        }
        
        if(typeof(config) == 'undefined') {
            alert(this._('no_design_for_share'));
            this.hideLoading(_class);
            return;
        }
        
        this.showLoading(_class);
        this.mainModel.unselectAll();
        
        $.ajax({
            url: this.mainModel.shareUrl,
            type: 'json',
            method: 'post',
            
            data: {
                'type': type,
                'img': this.mainModel.getDataImg(),
                'share_config': JSON.stringify(config),
                'product_id': this.mainModel.obj.options.parent_product_id,
                
                'form_key': $('[name="form_key"]').val()
            }
        })
                .done(function (response) {
                    self.hideLoading(_class);
                    if(response.error) {
                        alert(response.errMessage);
                        return;
                    }
                    if(response.share_url) {
                        window.open(response.share_url);
                    }
                });
    },

    showLoading: function (_class) {
        this.obj.get().removeClass(_class)
                .addClass('fa-circle-o-notch')
                .addClass('fa-spin');
    },

    hideLoading: function (_class) {
        this.obj.get().removeClass('fa-spin')
                .removeClass('fa-circle-o-notch')
                .addClass(_class);
    }
});
