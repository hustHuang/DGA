(function (jQuery) {
    jQuery.fn.inputLabel = function(text,opts) {
        o = jQuery.extend({ color: "#666", e:"focus", force : false, keep : true}, opts || {});
        var clearInput = function (e) {
            var target = jQuery(e.target);
            var value = jQuery.trim(target.val());
            if (e.type == e.data.obj.e && value == e.data.obj.innerText) {
                jQuery(target).css("color", "").val("");
                if (!e.data.obj.keep) {
                    jQuery(target).unbind(e.data.obj.e+" blur",clearInput);
                }
            } else if (e.type == "blur" && value == "" && e.data.obj.keep) {
                jQuery(this).css("color", e.data.obj.color).val(e.data.obj.innerText);
            }
        };
        return this.each(function () {
                    o.innerText = (text || false);
                    if (!o.innerText) {
                        var id = jQuery(this).attr("id");
                        o.innerText = jQuery(this).parents("form").find("label[for=" + id + "]").hide().text();
                    }
                    else 
                        if (typeof o.innerText != "string") {
                            o.innerText = jQuery(o.innerText).text();
                        }
            o.innerText = jQuery.trim(o.innerText);
            if (o.force || jQuery(this).val() == "") {
                jQuery(this).css("color", o.color).val(o.innerText);
            }
                jQuery(this).bind(o.e+" blur",{obj:o},clearInput);
            
        });
    };
})(jQuery);