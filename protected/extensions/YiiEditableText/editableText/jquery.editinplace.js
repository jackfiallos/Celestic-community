/**
 * inlineEdit function file for jQuery.
 *
 * @author Jackfiallos <erlingfiallos@gmail.com>
 * @link http://jackfiallos.com
 */

(function($) {
	$.fn.inlineEdit = function(options) {
        options = $.extend({
            hoverClass: 'hover',
            saveToUrl: '',
            buttonText: 'Save',
			onwaitClass: 'loading',
			name: '_edit',
			id: 0,
			token: '',
			ajax: false,
			requestType: "POST",
        }, options);

        return $.each(this, function() {
            $.inlineEdit(this, options);
        });
    }
	
	$.inlineEdit = function(obj, options) {
		var self = $(obj);
		var editing  = false;
		self.bind('dblclick', function(event) {
			if (editing) return;
			var ele = $(this);
			var text = ele.html().trim().replace(/\<br(\s*\/|)\>/g, '\r\n').trim();
			var newText;
			var textarea = '<div id="editInPlace" title="'+options.tooltip+'"><textarea id="'+options.name+'" rows="6" style="width:98%;">'+text+'</textarea>';
			var button	 = '<div><input id="_save" type="button" value="'+options.buttonText+'" /> <input id="_cancel" type="button" value="Cancel" /></div></div>';
			ele.contents().remove();
			ele.append(textarea+button);
			$(options.name).focus();
			editing = true;
			
			$('#_save').click(function(e) {
				e.preventDefault();
				newText = $("#editInPlace > textarea").val().replace(/\n/g, "<br />").replace(/\n\n+/g, '<br /><br />');
				$.ajax({
					url: options.saveToUrl,
					type: options.requestType,
					dataType: "html",
					data: {
						Comments: {comment_text:newText},
						id: options.id,
						YII_CSRF_TOKEN: options.token,
						ajax: options.ajax,
					},
					beforeSend:function(){
						self.addClass(options.onwaitClass);
					},
					success:function(result){
						self.removeClass(options.onwaitClass);
						newText = result;
					},
				});
				self.contents().remove();
				self.html(newText);
				editing = false;
			});
			
			$('#_cancel').click(function(e){
				e.preventDefault();
				newText = $("#editInPlace > textarea").val().replace(/\n/g, "<br />").replace(/\n\n+/g, '<br /><br />');
				self.contents().remove();
				self.html(newText);
				editing = false;
			});
		}).hover(
            function(){
                $(this).addClass(options.hoverClass);
            },
            function(){
                $(this).removeClass(options.hoverClass);
            }
        );
	};
})(jQuery);