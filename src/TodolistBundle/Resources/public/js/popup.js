/**
 * creates a popup object with optional callback function.
 *
 * @param string headerText
 * @param string bodyText
 * @param string type
 * @param function callback
 * @constructor
 */
var Popup = function (headerText, bodyText, type, callback) {
    this.type = type || 'message'; //default
    this.headerText = headerText;
    this.bodyText = bodyText;
    this.overlayHeight = $('body').height();
    this.height = null;
    this.adjustedHeight = null; // we set this later depending on the amount of content
    this.confirmed = null;
    this.prompt = null;
    this.rawHtml = null;

    //optional callback function
    this.callback = callback || null;

    if (type === 'confirm') {
        this.closableByClickingAnywhere = false;
    } else {
        this.closableByClickingAnywhere = true;
    }
};

Popup.prototype.close = function () {
    $('.overlay--black').remove();
    $('.popup').remove();
};

//creates a stylable overlay
Popup.prototype.createOverlay = function() {
    var self = this;

    var overlay = $('<div/>')
        .addClass('overlay--black');

    if (this.closableByClickingAnywhere) {
        overlay.click(self, function () {
            self.close();

            if (self.callback !== null) {
                self.callback();
            }
        });
    } else {
        overlay.addClass('cursor--default');
    }

    overlay.appendTo($('body'));
};

//creates a stylable  popup window
Popup.prototype.createPopupBody = function() {
    var self = this;

    var popup = $('<div/>')
        .addClass('popup');
    if (self.closableByClickingAnywhere) {
        popup.click(self, function () {
            self.close();
            if (self.callback !== null) {
                self.callback();
            }
        });
    } else {
        popup.addClass('cursor--default');
    }

    popup.appendTo($('body'));

    return popup;
};

Popup.prototype.show = function () {
    //remove active instances from the DOM
    $('.popup').remove();

    //To modify the current popup, it is necessary to pass the current popup object
    var self = this;

    self.createOverlay();
    var popup  = self.createPopupBody();

    if (self.headerText) {
        $('<h3/>')
            .text(this.headerText)
            .appendTo(popup);
    }

    if (self.bodyText) {
        $('<p/>')
            .text(this.bodyText)
            .appendTo(popup);
    }

    if (self.rawHtml) {
        $('<div/>')
            .html(this.rawHtml)
            .appendTo(popup);
    }

    //add elements depending on the type of popup
    switch (self.type) {
        case 'confirm':
            var buttonWrapper = $('<div/>')
                .addClass('popup_button_wrapper')
                .appendTo(popup);

            //yes button
            $('<button/>')
                .text('Ja')
                .addClass('button_popup--confirm')
                .addClass('button_base')
                .click(self, function () {
                    self.confirmed = true;
                    self.close();
                    if (self.callback !== null) {
                        self.callback();
                    }
                })
                .appendTo(buttonWrapper);

            //no button
            $('<button/>')
                .text('Nee')
                .addClass('button_popup--confirm')
                .addClass('button_base')
                .click(self, function () {
                    self.confirmed = false;
                    self.close();
                    if (self.callback !== null) {
                        self.callback();
                    }
                })
                .appendTo(buttonWrapper);
            break;
        case 'okButton':
            var buttonWrapper = $('<div/>')
                .addClass('popup_button_wrapper')
                .appendTo(popup);

            $('<button/>')
                .text('Ok')
                .addClass('button_popup--prompt')
                .addClass('button_base')
                .click(self, function () {
                    self.prompt = true;
                    self.close();
                    if (self.callback !== null) {
                        self.callback();
                    }
                })
                .appendTo(buttonWrapper);
            break;
    }

    //fix the overlay height if the page is loading content while the popup appears
    $('#overlay--black').height(this.overlayHeight);
    //adjust the height of the element if there is more text
    this.adjustedHeight = $('.popup').prop('scrollHeight');
    this.height = $('.popup').height();

    if (this.height !== null && this.adjustedHeight > this.height) {
        $('.popup').height(this.adjustedHeight);
    }
};