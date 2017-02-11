(function($){
    var callback_admin_obj = {
        tab_link:".tabs-menu a",
        tab_content:".tab-content",

        init:function()
        {
            this.tab_click();
            this.hide_empty_fields();
        },

        tab_click:function()
        {
            var _this = this;
            $(_this.tab_link).click(function(event) {
                event.preventDefault();
                $(this).parent().addClass("current");
                $(this).parent().siblings().removeClass("current");
                var tab = $(this).attr("href");
                $(_this.tab_content).not(tab).css("display", "none");
                $(tab).fadeIn();
            });

        },
        
        hide_empty_fields:function()
        {
            var _this = this;
            $(_this.tab_content).each(function() {
                var tab_th = $(_this.tab_content).find('th');
                $(tab_th).each(function() {
                    if ($(this).closest('div').find('td.'+$(this).attr('class')).length == 0) {
                        $(this).hide();
                    }
                })
            });
        }
    };

    $(document).ready(function() {
        callback_admin_obj.init();
    });
})(jQuery);