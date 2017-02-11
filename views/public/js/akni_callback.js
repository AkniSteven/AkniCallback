'use strict';
(function($){
    class Validator {
        constructor(form, name, tel, email, counter, thankyou, ajaxUrl) {
            this.counter = counter;
            this.form = form;
            this.name = name;
            this.tel  = tel;
            this.email= email;
            this.thankyou = thankyou;
            this.ajaxUrl = ajaxUrl;
            this.init();
        }
        
        init(){
            this.phone_mask();
            this.name_mask();
            this.validate_change_action();
            this.validate_click_action();
        }
        
        is_valid_phone_number(phone_number) {
            var regExpObj = /\(\d\d\d\)-\d\d-\d\d-\d\d\d/;
            return !(regExpObj.exec(phone_number) == null || phone_number.length != 15);
        }
        
        is_valid_name(name) {
            return name.length > 1 && name.length < 40;
        }
        
        is_valid_email(email){
            var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/;
            return re.test(email);
        }
        
        name_mask(){
            $(this.name).keydown(function(){
                if(/[^a-zA-Zа-яА-ЯёЁ`ґєҐЄ´ІіЇї .]/i.test(this.value)){
                    this.value = this.value.slice(0, -1)
                }
            });
            $(this.name).keyup(function(){
                if(/[^a-zA-Zа-яА-ЯёЁ`ґєҐЄ´ІіЇї .]/i.test(this.value)){
                    this.value = this.value.slice(0, -1)
                }
            });
        }
        
        phone_mask(){
            $(this.tel).mask("(999)-99-99-999")
        }
        
        validate_name(name){
            var _this = this;
            var errClass ='name-error-msg-'+this.counter;
            var errSelector = 'span.name-error-msg-'+this.counter;

            if (! _this.is_valid_name($(name).val())) {
                $(name).toggleClass('field-error', true);
                if (!$(errSelector).html()) {
                    $(name).after('<span class="'+errClass+'">' + $(name).attr('data-error') + '</span>');
                }
            } else {
                $(name).toggleClass('field-error', false);
                $(errSelector).fadeOut().remove();
            }
        }
        
        validate_tel(tel){
            var _this = this;
            var errClass ='tel-error-msg-'+this.counter;
            var errSelector = 'span.tel-error-msg-'+this.counter;

            if (! _this.is_valid_phone_number($(tel).val())) {
                $(tel).toggleClass('field-error', true);

                if (!$(errSelector).html()) {
                    $(tel).after('<span class="'+errClass+'">' + $(tel).attr('data-error')  + '</span>');
                }
            } else {
                $(tel).toggleClass('field-error', false);
                $(errSelector).fadeOut().remove();
            }
        }
        
        validate_email(email){
            var _this = this;
            var errClass ='email-error-msg-'+this.counter;
            var errSelector = 'span.email-error-msg-'+this.counter;

            if (! _this.is_valid_email($(email).val())) {
                $(email).toggleClass('field-error', true);

                if (!$(errSelector).html()) {
                    $(email).after('<span class="'+errClass+'">' + $(email).attr('data-error') + '</span>');
                }
            } else {
                $(email).toggleClass('field-error', false);
                $(errSelector).fadeOut().remove();
            }
        }
        
        validate_change_action(){
            var _this = this;

            $(_this.tel).change(function(){
                _this.validate_tel(this);
            });
            $(_this.name).change(function(){
                _this.validate_name(this);
            });
            $(_this.email).change(function(){
                _this.validate_email(this);
            });
        }
        
        sendForm(data) {
            var _this = this;
            $.ajax({
                type: 'POST',
                url: _this.ajaxUrl,
                data: {
                    'action':'sendCallback',
                    'data': data
                },
                success: function () {
                    $(_this.form).hide();
                    $(_this.thankyou).show();
                },
                error: function () {
                    console.log('this is error. Tell about it to me please (stevenaknidev@gmail.com).')
                }

            })
        }
        
        validate_click_action(){
            var _this = this;
            $(_this.form).submit(function(evt) {
                var valid = true;
                if ($(_this.tel).attr('name') != undefined) {
                    if (!_this.is_valid_phone_number($(_this.tel).val())) {
                        _this.validate_tel(_this.tel);
                        valid = false;
                    }
                }
                if ($(_this.name).attr('name') != undefined) {
                    if(! _this.is_valid_name($(_this.name).val())) {
                        _this.validate_name(_this.name);
                        valid = false;
                    }
                }
                if ($(_this.email).attr('name') != undefined) {
                    if(! _this.is_valid_email($(_this.email).val())) {
                        _this.validate_email(_this.email);
                        valid = false;
                    }
                }
                if(!valid) {
                    return false;
                }
                evt.preventDefault();
                _this.sendForm($(this).serialize());
            });
        }

    }
    
    $(document).ready(function () {
        $(".callback-form").each(function(i) {
            var currentId =   '#'+this.id;
            var ajaxUrl   =    $(currentId).data('url');
            var currentName = '#'+ $(currentId).find('.name').attr('id');
            var currentTel  = '#'+ $(currentId).find('.tel').attr('id');
            var currentEmail= '#'+ $(currentId).find('.email').attr('id');
            var thankYouText ='#'+ $(currentId).parent().find('.thank-you').attr('id');
            new Validator(currentId,currentName,currentTel,currentEmail,i,thankYouText,ajaxUrl);
        });
    });
})(jQuery);