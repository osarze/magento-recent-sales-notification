define([
    "jquery",
], function ($, alert) {
    'use strict';
    return function(config, element) {
        const $socialProof = $(element);
        let textContent = $(element).text();
        let recentSales = config;
        let indexToStop = recentSales.length - 1;
        let newText = '';
        let showPop = true;
        let index = 0;

        function setRandomPerson() {  
            if (showPop) {
                const person = recentSales[index];
                newText = textContent.replace(':person', person.name);
                newText = newText.replace(':location', person.state);
                newText = newText.replace(':product', person.product);
                newText = newText.replace(':price', person.price);
                newText = newText.replace(':time', person.time);
                $socialProof.find($('.custom-notification-content').text(newText));
                $socialProof.find('.custom-notification-image-wrapper img')[0].src = `${person.image}`;

                $socialProof.slideDown("slow");
                showPop = false;
            } else {
                $socialProof.slideUp("slow");
                showPop = true;
                if (indexToStop == index) {
                    showPop = false;
                    $socialProof.slideUp("slow");
                    clearInterval(showAtPopAtIntervals);
                }else {
                    index++;
                }
            }
        }

        let showAtPopAtIntervals = setInterval(setRandomPerson, 5000);

        $(".page-wrapper").on("click", ".custom-close", function() {
            $(".custom-social-proof").remove();
            $(".custom-social-proof").stop().slideToggle('slow');

        });
    }
    // $.widget('mage.wktestrequire', {
    //     options: {
    //         confirmMsg: ('divElement is removed.')
    //     },
    //     _create: function () {
    //         var self = this;
    //         $(self.options.divElement).remove();
    //         alert({
    //             content: self.options.confirmMsg
    //         });
    //     }
    // });
    // return $.mage.wktestrequire;
});