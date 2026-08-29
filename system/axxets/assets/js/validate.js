(function ($) {

    /*$.fn.nccValidate = function (options) {
     this.submit(function () {
     var onSubmit = (typeof window[options.onSubmit] == "function") ? options.onSubmit : "";
     return Validate($(this)) && (typeof window[onSubmit] != "function" || window[options.onSubmit]());
     }
     );
     //Code to handle direct calls to document.forms[...].submit()
     if (typeof this.attr('name') != "undefined") {
     var _formName = this.attr('name');
     var oldSubmit = document.forms[_formName].submit;
     var onSubmit = (typeof window[options.onSubmit] == "function") ? options.onSubmit : "";
     document.forms[_formName].submit = function () {
     if (Validate($(this)) && (typeof window[onSubmit] != "function" || window[options.onSubmit]()))
     oldSubmit.call(document.forms[_formName], arguments);
     }
     }
     function Validate(form) {
     options.style = (typeof options.style != "undefined" && options.style != "") ? options.style : "";
     options.messages = (typeof options.messages != "undefined" && options.messages == false) ? false : true;
     options.highlight = (typeof options.highlight != "undefined" && options.highlight == false) ? false : true;
     options.validableStyle = (typeof options.validableStyle != "undefined") ? options.validableStyle : 'ncc';
     var _validationResult = true;
     var _errorMessage = "";
     form.find('.' + options.validableStyle).each(function () {
     if ($(this).is(':disabled'))
     return;
     if (!_validationResult || (typeof $(this).attr('mandatory') == "undefined" && $(this).val() == '') || (typeof $(this).attr('mandatory') != "undefined" && $(this).attr('mandatory').toString() != 'true' && $(this).val() == ''))
     return;
     else {
     if (typeof $(this).attr('mandatory') != "undefined" && $(this).val() == '' && $(this).attr('mandatory').toString() == 'true')
     _elementIsValid = false;
     else {
     var imageRegex = /^.+\.(jpg|jpeg|gif|png)$/i;
     var phoneRegex = /^(\+)?((\s|\-)?[0-9]+)+$/;
     var numberRegex = /^[0-9]+$/;
     var numericRegex = /^(\-)?[0-9]+(\.[0-9]+)?$/;
     var moneyRegex = /^[0-9]+((\.|\,)[0-9]+)*$/;
     var moneyNoThousandRegex = /^[0-9]+(\.[0-9]+)?$/;
     var emailRegex = /^[^@]+@[^@]+\.[^@]+$/;
     var urlRegex = /^http(s)?\:[^\.]+\..+/;
     var dateRegex = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
     var minLength = (numberRegex.exec($(this).attr('mincount')) == null) ? 0 : $(this).attr('mincount');
     var maxLength = (numberRegex.exec($(this).attr('maxcount')) == null) ? 0 : $(this).attr('maxcount');
     var currentValue = ($(this).attr('content') != 'rich-text') ? $(this).val() : tinyMCE.get($(this).attr('id')).getContent();
     var _elementIsValid = true;
     switch ($(this).attr('content')) {
     case "text":
     _elementIsValid = !(currentValue.length < minLength || (maxLength != 0 && currentValue.length > maxLength));
     break;
     case "number":
     _elementIsValid = numberRegex.exec(currentValue) != null && parseInt(currentValue) >= minLength && (maxLength == 0 || parseInt(currentValue) <= maxLength);
     break;
     case "numeric":
     _elementIsValid = currentValue.match(numericRegex) && Math.abs(currentValue) >= minLength && (maxLength == 0 || Math.abs(currentValue) <= maxLength);
     break;
     case "money":
     _elementIsValid = moneyRegex.exec(currentValue) != null && parseFloat(currentValue) >= minLength && (maxLength == 0 || parseFloat(currentValue) <= maxLength);
     break;
     case "amount":
     _elementIsValid = moneyNoThousandRegex.exec(currentValue) != null && parseFloat(currentValue) >= minLength && (maxLength == 0 || parseFloat(currentValue) <= maxLength);
     break;
     case "email":
     _elementIsValid = emailRegex.exec(currentValue) != null;
     break;
     case "url":
     _elementIsValid = urlRegex.exec(currentValue) != null;
     break;
     case "phone":
     _elementIsValid = phoneRegex.exec(currentValue) != null;
     break;
     case "checkbox":
     if (typeof $(this).attr('mandatory') != "undefined")
     _elementIsValid = ($(this).attr('mandatory').toString() == "true" && $(this).is(':checked')) || $(this).attr('mandatory').toString() != "true";
     break;
     case "image":
     _elementIsValid = imageRegex.exec(currentValue) != null;
     break;
     case "date":
     _elementIsValid = dateRegex.exec(currentValue) != null;
     break;
     default:
     if (typeof $(this).attr('expression') != "undefined") {
     var regex = new RegExp($(this).attr('expression'));
     _elementIsValid = regex.exec(currentValue) != null;
     }
     break;
     }
     }
     if (_elementIsValid && typeof $(this).attr('in') != "undefined") {
     var exclusiveValues = $(this).attr('in').split(',');
     _elementIsValid = $.inArray(currentValue, exclusiveValues) != -1;
     }
     if (_elementIsValid && typeof $(this).attr('not') != "undefined") {
     var excludeValues = $(this).attr('not').split(',');
     _elementIsValid = $.inArray(currentValue, excludeValues) == -1;
     }
     if (!_elementIsValid) {
     var errorMessage = $(this).attr('message');
     errorMessage = (typeof errorMessage == "undefined" || errorMessage == '') ? "Please correct the highlighted errors" : errorMessage;
     if (options.highlight)
     $(this).addClass(options.style);
     if (options.messages)
     alert(errorMessage);
     _validationResult = false;
     $(this).focus();
     }
     else if (options.style != '')
     $(this).removeClass(options.style);

     }
     });

     return _validationResult;
     }
     };*/
	 
    $.fn.bubble = function (message) {
        $('.bubble').remove();
        var position = $(this).position();
        var bubbleId = Math.floor(Math.random());
        var bubbleLeft = Math.floor((position.left + ($(this).width() / 2)) - 97);
        var messageToShow = (typeof message == 'undefined' || message == '') ? $(this).attr('message') : message;
        toAppend = '<div id="error-' + bubbleId + '" class="bubble" style="position:absolute;left:' + bubbleLeft.toString() + 'px;top:' + (position.top - 35).toString() + 'px;">' + messageToShow + '</div>';
        $(toAppend).insertBefore($(this));
        setTimeout("HideBubble('" + bubbleId + "');", 3000);
    };
    $.fn.validate = function (options) {

        options.style = (typeof options.style != "undefined" && options.style != "") ? options.style : "";
        options.messages = (typeof options.messages != "undefined" && options.messages == false) ? true : false;
        options.bubble = (typeof options.bubble != "undefined" && options.bubble == true) ? true : false;
        options.highlight = (typeof options.highlight != "undefined" && options.highlight == false) ? false : true;
        options.validableStyle = (typeof options.validableStyle != "undefined") ? options.validableStyle : 'ncc';
        var _validationResult = true;
        var _errorMessage = "";
        this.find('.' + options.validableStyle).each(function () {
            var currentValue = ($(this).attr('content') != 'rich-text') ? $(this).val() : tinyMCE.get($(this).attr('id')).getContent();
            if ($(this).is(':disabled'))
                return;
            if (!_validationResult || (typeof $(this).attr('mandatory') == "undefined" && currentValue == '') || (typeof $(this).attr('mandatory') != "undefined" && $(this).attr('mandatory').toString() != 'true' && currentValue == ''))
                return;
            else {
                if (typeof $(this).attr('mandatory') != "undefined" && currentValue == '' && $(this).attr('mandatory').toString() == 'true')
                    _elementIsValid = false;
                else {
                    var imageRegex = /^.+\.(jpg|jpeg|gif|png)$/i;
                    var phoneRegex = /^(\+)?((\s|\-)?[0-9]+)+$/;
                    var numberRegex = /^[0-9]+$/;
                    var alphpnumRegex = /^[a-zA-Z0-9]+$/;
                    var numericRegex = /^(\-)?[0-9]+(\.[0-9]+)?$/;
                    var moneyRegex = /^[0-9]+((\.|\,)[0-9]+)*$/;
                    var moneyNoThousandRegex = /^[0-9]+(\.[0-9]+)?$/;
                    var emailRegex = /^[a-zA-Z0-9\-_\.]+@[a-zA-Z0-9\-_\.]+\.[a-zA-Z0-9\-_\.]+$/;
                    var urlRegex = /^http(s)?\:[^\.]+\..+/;
                    var dateRegex = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
                    var minLength = (numberRegex.exec($(this).attr('mincount')) == null) ? 0 : $(this).attr('mincount');
                    var maxLength = (numberRegex.exec($(this).attr('maxcount')) == null) ? 0 : $(this).attr('maxcount');
					var validateCode = /^[a-z]+[a-z0-9_]+$/;
					
                    var _elementIsValid = true;
                    if (currentValue == $(this).attr('title'))
                        _elementIsValid = false;
                    else {
                        switch ($(this).attr('content')) {
                            case "text":
                                _elementIsValid = !(currentValue.length < minLength || (maxLength != 0 && currentValue.length > maxLength));
                                break;
                            case "number":
                                _elementIsValid = numberRegex.exec(currentValue) != null && parseInt(currentValue) >= minLength && (maxLength == 0 || parseInt(currentValue) <= maxLength);
                                break;
                            case "numeric":
                                _elementIsValid = currentValue.match(numericRegex) && Math.abs(currentValue) >= minLength && (maxLength == 0 || Math.abs(currentValue) <= maxLength);
                                break;
                            case "money":
                                _elementIsValid = moneyRegex.exec(currentValue) != null && parseFloat(currentValue) >= minLength && (maxLength == 0 || parseFloat(currentValue) <= maxLength);
                                break;
                            case "amount":
                                _elementIsValid = moneyNoThousandRegex.exec(currentValue) != null && parseFloat(currentValue) >= minLength && (maxLength == 0 || parseFloat(currentValue) <= maxLength);
                                break;
                            case "email":
                                _elementIsValid = emailRegex.exec(currentValue) != null;
                                break;
                            case "alphanum":
                                _elementIsValid = alphpnumRegex.exec(currentValue) != null;
                                break;
                            case "url":
                                _elementIsValid = urlRegex.exec(currentValue) != null;
                                break;
                            case "phone":
                                _elementIsValid = phoneRegex.exec(currentValue) != null;
                                break;
                            case "checkbox":
                                if (typeof $(this).attr('mandatory') != "undefined")
                                    _elementIsValid = ($(this).attr('mandatory').toString() == "true" && $(this).is(':checked')) || $(this).attr('mandatory').toString() != "true";
                                break;
                            case "date":
                                _elementIsValid = dateRegex.exec(currentValue) != null;
                                break;
                            case "image":
                                _elementIsValid = imageRegex.exec(currentValue) != null;
                                break;

                            case "check_group_name":
                                if (typeof $(this).attr('mandatory') != "undefined") {
                                    var inputtype = $(this).attr("type");
                                    var inputname = $(this).attr("name");
                                    var checked = $("input[name='" + inputname + "']:checked").length;
                                    if (checked == 0)
                                        _elementIsValid = false;
                                }
                                break;
							 case "validate_code":
                                _elementIsValid = validateCode.exec(currentValue) != null;
								 if (!_elementIsValid) {
									$(this).attr('message','Invalid value.');
								 }
                                break;
                            default:
                                if (typeof $(this).attr('expression') != "undefined") {
                                    var regex = new RegExp($(this).attr('expression'));
                                    _elementIsValid = regex.exec(currentValue) != null;
                                }
                                break;
                        }
                    }
                }
                if (_elementIsValid && typeof $(this).attr('in') != "undefined") {
                    var exclusiveValues = $(this).attr('in').split(',');
                    _elementIsValid = $.inArray(currentValue, exclusiveValues) != -1;
                }
                if (_elementIsValid && typeof $(this).attr('not') != "undefined") {
                    var excludeValues = $(this).attr('not').split(',');
                    _elementIsValid = $.inArray(currentValue, excludeValues) == -1;
                }
                if (!_elementIsValid) {
                    var errorMessage = $(this).attr('message');
                    errorMessage = (typeof errorMessage == "undefined" || errorMessage == '') ? "Please correct the highlighted errors" : errorMessage;
                    if (options.highlight)
                        $(this).addClass(options.style);
                    if (options.messages || (options.bubble && $(this).attr('content') == 'rich-text'))
                        alert(errorMessage);
                    else if (options.bubble) {
                        $('.bubble').remove();
                        var position = $(this).position();
                        var bubbleId = Math.floor(Math.random());
                        var bubbleLeft = Math.floor((position.left + ($(this).width() / 2)) - 97);
                        toAppend = '<div id="error-' + bubbleId + '" class="bubble" style="width:240px;height:33px;position:absolute;left:' + bubbleLeft.toString() + 'px;top:' + (position.top - 35).toString() + 'px;">' + errorMessage + '</div>';
                        $(toAppend).insertBefore($(this));
                        setTimeout("HideBubble('" + bubbleId + "');", 3000);
                    }
                    _validationResult = false;
                    $(this).focus();
                }
                else if (options.style != '')
                    $(this).removeClass(options.style);

            }
        });
        return _validationResult;
    };
})(jQuery);
function HideBubble(bubbleId) {
    $('#error-' + bubbleId).fadeOut(500);
}